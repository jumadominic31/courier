<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Token;
use App\Token_bal;
use App\Token_statement;
use App\Company;
use Auth;

use Illuminate\Http\Request;

class TokensController extends Controller
{
    public function index()
    {
    	$user = Auth::user();
    	$company_id = $user->company_id;
        $token_bal = Token_bal::select(DB::raw('sum(balance) as token_bal'))->where('company_id', '=', $company_id)->pluck('token_bal')->first();
        $tokens = DB::table('token_bals')
                ->join('companies as c', 'token_bals.sender_company_id', '=', 'c.id')
                ->select('c.name as sender_company_name', 'token_bals.balance')
                ->where('company_id', '=', $company_id)->get();

    	return view('token.index', ['tokens' => $tokens, 'token_bal' => $token_bal]);
    }

    public function addToken()
    {
    	$user = Auth::user();
    	$company_id = $user->company_id;
        $user_id = $user->id;
    	$companies = Company::where('parent_company_id', '=', $company_id)->where('id', '!=', $company_id)->pluck('name','id')->all();
    	$tokens = Token_bal::where('company_id', '=', $company_id)->get();
    	return view('token.addToken', [ 'tokens' => $tokens, 'companies' => $companies]);
    }

    public function storeToken(Request $request)
    {
    	$user = Auth::user();
    	$company_id = $user->company_id;
        $user_id = $user->id;

		$this->validate($request, [
            'sender_company_id' => 'required',
            'amount' => 'required'
        ]);

    	$sender_company_id = $request->input('sender_company_id');
    	$amount = $request->input('amount');
    	$balance = Token::orderBy('id', 'desc')->where('company_id', '=', $company_id)->where('sender_company_id', '=', $sender_company_id)->pluck('balance')->first();
        if ($balance == NULL)
        {
            $balance = 0;
        }
    	$old_last_awb = Token::orderBy('id', 'desc')->where('company_id', '=', $company_id)->pluck('last_awb')->first();
    	$first_awb = $old_last_awb + 1;
    	$last_awb = $old_last_awb + $amount;

        $token = new Token();
        $token->company_id = $company_id;
        $token->sender_company_id = $sender_company_id;
        $token->amount = $request->input('amount');
        $new_balance = $balance + $amount;
        $token->balance = $new_balance;
        $token->first_awb = $first_awb;
        $token->last_awb = $last_awb;
        $token->curr_awb = $first_awb;
        $token->finished = 0;
        $token->updated_by = $user_id;
        $token->save();

        //update token balance table
        $token_bal_id = Token_bal::where('company_id', '=', $company_id)->where('sender_company_id', '=', $sender_company_id)->pluck('id')->first();
        if ($token_bal_id == NULL)
        {
            $token_bal = new Token_bal();
            $token_bal->company_id = $company_id;
            $token_bal->sender_company_id = $sender_company_id;
            $token_bal->balance = $new_balance;
            $token_bal->updated_by = $user_id;
            $token_bal->save();
        }
        else 
        {
            $new_bal = Token_bal::find($token_bal_id);
            $new_bal->balance = $new_balance;
            $new_bal->updated_by = $user_id;
            $new_bal->save();
        }

        return redirect('/token')->with('success', $amount.' Tokens added successfully.' );
    }
}
