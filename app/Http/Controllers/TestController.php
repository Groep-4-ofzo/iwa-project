<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\View\View; 

class TestController extends Controller{
    public function test(): view {
        $users = User::all();
        return view("test", compact('users'));
    }
}
