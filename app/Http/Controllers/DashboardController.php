<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Vehicle;
use App\Station;
use App\User;
use App\Txn;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
    	$user_id = Auth::user()->id;
    	$company_id = Auth::user()->company_id;
        $curr_date = date('Y-m-d');

        $vehicles = Vehicle::where('company_id', '=', $company_id)->count();
    	$stations = Station::where('company_id', '=', $company_id)->count();
        $drivers = User::where('company_id', '=', $company_id)->where('usertype', '=', 'driver')->count();
        $clerks = User::where('company_id', '=', $company_id)->where('usertype', '=', 'clerk')->count();
        
        //$sales = Txn::where('company_id', '=', $company_id)->select('company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('company_id')->pluck('total_sales')->first();
        $sales = Txn::where('company_id', '=', $company_id)->select('company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('company_id')->pluck('total_sales')->first();
        $booked = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','1')->count();
        $dispatched = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','2')->count();
        $delivered = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','3')->count();
        $received = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','4')->count();
        $topsales = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->select('origin_id', DB::raw('sum(price) as total_sales'))->groupBy('origin_id')->orderBy('total_sales', 'desc')->limit(3)->get();
        //$parcels = Txn::where('company_id', '=', $company_id)->select('origin_id', 'parcel_status_id', DB::raw('count(parcel_status_id) as status_count'))->groupBy('parcel_status_id')->groupBy('origin_id')->get();
        $parcels = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->select('origin_id', DB::raw("COUNT( CASE WHEN parcel_status_id = '1' THEN 1 ELSE NULL END ) AS 'created'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '2' THEN parcel_status_id ELSE NULL END ) AS 'dispatched'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '3' THEN parcel_status_id ELSE NULL END ) AS 'delivered'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '4' THEN parcel_status_id ELSE NULL END ) AS 'received'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '5' THEN parcel_status_id ELSE NULL END ) AS 'lost'"))->groupBy('origin_id')->get();

    	return view('dashboard.index', ['vehicles' => $vehicles, 'stations' => $stations, 'drivers' => $drivers, 'clerks' => $clerks, 'sales' => $sales, 'booked' => $booked, 'dispatched' => $dispatched, 'delivered' => $delivered, 'received' => $received, 'topsales' => $topsales, 'parcels' => $parcels]);
    }
}
