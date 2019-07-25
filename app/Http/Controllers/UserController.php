<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     */
    public function index()
    {

        return view('/account/settings', [
            'user' => Auth::user()
        ]);
    }
}
