<?php
namespace App;

use App\Models\User;
use App\Models\UsersModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class Auth {

    public static function login(string $email, string $password) : string|false
    {
        return false;
    }

    public static function logout($token)
    {
        // Code..  
    }

    public static function verifyToken(string $token = null)
    {
        $token = $token ?? isset($_SERVER['HTTP_AUTHORIZATION']) ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) : null;
        if (empty($token)) {
            return false;
        }

        $decode = self::decodeToken($token);
        return !empty($decode) ?? $decode;
    }

    public static function generateToken(User $user)
    {
        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'logged_at' => now()->toDateTimeString(),
            'expired_at' => now()->addDay()->toDateTimeString()
        ];

        $key = config('app.key');
        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
    }

    public static function decodeToken(string $token) : array|false
    {
        $key = config('app.key');
        try {
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            return (array) $decode;
        } catch (SignatureInvalidException $e) {
            logger('error', $e->getMessage(), ['token' => $token]);
            return false;
        } 
        catch (\Exception $th) {
            logger('error', $th->getMessage(), ['token' => $token]);
            return false;
        } 
    }

    public static function authenticate(string $token)
    {
        $decoded = self::decodeToken($token);
        extract($decoded);
        $expired_at = now()->create($expired_at);
        
        if ($expired_at < now()) return ['status' => false, 'message' => 'Token has been expired!'];
        
        $user = User::where('email', $email)->first();

        if (empty($user)) return ['status' => false, 'message' => 'User Unknown!'];

        return ['status' => true, 'user' => $user];
    }

    public static function user() : User
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return null;
        
        $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        if (!self::verifyToken($token)) return null;
        $autenticated = self::authenticate($token);
        if (!$autenticated['status']) return null;

        return $autenticated['user'];
    }

}
