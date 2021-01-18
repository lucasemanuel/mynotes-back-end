<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response(['message' => 'E-mail ou senha estão incorretos.'], 401);
        }

        return response(['token' => $token]);
    }

    public function logout()
    {
        auth()->logout();

        return response(['message' => 'Até mais =D']);
    }

    public function refresh()
    {
        $token = auth()->refresh();

        return response(['token' => $token]);
    }

    public function check()
    {
        return response('', 204);
    }
}
