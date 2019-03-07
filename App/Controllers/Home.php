<?php

   namespace App\Controllers;
   use \Core\View;

   class Home extends \Core\Controller
   {
      public function index_action()
      {
            //View::render('Home/index.php', ['name' => 'Dave', 'colors' => ['red', 'green', 'blue']]);

            View::render_template('Home/index.html', [
               'name' => 'Muhammad',
               'colors' => ['green', 'blue', 'red']
            ]);
      }
   }
