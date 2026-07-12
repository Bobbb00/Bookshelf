<?php

namespace App\Controllers;

use App\Models\AppUserModel;

class UserController extends BaseController
{
    /**
     * Show the user profile page.
     */
    public function profile()
    {
        $userModel = new AppUserModel();
        $user = $userModel->find(user_id());

        return view('user/profile', [
            'user'       => $user,
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile()
    {
        $userId = user_id();

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => "required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{$userId}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'alamat'   => 'required|min_length[5]',
            'no_hp'    => 'required|min_length[8]|max_length[20]',
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password']     = 'required|strong_password';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Gunakan raw DB query untuk custom fields agar tidak memicu
        // afterInsert hook myth/auth yang bisa mereset role user
        $db = \Config\Database::connect();

        $updateData = [
            'fullname'   => $this->request->getPost('fullname'),
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'alamat'     => $this->request->getPost('alamat'),
            'no_hp'      => $this->request->getPost('no_hp'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Update password jika diisi (hash manual)
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $success = $db->table('users')->where('id', $userId)->update($updateData);

        if ($success) {
            session()->setFlashdata('success', 'Profil Anda berhasil diperbarui!');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui profil.');
        }

        return redirect()->to('/profile');
    }
}
