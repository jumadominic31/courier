<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ParcelType;
use App\ParcelStatus;
use App\Company;
use JWTAuth;
use Validator;
use Auth;
use Session;

class ParcelsController extends Controller
{
    public function index()
    {
    	$company_id = Auth::user()->company_id;
    	$parceltype = ParcelType::where('company_id', '=', $company_id)->get();
    	$parcelstatus = ParcelStatus::get();

        return view('parcel.index', ['parceltype' => $parceltype, 'parcelstatus' => $parcelstatus]);
    }

    public function getparcelTypes()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$parceltype = ParcelType::where('company_id', '=', $company_id)->get();

    	return response()->json(['parceltype' => $parceltype], 201);
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $parceltype = ParcelType::where('company_id', '=', $company_id)->find($id);
        if ($parceltype == null){
            return redirect('/parcel')->with('error', 'Parcel type not found');
        }
        $companies = Company::pluck('name','id');
        return view('parcel.edit',['parceltype'=> $parceltype, 'companies' => $companies]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'unit' => 'required',
            'rate' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        
        $parceltype = ParcelType::find($id);
        $parceltype->name = $request->input('name');
        $parceltype->unit = $request->input('unit');
        $parceltype->rate = $request->input('rate');
        if (Auth::user()->usertype == 'superadmin') {
            $parceltype->company_id = $request->input('company_id');;
        } 
        else {
            $parceltype->company_id = $company_id;
        }
        $parceltype->updated_by = $user_id;
        $parceltype->save();
        
        return redirect('/parcel')->with('success', 'ParcelType details updated');
    }

    public function create()
    {
        $companies = Company::pluck('name','id');
        return view('parcel.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'unit' => 'required',
            'rate' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        $existing = ParcelType::where('company_id', '=', $company_id)->pluck('name')->toArray();
        $name = $request->input('name');
        $name = strtolower($name);
        if (in_array($name, array_map("strtolower", $existing))){
            return redirect('/parcel')->with('error', 'ParcelType Exists');
        }

        $parceltype = new ParcelType;
        $parceltype->name = $request->input('name');
        $parceltype->unit = $request->input('unit');
        $parceltype->rate = $request->input('rate');
        if (Auth::user()->usertype == 'superadmin') {
            $parceltype->company_id = $request->input('company_id');;
        } 
        else {
            $parceltype->company_id = $company_id;
        }
        $parceltype->updated_by = $user_id;
        $parceltype->save();

        return redirect('/parcel')->with('success', 'ParcelType Created');
    }

    public function destroy($id)
    {
        $parceltype = ParcelType::find($id);
        $parceltype->delete();
        return redirect('/parcel')->with('success', 'ParcelType Deleted');
    }
}
