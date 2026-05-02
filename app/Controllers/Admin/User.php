<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Entities\User as UserEntity;

class User extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        // Query to get all users with their group names
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, users.username, users.email, auth_groups.name as role');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left');
        $builder->where('users.deleted_at', null);
        $users = $builder->get()->getResultArray();

        $data = [
            'title' => 'Kelola User',
            'users' => $users,
        ];

        return view('admin/user/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah User Baru',
            'validation' => \Config\Services::validation(),
            'roles'      => $this->groupModel->findAll(),
        ];

        return view('admin/user/create', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
            'role'     => 'required|is_not_unique[auth_groups.id]',
        ];

        if (!$this->validate($rules)) {
            return view('admin/user/create', [
                'title'      => 'Tambah User Baru',
                'validation' => $this->validator,
                'roles'      => $this->groupModel->findAll(),
            ]);
        }

        // Create user entity (this will automatically hash the password)
        $user = new UserEntity([
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'active'   => 1, // Auto activate
        ]);

        // Insert user to database
        $this->userModel->save($user);
        $userId = $this->userModel->getInsertID();

        // Assign role (group)
        $this->groupModel->addUserToGroup($userId, $this->request->getPost('role'));

        session()->setFlashdata('success', 'User berhasil ditambahkan!');
        return redirect()->to('/user');
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Get user's current role
        $db = \Config\Database::connect();
        $userGroup = $db->table('auth_groups_users')->where('user_id', $id)->get()->getRowArray();
        $currentRoleId = $userGroup ? $userGroup['group_id'] : null;

        $data = [
            'title'      => 'Edit User',
            'user'       => $user,
            'currentRole'=> $currentRoleId,
            'roles'      => $this->groupModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/user/edit', $data);
    }

    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User dengan ID ' . $id . ' tidak ditemukan.');
        }

        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'     => 'required|is_not_unique[auth_groups.id]',
        ];

        // Only validate password if it's filled
        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'required|min_length[8]';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            $db = \Config\Database::connect();
            $userGroup = $db->table('auth_groups_users')->where('user_id', $id)->get()->getRowArray();
            $currentRoleId = $userGroup ? $userGroup['group_id'] : null;

            return view('admin/user/edit', [
                'title'      => 'Edit User',
                'user'       => $user,
                'currentRole'=> $currentRoleId,
                'roles'      => $this->groupModel->findAll(),
                'validation' => $this->validator,
            ]);
        }

        // Update basic info
        $updateData = [
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
        ];

        // Update password if provided (Entity will hash it)
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userEntity = new UserEntity();
            $userEntity->password = $password;
            $updateData['password_hash'] = $userEntity->password_hash;
        }

        $this->userModel->update($id, $updateData);

        // Update role
        $newRoleId = $this->request->getPost('role');
        $db = \Config\Database::connect();
        
        // Remove old roles
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        
        // Assign new role
        $this->groupModel->addUserToGroup($id, $newRoleId);

        session()->setFlashdata('success', 'Data user berhasil diperbarui!');
        return redirect()->to('/user');
    }

    public function delete(int $id)
    {
        // Prevent deleting yourself
        if ($id == user_id()) {
            session()->setFlashdata('error', 'Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!');
            return redirect()->to('/user');
        }

        $db = \Config\Database::connect();
        
        // Hapus foreign key dependencies terlebih dahulu (jika cascade tidak aktif)
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        $db->table('auth_logins')->where('user_id', $id)->delete();
        $db->table('auth_activation_attempts')->where('token', $id)->delete(); // just in case
        $db->table('auth_reset_attempts')->where('email', $this->userModel->find($id)->email ?? '')->delete();
        
        // Hapus user secara permanen (purge)
        $this->userModel->delete($id, true);

        session()->setFlashdata('success', 'User berhasil dihapus secara permanen!');
        return redirect()->to('/user');
    }
}
