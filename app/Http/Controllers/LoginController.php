<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate()
    {
        if (Auth::attempt(['username' => request()->username, 'password' => request()->password])) {
            // Authentication passed...
            redirect()->intended('/');
            return response()->json(['username' => Auth::user()->username, 'name' => Auth::user()->name]);
        }
        return ['success' => false];
    }
}
