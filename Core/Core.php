<?php

   namespace Core;
   use \Core\Model;

   class Core
   {
      protected $db;
      protected $router;

      public function __construct()
      {
         $this->db = new \Core\Database();
         $this->router = new \Core\Router();
      }
      public function run()
      {
         // Set up the database in the models
         Model::set_database($this->db);

         // Add some routing rules
         $this->router->add('', ['controller' => 'Home', 'action' => 'index']);
         $this->router->add('{controller}/{action}');
         $this->router->add('{controller}/{id:\d+}/{action}', ['namespace' => 'Admin']);
         $this->router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

         // Dispatch the requested url
         $url = trim($_SERVER['REQUEST_URI'], '/');
         $this->router->dispatch($url);

      }
   }
