<?php
namespace App\Middlewares;

use App\Auth;
use App\Request;
use App\Response;

class AuthMiddleware {

    public function handle(Request $request, Response $response)
    {
        if (!Auth::verifyToken()) return abort(401, "User unknown!");
        return $response->ok()->build();
    }

}