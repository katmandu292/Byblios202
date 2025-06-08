<?php

namespace Framework;

class Router {

   protected $routes = [];

   /**
    * Add a new route
    *
    * @param string $method
    * @param string $uri
    * @param string $action
    * @return void
    */
   public function registerRoute($method, $uri, $action)
   {
     list($controller, $controllerMethod) = explode('@', $action);

     $this->routes[] = [
       'method' => $method,
       'uri' => $uri,
       'controller' => $controller,
       'controllerMethod' => $controllerMethod
     ];

   }

   /**
    * Add a GET route
    *
    * @param string $uri
    * @param string $controller
    * @return void
    */
   public function get($uri, $controller)
   {
     $this->registerRoute('GET', $uri, $controller);
   }

   /**
    * Add a POST route
    *
    * @param string $uri
    * @param string $controller
    * @return void
    */
   public function post($uri, $controller)
   {
     $this->registerRoute('POST', $uri, $controller);
   }

   /**
    * Add a DELETE route
    *
    * @param string $uri
    * @param string $controller
    * @return void
    */
   public function delete($uri, $controller)
   {
     $this->registerRoute('DELETE', $uri, $controller);
   }

   /**
    * Add a PUT route
    *
    * @param string $uri
    * @param string $controller
    * @return void
    */
   public function put($uri, $controller)
   {
     $this->registerRoute('PUT', $uri, $controller);
   }

   /**
    * Route the request
    *
    * @param string $uri
    * @param string $method
    * @return void
    */
   public function route($uri, $method)
   {
     foreach($this->routes as $route) {

       inspect(strpos($uri,$route['uri']));

       if($route['uri'] === $uri && $route['method'] === $method) {
//       Extract Controller and Controller Method
         $controller = 'App\\controllers\\' . $route['controller'];
         $controllerMethod = $route['controllerMethod'];
//       Instantiate the Controller and call the method
         $controllerInstance = new $controller();
         $controllerInstance->$controllerMethod();
         return;
       } else {
         if(strpos($uri,$route['uri']) === false && $route['method'] === $method) {
            $volumeId = $this->getId($this->getRest($_SERVER['REQUEST_URI']));
            $controller = 'App\\controllers\\' . $route['controller'];
            $controllerMethod = $route['controllerMethod'];
            $controllerInstance = new $controller();
            if($controllerMethod === 'show') {
               echo "Going to apply " . $route['controller'] . "->" . $controllerMethod;
               $controllerInstance->$controllerMethod($volumeId);
               return;
            }
         }
       }
     }

     http_response_code(404);
     loadView('error/404');
     exit;
   }

   /**
    *
    * @param string $suffix
    * @return string
    */
   private function getId($suffix)
   {
     return substr($suffix,(1+strpos($suffix,'=')));
   }

   /**
    *
    * @param string $request
    * @return string
    */
   private function getRest($request)
   {
     return substr($request,(1+strpos($request,'?')));
   }
}

?>

