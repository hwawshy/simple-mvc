<?php

   namespace Core;
   abstract class Controller
   {
      protected $route_params = [];

      public function __construct($params)
      {
         $this->route_params = $params;
      }

      public function __call($name, $args)
      {
         $name = $name . '_action';

         if (method_exists($this, $name))
         {
            if ($this->before() !== false)
            {
               call_user_func_array([$this, $name], $args);
               $this->after();
            }
         }
         else
         {
            echo 'Method ' . $name . ' not found in controller ' . get_class($this);
         }
      }

      protected function before()
      {

      }

      protected function after()
      {

      }
   }
