<?php

namespace Src\Controllers;

use Src\Attributes\Auth;
use Src\Attributes\Route;
use Src\Models\User;

class StaffController extends Controller
{
    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/users')]
    public function users()
    {
        $users = (new User)->limit(10)->get();

        $this->json($users);
    }

    #[Auth('admin', '/admin/login')]
    #[Route('GET', '/staffs', 'staff.all')]
    public function index()
    {
        $pageTitle = 'Users/Staff';
        $search    = $this->get('search');
        $query     = $this->db()->from('users');

        if ($search && !is_null($search)) {
            $query->whereLike('first_name', $search);
        }
        
        $users     = $query->latest('created_at')->paginate(10);

        $this->render('index', compact('pageTitle', 'users'));
    }

    #[Auth('admin', '/admin/login')]
    #[Route('POST', '/staffs')]
    public function saveStaff()
    {
        $firstName = $this->post('first_name', 'required|min:3');
        $lastName  = $this->post('last_name', 'required|min:3');
        $email     = $this->post('email', 'required|email');
        $phone     = $this->post('phone', 'required');
        $address   = $this->post('address', 'required');

        $errors = $this->validation();

        if ($errors) {
            flash('error', $this->renderErrors());
            return $this->renderUnprocessable('index', [
                'pageTitle' => 'Users/Staff',
                'users' => $this->db()->from('users')->latest('created_at')->paginate(10),
            ]);
        }

        $this->db()->insert('users', [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address,
        ]);

        flash('success', 'User/Staff created successfully');

        redirect('/staffs');
    }

    #[Auth('admin', '/admin/login')]
    #[Route('POST', '/staffs/{id}/update', 'staff.update')]
    public function updateStaff($id)
    {
        $firstName = $this->post('first_name', 'required|min:3');
        $lastName  = $this->post('last_name', 'required|min:3');
        $email     = $this->post('email', 'required|email');
        $phone     = $this->post('phone', 'required');
        $address   = $this->post('address', 'required');

        $errors = $this->validation();

        if ($errors) {
            flash('error', $this->renderErrors());
            return $this->renderUnprocessable('index', [
                'pageTitle' => 'Users/Staff',
                'users' => $this->db()->from('users')->latest('created_at')->paginate(10),
            ]);
        }

        $this->db()->update('users', [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address,
        ], [
            'id' => $id
        ]);

        flash('success', 'User/Staff updated successfully');

        redirect('/staffs');
    }

    #[Auth('admin', '/admin/login')]
    #[Route('POST', '/staffs/delete/{id}', 'staff.delete')]
    public function deleteStaff($id)
    {
        $this->db()->delete('users', [
            'id' => $id
        ]);

        flash('success', 'User/Staff deleted successfully');

        redirect('/staffs');
    }
}
