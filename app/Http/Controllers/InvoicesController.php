<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Invoice;
use App\Txn;
use App\TxnLog;
use App\Company;
use App\User;
use App\UserLog;
use App\Station;
use App\Zone;
use App\ParcelStatus;
use App\ParcelType;
use App\Vehicle;
use JWTAuth;
use Validator;
use Auth;
use Session;
use PDF;

class InvoicesController extends Controller
{
	public function getInvoices(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $company_details = Company::where('id', '=', $company_id)->get();
        $curr_date = date('Y-m-d');
        
        $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name', 'id')->all();
        $tot_amount = 0;
        $tot_paid = 0;
        $tot_bal = 0;
        $tot_count = 0;

        $invoice_num = $request->input('invoice_num');
        $sender_company_id = $request->input('sender_company_id');
        $first_date = $request->input('first_date');
        $last_date = $request->input('last_date');
        
        if ($request->isMethod('POST')){
            $invoices = Invoice::where('company_id', '=', $company_id);
            $tot_amount = Invoice::select('company_id', DB::raw('sum(amount) as tot_amount'))->where('company_id', '=', $company_id);
            $tot_paid = Invoice::select('company_id', DB::raw('sum(paid) as tot_paid'))->where('company_id', '=', $company_id);
            $tot_bal = Invoice::select('company_id', DB::raw('sum(bal) as tot_bal'))->where('company_id', '=', $company_id);

            if ($invoice_num != NULL){
                $invoices = $invoices->where('invoice_num','like','%'.$invoice_num.'%');
                $tot_amount = $tot_amount->where('invoice_num','like','%'.$invoice_num.'%');
                $tot_paid = $tot_paid->where('invoice_num','like','%'.$invoice_num.'%');
                $tot_bal = $tot_bal->where('invoice_num','like','%'.$invoice_num.'%');
            }
            if ($sender_company_id != NULL){
                $invoices = $invoices->where('sender_company_id','=', $sender_company_id);
                $tot_amount = $tot_amount->where('sender_company_id','=', $sender_company_id);
                $tot_paid = $tot_paid->where('sender_company_id','=', $sender_company_id);
                $tot_bal = $tot_bal->where('sender_company_id','=', $sender_company_id);
            }
            if ($first_date != NULL){
                if ($last_date != NULL){
                    $invoices = $invoices->where(DB::raw('date(created_at)'), '<=', $last_date)->where(DB::raw('date(created_at)'),'>=',$first_date);
                    $tot_amount = $tot_amount->where(DB::raw('date(created_at)'), '<=', $last_date)->where(DB::raw('date(created_at)'),'>=',$first_date);
                    $tot_paid = $tot_paid->where(DB::raw('date(created_at)'), '<=', $last_date)->where(DB::raw('date(created_at)'),'>=',$first_date);
                    $tot_bal = $tot_bal->where(DB::raw('date(created_at)'), '<=', $last_date)->where(DB::raw('date(created_at)'),'>=',$first_date);
                } 
                else{
                    $invoices = $invoices->where(DB::raw('date(created_at)'), '=', $first_date);
                    $tot_amount = $tot_amount->where(DB::raw('date(created_at)'), '=', $first_date);
                    $tot_paid = $tot_paid->where(DB::raw('date(created_at)'), '=', $first_date);
                    $tot_bal = $tot_bal->where(DB::raw('date(created_at)'), '=', $first_date);
                }
            }

            $tot_count = $invoices->count();
            
            $tot_amount = $tot_amount->groupBy('company_id')->pluck('tot_amount')->first();
            $tot_paid = $tot_paid->groupBy('company_id')->pluck('tot_paid')->first();
            $tot_bal = $tot_bal->groupBy('company_id')->pluck('tot_bal')->first();

            if ($tot_amount == NULL) {
                $tot_amount = 0;
            }
            if ($tot_paid == NULL) {
                $tot_paid = 0;
            }
            if ($tot_bal == NULL) {
                $tot_bal = 0;
            }

            //setting defaults for options
            if ($invoice_num == NULL){
                $invoice_num = 'All';
            }
            if ($sender_company_id == '0') {
                $sender_company_name = 'Others';
            } 
            else if ($sender_company_id != NULL) {
                $sender_company_name = Company::where('id', '=', $sender_company_id)->pluck('name')->first();
            } 
            else {
                $sender_company_name = 'All';
            }
                        
            if ($request->submitBtn == 'CreatePDF') {
                $invoices = $invoices->orderBy('id','desc')->limit(50)->get();
                $pdf = PDF::loadView('pdf.invoice_list', ['invoices' => $invoices, 'company_details' => $company_details, 'curr_date' => $curr_date, 'tot_amount' => $tot_amount, 'tot_paid' => $tot_paid, 'tot_bal' => $tot_bal, 'tot_count' => $tot_count, 'invoice_num' => $invoice_num, 'sender_company_name' => $sender_company_name, 'first_date' => $first_date, 'last_date' => $last_date]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('invoice_list.pdf');
            }

            $invoices = $invoices->orderBy('id','desc')->paginate(10);
        }
        else {
            $tot_count = Invoice::where('company_id','=',$company_id)->count();
            $tot_amount = Invoice::select('company_id', DB::raw('sum(amount) as tot_amount'))->where('company_id', '=', $company_id)->groupBy('company_id')->pluck('tot_amount')->first();
            $tot_paid = Invoice::select('company_id', DB::raw('sum(paid) as tot_paid'))->where('company_id', '=', $company_id)->groupBy('company_id')->pluck('tot_paid')->first();
            $tot_bal = Invoice::select('company_id', DB::raw('sum(bal) as tot_bal'))->where('company_id', '=', $company_id)->groupBy('company_id')->pluck('tot_bal')->first();
            $invoices = Invoice::where('company_id','=',$company_id)->orderBy('id','desc')->paginate(10);
            if ($tot_amount == NULL) {
                $tot_amount = 0;
            }
        }

        return view('invoice.index', ['invoices' => $invoices, 'tot_amount' => $tot_amount, 'tot_paid' => $tot_paid, 'tot_bal' => $tot_bal, 'tot_count' => $tot_count, 'cuscompanies' => $cuscompanies]);
    }

    public function selTxns($id)
    {
    	$company_id = Auth::user()->company_id;
    	$txns = Txn::where('company_id','=',$company_id)->where('sender_company_id','=',$id)->where('invoiced','=','0')->where('price','!=',NULL)->orderBy('id','desc')->get();
    	return response()->json($txns);
    }

    public function addInvoice()
    {
    	$company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name', 'id')->all();
        $company_details = Company::where('id', '=', $company_id)->get();
        // $txns = Txn::where('company_id','=',$parent_company_id)->where('invoiced','=','0')->where('price','!=',NULL)->orderBy('id','desc')->get();
        $txns = Txn::where('company_id','=','0')->get();
    	return view('invoice.add', [ 'cuscompanies' => $cuscompanies]);
    }

    public function storeInvoice(Request $request)
    {
    	$user = Auth::user();
        $company_id = Auth::user()->company_id;
		$validator = Validator::make(($request->all()), [
            'txn_id' => 'required'
        ]);

    	$sel_txns = $request->input('txn_id');
    	// $txns = implode(" ", $sel_txns);
    	$count = 0;
    	$tot_amount = 0;

    	$last_invoice = Invoice::where('company_id','=',$company_id)->orderBy('id','desc')->pluck('invoice_num')->first();
    	$last_invoice = substr($last_invoice, 3, 5);
    	if ($last_invoice == NULL){
    		$last_invoice = 10000;
    	}
    	$curr_invoice = $last_invoice + 1;
    	$prefix = Company::where('id', '=', $company_id)->pluck('name')->first();
    	$prefix = strtoupper($prefix);
    	$prefix = substr($prefix, 0, 3);
    	$curr_invoice = $prefix.$curr_invoice;

    	if (count($sel_txns) > 0){
    		
	    	foreach ($sel_txns as $sel){
	    		//calculate invoice count, totals, sender_company_id
	    		$txn = Txn::find($sel);
	    		$count += 1;
	    		$tot_amount += $txn->price;
	    		$sender_company_id = $txn->sender_company_id;
	    	}

	    	//create invoice
	    	$invoice = new Invoice;
	    	$invoice->invoice_num = $curr_invoice;
	    	$invoice->company_id = $company_id;
	    	$invoice->sender_company_id = $sender_company_id;
	    	$invoice->amount = $tot_amount;
	    	$invoice->paid = 0;
	    	$invoice->bal = $tot_amount;
	    	$invoice->save();

	    	foreach ($sel_txns as $sel){
	    		//update invoice details
	    		$txn = Txn::find($sel);
	    		$txn->invoiced = 1;
	    		$txn->updated_by = $user->id;
	    		$txn->invoice_id = $invoice->id;
	    		$txn->save();
	    	}
	    }
    	return redirect('/invoice')->with('success', 'Invoice Added. Count: '. $count .' Total: '.$tot_amount );
    }

    public function voidInvoice($id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;

        //void invoice
        $invoice = Txn::find($id);
        $invoice->amount = 0;
        $invoice->bal = 0 - $invoice->paid;
        $invoice->save();

        $invoice_num = $invoice->invoice_num;

        $sel_txns = Txn::select('id')->where('invoice_id', '=', $id)->pluck('id')->toArray();
        if (count($sel_txns) > 0){
            foreach ($sel_txns as $sel){
                //update invoice details
                $txn = Txn::find($sel);
                $txn->invoiced = 0;
                $txn->updated_by = $user->id;
                $txn->invoice_id = NULL;
                $txn->save();
            }

        }
        return redirect('/invoice')->with('success', 'Invoice '. $invoice_num .' voided. ');
    }

    public function showInvoice($id)
    {
    	$user = Auth::user();
    	$company_id = Auth::user()->company_id;

    	$txns = Txn::where('invoice_id', '=', $id)->get();
    	$invoice = Invoice::where('id', '=', $id)->first();

    	return view('invoice.show', ['txns' => $txns, 'invoice' => $invoice]);
    }
}
