<?php

   namespace App\Controllers\Admin;

   use \Core\View;
   use \App\Models\User;

   class Users extends \Core\Controller
   {

      public function index_action()
      {
         //print_r($this->route_params);
      }

      public function profile_action()
      {
         if (is_numeric($this->route_params['id']))
         {
            $id = intval($this->route_params['id']);
            $user = User::find_by_id($id);
            if ($user)
            {
               View::render_template('Users/profile.html', $user->db_attributes()); // CAUTION: Password included
            }
            else
            {
               echo 'User not found';
            }
         }
      }

   }
