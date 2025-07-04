<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //register an admin
    public function store()
    {
        try {
            $data = request()->validate([
                'name' => 'required | min:8',
                'phone' => ['digits:10', Rule::unique('admins', 'phone')],
                'email' => ['required', 'email', Rule::unique('admins', 'email')],
                'password' => 'required|min:8'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $data['username'] = substr($data['email'], 0, strpos($data['email'], '@'));

        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create($data);

        return response()->json([
            'message' => 'Admin created successfully',
            'user' => $admin,
            'token' => $admin->createToken('admin-token')->plainTextToken
        ], 201);
    }

    //login an admin
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

        if (!$admin || ! Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Authentication Successful',
                'data' => $admin
            ], 200);
        }
    }

    //For logging out an admin(who is also a user)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    //For getting current user profile
    public function profile(Request $request)
    {
        return response()->json([
            'admin' => $request->user()
        ], 200);
    }

    //For updating user profile
    public function update(Request $request)
    {
        try {
            $admin = $request->user();

            $data = request()->validate([
                'name' => 'required|min:8',
                'username' => ['required', 'min:8', Rule::unique('admins', 'username')->ignore($request->user()->id)],
                'phone' => ['required', 'digits:10', Rule::unique('admins', 'phone')->ignore($request->user()->id)],
                'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($request->user()->id)],
                'password' => 'required|min:8',
            ]);

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $admin->update($data);

            return response()->json(['message' => 'Profile updated']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
     public function getProfile()
    {
        $admin = Auth::user(); 
    
        if (!$admin) {
        return response()->json(['message' => 'Admin not found'], 404);
        }
    
        return response()->json([
        'name' => $admin->name,
        'username' => $admin->username,
        'email' => $admin->email,
        'phone' => $admin->phone,
        'avatar' => $admin->avatar ?? 'https://i.pravatar.cc/100?u=' . urlencode($admin->email),
        ]);
    }
    // Return all active admins
    public function getActiveAdmins()
{
        $admins = Admin::all(); // Get all admins
    
        $data = $admins->map(function ($admin) {
        return [
        'name' => $admin->name,
        'email' => $admin->email,
        'avatar' => $admin->avatar ?? 'https://i.pravatar.cc/100?u=' . $admin->email
        ];
    });

    return response()->json($data);
    }

     Public function stores(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Admin created successfully',
        'data' => $admin
    ], 201);
    }

}
