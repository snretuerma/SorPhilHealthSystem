<?php

namespace App\Http\Controllers;

use Auth;

use App\Models\User;

use App\Http\Requests\resetPassRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function resetObserver()
    {
        return view('roles.observer.reset');
    }

    public function resetPass(resetPassRequest $request)
    {
        $new_pass = User::find(Auth::user()->id);
        $new_pass->password = Hash::make($request->password);
        $new_pass->save();
    }
}
