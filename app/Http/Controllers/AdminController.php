<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\Budget;
use App\Models\Hospital;
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
        return $budget->save();
    }
    
}
