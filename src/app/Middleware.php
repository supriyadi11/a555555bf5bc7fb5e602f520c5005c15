<?php
namespace App;

use App\Enum\ResponseEnum;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\OnlyCompanyMiddleware;
use App\Middlewares\OnlyFrelancerMiddleware;

class Middleware {

    /**
     * Register the middleware
     *
     * @var array $_list
     */
    private array $_list = [
        'auth' => AuthMiddleware::class,
        'onlyFreelancer' => OnlyFrelancerMiddleware::class,
        'onlyCompany' => OnlyCompanyMiddleware::class
    ];

    /**
     * Check if you have the middleware
     *
     * @param string $middlewareName
     * @return boolean
     */
    public function hasMiddleware(string $middlewareName)
    {
        return in_array($middlewareName, array_keys($this->_list));
    }

    /**
     * Execute the middleware by name from list
     *
     * @param string $middleware
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public function handle(string $middleware, Request $request, Response $response) : array
    {
        $instance = new $this->_list[$middleware];
        return $instance->handle($request, $response);
    }
}