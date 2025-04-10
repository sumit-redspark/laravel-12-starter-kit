<?php

namespace App\Enums;

class Role
{
     const SUPER_ADMIN = 'Super Admin';
     const ADMIN = 'Admin';
     const USER = 'User';

     /**
      * Get all roles as an array
      *
      * @return array
      */
     public static function all(): array
     {
          return [
               self::SUPER_ADMIN,
               self::ADMIN,
               self::USER,
          ];
     }

     /**
      * Get all admin roles
      *
      * @return array
      */
     public static function adminRoles(): array
     {
          return [
               self::SUPER_ADMIN,
               self::ADMIN,
          ];
     }
}
