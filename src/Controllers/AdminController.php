<?php

namespace Src\Controllers;

use PDO;
use Src\Attributes\Auth;
use Src\Attributes\Guest;
use Src\Attributes\Route;
use Src\Session;

class AdminController extends Controller
{
    // ── Auth ──────────────────────────────────────────────────────────────────

    #[Guest('admin', '/admin')]
    #[Route('GET', '/admin/login')]
    public function login()
    {
        $this->render('admin/login', ['pageTitle' => 'Sign In']);
    }

    #[Guest('admin', '/admin')]
    #[Route('POST', '/admin/login')]
    public function postLogin()
    {
        $email    = $this->post('email',    'required|email');
        $password = $this->post('password', 'required');

        if ($this->validation()) {
            flash('error', $this->renderErrors());
            return $this->renderUnprocessable('admin/login', ['pageTitle' => 'Sign In']);
        }

        $admin = $this->db()->from('admins')->where('email', '=', $email)->first();

        if (!$admin || !$admin->is_active || !password_verify($password, $admin->password)) {
            flash('error', 'Invalid credentials. Please try again.');
            return $this->renderUnprocessable('admin/login', ['pageTitle' => 'Sign In']);
        }

        unset($admin->password);
        auth('admin')->login($admin);

        $this->db()->update('admins', ['last_login_at' => date('Y-m-d H:i:s')], ['id' => $admin->id]);

        Session::clearOldValues();
        redirect('/admin');
    }

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin/logout')]
    public function logout()
    {
        auth('admin')->logout();
        redirect('/admin/login');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin')]
    public function dashboard()
    {
        $stats = [
            'users'  => $this->db()->from('users')->count(),
            'admins' => $this->db()->from('admins')->count(),
        ];

        $this->render('admin/dashboard', [
            'pageTitle' => 'Dashboard',
            'admin'     => user('admin'),
            'stats'     => $stats,
            'page'      => 'dashboard',
        ]);
    }

    // ── Users CRUD ────────────────────────────────────────────────────────────

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin/users')]
    public function users()
    {
        $search  = trim($_GET['search'] ?? '');
        $role    = $_GET['role']   ?? '';
        $status  = $_GET['status'] ?? '';
        $curPage = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $offset  = ($curPage - 1) * $perPage;

        $pdo        = $this->db()->getConnection();
        $conditions = [];
        $params     = [];

        if ($search) {
            $conditions[] = "(CONCAT(first_name,' ',last_name) LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if ($role) {
            $conditions[] = 'role = :role';
            $params[':role'] = $role;
        }
        if ($status) {
            $conditions[] = 'status = :status';
            $params[':status'] = $status;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM users $where");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $dataStmt = $pdo->prepare("SELECT * FROM users $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
        $dataStmt->execute($params);
        $users = $dataStmt->fetchAll(PDO::FETCH_OBJ);

        $this->render('admin/users/index', [
            'pageTitle'   => 'Users',
            'users'       => $users,
            'total'       => $total,
            'totalPages'  => max(1, (int) ceil($total / $perPage)),
            'currentPage' => $curPage,
            'perPage'     => $perPage,
            'search'      => $search,
            'role'        => $role,
            'status'      => $status,
            'page'        => 'users',
        ]);
    }

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin/users/create')]
    public function createUser()
    {
        $this->render('admin/users/create', [
            'pageTitle' => 'Create User',
            'page'      => 'users',
        ]);
    }

    #[Auth('admin', '/admin/login')]
    #[Route('POST', '/admin/users/store')]
    public function storeUser()
    {
        $firstName = $this->post('first_name', 'required|min:2');
        $lastName  = $this->post('last_name',  'required|min:2');
        $email     = $this->post('email',       'required|email');
        $password  = $this->post('password',    'required|min:8');
        $phone     = $this->post('phone');
        $gender    = $this->post('gender');
        $role      = $this->post('role')   ?: 'viewer';
        $status    = $this->post('status') ?: 'active';

        if ($this->validation()) {
            flash('error', $this->renderErrors());
            return $this->renderUnprocessable('admin/users/create', [
                'pageTitle' => 'Create User',
                'page'      => 'users',
            ]);
        }

        if ($this->db()->from('users')->where('email', '=', $email)->first()) {
            flash('error', 'A user with that email already exists.');
            return $this->renderUnprocessable('admin/users/create', [
                'pageTitle' => 'Create User',
                'page'      => 'users',
            ]);
        }

        $this->db()->insert('users', [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'phone'      => $phone ?: null,
            'gender'     => $gender ?: null,
            'role'       => $role,
            'status'     => $status,
        ]);

        flash('success', "$firstName $lastName created successfully.");
        redirect('/admin/users');
    }

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin/users/{id}/edit')]
    public function editUser($id)
    {
        $user = $this->db()->from('users')->where('id', '=', $id)->first();
        if (!$user) {
            flash('error', 'User not found.');
            redirect('/admin/users');
        }

        $this->render('admin/users/edit', [
            'pageTitle' => 'Edit User',
            'user'      => $user,
            'page'      => 'users',
        ]);
    }

    #[Auth('admin', '/admin/login')]
    #[Route('POST', '/admin/users/{id}/update')]
    public function updateUser($id)
    {
        $user = $this->db()->from('users')->where('id', '=', $id)->first();
        if (!$user) {
            flash('error', 'User not found.');
            redirect('/admin/users');
        }

        $firstName = $this->post('first_name', 'required|min:2');
        $lastName  = $this->post('last_name',  'required|min:2');
        $email     = $this->post('email',       'required|email');
        $phone     = $this->post('phone');
        $gender    = $this->post('gender');
        $role      = $this->post('role')   ?: $user->role;
        $status    = $this->post('status') ?: $user->status;
        $password  = $this->post('password');

        if ($this->validation()) {
            flash('error', $this->renderErrors());
            return $this->renderUnprocessable('admin/users/edit', [
                'pageTitle' => 'Edit User',
                'user'      => $user,
                'page'      => 'users',
            ]);
        }

        $data = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'phone'      => $phone ?: null,
            'gender'     => $gender ?: null,
            'role'       => $role,
            'status'     => $status,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->db()->update('users', $data, ['id' => $id]);

        flash('success', "$firstName $lastName updated successfully.");
        redirect('/admin/users');
    }

    #[Auth('admin', '/admin/login')]
    #[Route('POST', '/admin/users/{id}/delete')]
    public function deleteUser($id)
    {
        $user = $this->db()->from('users')->where('id', '=', $id)->first();
        if ($user) {
            $this->db()->delete('users', ['id' => $id]);
            flash('success', "{$user->first_name} {$user->last_name} deleted.");
        } else {
            flash('error', 'User not found.');
        }
        redirect('/admin/users');
    }
}
