<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    public function index()
    {
        return view('roles.user.index');
    }

    public function patients()
    {
        return view('roles.user.patients');
    }

    public function getPatients()
    {
        return Patient::where('hospital_id', Auth::user()->hospital_id)->get();
    }

    public function deletePatients(Request $req)
    {
        //$data = Data::find($req->iid)->delete();
         return Patient::where('id', $req->id)->delete();
        //return $req->id;
        //return Patient::where('hospital_id', Auth::user()->hospital_id)->get();
    }

}
