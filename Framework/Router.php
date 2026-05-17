<?php

namespace Framework;

class Router
{
    protected $routes = [];

    public function RegisterRoute($method, $uri, $action, $middleware = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    /**
     * Add a GET route
     * 
     * @param string $uri
     * @param string $action
     * @param array $middleware
     * @return void
     */
    public function get($uri, $action, $middleware = [])
    {
        $this->RegisterRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Add a POST route
     * 
     * @param string $uri
     * @param string $action
     * @param array $middleware
     * @return void
     */
    public function post($uri, $action, $middleware = [])
    {
        $this->RegisterRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Add a PUT route
     * 
     * @param string $uri
     * @param string $action
     * @param array $middleware
     * @return void
     */
    public function put($uri, $action, $middleware = [])
    {
        $this->RegisterRoute('PUT', $uri, $action, $middleware);
    }

    /**
     * Add a DELETE route
     * 
     * @param string $uri
     * @param string $action
     * @param array $middleware
     * @return void
     */
    public function delete($uri, $action, $middleware = [])
    {
        $this->RegisterRoute('DELETE', $uri, $action, $middleware);
    }
    public function error($httpCode = 404)
    {
        http_response_code($httpCode);
        loadView("error", [
            'status' => $httpCode,
            'message' => $httpCode == 404 ? 'Page not found' : 'An error occurred'
        ]);
        exit;
    }
    public function route($uri, $method)
    {
        $requestMethod = $method;
        
        // Check for _method input to override request method
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }
        
        // Split URI into segments
        $uriSegments = explode('/', trim(parse_url($uri, PHP_URL_PATH), '/'));
        
        foreach ($this->routes as $route) {
            // Split route into segments
            $routeSegments = explode('/', trim($route['uri'], '/'));
            
            // Check if segment counts match and method matches
            $match = count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === strtoupper($requestMethod);
            
            // If counts don't match, skip this route
            if (!$match) {
                continue;
            }
            
            // Check for params
            $params = [];
            
            $match = true;
            
            // Loop through segments to check for match and extract params
            for ($i = 0; $i < count($uriSegments); $i++) {
                // If route segment does not match URI segment and is not a param
                if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                    $match = false;
                    break;
                }
                
                // Check for param and add to params array
                if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                    $params[$matches[1]] = $uriSegments[$i];
                }
            }
            
            if ($match) {
                // Extract controller and method from action
                list($controller, $controllerMethod) = explode('@', $route['action']);
                
                // Check for middleware
                $middleware = $route['middleware'] ?? [];
                
                if (!empty($middleware)) {
                    foreach ($middleware as $role) {
                        $middlewareClass = 'Framework\\Middleware\\Authorize';
                        $middlewareInstance = new $middlewareClass();
                        $middlewareInstance->handle($role);
                    }
                }
                
                // Instantiate controller and call method
                $controller = "App\\Controllers\\{$controller}";
                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod($params);
                return;
            }
        }

        $this->error(404);
    }
}
