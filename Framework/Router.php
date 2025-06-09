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

//    Split the current URI into segments
       $uriSegments = explode('/', trim($uri, '/'));
//    Split the route URI into segments
       $routeSegments = explode('/', trim($route['uri'], '/'));
       $match = true;

       $params = [];
       $match = true;

       for ($i = 0; $i < count($uriSegments); $i++) {
          if (isset($routeSegments[$i])) {
             if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                $match = false;
                break;
             }
          } else {
             $match = false;
             break;
          }
       }

       if($match && $route['method'] === $method) {
          parse_str($_SERVER['QUERY_STRING'],$params);
//  Extract Controller and Controller Method
          $controller = 'App\\controllers\\' . $route['controller'];
          $controllerMethod = $route['controllerMethod'];
//  Instantiate the Controller and call the method
          $controllerInstance = new $controller();
          $controllerInstance->$controllerMethod($params);
          return;
       }
     }

   http_response_code(404);
   loadView('error/404');
   exit;
   }

}

?>

