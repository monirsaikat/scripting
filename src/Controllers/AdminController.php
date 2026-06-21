<?php

namespace Src\Controllers;

use Src\Attributes\Auth;
use Src\Attributes\Guest;
use Src\Attributes\Route;
use Src\Session;

class AdminController extends Controller
{
    #[Guest('admin', '/admin')]
    #[Route('GET', '/admin/login')]
    public function login()
    {
        $pageTitle = 'Admin Login';

        $this->render('admin/login', compact('pageTitle'));
    }

    #[Guest('admin', '/admin')]
    #[Route('POST', '/admin/login')]
    public function postLogin()
    {
        $email = $this->post('email', 'required|email');
        $password = $this->post('password', 'required');

        if ($this->validation()) {
            flash('error', $this->renderErrors());
            redirect('/admin/login');
        }

        $admin = $this->db()->from('admins')->where('email', '=', $email)->first();

        if (!$admin || !$admin->is_active || !password_verify($password, $admin->password)) {
            flash('error', 'Invalid admin credentials');
            redirect('/admin/login');
        }

        unset($admin->password);
        auth('admin')->login($admin);

        $this->db()->update('admins', [
            'last_login_at' => date('Y-m-d H:i:s'),
        ], [
            'id' => $admin->id,
        ]);

        Session::clearOldValues();
        flash('success', 'Welcome back, ' . $admin->name);
        redirect('/admin');
    }

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin')]
    public function dashboard()
    {
        $pageTitle = 'Admin Dashboard';
        $admin = user('admin');
        $stats = [
            'users' => count($this->db()->from('users')->get()),
            'admins' => count($this->db()->from('admins')->get()),
        ];

        $this->render('admin/dashboard', compact('pageTitle', 'admin', 'stats'));
    }

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/admin/logout')]
    public function logout()
    {
        auth('admin')->logout();
        flash('success', 'Admin logged out');
        redirect('/admin/login');
    }
}
