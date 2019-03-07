<?php

   // Turn on output buffering
   ob_start();

   // Start sessions
   session_start();

   // Autoloader
   /*spl_autoload_register(function($class_name)
   {

      $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
      if (file_exists($file))
      {
         require_once($file);
      }

   });*/

   // Some path constants
   define('ROOT', dirname(__FILE__));
   define('VIEWS', ROOT . '/App/Views/');

   // Composer Autoloader
   require_once(ROOT . '/vendor/autoload.php');
