<?php
namespace App\Controllers;

use App\Auth;
use App\Models\User;
use App\Request;
use App\Response;

class AuthController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function oauth()
    {
        include __DIR__ . '/../OAuth.php';
    }

    public function oauthCallback(Request $request)
    {
        include __DIR__ . '/../OAuth.php';
        $data = $user->toArray();
        $req = User::where('email', $data['email'])->first();
        if (empty($req)) {
            $hasil = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['email'], PASSWORD_BCRYPT),
            ]);
    
            if (!$hasil) {
                return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, [
                    'message' => 'Something went wrong!'
                ]);
            }               
        }

        $array=array(
            'name' => $data['name'],
            'email' => $data['email']
        );

        $token = Auth::generateToken(User::where('email', $data['email'])->first());

         return $this->response(Response::HTTP_OK, [
            'message' => 'You\'re logged in',
            'data' => [
                'token' => $token,
                'user' => $array
            ]
        ]);
       
    }
}