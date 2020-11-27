<?php
/**
 * @file HomeController
 * Controller for handling the default behavior of Laravel landing pages
 */

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

/**
 * @category App\Http\Controllers
 * @package
 * @author Shannon Francis Retuerma <snretuerma@up.edu.ph>
 * @author Mark Dy <markdy61@gmail.com>
 * @author Paul Bryan Dy <dy.paulbryan@gmail.com>
 * @author John Kevin Noguera <johnkevin0829@gmail.com>
 * @copyright  2020-present Provincial Government of Sorsogon ICTD
 * @license N/A
 * @version Development v.2
 */
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
    public function index(): Response
    {
        if (Auth::user()->hasRole('admin')) {
            return redirect('/admin');
        }
        if (Auth::user()->hasRole('user')) {
            return redirect('/user');
        }
        if (Auth::user()->hasRole('observer')) {
            return redirect('/observer');
        }
    }
}
