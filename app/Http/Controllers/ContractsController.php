<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Contract;
use App\User;
use Auth;

class ContractsController extends Controller
{
    public function index()
    {
    	$user = Auth::user();
    	$user_id = $user->id;
    	$parent_company_id = $user->company_id;
        $contracts = Contract::where('parent_company_id', '=', $parent_company_id)->get();

        return view('contracts.index', ['contracts' => $contracts]);
    }

    public function create()
    {
    	$user = Auth::user();
    	$user_id = $user->id;
    	$parent_company_id = $user->company_id;
    	$cuscompanies = Company::where('parent_company_id', '=', $parent_company_id)->where('id', '!=', $parent_company_id)->pluck('name','id')->all();

        return view('contracts.create', ['cuscompanies' => $cuscompanies]);
    }

    public function store(Request $request)
    {
    	$user = Auth::user();
    	$user_id = $user->id;
    	$parent_company_id = $user->company_id;

        $this->validate($request, [
            'company_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'min_charge' => 'required|numeric',
            'txns_limit' => 'required|numeric',
            'txn_cost_overlimit' => 'required|numeric'
        ]);

        $last_contract = Contract::where('parent_company_id', '=', $parent_company_id)->orderBy('id','desc')->pluck('contract_num')->first();
    	$last_contract = substr($last_contract, 3, 5);
    	if ($last_contract == NULL){
    		$last_contract = 10000;
    	}
    	$curr_contract = $last_contract + 1;
    	$prefix = Company::where('id', '=', $parent_company_id)->pluck('name')->first();
    	$prefix = strtoupper($prefix);
    	$prefix = substr($prefix, 0, 3);
    	$curr_contract = $prefix.$curr_contract;

        $company_id = $request->input('company_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $min_charge = $request->input('min_charge');
        $txns_limit = $request->input('txns_limit');
        $txn_cost_overlimit = $request->input('txn_cost_overlimit');

       	$contract = new Contract;
       	$contract->contract_num = $curr_contract;
       	$contract->parent_company_id = $parent_company_id;
       	$contract->company_id = $company_id;
       	$contract->start_date = $start_date;
       	$contract->end_date = $end_date;
       	$contract->min_charge = $min_charge;
       	$contract->txns_limit = $txns_limit;
       	$contract->txn_cost_overlimit = $txn_cost_overlimit;
       	$contract->status = '1';
       	$contract->updated_by = $user_id;
       	$contract->save();

        return redirect('/contracts')->with('success', 'Contract Created');
    }

    public function edit($id)
    {
    	$user = Auth::user();
    	$user_id = $user->id;
    	$parent_company_id = $user->company_id;
    	$contract = Contract::where('parent_company_id', '=', $parent_company_id)->find($id);
    	if ($contract == null){
            return redirect('/contracts')->with('error', 'Contract not found');
        }

        return view('contracts.edit', ['contract'=> $contract]);
    }

    public function update(Request $request, $id)
    {
    	$user = Auth::user();
    	$user_id = $user->id;
    	$parent_company_id = $user->company_id;

        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'min_charge' => 'required|numeric',
            'txns_limit' => 'required|numeric',
            'txn_cost_overlimit' => 'required|numeric',
            'status' => 'required'
        ]);
        
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $min_charge = $request->input('min_charge');
        $txns_limit = $request->input('txns_limit');
        $txn_cost_overlimit = $request->input('txn_cost_overlimit');
        $status = $request->input('status');

        $contract = Contract::find($id);
       	$contract->start_date = $start_date;
       	$contract->end_date = $end_date;
       	$contract->min_charge = $min_charge;
       	$contract->txns_limit = $txns_limit;
       	$contract->txn_cost_overlimit = $txn_cost_overlimit;
       	$contract->status = $status;
       	$contract->updated_by = $user_id;
       	$contract->save();

       	return redirect('/contracts')->with('success', 'Contract updated');        
    }
}
