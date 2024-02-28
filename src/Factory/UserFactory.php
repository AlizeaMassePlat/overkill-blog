<?php

namespace App\Factory;

use App\Model\AdminUserModel;
use App\Model\GuestUserModel;
use App\Model\UserModel;
use App\Model\GoogleUserModel;

class UserFactory {
    public static function createUser($role) {
        $role ?? "ROLE_GUEST"; 
        
        switch ($role) {
            case "ROLE_ADMIN":
                $user = new AdminUserModel();
                break;
            case "ROLE_USER":
                $user = new UserModel();
                break;
            case "ROLE_GOOGLE":
                $user = new GoogleUserModel();
                break;
            case "ROLE_GUEST":
            default:
                $user = new GuestUserModel();
                break;
        }
        return $user;
    }

}