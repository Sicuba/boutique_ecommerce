<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
        $userData = session('user_data');
        return view('auth.login', ['data'=>$userData]);
    }

    public function store(Request $request){
       return view('auth.login', ['data'=> $request->all()]);
    }
}
