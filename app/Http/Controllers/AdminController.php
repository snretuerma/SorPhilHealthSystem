<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\Budget;
use App\Models\Hospital;
use App\Models\Personnel;
use Auth;
use DB;
use Dotenv\Store\File\Paths;;

use Carbon\Carbon;

use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('roles.admin.index');
    }
    //Personnel
    public function personnel()
    {
        return view('roles.admin.personnel');
    }

    public function getPersonnel()
    {
        $personnel = DB::table('personnels')
            ->join('hospitals', 'personnels.hospital_id', '=', 'hospitals.id')
            ->select('personnels.*', 'hospitals.hospital_code')
            ->whereNull('personnels.deleted_at')
            ->get();
        return $personnel;
    }

    public function addPersonnel(Request $request)
    {
        $personnel = new Personnel;
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel->birthdate = $date;
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->sex = $request->sex;
        $personnel->hospital_id=$request->codeholder;
        $personnel->save();
        return $personnel;
    }

    public function editPersonnel(Request $request)
    {
        $personnel = Personnel::find($request->id);
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel->birthdate = $date;
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->sex = $request->sex;
        $personnel->hospital_id=$request->codeholder;
        $personnel->save();
        return $personnel;
    }

    //Budget
    public function budget()
    {
        return view('roles.admin.budget');
    }

    public function getBudget()
    {
        $budget = DB::table('budgets')
            ->join('hospitals', 'budgets.hospital_id', '=', 'hospitals.id')
            ->select('budgets.*', 'hospitals.hospital_code')
            ->whereNull('budgets.deleted_at')
            ->orderby('start_date','desc')
            ->get();
        return $budget;
    }

    public function addBudget(Request $request)
    {
        $budget = new Budget;
        $startdate = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $enddate= Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->start_date=$startdate;
        $budget->total = $request->total;
        $budget->end_date=$enddate;
        $budget->hospital_id=$request->codeholder;
        $budget->save();
        return $budget;
    }

    public function editBudget(Request $request)
    {
        $budget = Budget::find($request->id);
        $budget->hospital_id = $request->codeholder;
        $budget->total = $request->total;
        $budget->start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $budget->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->save();
        return $budget;
    }
    
}
