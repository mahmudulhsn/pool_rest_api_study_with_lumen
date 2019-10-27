<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PDOException;

class UserController extends Controller
{
    public function index()
    {
        $users = app('db')->table('users')->get();
        return response()->json($users);
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'full_name' => 'required',
                'username' => 'required|min:5',
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        try {
            $id = app('db')->table('users')->insertGetId([
                'full_name' => trim($request->input('full_name')),
                'username' =>  strtolower(trim($request->input('username'))),
                'email' =>  strtolower(trim($request->input('email'))),
                'password' =>  app('hash')->make($request->input('password')),
                'created_at' =>  Carbon::now(),
                'updated_at' =>  Carbon::now(),
            ]);

            $user = app('db')->table('users')->select('full_name', 'email', 'username', 'password')
            ->where('id', $id)->first();

            return response()->json([
                'full_name' => $user->full_name,
                'username' =>  $user->username,
                'email' =>  $user->email,
                'password' =>  $user->password,
            ], 201);

        } catch (PDOException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

    }


    // authenticcate the user
    public function authenticate(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
        $token = app('auth')->attempt($request->only('email', 'password'));
        if ($token) {
            return response()->json([
                'success' => true,
                'message' => 'User authenticate.',
                'token' => $token,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid Credential.',
        ], 400);
    }

    // singler profile
    public function me()
    {
        $user = app('auth')->user();
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User Match.',
                'user' => $user,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'User Not Found.',
        ], 404);
    }








}
