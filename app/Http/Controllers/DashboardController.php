<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Vehicle;
use App\Station;
use App\Company;
use App\User;
use App\Txn;
use App\TxnLog;
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
        $customers = Company::where('parent_company_id', '=', $company_id)->count();
        $drivers = User::where('company_id', '=', $company_id)->where('usertype', '=', 'driver')->count();
        $clerks = User::where('company_id', '=', $company_id)->where('usertype', '=', 'clerk')->count();
        
        //$sales = Txn::where('company_id', '=', $company_id)->select('company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('company_id')->pluck('total_sales')->first();
        $sales = Txn::where('company_id', '=', $company_id)->select('company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('company_id')->pluck('total_sales')->first();
        if ($sales == NULL){
            $sales = 0;
        }
        $booked = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','7')->count();
        $dispatched = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','8')->count();
        $received = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','4')->count();
        $topsales = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->select('sender_company_id', DB::raw('sum(price) as total_sales'))->groupBy('sender_company_id')->orderBy('total_sales', 'desc')->limit(3)->get();
        //$parcels = Txn::where('company_id', '=', $company_id)->select('origin_id', 'parcel_status_id', DB::raw('count(parcel_status_id) as status_count'))->groupBy('parcel_status_id')->groupBy('origin_id')->get();
        $parcels = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->select('sender_company_id', DB::raw("COUNT( CASE WHEN parcel_status_id = '1' THEN 1 ELSE NULL END ) AS 'created'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '2' THEN parcel_status_id ELSE NULL END ) AS 'dispatched'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '3' THEN parcel_status_id ELSE NULL END ) AS 'delivered'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '4' THEN parcel_status_id ELSE NULL END ) AS 'received'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '5' THEN parcel_status_id ELSE NULL END ) AS 'lost'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '7' THEN parcel_status_id ELSE NULL END ) AS 'booked'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '8' THEN parcel_status_id ELSE NULL END ) AS 'picked'"))->groupBy('sender_company_id')->get();

    	return view('dashboard.index', ['vehicles' => $vehicles, 'stations' => $stations, 'customers' => $customers, 'drivers' => $drivers, 'clerks' => $clerks, 'sales' => $sales, 'booked' => $booked, 'dispatched' => $dispatched, 'received' => $received, 'topsales' => $topsales, 'parcels' => $parcels]);
    }

    public function courier()
    {
        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        $curr_date = date('Y-m-d');

        $vehicles = Vehicle::where('company_id', '=', $company_id)->count();
        $stations = Station::where('company_id', '=', $company_id)->count();
        $customers = Company::where('parent_company_id', '=', $company_id)->count();
        $drivers = User::where('company_id', '=', $company_id)->where('usertype', '=', 'driver')->count();
        $clerks = User::where('company_id', '=', $company_id)->where('usertype', '=', 'clerk')->count();
        
        //$sales = Txn::where('company_id', '=', $company_id)->select('company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('company_id')->pluck('total_sales')->first();
        $sales = Txn::where('company_id', '=', $company_id)->select('company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('company_id')->pluck('total_sales')->first();
        if ($sales == NULL){
            $sales = 0;
        }
        $booked = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','7')->count();
        $dispatched = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','8')->count();
        $received = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','4')->count();
        $topsales = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->select('sender_company_id', DB::raw('sum(price) as total_sales'))->groupBy('sender_company_id')->orderBy('total_sales', 'desc')->limit(3)->get();
        //$parcels = Txn::where('company_id', '=', $company_id)->select('origin_id', 'parcel_status_id', DB::raw('count(parcel_status_id) as status_count'))->groupBy('parcel_status_id')->groupBy('origin_id')->get();
        $parcels = Txn::where('company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->select('sender_company_id', DB::raw("COUNT( CASE WHEN parcel_status_id = '1' THEN 1 ELSE NULL END ) AS 'created'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '2' THEN parcel_status_id ELSE NULL END ) AS 'dispatched'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '3' THEN parcel_status_id ELSE NULL END ) AS 'delivered'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '4' THEN parcel_status_id ELSE NULL END ) AS 'received'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '5' THEN parcel_status_id ELSE NULL END ) AS 'lost'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '7' THEN parcel_status_id ELSE NULL END ) AS 'booked'"), DB::raw("COUNT( CASE WHEN parcel_status_id = '8' THEN parcel_status_id ELSE NULL END ) AS 'picked'"))->groupBy('sender_company_id')->get();

        return view('dashboard.index', ['vehicles' => $vehicles, 'stations' => $stations, 'customers' => $customers, 'drivers' => $drivers, 'clerks' => $clerks, 'sales' => $sales, 'booked' => $booked, 'dispatched' => $dispatched, 'received' => $received, 'topsales' => $topsales, 'parcels' => $parcels]);
    }

    public function customer()
    {
        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        $curr_date = date('Y-m-d');

        $costs = Txn::where('sender_company_id', '=', $company_id)->select('sender_company_id', DB::raw('sum(price) as total_sales'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '=', $curr_date)->groupBy('sender_company_id')->pluck('total_sales')->first();
        if ($costs == NULL){
            $costs = 0;
        }

        $booked = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','7')->count();
        $dispatched = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','8')->count();
        $received = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)->where('parcel_status_id','=','4')->count();

        $parcels = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '=', $curr_date)
            ->select('sender_name', DB::raw("COUNT( CASE WHEN parcel_status_id = '1' THEN 1 ELSE NULL END ) AS 'created'"), 
                DB::raw("COUNT( CASE WHEN parcel_status_id = '2' THEN parcel_status_id ELSE NULL END ) AS 'dispatched'"), 
                DB::raw("COUNT( CASE WHEN parcel_status_id = '3' THEN parcel_status_id ELSE NULL END ) AS 'delivered'"), 
                DB::raw("COUNT( CASE WHEN parcel_status_id = '4' THEN parcel_status_id ELSE NULL END ) AS 'received'"), 
                DB::raw("COUNT( CASE WHEN parcel_status_id = '5' THEN parcel_status_id ELSE NULL END ) AS 'lost'"), 
                DB::raw("COUNT( CASE WHEN parcel_status_id = '7' THEN parcel_status_id ELSE NULL END ) AS 'booked'"), 
                DB::raw("COUNT( CASE WHEN parcel_status_id = '8' THEN parcel_status_id ELSE NULL END ) AS 'picked'"))
            ->groupBy('sender_name')->get();

        return view('dashboard.customer', ['costs' => $costs, 'booked' => $booked, 'dispatched' => $dispatched, 'received' => $received, 'parcels' => $parcels]);
    }
}
