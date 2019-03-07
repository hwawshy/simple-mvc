<?php

   namespace Core;
   class View
   {
      public static function render($view, $args = [])
      {
         extract($args, EXTR_SKIP);
         $file = VIEWS . $view;
         if (is_readable($file))
            require($file);
         else
            echo $file . ' not found';
      }

      public static function render_template($template, $args = [])
      {
         static $twig = null;
         if ($twig === null)
         {
            $loader = new \Twig\Loader\FilesystemLoader(VIEWS);
            $twig = new \Twig\Environment($loader);
         }

         echo $twig->render($template, $args);
      }
   }
