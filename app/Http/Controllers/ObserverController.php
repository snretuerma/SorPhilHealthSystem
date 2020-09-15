<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObserverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:observer');
    }

    public function index()
    {
        return view('roles.observer.index');
    }
}
