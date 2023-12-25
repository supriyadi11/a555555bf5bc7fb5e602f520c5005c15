<?php
namespace App\Middlewares;

use App\Auth;
use App\Models\UsersModel;
use App\Request;
use App\Response;

class OnlyFrelancerMiddleware {
    
    public function handle(Request $request, Response $response)
    {
        $usersModel = new UsersModel();
        $count = $usersModel->userCountCompanies(Auth::user()['id']);
        if ($count > 0) return abort(403, "Access Not Granted!");
        return $response->ok()->build();
    }

}