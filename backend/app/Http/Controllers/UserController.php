<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller
{
    //For Authentication of a user
    public function login()
    {
        try {
            $data = request()->validate([
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication Failed',
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Authentication Successful',
                'data' => $user
            ], 200);
        }
    }

    //For Registering a new User
    public function store()
    {
        try {
            $data = request()->validate([
                'name' => 'required|min:8',
                'username' => ['required', 'min:8', Rule::unique('users', 'username')],
                'phone' => ['required', 'digits:10', Rule::unique('users', 'phone')],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => 'required|min:8'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }
}
