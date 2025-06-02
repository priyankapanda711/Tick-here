<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
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
