<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Zone;
use App\Station;
use App\Company;
use App\User;
use Auth;

class ZonesController extends Controller
{
    public function index()
    {   
        if (Auth::user()->usertype == 'superadmin') {
            $zones = Zone::orderBy('company_id','asc')->paginate(10);
        }
        else {
            $company_id = Auth::user()->company_id;
            $zones = Zone::where('company_id', '=', $company_id)->orderBy('name','asc')->paginate(10);
        }
        return view('zone.index', ['zones' => $zones]);
    }

    public function show($id)
    {
    	$company_id = Auth::user()->company_id;
    	$zone = Zone::where('company_id', '=', $company_id)->find($id);
        if ($zone == null){
            return redirect('/zone')->with('error', 'Zone not found');
        }
        if (Auth::user()->usertype == 'superadmin') {
            $stations = Station::orderBy('company_id','asc')->paginate(10);
        }
        else {
            $company_id = Auth::user()->company_id;
            $stations = Station::where('company_id', '=', $company_id)->where('zone_id','=', $id)->orderBy('name','asc')->paginate(10);
        }
        $zone_name = Zone::select('name')->where('id', '=', $id)->pluck('name')->first();
        return view('zone.show', ['stations' => $stations, 'zone_name' => $zone_name]);
    }

    public function getzones($id)
    {
        $company_id = Auth::user()->company_id;
        $zones = Zone::where('company_id', '=', $company_id)->where('id', '!=', $id)->select('id','name')->get();
        return response()->json($zones);
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $zone = Zone::where('company_id', '=', $company_id)->find($id);
        if ($zone == null){
            return redirect('/zone')->with('error', 'Zone not found');
        }
        $companies = Company::pluck('name','id');
        return view('zone.edit',['zone'=> $zone, 'companies' => $companies]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        
        $zone = Zone::find($id);
        $zone->name = $request->input('name');
        $zone->status = $request->input('status');
        if (Auth::user()->usertype == 'superadmin') {
            $zone->company_id = $request->input('company_id');;
        } 
        else {
            $zone->company_id = $company_id;
        }
        $zone->updated_by = $user_id;
        $zone->save();
        
        return redirect('/zone')->with('success', 'Zone details updated');
    }

    public function create()
    {
        $companies = Company::pluck('name','id');
        return view('zone.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        $existing = Zone::where('company_id', '=', $company_id)->pluck('name')->toArray();
        $name = $request->input('name');
        $name = strtolower($name);
        if (in_array($name, array_map("strtolower", $existing))){
            return redirect('/zone')->with('error', 'Zone Exists');
        }

        $zone = new Zone;
        $zone->name = $request->input('name');
        $zone->status = $request->input('status');
        if (Auth::user()->usertype == 'superadmin') {
            $zone->company_id = $request->input('company_id');;
        } 
        else {
            $zone->company_id = $company_id;
        }
        $zone->updated_by = $user_id;
        $zone->save();

        return redirect('/zone')->with('success', 'Zone Created');
    }

    public function destroy($id)
    {
        $zone = Zone::find($id);
        $stationcnt = Station::select('id')->where('zone_id','=',$id)->get()->count();
        if ($stationcnt > 0){
            return redirect('/zone')->with('error', 'Zone has associated stations which should be deleted first');
        }
        $zone->delete();
        return redirect('/zone')->with('success', 'Zone Deleted');
    }
}
