<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Station;
use App\Company;
use App\User;
use App\Zone;
use Auth;

class StationsController extends Controller
{
    public function index()
    {   
        if (Auth::user()->usertype == 'superadmin') {
            $stations = Station::orderBy('company_id','asc')->paginate(10);
        }
        else {
            $company_id = Auth::user()->company_id;
            $stations = Station::where('company_id', '=', $company_id)->orderBy('name','asc')->paginate(10);
        }
        return view('station.index', ['stations' => $stations]);
    }

    public function show()
    {
        
    }

    public function getstations($id)
    {
        $company_id = Auth::user()->company_id;
        $stations = Station::where('company_id', '=', $company_id)->where('id', '!=', $id)->select('id','name')->get();
        return response()->json($stations);
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $zones = Zone::where('company_id', '=', $company_id)->pluck('name', 'id')->all();
        $station = Station::where('company_id', '=', $company_id)->find($id);

        if ($station == null){
            return redirect('/station')->with('error', 'Station not found');
        }
        $companies = Company::pluck('name','id');
        return view('station.edit',['station'=> $station, 'companies' => $companies, 'zones' => $zones]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'zone_id' => 'required',
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        
        $station = Station::find($id);
        $station->name = $request->input('name');
        $station->zone_id = $request->input('zone_id');
        $station->status = $request->input('status');
        if (Auth::user()->usertype == 'superadmin') {
            $station->company_id = $request->input('company_id');;
        } 
        else {
            $station->company_id = $company_id;
        }
        $station->updated_by = $user_id;
        $station->save();
        
        return redirect('/station')->with('success', 'Station details updated');
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $companies = Company::pluck('name','id');
        $zones = Zone::where('company_id', '=', $company_id)->pluck('name', 'id')->all();
        return view('station.create', ['companies' => $companies, 'zones' => $zones]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'zone_id' => 'required',
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        $existing = Station::where('company_id', '=', $company_id)->pluck('name')->toArray();
        $name = $request->input('name');
        $name = strtolower($name);
        if (in_array($name, array_map("strtolower", $existing))){
            return redirect('/station')->with('error', 'Station Exists');
        }

        $station = new Station;
        $station->name = $request->input('name');
        $station->zone_id = $request->input('zone_id');
        $station->status = $request->input('status');
        if (Auth::user()->usertype == 'superadmin') {
            $station->company_id = $request->input('company_id');;
        } 
        else {
            $station->company_id = $company_id;
        }
        $station->updated_by = $user_id;
        $station->save();

        return redirect('/station')->with('success', 'Station Created');
    }

    public function destroy($id)
    {
        $station = Station::find($id);
        $usercnt = User::select('id')->where('station_id','=',$id)->get()->count();
        if ($usercnt > 0){
            return redirect('/station')->with('error', 'Station has associated users which should be deleted first');
        }
        $station->delete();
        return redirect('/station')->with('success', 'Station Deleted');
    }
}
