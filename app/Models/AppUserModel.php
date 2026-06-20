<?php

namespace App\Models;

use Myth\Auth\Models\UserModel as MythUserModel;

/**
 * AppUserModel extends Myth\Auth UserModel untuk menambahkan
 * custom fields seperti fullname, user_img, alamat, no_hp.
 */
class AppUserModel extends MythUserModel
{
    // Tambahkan field custom ke allowedFields
    protected $allowedFields = [
        'email',
        'username',
        'password_hash',
        'reset_hash',
        'reset_at',
        'reset_expires',
        'activate_hash',
        'status',
        'status_message',
        'active',
        'force_pass_reset',
        'permissions',
        'deleted_at',
        // Custom fields
        'fullname',
        'user_img',
        'alamat',
        'no_hp',
    ];
}
