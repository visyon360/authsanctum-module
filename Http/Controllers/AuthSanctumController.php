<?php

namespace Modules\AuthSanctum\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\AuthSanctum\Http\Requests\LoginRequest;
use Modules\AuthSanctum\Http\Requests\RegisterRequest;
use Modules\Users\Entities\User;

class AuthSanctumController extends Controller
{
	public function register(RegisterRequest $request)
	{
		$user = User::create([
			'name' => $request->name,
			'password' => bcrypt($request->password),
			'email' => $request->email
		]);

		return response()->json([
			'token' => $user->createToken('API Token')->plainTextToken,
			'user' => $user
		]);
	}

	public function login(LoginRequest $request)
	{
		if (!Auth::attempt([
			'email' => $request->email,
			'password' => $request->password])
		) {
			return abort(401, 'Credentials not match');
		}

		return response()->json([
			'token' => auth()->user()->createToken('API Token')->plainTextToken,
			'user' => auth()->user()
		]);
	}

	public function logout()
	{
		auth()->user()->tokens()->delete();

		return response()->json('Tokens deleted');
	}
}
