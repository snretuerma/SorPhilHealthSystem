<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('admin')) {
            return redirect('/admin');
        }
        if(Auth::user()->hasRole('user')) {
            return redirect('/user');
        }
        if(Auth::user()->hasRole('observer')) {
            return redirect('/observer');
        }
    }
}