<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\User;
use Auth;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('company.index', ['companies' => $companies]);
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        if (Auth::user()->usertype != 'superadmin'){
            $company = Company::findOrFail($company_id);
        }
        else {
            $company = Company::find($id);
        }
        return view('company.edit',['company'=> $company]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'sometimes|nullable|email',
            'logo' => 'image|max:1999'
        ]);
        
        $user_id = Auth::user()->id;

        if ($request->file('logo') != NULL){ 
            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            // Uplaod image
            $path= $request->file('logo')->storeAs('public/logos', $filenameToStore);
        }
        $company = Company::find($id);
        $company->name = $request->input('name');
        $company->address = $request->input('address');
        $company->city = $request->input('city');
        $company->phone = $request->input('phone');
        $company->email = $request->input('email');
        if ($request->input('status') != NULL){
            $company->status = $request->input('status');
        }
        else{
            $company->status = '1';
        }
        if ($request->file('logo') != NULL){
            $company->logo = $filenameToStore;
        }
        if ($request->input('pin') != NULL){
            $company->pin = $request->input('pin');
        }
        $company->updated_by = $user_id;
        $company->save();

        //Update users status
        User::where('company_id', $id)->update(['status' => $company->status]);

        if (Auth::user()->usertype == 'superadmin'){
            return redirect('/company')->with('success', 'Company details updated');
        }
        else {
            return redirect('/users/profile')->with('success', 'Company details updated');
        }
        
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:companies',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'sometimes|nullable|email',
            'logo' => 'image|max:1999',
            'status' => 'required'
        ]);

        $user_id = Auth::user()->id;

        if ($request->file('logo') != NULL){ 
            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            // Uplaod image
            $path= $request->file('logo')->storeAs('public/logos', $filenameToStore);
        }
        $name = $request->input('name');
        $company = new Company;
        $company->name = $name;
        $company->shortname = strtolower(substr($name, 0, strrpos($name, ' ')));
        $company->address = $request->input('address');
        $company->city = $request->input('city');
        $company->phone = $request->input('phone');
        $company->email = $request->input('email');
        $company->status = $request->input('status');
        if ($request->file('logo') != NULL){ 
            $company->logo = $filenameToStore;
        }
        if ($request->input('pin') != NULL){
            $company->pin = $request->input('pin');
        }
        $company->updated_by = $user_id;
        $company->save();

        return redirect('/company')->with('success', 'Company Created');
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        return redirect('/company')->with('success', 'Company Deleted');
    }
}
