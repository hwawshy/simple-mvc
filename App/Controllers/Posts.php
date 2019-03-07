<?php

   namespace App\Controllers;
   use \Core\View;

   class Posts extends \Core\Controller
   {
      public function index_action()
      {
         View::render_template('Posts/index.html');
      }
   }
