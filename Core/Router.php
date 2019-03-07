<?php


   namespace Core;
   class Router
   {

      /**
       * Array of regular expressions to match the url against
       * Specifies accepted route patterns
       * @var array
       */
      protected $routes = [];

      /**
       * Params from the matched route
       * @var array
       */
      protected $params = [];

      public function add($route, $params = [])
      {
         // escape forward slashes
         $route = preg_replace('/\//', '\\/', $route);
         // convert variables
         $route = preg_replace('/\{([a-z-]+)\}/', '(?<\1>[a-z-]+)', $route);
         // look for optional variables
         $route = preg_replace('/\{([a-z-]+):([^\}]+)\}/', '(?<\1>\2)', $route);
         // add start and end delimiters
         $route = '/^' . $route . '$/';

         $this->routes[$route] = $params;
      }

      public function get_routes()
      {
         return $this->routes;
      }

      /**
       * matches the given url against one of the routes
       * @param  string $url the url
       * @return bool   true on success or false on failure
       */
      public function match($url)
      {
         foreach ($this->routes as $route => $params)
         {
            if (preg_match($route, $url, $matches))
            {
               foreach ($matches as $key => $match)
               {
                  if (is_string($key))
                     $params[$key] = $match;
               }

               $this->params = $params;
               return true;
            }
         }

         return false;
      }

      public function dispatch($url)
      {
         $url = $this->remove_query_string($url);
         if ($this->match($url))
         {
            $controller = $this->convert_to_studly_caps($this->params['controller']);
            //$controller = "App\Controllers\\$controller";
            $controller = $this->get_namespace() . $controller;
            if (class_exists($controller))
            {
               $controller_object = new $controller($this->params);
               $action = $this->convert_dashes_to_underscores($this->params['action']);
               if (preg_match('/_action$/i', $action) == 0) {
                   $controller_object->$action();
               } else {
                   throw new \Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
               }
            }
            else {
               echo 'No class';
            }
         }
         else
         {
            echo 'No match';
         }
      }

      public function get_params()
      {
         return $this->params;
      }

      /**
       * converts the dashed controller from url to studly caps
       * @param  string $string the controller name
       * @return string         controller name in studly caps
       */

      protected function convert_to_studly_caps($string)
      {
         return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
      }

      protected function convert_dashes_to_underscores($string)
      {
         return str_replace('-', '_', $string);
      }

      protected function remove_query_string($url)
      {
         if ($url != '')
         {
            $parts = explode('?', $url, 2);
            if (strpos($parts[0], '=') === false)
            {
               $url = $parts[0];
            }
            else
            {
               $url = '';
            }
         }

         return $url;
      }

      protected function get_namespace()
      {
         $namespace = "App\Controllers\\";
         if (array_key_exists('namespace', $this->params))
         {
            $namespace .= $this->params['namespace'] . '\\';
         }

         return $namespace;
      }
   }
