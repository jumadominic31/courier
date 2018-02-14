<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;
use App\User;
use App\Company;
use Auth;

class VehiclesController extends Controller
{
    public function index()
    {
        if (Auth::user()->usertype == 'superadmin') {
            $vehicles = Vehicle::orderBy('company_id','asc')->paginate(10);
        }
        else {
            $company_id = Auth::user()->company_id;
            $vehicles = Vehicle::where('company_id', '=', $company_id)->orderBy('name','asc')->paginate(10);
        }
        return view('vehicle.index', ['vehicles' => $vehicles]);
    }

    public function show()
    {
        
    }

    public function getvehicles()
    {
        $company_id = Auth::user()->company_id;
        $vehicles = Vehicle::where('company_id', '=', $company_id)->select('id','name')->get();
        return response()->json($vehicles);
    }

    public function edit($id)
    {
        $comp_id = Auth::user()->company_id;
        $vehicle = Vehicle::where('company_id', '=', $comp_id)->find($id);
        if ($vehicle == null){
            return redirect('/vehicle')->with('error', 'Vehicle not found');
        }
        $companies = Company::pluck('name','id');
        $company_id = $vehicle->company_id;
       	$owners = User::where('usertype','=','vehicleowner')->where('company_id', '=', $company_id)->pluck('fullname', 'id');
        return view('vehicle.edit',['vehicle'=> $vehicle, 'owners' => $owners, 'companies' => $companies]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
            'company_id' => 'sometimes|required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        
        $vehicle = Vehicle::find($id);
        $vehicle->name = $request->input('name');
        $vehicle->status = $request->input('status');
        if (Auth::user()->usertype == 'superadmin') {
            $vehicle->company_id = $request->input('company_id');;
        } 
        else {
            $vehicle->company_id = $company_id;
        }
        $vehicle->updated_by = $user_id;
        $vehicle->save();
        
        return redirect('/vehicle')->with('success', 'Vehicle details updated');
    }

    public function create()
    {
    	$companies = Company::pluck('name','id');
        $company_id = Auth::user()->company_id;
        $owners = User::where('usertype','=','vehicleowner')->where('company_id', '=', $company_id)->pluck('fullname', 'id');
        return view('vehicle.create',['owners' => $owners, 'companies' => $companies]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
            'company_id' => 'sometimes|required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        $vehicle = new Vehicle;
        $vehicle->name = $request->input('name');
        $vehicle->status = $request->input('status');
        if (Auth::user()->usertype == 'superadmin') {
            $vehicle->company_id = $request->input('company_id');;
        } 
        else {
            $vehicle->company_id = $company_id;
        }
        $vehicle->updated_by = $user_id;
        $vehicle->save();

        return redirect('/vehicle')->with('success', 'Vehicle Created');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);
        $vehicle->delete();
        return redirect('/vehicle')->with('success', 'Vehicle Deleted');
    }
}

