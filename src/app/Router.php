<?php
namespace App;

use App\Request;

class Router {

    private static array $_routeList = [];
    private static string $_pathPrefix = 'api/';

    public static function add(string $method, string $path, array $handler, array $middlewares = [])
    {
        $caller = getFuncCaller(2);
        if (!empty($caller) && $caller['function'] == 'middleware' && $caller['class'] == self::class) {
            $middlewares = array_merge($middlewares, first($caller['args']));
        }

        $path = trim($path, '/');

        self::$_routeList[] = [
            'method' => $method,
            'path' => self::$_pathPrefix.$path,
            'action' => [
                'class' => $handler[0],
                'function' => $handler[1]
            ],
            'middlewares' => $middlewares
        ];
    }    

    public static function middleware(array $middlewares, Callable $routes)
    {
        $routes();
    }

    public static function run(Request $request)
    {
        if (self::_hasRoute($request)) {
            $request->params = self::_getParamFormPath($request->request['method'], $request->request['path']);
            $route = self::_getRoute($request->request['method'], $request->request['path']);
            if (!empty($route['middlewares'])) {
                $middlewareInstance = new Middleware;
                $response = new Response;
                foreach ($route['middlewares'] as $name) {
                    $result = $middlewareInstance->handle($name, request(), $response);
                    if ($result['httpCode'] >= 400) return $result;
                }
            }

            return self::_handle($request, $route['action']['class'], $route['action']['function']);                
        };

        return abort(404, "Route Not Found!");
    }    

    private static function _handle(Request $request, ...$params)
    {
        $controller = new $params[0];
        return call_user_func_array([$controller, $params[1]], array_values([$request, ...$request->params]));
    }

    private static function _hasRoute(Request $request) 
    {
        $req = $request->request;
        return !empty(self::_getRoute($req['method'], $req['path']));
    }

    private static function _getRoute(string $method, string $path) : array|null
    {
        $filtered = array_filter(self::$_routeList, function ($route) use ($method, $path) {
            $routePathRegex = preg_replace("/{[a-z]+}/", "[a-zA-Z0-9]+", $route['path']);
            return preg_match_all("#$routePathRegex$#", $path) && $route['method'] == $method;
        });
        return first($filtered) ?? null;
    }

    private static function _getParamFormPath(string $method, string $path)
    {        
        $route = self::_getRoute($method, $path);
        $paramKeys = preg_grep('#{[a-zA-Z0-9]+}#', explode('/', $route['path']));
        $pathArr = explode('/', $path);
        $result = [];
        foreach ($paramKeys as $i => $key) {
            $key = ltrim(rtrim($key, '}'), '{');
            $result[$key] = $pathArr[$i];
        }
        return $result;
    }
}