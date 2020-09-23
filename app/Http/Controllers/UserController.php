<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Column;


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
        $data=Patient::all();
        return Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('sex',function(Patient $patientgender){
            
            return $patientgender->sex===1? "Male" : "Female";
        })
        ->editColumn('marital_status',function(Patient $patientmarital){
            $type = "";
            switch($patientmarital->marital_status){
                case (0):
                    $type='Single';
                break;
                
                case (1):
                    $type='Married';
                break;
                
                case (2):
                    $type='Divorced';
                break;
                
                case (3) :
                    $type='Widowed';
                break;
                
                default:
                $type='Others/Prefer not to say';
            }
            return $patientmarital->marital_status=$type;
        })
        ->make(true);
    }

}
