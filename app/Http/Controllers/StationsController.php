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
        $station = Station::where('company_id', '=', $company_id)->find($id);

        if ($station == null){
            return redirect('/station')->with('error', 'Station not found');
        }
        $companies = Company::pluck('name','id');
        return view('station.edit',['station'=> $station, 'companies' => $companies]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        
        $station = Station::find($id);
        $station->name = $request->input('name');
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
        return view('station.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
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

    public function cusbranches($id)
    {
        $company_id = Auth::user()->company_id;
        $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '=', $id)->where('id', '!=', $company_id)->select('id')->count();
        if ($cuscompanies == 0 ){
            return redirect('/customer')->with('error', 'Company Not Found');
        }
        $stations = Station::where('company_id', '=', $id)->get();
        $company_name = Company::where('id', '=', $id)->pluck('name')->first();
        return view('cusbranches.index',['stations'=> $stations, 'company_id' => $id, 'company_name' => $company_name]);
    }

    public function cusbranchcreate($id)
    {
        $parent_company_id = Auth::user()->company_id;
        // $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name','id')->all();
        $cuscompanies = Company::where('parent_company_id', '=', $parent_company_id)->where('id', '=', $id)->where('id', '!=', $parent_company_id)->select('id')->count();
        $company_name = Company::select('name')->where('id', '=', $id)->pluck('name')->first();
        if ($cuscompanies == 0 ){
            return redirect('/customer')->with('error', 'Company Not Found');
        }
        $stations = Station::where('company_id', '=', $id)->pluck('name','id')->all();
        return view('cusbranches.create', ['branches' => $stations, 'company_id' => $id, 'company_name' => $company_name]);
    }

    public function cusbranchstore(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required'
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        $company_id = $id;

        $existing = Station::where('company_id', '=', $company_id)->pluck('name')->toArray();
        $name = $request->input('name');
        $name = strtolower($name);
        if (in_array($name, array_map("strtolower", $existing))){
            return redirect('cusbranches/'.$company_id)->with('error', 'Station Exists');
        }

        $station = new Station;
        $station->name = $request->input('name');
        $station->building = $request->input('building');
        $station->floor_office = $request->input('floor_office');
        $station->street = $request->input('street');
        $station->area = $request->input('area');
        $station->status = $request->input('status');
        $station->company_id = $company_id;
        $station->updated_by = $user_id;
        $station->save();

        return redirect('cusbranches/'.$company_id)->with('success', 'Station Created');
    }

    public function editCusbranch($id)
    {
        $parent_company_id = Auth::user()->company_id;
        $user_id = Auth::user()->id;
        $company_id = Station::select('company_id')->where('id', '=', $id)->pluck('company_id')->first();
        $company_name = Company::select('name')->where('id', '=', $company_id)->pluck('name')->first();
        $station = Station::where('company_id', '!=', $parent_company_id)->find($id);
        if ($station == NULL){
            return redirect('/customer')->with('error', 'Station Not Found');
        }

        return view('cusbranches.edit',['station' => $station, 'company_id' => $company_id, 'company_name' => $company_name]);
    }

    public function updateCusbranch(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $parent_company_id = Auth::user()->company_id;
        $company_id = Station::select('company_id')->where('id', '=', $id)->pluck('company_id')->first();
        
        $station = Station::find($id);
        $station->name = $request->input('name');
        $station->building = $request->input('building');
        $station->floor_office = $request->input('floor_office');
        $station->street = $request->input('street');
        $station->area = $request->input('area');
        $station->status = $request->input('status');
        $station->updated_by = $user_id;
        $station->save();
        
        return redirect('/cusbranches/'.$company_id)->with('success', 'Station details updated');
    }
}
