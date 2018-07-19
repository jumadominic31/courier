<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Txn;
use App\TxnLog;
use App\Company;
use App\User;
use App\UserLog;
use App\Station;
use App\ParcelStatus;
use App\ParcelType;
use App\Vehicle;
use App\Zone;
use JWTAuth;
use Validator;
use Auth;
use Session;
use PDF;

class RiderController extends Controller
{
    //api signin
    public function signinRider(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);
        // $credentials = $request->only('username', 'password');
        $credentials = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'driver', 'status' => 1);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials!'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token!'
            ], 500);
        }
        //to log user signin to user_logs table
        $userlogin = new UserLog();
        $userlogin->username = $request->input('username');
        $userlogin->activity = "Login";
        $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlogin->save();
        //
        return response()->json([
            'token' => $token
        ], 200);
    }

    public function getRiderstations()
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $stations = Zone::where('company_id', '=', $parent_company_id)->select('id','name')->get();
        return response()->json($stations);
    }

    public function getRidercustomers()
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $customers = Company::where('parent_company_id', '=', $parent_company_id)->where('id', '!=', $parent_company_id)->select('id','name')->get();
        return response()->json($customers);
    }

    public function getRiderparcelTypes()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
    	$parceltype = ParcelType::where('company_id', '=', $parent_company_id)->get();
		return response()->json(['parceltype' => $parceltype], 201);
    }

    public function getRideruserdetails($username)
    {
        $userdetails = DB::table('users')
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->select('users.id', 'users.username', 'users.fullname', 'users.company_id', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email')
            ->where('users.username', '=', $username)
            ->get();
        return response()->json($userdetails);
    }

    public function postRidercreateTxn(Request $request)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
        
        $validator = Validator::make(($request->all()), [
            'dest_addr' => 'required',
            'origin_addr' => 'required',
            'parcel_type_id' => 'required',
            // 'price' => 'required',
            'sender_name' => 'required',
            'sender_company_id' => 'required',
            // 'sender_company_name' => 'required',
            'sender_phone' => 'required',
            'sender_sign' => 'required',
            'receiver_name' => 'required',
            'receiver_company_name' => 'required',
            'receiver_phone' => 'required'
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {

            $sender_sign = $request->input('sender_sign');
            $sender_sign = substr($sender_sign, strpos($sender_sign, ",")+1);
            $image = base64_decode($sender_sign);
            $filenameToStore = 'sender_'.time().'.'.'png';
            $path = public_path() . '\storage\sender_sign\\'. $filenameToStore;
            file_put_contents($filenameToStore, $image);

            $prefix = Company::where('id', '=', $company_id)->pluck('name')->first();
        	$prefix = strtoupper($prefix);
        	$prefix = substr($prefix, 0, 3);
            function randomDigits($length){
                $num = '';
                $numbers = range(0,9);
                shuffle($numbers);
                for($i = 0;$i < $length;$i++)
                   $num .= $numbers[$i];
                return $num;
            }
            $newawbnum = randomDigits(5);
        	$newawbnum = $prefix.date('ymd').$newawbnum;
            // $price = $request->input('price');
            // $vat = 0.16 * $price;
            $receiver_code = randomDigits(6);
            $receiver_code_hash = Hash::make($receiver_code);

            $parcel_desc = $request->input('parcel_desc');
            $sender_company_name = $request->input('sender_company_name');
            $sender_company_id = $request->input('sender_company_id');

            $txn = new Txn;
            $txn->awb_num = $newawbnum;
            $txn->clerk_id = $user->id;
            $txn->origin_addr = $request->input('origin_addr');
            $txn->dest_addr = $request->input('dest_addr');
            $txn->company_id = $company_id;
            $txn->driver_id = $user->id;
            $txn->parcel_status_id = '8';
            $txn->parcel_type_id = $request->input('parcel_type_id');
            if ($parcel_desc != NULL){
                $txn->parcel_desc = $parcel_desc;
            }
            $txn->mode = $request->input('mode');
            $txn->round = $request->input('round');
            // $txn->price = $price;
            // $txn->vat = $vat;
            $txn->sender_name = $request->input('sender_name');
            $txn->sender_company_id = $sender_company_id;
            $txn->sender_company_name = $sender_company_name;
            $txn->sender_phone = $request->input('sender_phone');
            $txn->sender_id_num = $request->input('sender_id_num');
            if ($request->input('sender_sign') != NULL){
                $txn->sender_sign = $filenameToStore;
            }
            $txn->receiver_name = $request->input('receiver_name');
            $txn->receiver_company_name = $request->input('receiver_company_name');
            $txn->receiver_phone = $request->input('receiver_phone');
            $txn->receiver_code = $receiver_code_hash;
            $txn->updated_by = $user->id;
            $txn->save();

            $txnlog = new TxnLog;
            $txnlog->awb_id = $txn->id;
            $txnlog->status_id = $txn->parcel_status_id;
            // $txnlog->origin_id = $txn->origin_id;
            // $txnlog->dest_id = $txn->dest_id;
            $txnlog->updated_by = $user->id;
            $txnlog->company_id = $company_id;
            $txnlog->sender_company_id = $sender_company_id;
            $txnlog->save();

            //Create return AWB
            if ($txn->round == 1)
            {
                $returnawbnum = randomDigits(5);
                $returnawbnum = $prefix.date('ymd').$returnawbnum;
                // $price = $request->input('price');
                // $vat = 0.16 * $price;
                $receiver_code = randomDigits(6);
                $receiver_code_hash = Hash::make($receiver_code);

                $parcel_desc = $request->input('parcel_desc');
                $sender_company_name = $request->input('sender_company_name');
                $sender_company_id = $request->input('sender_company_id');

                $txn = new Txn;
                $txn->awb_num = $returnawbnum;
                $txn->clerk_id = $user->id;
                $txn->origin_addr = $request->input('dest_addr');
                $txn->dest_addr = $request->input('origin_addr');
                $txn->company_id = $company_id;
                $txn->driver_id = $user->id;
                $txn->parcel_status_id = '8';
                $txn->parcel_type_id = $request->input('parcel_type_id');
                if ($parcel_desc != NULL){
                    $txn->parcel_desc = $parcel_desc;
                }
                $txn->mode = $request->input('mode');
                $txn->round = $request->input('round');
                // $txn->price = $price;
                // $txn->vat = $vat;
                $txn->sender_name = $request->input('sender_name');
                $txn->sender_company_id = $sender_company_id;
                $txn->sender_company_name = $sender_company_name;
                $txn->sender_phone = $request->input('sender_phone');
                $txn->sender_id_num = $request->input('sender_id_num');
                if ($request->input('sender_sign') != NULL){
                    $txn->sender_sign = $filenameToStore;
                }
                $txn->receiver_name = $request->input('receiver_name');
                $txn->receiver_company_name = $request->input('receiver_company_name');
                $txn->receiver_phone = $request->input('receiver_phone');
                $txn->receiver_code = $receiver_code_hash;
                $txn->updated_by = $user->id;
                $txn->save();

                $txnlog = new TxnLog;
                $txnlog->awb_id = $txn->id;
                $txnlog->status_id = $txn->parcel_status_id;
                // $txnlog->origin_id = $txn->origin_id;
                // $txnlog->dest_id = $txn->dest_id;
                $txnlog->updated_by = $user->id;
                $txnlog->company_id = $company_id;
                $txnlog->sender_company_id = $sender_company_id;
                $txnlog->save();
            }

            $sender_phone = $request->input('sender_phone');
            $receiver_phone = $request->input('receiver_phone');
            
            // $atgusername   = SmsApi::select('atgusername')->where('company_id', '=', $company_id)->pluck('atgusername')->first();
            // $atgapikey     = SmsApi::select('atgapikey')->where('company_id', '=', $company_id)->pluck('atgapikey')->first();
            // $atgsender_id  = SmsApi::select('atgsender_id')->where('company_id', '=', $company_id)->pluck('atgsender_id')->first();
            
            // if (($atgusername == NULL) || ($atgapikey == NULL))
            // {
            //     $atgusername   = env('ATGUSERNAME');
            //     $atgapikey     = env('ATGAPIKEY');
            // }
            // // Send password via SMS
            
            // if ($atgusername != NULL) {
            //     if ($sender_phone != NULL)
            //     {
            //         // $atgusername   = env('ATGUSERNAME');
            //         // $atgapikey     = env('ATGAPIKEY');
                    
            //         $recipients = '+254'.$sender_phone;
            //         $message    = "Dear sender, Your parcel has been picked for delivery under AWB ".$txn->awb_num.". Check status at http://bit.ly/2Dv9o7m";
            //         $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
            //         try 
            //         { 
            //             if ($atgsender_id != NULL){
            //                 $send_results = $gateway->sendMessage($recipients, $message, $atgsender_id);
            //             } 
            //             else {
            //                 $send_results = $gateway->sendMessage($recipients, $message);
            //             }
            //             // $send_results = $gateway->sendMessage($recipients, $message);
            //         }
            //         catch ( AfricasTalkingGatewayException $e )
            //         {
            //           echo 'Encountered an error while sending: '.$e->getMessage();
            //         }
            //     }
    
            //     if ($receiver_phone != NULL)
            //     {
            //         // $atgusername   = env('ATGUSERNAME');
            //         // $atgapikey     = env('ATGAPIKEY');
            //         $recipients = '+254'.$receiver_phone;
            //         $message    = "Dear receiver, please expect parcel booked under AWB ".$txn->awb_num.". Your code is ".$receiver_code. ". Check status at http://bit.ly/2Dv9o7m";
            //         $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
            //         try 
            //         { 
            //             if ($atgsender_id != NULL){
            //                 $rec_results = $gateway->sendMessage($recipients, $message, $atgsender_id);
            //             } 
            //             else {
            //                 $rec_results = $gateway->sendMessage($recipients, $message);
            //             }
            //             // $rec_results = $gateway->sendMessage($recipients, $message);
            //         }
            //         catch ( AfricasTalkingGatewayException $e )
            //         {
            //           echo 'Encountered an error while sending: '.$e->getMessage();
            //         }
            //     }
            // }

            return response()->json(['txn' => $txn, 'receiver_code' => $receiver_code], 201);
        }
    }

    public function getRiderbookedTxn()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
    	$station_id = $user->station_id;

		$txn = DB::table('txns')
                ->join('zones as s1', 'txns.origin_id', '=', 's1.id')
                ->join('zones as s2', 'txns.dest_id', '=', 's2.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.parcel_status_id', '=', '7')
                ->orderby('txns.id', 'desc')->get();

    	return response()->json(['txn' => $txn], 201);
    }

    public function getRiderpickupTxn()
    {
        $user = JWTAuth::parseToken()->toUser();
        $user_id = $user->id;
        $company_id = $user->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();

        $txn = DB::table('txns')
                ->select('txns.id', 'txns.awb_num', 'txns.origin_addr', 'txns.dest_addr', 'txns.sender_company_name', 'txns.sender_name')
                ->where('txns.company_id', '=', $parent_company_id)
                ->where('txns.parcel_status_id', '=', '9')
                ->where('driver_id', '=', $user_id)
                ->orderby('txns.id', 'desc')->get();

        return response()->json(['txn' => $txn], 201);
    }

    public function getRiderdropTxn()
    {
        $user = JWTAuth::parseToken()->toUser();
        $user_id = $user->id;
        $company_id = $user->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();

        $txn = DB::table('txns')
                ->select('txns.id', 'txns.awb_num', 'txns.origin_addr', 'txns.dest_addr', 'txns.receiver_name')
                ->where('txns.company_id', '=', $parent_company_id)
                ->where('txns.parcel_status_id', '=', '2')
                ->where('driver_id', '=', $user_id)
                ->orderby('txns.id', 'desc')->get();

        return response()->json(['txn' => $txn], 201);
    }

    public function getRidercustbookedTxn($sender_company_id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $station_id = $user->station_id;

        // select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_addr', 'txns.dest_addr',  'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
        $txn = DB::table('txns')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_addr', 'txns.dest_addr',  'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.parcel_status_id', '=', '7')
                ->where('txns.sender_company_id', '=', $sender_company_id)
                ->orderby('txns.id', 'desc')->get();

        return response()->json(['txn' => $txn], 201);
    }

    public function postRiderpickTxn(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        
        $validator = Validator::make(($request->all()), [
            // 'sender_pass' => 'required',
            'sender_sign' => 'required',
            'ids' => 'required'
        ]);

        $txn_success = array();
        $txn_failed = array();
        $clerk_ids = array();

        function randomDigits($length){
            $num = '';
            $numbers = range(0,9);
            shuffle($numbers);
            for($i = 0;$i < $length;$i++)
               $num .= $numbers[$i];
            return $num;
        }
        $receiver_code = randomDigits(6);
        $receiver_code_hash = Hash::make($receiver_code);

        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } 
        else {
            $sender_sign = $request->input('sender_sign');
            $sender_sign = substr($sender_sign, strpos($sender_sign, ",")+1);
            $image = base64_decode($sender_sign);
            $filenameToStore = 'driver_'.time().'.'.'png';
            $path = public_path() . '\storage\sender_sign\\'. $filenameToStore;
            file_put_contents($path, $image);

            $ids = $request->input('ids');

            foreach($ids as $id){
                array_push($clerk_ids, $id['clerk_id']);
            }

            $same_clerk = count(array_unique($clerk_ids));

            if ($same_clerk > 1){
                return response()->json(['sender_err' => 'diff senders'], 200);                
            }
            else {
                foreach($ids as $id){
                    $sender_pass = $request->input('sender_pass');
                    $clerk_id = $id['clerk_id'];
                    // $hashed_sender_pass = User::select('password')->where('id', '=', $clerk_id)->pluck('password')->first();
                    // if (Hash::check($sender_pass, $hashed_sender_pass)) {

                        $txn = Txn::find($id['id']);
                        $txn->parcel_status_id = '8';
                        $txn->sender_sign =  $filenameToStore;
                        $txn->receiver_code = $receiver_code_hash;
                        $txn->driver_id = $user->id;
                        $txn->updated_by = $user->id;
                        $txn->save();

                        $sender_phone = $txn->sender_phone;
                        $receiver_phone = $txn->receiver_phone;

                        $sender_company_id = $txn->sender_company_id;

                        $txnlog = new TxnLog;
                        $txnlog->awb_id = $txn->id;
                        $txnlog->status_id = '8';
                        $txnlog->origin_id = $txn->origin_id;
                        $txnlog->dest_id = $txn->dest_id;
                        if ($request->input('notes') != NULL){
                            $txnlog->notes = $request->input('notes');
                        }
                        $txnlog->updated_by = $user->id;
                        $txnlog->company_id = $company_id;
                        $txnlog->sender_company_id = $sender_company_id;
                        $txnlog->save();

                        // $atgusername   = SmsApi::select('atgusername')->where('company_id', '=', $company_id)->pluck('atgusername')->first();
                        // $atgapikey     = SmsApi::select('atgapikey')->where('company_id', '=', $company_id)->pluck('atgapikey')->first();
                        // $atgsender_id  = SmsApi::select('atgsender_id')->where('company_id', '=', $company_id)->pluck('atgsender_id')->first();
                        
                        // if (($atgusername == NULL) || ($atgapikey == NULL))
                        // {
                        //     $atgusername   = env('ATGUSERNAME');
                        //     $atgapikey     = env('ATGAPIKEY');
                        // }
                        
                        // if ($sender_phone != NULL)
                        // {
                        //     $recipients = '+'.$sender_phone;
                        //     $message    = "Dear sender, Your parcel been has picked for delivery under AWB ".$txn->awb_num.". Check status at http://bit.ly/2Dv9o7m";
                        //     $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
                        //     try 
                        //     { 
                        //         if ($atgsender_id != NULL){
                        //             $send_results = $gateway->sendMessage($recipients, $message, $atgsender_id);
                        //         } 
                        //         else {
                        //             $send_results = $gateway->sendMessage($recipients, $message);
                        //         }
                        //     }
                        //     catch ( AfricasTalkingGatewayException $e )
                        //     {
                        //       echo 'Encountered an error while sending: '.$e->getMessage();
                        //     }
                        // }
                        
                        // if ($receiver_phone != NULL)
                        // {
                        //     $recipients = '+'.$receiver_phone;
                        //     $message    = "Dear receiver, please expect parcel booked under AWB ".$txn->awb_num.". Your code is ".$receiver_code. ". Check status at http://bit.ly/2Dv9o7m";
                        //     $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
                        //     try 
                        //     { 
                        //         if ($atgsender_id != NULL){
                        //             $rec_results = $gateway->sendMessage($recipients, $message, $atgsender_id);
                        //         } 
                        //         else {
                        //             $rec_results = $gateway->sendMessage($recipients, $message);
                        //         }
                        //     }
                        //     catch ( AfricasTalkingGatewayException $e )
                        //     {
                        //       echo 'Encountered an error while sending: '.$e->getMessage();
                        //     }
                        // }

                        array_push($txn_success, $id['awb_num']);
                    // }
                    // else {
                    //     array_push($txn_failed, $id['awb']);
                    // }
                }
                return response()->json(['success' => $txn_success, 'failed' => $txn_failed], 201);
            }
            
        }
    }

    public function getRiderpickedTxn()
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $station_id = $user->station_id;

        $txn = DB::table('txns')
                ->join('zones as s1', 'txns.origin_id', '=', 's1.id')
                ->join('zones as s2', 'txns.dest_id', '=', 's2.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.parcel_status_id', '=', '8')
                ->orderby('txns.id', 'desc')->get();

        return response()->json(['txn' => $txn], 201);
    }

    public function getRidercustpickedTxn($sender_company_id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $station_id = $user->station_id;

        $txn = DB::table('txns')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_addr', 'txns.dest_addr',  'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.parcel_status_id', '=', '8')
                ->where('txns.sender_company_id', '=', $sender_company_id)
                ->orderby('txns.id', 'desc')->get();

        return response()->json(['txn' => $txn], 201);
    }

    public function postRiderreceiveTxn($id, Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
    
        $validator = Validator::make(($request->all()), [
            'receiver_sign' => 'required',
            // 'receiver_code' => 'required'
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $receiver_sign = $request->input('receiver_sign');
            $receiver_sign = substr($receiver_sign, strpos($receiver_sign, ",")+1);
            $image = base64_decode($receiver_sign);
            $filenameToStore = 'receiver_'.time().'.'.'png';
            $path = public_path() . '\storage\receiver_sign\\'. $filenameToStore;
            file_put_contents($path, $image);

            $receiver_name = $request->input('receiver_name');
            $receiver_phone = $request->input('receiver_phone');
            // $receiver_code = $request->input('receiver_code');

            // $hashed_receiver_code = Txn::select('receiver_code')->where('id', '=', $id)->pluck('receiver_code')->first();

            // if (Hash::check($receiver_code, $hashed_receiver_code)) {
                $txn = Txn::find($id);
                $txn->parcel_status_id = '4';
                $txn->receiver_sign =  $filenameToStore;
                if ($receiver_name != NULL) { $txn->receiver_name = $receiver_name; }
                if ($receiver_phone != NULL) { $txn->receiver_phone = $receiver_phone; }
                if ($request->input('receiver_id_num') != NULL){
                    $txn->receiver_id_num = $request->input('receiver_id_num');
                }
                $txn->updated_by = $user->id;
                $txn->save();

                $sender_company_id = $txn->sender_company_id;
                
                $txnlog = new TxnLog;
                $txnlog->awb_id = $txn->id;
                $txnlog->status_id = '4';
                // $txnlog->origin_id = $txn->origin_id;
                // $txnlog->dest_id = $txn->dest_id;
                if ($request->input('notes') != NULL){
                    $txnlog->notes = $request->input('notes');
                }
                $txnlog->updated_by = $user->id;
                $txnlog->company_id = $company_id;
                $txnlog->sender_company_id = $sender_company_id;
                $txnlog->save();

                return response()->json(['txn' => $txn], 201);
            // }
            // else {
            //     return response()->json(['errormsg' => 'code error'], 201);
            // }
        }
    }

    public function getRiderreceivedTxn()
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $curr_date = date('Y-m-d');

        $txn = Txn::where('company_id', '=', $company_id)->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('parcel_status_id', '=', '4')->orderby('id', 'desc')->get();
        
        return response()->json(['txn' => $txn], 201);
    }

    public function getRidercustreceivedTxn($sender_company_id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $curr_date = date('Y-m-d');

        $txn = Txn::where('company_id', '=', $company_id)->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('parcel_status_id', '=', '4')->where('sender_company_id', '=', $sender_company_id)->orderby('id', 'desc')->get();
        
        return response()->json(['txn' => $txn], 201);
    }

    public function getRiderTxnDet($awb_num)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;

        // $txn = DB::table('txns')
        //         ->join('zones as s1', 'txns.origin_id', '=', 's1.id')
        //         ->join('zones as s2', 'txns.dest_id', '=', 's2.id')
        //         ->join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
        //         ->join('parcel_statuses', 'txns.parcel_status_id', '=', 'parcel_statuses.id')
        //         ->select('txns.id', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_status_id', 'parcel_statuses.name as parcel_status_name', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
        //         ->where('txns.company_id', '=', $company_id)
        //         ->where('txns.awb_num', '=', $awb_num)
        //         ->get();

        $txn = DB::table('txns')
                // ->join('zones as s1', 'txns.origin_id', '=', 's1.id')
                // ->join('zones as s2', 'txns.dest_id', '=', 's2.id')
                ->join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
                ->join('parcel_statuses', 'txns.parcel_status_id', '=', 'parcel_statuses.id')
                ->select('txns.id', 'txns.clerk_id', 'txns.origin_addr', 'txns.dest_addr', 'txns.parcel_status_id', 'txns.parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_status_id', 'parcel_statuses.name as parcel_status_name', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.mode', 'txns.round', 'txns.sender_name', 'txns.sender_company_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_company_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.awb_num', '=', $awb_num)
                ->get();
        return response()->json(['txn' => $txn], 201);
    }

    public function getRiderDailySumm()
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $rider_id = $user->id;
        $curr_date = date('Y-m-d');

        //show num booked, picked and received
        $booked = Txn::select('parcel_status_id', DB::raw('count(parcel_status_id) as tot_amount'))->where('parcel_status_id', '=', '7')->where('company_id', '=', $company_id)->groupBy('parcel_status_id')->pluck('tot_amount')->first();
        $picked = TxnLog::select('status_id', DB::raw('count(status_id) as tot_amount'))->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('status_id', '=', '8')->where('company_id', '=', $company_id)->where('updated_by', '=', $rider_id)->groupBy('status_id')->pluck('tot_amount')->first();
        $received = TxnLog::select('status_id', DB::raw('count(status_id) as tot_amount'))->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('status_id', '=', '4')->where('company_id', '=', $company_id)->where('updated_by', '=', $rider_id)->groupBy('status_id')->pluck('tot_amount')->first();

        return response()->json(['booked' => $booked, 'picked' => $picked, 'received' => $received], 201);                        
    }
}
