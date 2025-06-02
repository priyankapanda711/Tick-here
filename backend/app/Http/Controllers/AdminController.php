<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //
    public function store()
    {
        try {
            $data = request()->validate([
                'name' => 'required|min:8',
                'username' => ['required', 'min:8', Rule::unique('admins', 'username')],
                'phone' => ['required', 'digits:10', Rule::unique('admins', 'phone')],
                'email' => ['required', 'email', Rule::unique('admins', 'email')],
                'password' => 'required|min:8'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $data['password'] = bcrypt($data['password']);

        $admin = Admin::create($data);

        return response()->json([
            'message' => 'Admin created successfully',
            'user' => $admin
        ], 201);
    }

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

        $admin = Admin::where('email', $data['email'])->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication Failed',
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Authentication Successful',
                'data' => $admin
            ], 200);
        }
    }
}
