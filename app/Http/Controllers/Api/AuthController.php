<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\RecoveryRequest;
use App\Http\Controllers\Controller;
use App\Mail\RecoveryPasswordMail;
use App\RecoveryPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'recovery']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'E-mail ou senha estão incorretos.'
            ], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json();
    }

    public function refresh()
    {
        $token = auth()->refresh();

        return response()->json(['token' => $token]);
    }

    public function recovery(RecoveryRequest $request)
    {
        $user = User::where('email', $request->input('email'))->firstOrFail();

        DB::beginTransaction();
        try {
            $user->recoveryPasswords()->delete();

            $recoveryPassword = new RecoveryPassword();
            $recoveryPassword->user_id = $user->id;
            $recoveryPassword->save();
            Mail::send(new RecoveryPasswordMail($recoveryPassword));

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'error' => 'Falha ao enviar email de recuperação da senha'
            ], 400);
        }

        return response()->json();
    }
}
