<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['store']);
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
}
