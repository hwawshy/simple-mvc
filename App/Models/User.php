<?php

   namespace App\Models;
   class User extends \Core\Model
   {
      protected static $table_name = 'users';
      protected static $db_columns = ['id', 'username', 'password', 'email'];

      public $id;
      public $username;
      public $password;
      public $email;
   }
