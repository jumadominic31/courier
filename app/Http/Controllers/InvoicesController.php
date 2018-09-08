<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Invoice;
use App\Nuinvoice;
use App\Txn;
use App\TxnLog;
use App\Company;
use App\Contract;
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
use Carbon\Carbon;

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
    public function getInvoices2(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $company_details = Company::where('id', '=', $company_id)->get();
        $curr_date = date('Y-m-d');
        
        $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name', 'id')->all();

        $invoice_num = $request->input('invoice_num');
        $company_id = $request->input('company_id');
        $month = $request->input('month');
        
        if ($request->isMethod('POST')){
            $invoices = Nuinvoice::where('voided', '=', '0')->where('parent_company_id', '=', $parent_company_id);

            if ($invoice_num != NULL){
                $invoices = $invoices->where('invoice_num','like','%'.$invoice_num.'%');
            }
            if ($company_id != NULL){
                $invoices = $invoices->where('company_id','=', $company_id);
            }
            if ($month != NULL){
                $invoices = $invoices->where(DB::raw('DATE_FORMAT(month, "%Y-%m")'), '=', $month);
            }
                        
            if ($request->submitBtn == 'CreatePDF') {
                $invoices = $invoices->orderBy('id','desc')->limit(50)->get();
                $pdf = PDF::loadView('pdf.invoice_list', ['invoices' => $invoices, 'company_details' => $company_details]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('invoice_list.pdf');
            }

            $invoices = $invoices->orderBy('month','desc')->get();
        }
        else {
            $invoices = Nuinvoice::where('voided', '=', '0')->where('parent_company_id','=',$parent_company_id)->orderBy('month','desc')->get();
        }

        return view('invoice.index2', ['invoices' => $invoices, 'cuscompanies' => $cuscompanies]);
    }

    public function selTxns($id)
    {
    	$company_id = Auth::user()->company_id;
    	$txns = Txn::join('companies as c', 'txns.sender_company_id', '=', 'c.id')
    		->join('parcel_types as partype', 'txns.parcel_type_id', '=', 'partype.id')
    		->join('parcel_statuses as parstat', 'txns.parcel_status_id', '=', 'parstat.id')
    		->select('txns.id as id', 'c.name as sender_company_name', 'txns.awb_num as awb_num', 'txns.origin_addr as origin_addr', 'txns.dest_addr as dest_addr', 'partype.name as parcel_type', 'txns.price as price', 'txns.vat as vat', DB::raw('(CASE WHEN txns.mode = 1 THEN "Express" ELSE "Normal" END) AS mode'), 'parstat.name as parcel_status', 'txns.created_at as created_at', DB::raw('(CASE WHEN txns.invoiced = 1 THEN "Yes" ELSE "No" END) AS invoiced'))
    		->where('txns.company_id','=',$company_id)
    		->where('txns.sender_company_id','=',$id)
    		->where('txns.invoiced','=','0')
    		->where('txns.price','!=',NULL)
    		->orderBy('txns.id','desc')
    		->get();
    	return response()->json($txns);
    }

    public function addInvoice()
    {
    	$company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name', 'id')->all();;
    	return view('invoice.add', [ 'cuscompanies' => $cuscompanies]);
    }

    public function storeInvoice(Request $request)
    {
    	$user = Auth::user();
        $company_id = Auth::user()->company_id;

        $this->validate($request, [
            'txn_id' => 'required'
        ]);

    	$sel_txns = $request->input('txn_id');
        if ($sel_txns != NULL) {
    	   $txns = implode(" ", $sel_txns);
        }
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
            $invoice->vat = $tot_amount * 0.16;
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

    public function addInvoice2()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $company_id = $user->company_id;

        // $cuscompanies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name', 'id')->all();
        $cuscompanies = DB::table('companies as comp')->join('contracts as contr', 'comp.id', '=', 'contr.company_id')->where('comp.parent_company_id', '=', $company_id)->where('comp.id', '!=', $company_id)->where('contr.status', '=', '1')->pluck('comp.name', 'comp.id')->all();

        return view('invoice.add2', [ 'cuscompanies' => $cuscompanies]);
    }

    public function storeInvoice2(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $parent_company_id = $user->company_id;

        $this->validate($request, [
            'company_id' => 'required',
            'month' => 'required|date'
        ]);

        $curr_date = date('Y-m');
        $company_id = $request->input('company_id');
        $month = $request->input('month');
        $formatted_month = $month."-01 00:00:01";

        $last_invoice = Nuinvoice::where('parent_company_id','=',$parent_company_id)->orderBy('id','desc')->pluck('invoice_num')->first();
        $last_invoice = substr($last_invoice, 3, 5);
        if ($last_invoice == NULL){
            $last_invoice = 20000;
        }
        $curr_invoice = $last_invoice + 1;
        $prefix = Company::where('id', '=', $parent_company_id)->pluck('name')->first();
        $prefix = strtoupper($prefix);
        $prefix = substr($prefix, 0, 3);
        $curr_invoice = $prefix.$curr_invoice;

        //check if month is ended or invoice exists
        if ($month >= $curr_date) {
            return redirect('/invoice/add2')->with('error', 'Cannot create invoice. Choose upto the previous month ');   
        }

        $invoice_exist = Nuinvoice::where('company_id', '=', $company_id)->where('voided', '=', 0)->where(DB::raw('DATE_FORMAT(month, "%Y-%m")'), '=', $month)->count();
        if ($invoice_exist > 0) {
            return redirect('/invoice/add2')->with('error', 'Invoice Existing. ');
        }

        // Extracting invoice details
        $contract_id = Contract::where('company_id', '=', $company_id)->where('status', '=', '1')->pluck('id')->first();
        $min_charge = Contract::where('id', '=', $contract_id)->pluck('min_charge')->first();
        $min_txns = Contract::where('id', '=', $contract_id)->pluck('txns_limit')->first();
        $txn_cost_overlimit = Contract::where('id', '=', $contract_id)->pluck('txn_cost_overlimit')->first();
        $big_luggage = Contract::where('id', '=', $contract_id)->pluck('big_luggage')->first();
        $out_coverage = Contract::where('id', '=', $contract_id)->pluck('out_coverage')->first(); 

        $txns = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(txn_date, "%Y-%m")'), '=', $month)->count();
        $big_luggage_txns = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(txn_date, "%Y-%m")'), '=', $month)->where('big_luggage', '=', '1')->count();
        $out_coverage_txns = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('DATE_FORMAT(txn_date, "%Y-%m")'), '=', $month)->where('out_coverage', '=', '1')->count();
        $total_txns = $txns - ($big_luggage_txns + $out_coverage_txns);
        $big_luggage_charge = $big_luggage_txns * $big_luggage;
        $out_coverage_charge = $out_coverage_txns * $out_coverage;
        if ($total_txns <= $min_txns){
            $extra_txns = 0;
            $extra_charge = 0;
        }
        else {
            $extra_txns = $total_txns - $min_txns;
            $extra_charge = $extra_txns * $txn_cost_overlimit;
        }
        $subtotal_charge = $min_charge + $extra_charge + $big_luggage_charge + $out_coverage_charge;
        $vat = 0.16 * $subtotal_charge;
        
        //post invoice
        $nuinvoice = new Nuinvoice;
        $nuinvoice->invoice_num = $curr_invoice;
        $nuinvoice->company_id = $company_id;
        $nuinvoice->parent_company_id = $parent_company_id;
        $nuinvoice->month = $formatted_month;
        $nuinvoice->contract_id = $contract_id;
        $nuinvoice->min_txns = $min_txns;
        $nuinvoice->extra_txns = $extra_txns;
        $nuinvoice->total_txns = $total_txns;
        $nuinvoice->big_luggage_txns = $big_luggage_txns;
        $nuinvoice->out_coverage_txns = $out_coverage_txns;
        $nuinvoice->min_charge = $min_charge;
        $nuinvoice->extra_charge = $extra_charge;
        $nuinvoice->big_luggage_charge = $big_luggage_charge;
        $nuinvoice->out_coverage_charge = $out_coverage_charge;
        $nuinvoice->subtotal_charge = $subtotal_charge;
        $nuinvoice->discount = 0;
        $nuinvoice->total_charge = $subtotal_charge;
        $nuinvoice->vat = $vat;
        $nuinvoice->paid = 0;
        $nuinvoice->bal = $subtotal_charge;
        $nuinvoice->save();


        return redirect('/invoice2')->with('success', 'Invoice Added. ');
    }    

    public function voidInvoice(Request $request, $id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;

        //void invoice
        $invoice = Nuinvoice::find($id);
        $invoice->voided = 1;
        $invoice->save();

        $invoice_num = $invoice->invoice_num;

        $userlog = new UserLog();
        $userlog->username = $user->username;
        $userlog->activity = "Voided invoice id". $invoice_num;
        $userlog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlog->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlog->company_id = $company_id;
        $userlog->save();

        return redirect('/invoice2')->with('success', 'Invoice '. $invoice_num .' voided. ');
	}

    public function showInvoice($id)
    {
    	$user = Auth::user();
    	$company_id = Auth::user()->company_id;
        $invoice = Invoice::where('company_id', '=', $company_id)->where('id', '=', $id)->first();

        if ($invoice == null){
            return redirect('/invoice')->with('error', 'Invoice not found');
        }

    	$txns = Txn::where('invoice_id', '=', $id)->get();

    	return view('invoice.show', ['txns' => $txns, 'invoice' => $invoice]);
    }

    public function printInvoice($id)
    {
    	$user = Auth::user();
    	$company_id = Auth::user()->company_id;
        $company_details = Company::where('id', '=', $company_id)->first();
        $invoice = Invoice::where('company_id', '=', $company_id)->where('id', '=', $id)->first();
        if ($invoice == null){
            return redirect('/invoice')->with('error', 'Invoice not found');
        }
        
    	$txns = Txn::where('invoice_id', '=', $id)->get();
        $curr_date = new Carbon($invoice->created_at);
        $due_date = $curr_date->addMonth(1);

        $sender_company_id = $invoice->sender_company_id;

        $sender_company_name = Company::select('name')->where('id', '=', $sender_company_id)->pluck('name')->first();
        $sender_cusadmin_fullname = User::select('fullname')->where('company_id', '=', $sender_company_id)->pluck('fullname')->first();
        $sender_cusadmin_phone = User::select('phone')->where('company_id', '=', $sender_company_id)->pluck('phone')->first();

    	return view('invoice.print', ['txns' => $txns, 'invoice' => $invoice, 'company_details' => $company_details, 'sender_company_name' => $sender_company_name, 'sender_cusadmin_fullname' => $sender_cusadmin_fullname, 'sender_cusadmin_phone' => $sender_cusadmin_phone, 'due_date' => $due_date]);
    }

    public function showInvoice2($id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        $invoice = Nuinvoice::where('parent_company_id', '=', $company_id)->where('id', '=', $id)->first();

        if ($invoice == null){
            return redirect('/invoice2')->with('error', 'Invoice not found');
        }

        return view('invoice.show2', ['invoice' => $invoice]);
    }

    public function printInvoice2($id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;

        $company_details = Company::where('id', '=', $company_id)->first();

        $invoice = Nuinvoice::where('voided', '=', '0')->where('parent_company_id', '=', $company_id)->where('id', '=', $id)->first();
        if ($invoice == null){
            return redirect('/invoice2')->with('error', 'Invoice not found');
        }
        $contract_id = Nuinvoice::where('parent_company_id', '=', $company_id)->where('id', '=', $id)->pluck('contract_id')->first();
        $contract = Contract::where('parent_company_id', '=', $company_id)->where('id', '=', $contract_id)->first();

        $curr_date = new Carbon($invoice->created_at);
        $due_date = $curr_date->addMonth(1);

        $invoice_month = new Carbon($invoice->month);
        $invoice_mon = $invoice_month->englishMonth; 
        $invoice_yr = $invoice_month->year;
        $formatted_month = $invoice_mon. '-' .$invoice_yr;

        $sender_company_id = $invoice->company_id;

        $sender_company_name = Company::select('name')->where('id', '=', $sender_company_id)->pluck('name')->first();
        $sender_cusadmin_fullname = User::select('fullname')->where('company_id', '=', $sender_company_id)->pluck('fullname')->first();
        $sender_cusadmin_phone = User::select('phone')->where('company_id', '=', $sender_company_id)->pluck('phone')->first();

        return view('invoice.print2', ['invoice' => $invoice, 'company_details' => $company_details, 'sender_company_name' => $sender_company_name, 'sender_cusadmin_fullname' => $sender_cusadmin_fullname, 'sender_cusadmin_phone' => $sender_cusadmin_phone, 'due_date' => $due_date, 'formatted_month' => $formatted_month, 'contract' => $contract]);
    }
}
