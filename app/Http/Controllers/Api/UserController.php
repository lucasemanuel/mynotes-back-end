<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['index']);
    }

    public function index()
    {
        return response(Auth::user());
    }

    public function store(StoreRequest $request)
    {
        $request->validated();

        $user = new User();
        $user->fill($request->all());
        $user->save();

        return response($user, 201);
    }

    public function update(UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->recoveryPassword->delete();
            $request->user->update([
                'password' => $request->input('password')
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response('Erro ao atualizar a senha!', 400);
        }

        return response('');
    }
}
