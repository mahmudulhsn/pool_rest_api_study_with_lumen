<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{

    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome to Our Pools Api'
        ]);
    }
}
