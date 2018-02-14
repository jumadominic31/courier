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
use JWTAuth;
use Validator;
use Auth;
use Session;
use PDF;

class TxnsController extends Controller
{
    public function postcreateTxn(Request $request)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
        
        $validator = Validator::make(($request->all()), [
            'dest_id' => 'required',
            'parcel_type_id' => 'required',
            'price' => 'required',
            'sender_name' => 'required',
            'sender_phone' => 'required',
            'sender_sign' => 'required',
            'receiver_name' => 'required',
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
            $price = $request->input('price');
            $vat = 0.16 * $price;
            $receiver_code = randomDigits(6);
            $receiver_code_hash = Hash::make($receiver_code);

            $parcel_desc = $request->input('parcel_desc');

            $txn = new Txn;
            $txn->awb_num = $newawbnum;
            $txn->clerk_id = $user->id;
            $txn->origin_id = $user->station_id;
            $txn->dest_id = $request->input('dest_id');
            $txn->company_id = $company_id;
            $txn->parcel_status_id = '1';
            $txn->parcel_type_id = $request->input('parcel_type_id');
            if ($parcel_desc != NULL){
                $txn->parcel_desc = $parcel_desc;
            }
            $txn->price = $price;
            $txn->vat = $vat;
            $txn->sender_name = $request->input('sender_name');
            $txn->sender_phone = $request->input('sender_phone');
            $txn->sender_id_num = $request->input('sender_id_num');
            if ($request->input('sender_sign') != NULL){
                $txn->sender_sign = $filenameToStore;
            }
            $txn->receiver_name = $request->input('receiver_name');
            $txn->receiver_phone = $request->input('receiver_phone');
            $txn->receiver_code = $receiver_code_hash;
            $txn->updated_by = $user->id;
            $txn->save();

            $txnlog = new TxnLog;
            $txnlog->awb_id = $txn->id;
            $txnlog->status_id = $txn->parcel_status_id;
            $txnlog->origin_id = $txn->origin_id;
            $txnlog->dest_id = $txn->dest_id;
            $txnlog->updated_by = $user->id;
            $txnlog->company_id = $company_id;
            $txnlog->save();

            // $sender_phone = '254'.$request->input('sender_phone');
            // $receiver_phone = '254'.$request->input('receiver_phone');
            // Send password via SMS
            // if ($sender_phone != NULL)
            // {
            //     $atgusername   = env('ATGUSERNAME');
            //     $atgapikey     = env('ATGAPIKEY');
            //     $recipients = '+'.$sender_phone;
            //     $message    = "Dear sender, Your parcel is booked under AWB ".$txn->awb_num.". Cost = ".$txn->price. ". Check status at http://bit.ly/2Cr4KuL";
            //     $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
            //     try 
            //     { 
            //       $send_results = $gateway->sendMessage($recipients, $message);
            //     }
            //     catch ( AfricasTalkingGatewayException $e )
            //     {
            //       echo 'Encountered an error while sending: '.$e->getMessage();
            //     }
            // }

        //     if ($receiver_phone != NULL)
        //     {
        //         $atgusername   = env('ATGUSERNAME');
        //         $atgapikey     = env('ATGAPIKEY');
        //         $recipients = '+'.$receiver_phone;
        //         $message    = "Dear receiver, please expect parcel booked under AWB ".$txn->awb_num.". Your code is ".$receiver_code. ". Check status at http://bit.ly/2Cr4KuL";
        //         $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
        //         try 
        //         { 
        //           $rec_results = $gateway->sendMessage($recipients, $message);
        //         }
        //         catch ( AfricasTalkingGatewayException $e )
        //         {
        //           echo 'Encountered an error while sending: '.$e->getMessage();
        //         }
        //     }
            
        //     //return response()->json(['txn' => $txn, 'send_results' => $send_results, 'rec_results' => $rec_results], 201);
            return response()->json(['txn' => $txn, 'receiver_code' => $receiver_code], 201);
        }
    }

    public function getcreatedTxn()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$station_id = $user->station_id;

		$txn = DB::table('txns')
                ->join('stations as s1', 'txns.origin_id', '=', 's1.id')
                ->join('stations as s2', 'txns.dest_id', '=', 's2.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.origin_id', '=', $station_id)
                ->where('txns.parcel_status_id', '=', '1')
                ->orderby('txns.id', 'desc')->get();

    	return response()->json(['txn' => $txn], 201);
    }

    public function getTxnDet($awb_num)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;

        $txn = DB::table('txns')
                ->join('stations as s1', 'txns.origin_id', '=', 's1.id')
                ->join('stations as s2', 'txns.dest_id', '=', 's2.id')
                ->join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
                ->join('parcel_statuses', 'txns.parcel_status_id', '=', 'parcel_statuses.id')
                ->select('txns.id', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_status_id', 'parcel_statuses.name as parcel_status_name', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.awb_num', '=', $awb_num)
                ->get();
    	return response()->json(['txn' => $txn], 201);
    }

    public function getTxnStatusDet($id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $statusDet = DB::table('txn_logs as t')
                        ->join('parcel_statuses as p', 't.status_id', '=', 'p.id')
                        ->join('users as u', 't.updated_by', '=', 'u.id')
                        ->select('t.awb_id', 't.status_id', 'p.name as status_name', 't.updated_by', 'u.username as username', 't.updated_at')
                        ->where('t.awb_id', '=', $id)
                        ->orderby('t.id', 'desc')
                        ->get();
        return response()->json(['statusDet' => $statusDet], 201);                        
    }

    public function getDailySumm()
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        $origin_id = $user->station_id;
        $clerk_id = $user->id;
        $curr_date = date('Y-m-d');

        //show num created, dispatched, delivered or received
        $created = TxnLog::select('status_id', DB::raw('count(status_id) as tot_amount'))->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('origin_id', '=', $origin_id)->where('status_id', '=', '1')->where('company_id', '=', $company_id)->groupBy('status_id')->pluck('tot_amount')->first();
        $dispatched = TxnLog::select('status_id', DB::raw('count(status_id) as tot_amount'))->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('origin_id', '=', $origin_id)->where('status_id', '=', '2')->where('company_id', '=', $company_id)->groupBy('status_id')->pluck('tot_amount')->first();
        $delivered = TxnLog::select('status_id', DB::raw('count(status_id) as tot_amount'))->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('dest_id', '=', $origin_id)->where('status_id', '=', '3')->where('company_id', '=', $company_id)->groupBy('status_id')->pluck('tot_amount')->first();
        $received = TxnLog::select('status_id', DB::raw('count(status_id) as tot_amount'))->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('dest_id', '=', $origin_id)->where('status_id', '=', '4')->where('company_id', '=', $company_id)->groupBy('status_id')->pluck('tot_amount')->first();

        //show collection per user
        $collected = Txn::select('clerk_id', DB::raw('sum(price) as tot_amount'))->where(DB::raw('date(created_at)'), '=', $curr_date)->where('company_id', '=', $company_id)->where('clerk_id' , '=' , $clerk_id)->groupBy('clerk_id')->pluck('tot_amount')->first();

        return response()->json(['created' => $created, 'dispatched' => $dispatched, 'delivered' => $delivered, 'received' => $received, 'collected' => $collected], 201);                        
    }

    public function postdispatchTxn($id, Request $request)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
        
        $validator = Validator::make(($request->all()), [
            'driver_id' => 'required',
            'vehicle_id' => 'required',
            'driver_pin' => 'required',
            'driver_sign' => 'required'
        ]);
        $driver_id = $request->input('driver_id');
        $driver_pin = $request->input('driver_pin');
        $hashed_driver_pin = User::select('password')->where('id', '=', $driver_id)->pluck('password')->first();

        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        }  
        if (Hash::check($driver_pin, $hashed_driver_pin)) {

            $driver_sign = $request->input('driver_sign');
            $driver_sign = substr($driver_sign, strpos($driver_sign, ",")+1);
            $image = base64_decode($driver_sign);
            $filenameToStore = 'driver_'.$driver_id.'_'.time().'.'.'png';
            $path = public_path() . '\storage\driver_sign\\'. $filenameToStore;
            file_put_contents($path, $image);

            $txn = Txn::find($id);
            $txn->parcel_status_id = '2';
            $txn->driver_id = $request->input('driver_id');
            $txn->vehicle_id = $request->input('vehicle_id');
            if ($request->input('driver_sign') != NULL){
                $txn->pick_driver_sign = $filenameToStore;
            }
            $txn->updated_by = $user->id;
            $txn->save();

            $txnlog = new TxnLog;
            $txnlog->awb_id = $txn->id;
            $txnlog->status_id = '2';
            $txnlog->origin_id = $txn->origin_id;
            $txnlog->dest_id = $txn->dest_id;
            if ($request->input('notes') != NULL){
                $txnlog->notes = $request->input('notes');
            }
            $txnlog->updated_by = $user->id;
            $txnlog->company_id = $company_id;
            $txnlog->save();
            
            return response()->json(['txn' => $txn], 201);
        }
        else {
            return response()->json(['errormsg' => 'code error'], 201);
        }
    }

    public function postdispatchTxns(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;
        
        $validator = Validator::make(($request->all()), [
            'driver_id' => 'required',
            'vehicle_id' => 'required',
            'driver_pin' => 'required',
            'driver_sign' => 'required',
            'ids' => 'required'
        ]);
        $driver_id = $request->input('driver_id');
        $driver_pin = $request->input('driver_pin');
        $hashed_driver_pin = User::select('password')->where('id', '=', $driver_id)->pluck('password')->first();

        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } 

        if (Hash::check($driver_pin, $hashed_driver_pin)) {
            $driver_sign = $request->input('driver_sign');
            $driver_sign = substr($driver_sign, strpos($driver_sign, ",")+1);
            $image = base64_decode($driver_sign);
            $filenameToStore = 'driver_'.$driver_id.'_'.time().'.'.'png';
            $path = public_path() . '\storage\driver_sign\\'. $filenameToStore;
            file_put_contents($path, $image);

            $ids = $request->input('ids');
            $num_awbs = count($ids);
            $ids_array = [];

            foreach($ids as $id){
                $txn = Txn::find($id['id']);
                $txn->parcel_status_id = '2';
                $txn->driver_id = $request->input('driver_id');
                $txn->vehicle_id = $request->input('vehicle_id');
                if ($request->input('driver_sign') != NULL){
                    $txn->pick_driver_sign = $filenameToStore;
                }
                $txn->updated_by = $user->id;
                $txn->save();

                $txnlog = new TxnLog;
                $txnlog->awb_id = $txn->id;
                $txnlog->status_id = '2';
                $txnlog->origin_id = $txn->origin_id;
                $txnlog->dest_id = $txn->dest_id;
                if ($request->input('notes') != NULL){
                    $txnlog->notes = $request->input('notes');
                }
                $txnlog->updated_by = $user->id;
                $txnlog->company_id = $company_id;
                $txnlog->save();

                array_push($ids_array, $id['awb']);

                $driver_phone = $txn->driver['phone'];
            }
            $ids_string = implode(",",$ids_array);
            
            // Send password via SMS
            // if ($driver_phone != NULL)
            // {
            //     $atgusername   = env('ATGUSERNAME');
            //     $atgapikey     = env('ATGAPIKEY');
            //     $recipients = '+'.$driver_phone;
            //     $message    = "The driver code for the AWBs:".$ids_string." is ".$driver_code;
            //     $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
            //     try 
            //     { 
            //       $send_results = $gateway->sendMessage($recipients, $message);
            //     }
            //     catch ( AfricasTalkingGatewayException $e )
            //     {
            //       echo 'Encountered an error while sending: '.$e->getMessage();
            //     }
            // }
            
            return response()->json(['ids' => $ids], 201);
        }
        else {
            return response()->json(['errormsg' => 'code error'], 201);
        }
    }

    public function getorigindispatchedTxn()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$station_id = $user->station_id;
        $curr_date = date('Y-m-d');

    	$txn = DB::table('txns')
                ->join('stations as s1', 'txns.origin_id', '=', 's1.id')
                ->join('stations as s2', 'txns.dest_id', '=', 's2.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id','txns.parcel_status_id', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.origin_id', '=', $station_id)
                ->where(DB::raw('date(txns.updated_at)'), '=', $curr_date)
                ->where('txns.parcel_status_id', '=', '2')
                ->orderby('txns.id', 'desc')->get();
                //Txn::where('company_id', '=', $company_id)->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('origin_id', '=', $station_id)->where('parcel_status_id', '=', '2')->orderby('id', 'desc')->get();
    	
    	return response()->json(['txn' => $txn], 201);
    }

    public function getdestdispatchedTxn()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$station_id = $user->station_id;

    	//$txn = Txn::where('company_id', '=', $company_id)->where('dest_id', '=', $station_id)->where('parcel_status_id', '=', '2')->orderby('id', 'desc')->get();
        $txn = DB::table('txns')
                ->join('stations as s1', 'txns.origin_id', '=', 's1.id')
                ->join('stations as s2', 'txns.dest_id', '=', 's2.id')
                ->join('users as u', 'txns.driver_id', '=', 'u.id')
                ->join('vehicles as v', 'txns.vehicle_id', '=', 'v.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id', 's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id', 'txns.parcel_desc', 'txns.price', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'u.fullname as driver_name', 'txns.vehicle_id', 'v.name as vehicle_name',  'txns.updated_by')
                ->where('txns.company_id', '=', $company_id)
                ->where('txns.dest_id', '=', $station_id)
                ->where('txns.parcel_status_id', '=', '2')
                ->orderby('txns.id', 'desc')
                ->get();
    	
    	return response()->json(['txn' => $txn], 201);
    }

    public function postdeliverTxn($id, Request $request)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;

        $validator = Validator::make(($request->all()), [
            'driver_sign' => 'required',
            'driver_pin' => 'required'
        ]);
        $driver_id = $request->input('driver_id');
        $driver_pin = $request->input('driver_pin');

        $hashed_driver_pin = User::select('password')->where('id', '=', $driver_id)->pluck('password')->first();

        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $driver_sign = $request->input('driver_sign');
            $driver_sign = substr($driver_sign, strpos($driver_sign, ",")+1);
            $image = base64_decode($driver_sign);
            $filenameToStore = 'driver_'.time().'.'.'png';
            $path = public_path() . '\storage\driver_sign\\'. $filenameToStore;
            file_put_contents($path, $image);

            if (Hash::check($driver_pin, $hashed_driver_pin)) {

                $txn = Txn::find($id);
                $txn->parcel_status_id = '3';
                $txn->drop_driver_sign =  $filenameToStore;
                $txn->updated_by = $user->id;
                $txn->save();

                $txnlog = new TxnLog;
                $txnlog->awb_id = $txn->id;
                $txnlog->status_id = '3';
                $txnlog->origin_id = $txn->origin_id;
                $txnlog->dest_id = $txn->dest_id;
                $txnlog->updated_by = $user->id;
                $txnlog->company_id = $company_id;
                $txnlog->save();

                return response()->json(['txn' => $txn], 201);
            }
            else {
                return response()->json(['errormsg' => 'code error', 'id' => $id], 201);
            }
        }
    }

    public function postdeliverTxns(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $company_id = $user->company_id;

        $validator = Validator::make(($request->all()), [
            'driver_sign' => 'required',
            'driver_pin' => 'required',
            'ids' => 'required'
        ]);

        $txn_success = array();
        $txn_failed = array();
        $driver_ids = array();

        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $driver_sign = $request->input('driver_sign');
            $driver_sign = substr($driver_sign, strpos($driver_sign, ",")+1);
            $image = base64_decode($driver_sign);
            $filenameToStore = 'driver_'.time().'.'.'png';
            $path = public_path() . '\storage\driver_sign\\'. $filenameToStore;
            file_put_contents($path, $image);

            $ids = $request->input('ids');

            foreach($ids as $id){
                array_push($driver_ids, $id['driver_id']);
            }

            $same_driver = count(array_unique($driver_ids));

            if ($same_driver > 1){
                return response()->json(['driver_err' => 'diff drivers'], 200);                
            }
            else {
                foreach($ids as $id){
                    $driver_pin = $request->input('driver_pin');
                    $driver_id = $id['driver_id'];
                    $hashed_driver_pin = User::select('password')->where('id', '=', $driver_id)->pluck('password')->first();
                    if (Hash::check($driver_pin, $hashed_driver_pin)) {

                        $txn = Txn::find($id['id']);
                        $txn->parcel_status_id = '3';
                        $txn->drop_driver_sign =  $filenameToStore;
                        $txn->updated_by = $user->id;
                        $txn->save();

                        $txnlog = new TxnLog;
                        $txnlog->awb_id = $txn->id;
                        $txnlog->status_id = '3';
                        $txnlog->origin_id = $txn->origin_id;
                        $txnlog->dest_id = $txn->dest_id;
                        if ($request->input('notes') != NULL){
                            $txnlog->notes = $request->input('notes');
                        }
                        $txnlog->updated_by = $user->id;
                        $txnlog->company_id = $company_id;
                        $txnlog->save();

                        array_push($txn_success, $id['awb']);
                    }
                    else {
                        array_push($txn_failed, $id['awb']);
                    }
                }
                return response()->json(['success' => $txn_success, 'failed' => $txn_failed], 201);
            }
            
        }
    }

    public function getdeliveredTxn()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$station_id = $user->station_id;

    	$txn = Txn::where('company_id', '=', $company_id)->where('dest_id', '=', $station_id)->where('parcel_status_id', '=', '3')->orderby('id', 'desc')->get();
    	
    	return response()->json(['txn' => $txn], 201);
    }

    public function postreceiveTxn($id, Request $request)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    
    	$validator = Validator::make(($request->all()), [
            'receiver_id_num' => 'required',
            'receiver_sign' => 'required',
            'receiver_code' => 'required'
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
            $receiver_code = $request->input('receiver_code');

            $hashed_receiver_code = Txn::select('receiver_code')->where('id', '=', $id)->pluck('receiver_code')->first();

            if (Hash::check($receiver_code, $hashed_receiver_code)) {
    	        $txn = Txn::find($id);
    	        $txn->parcel_status_id = '4';
                $txn->receiver_sign =  $filenameToStore;
    	        if ($receiver_name != NULL) { $txn->receiver_name = $receiver_name; }
    	        if ($receiver_phone != NULL) { $txn->receiver_phone = $receiver_phone; }
    	        $txn->receiver_id_num = $request->input('receiver_id_num');
    	        $txn->updated_by = $user->id;
    	        $txn->save();

    	        $txnlog = new TxnLog;
    	        $txnlog->awb_id = $txn->id;
    	        $txnlog->status_id = '4';
    	        $txnlog->origin_id = $txn->origin_id;
    	        $txnlog->dest_id = $txn->dest_id;
                if ($request->input('notes') != NULL){
                    $txnlog->notes = $request->input('notes');
                }
    	        $txnlog->updated_by = $user->id;
                $txnlog->company_id = $company_id;
    	        $txnlog->save();

                return response()->json(['txn' => $txn], 201);
            }
            else {
                return response()->json(['errormsg' => 'code error'], 201);
            }
        }
    }

    public function getreceivedTxn()
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$company_id = $user->company_id;
    	$station_id = $user->station_id;
        $curr_date = date('Y-m-d');

    	$txn = Txn::where('company_id', '=', $company_id)->where(DB::raw('date(updated_at)'), '=', $curr_date)->where('dest_id', '=', $station_id)->where('parcel_status_id', '=', '4')->orderby('id', 'desc')->get();
    	
    	return response()->json(['txn' => $txn], 201);
    }

    public function getShipments(Request $request)
    {

        $company_id = Auth::user()->company_id;
        $company_details = Company::where('id', '=', $company_id)->get();
        $curr_date = date('Y-m-d');
        
        $parcel_status = ParcelStatus::pluck('name', 'id')->all();
        $clerks = User::where('usertype','=','clerk')->where('company_id', '=', $company_id)->pluck('fullname', 'id')->all();
        $stations = Station::where('company_id', '=', $company_id)->pluck('name', 'id')->all();
        $tot_coll = 0;

        $awb_num = $request->input('awb_num');
        $origin_id = $request->input('origin_id');
        $dest_id = $request->input('dest_id');
        $sender_name = $request->input('sender_name');
        $receiver_name = $request->input('receiver_name');
        $parcel_status_id = $request->input('parcel_status_id');
        $first_date = $request->input('first_date');
        $last_date = $request->input('last_date');
        $clerk_id = $request->input('clerk_id');

        if ($request->isMethod('POST')){
            $txns = Txn::where('company_id', '=', $company_id);
            $tot_coll = Txn::select('company_id', DB::raw('sum(price) as tot_coll'))->where('company_id', '=', $company_id);

            if ($awb_num != NULL){
                $txns = $txns->where('awb_num','like','%'.$awb_num.'%');
                $tot_coll = $tot_coll->where('awb_num','like','%'.$awb_num.'%');
            }
            if ($origin_id != NULL){
                $txns = $txns->where('origin_id','=', $origin_id);
                $tot_coll = $tot_coll->where('origin_id','=', $origin_id);
            }
            if ($dest_id != NULL){
                $txns = $txns->where('dest_id','=', $dest_id);
                $tot_coll = $tot_coll->where('dest_id','=', $dest_id);
            }
            if ($sender_name != NULL){
                $txns = $txns->where('sender_name','like','%'.$sender_name.'%');
                $tot_coll = $tot_coll->where('sender_name','like','%'.$sender_name.'%');
            }
            if ($receiver_name != NULL){
                $txns = $txns->where('receiver_name','like','%'.$receiver_name.'%');
                $tot_coll = $tot_coll->where('receiver_name','like','%'.$receiver_name.'%');
            }
            if ($parcel_status_id != NULL){
                $txns = $txns->where('parcel_status_id','=', $parcel_status_id);
                $tot_coll = $tot_coll->where('parcel_status_id','=', $parcel_status_id);
            }
            if ($first_date != NULL){
                if ($last_date != NULL){
                    $txns = $txns->where(DB::raw('date(created_at)'), '<=', $last_date)->where(DB::raw('date(created_at)'),'>=',$first_date);
                    $tot_coll = $tot_coll->where(DB::raw('date(created_at)'), '<=', $last_date)->where(DB::raw('date(created_at)'),'>=',$first_date);
                } 
                else{
                    $txns = $txns->where(DB::raw('date(created_at)'), '=', $first_date);
                    $tot_coll = $tot_coll->where(DB::raw('date(created_at)'), '=', $first_date);
                }
            }
            if ($clerk_id != NULL){
                $txns = $txns->where('clerk_id','=', $clerk_id);
                $tot_coll = $tot_coll->where('clerk_id','=', $clerk_id);
            }

            $txns = $txns->orderBy('id','desc')->limit(50)->get();
            $tot_coll = $tot_coll->groupBy('company_id')->pluck('tot_coll')->first();
            if ($tot_coll == NULL) {
                $tot_coll = 0;
            }
            
            if ($request->submitBtn == 'CreatePDF') {
                $pdf = PDF::loadView('pdf.shipments', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'tot_coll' => $tot_coll]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('shipments.pdf');
            }
        }
        else {
            $txns = Txn::where('company_id','=',$company_id)->orderBy('id','desc')->limit(50)->get();
            $tot_coll = Txn::select('company_id', DB::raw('sum(price) as tot_coll'))->where('company_id', '=', $company_id)->groupBy('company_id')->pluck('tot_coll')->first();
            if ($tot_coll == NULL) {
                $tot_coll = 0;
            }
        }

        return view('shipments.index', ['txns' => $txns, 'stations' => $stations, 'clerks' => $clerks, 'parcel_status' => $parcel_status, 'tot_coll' => $tot_coll]);
    }

    public function getAwbsearch(Request $request)
    {
        $awb_num = $request->input('awb_num');
        $txn = [];
        $statusDet = [];

        if ($request->isMethod('POST')){
            $this->validate($request, [
                'awb_num' => 'required'
            ]);
            $txn = DB::table('txns')
                ->join('stations as s1', 'txns.origin_id', '=', 's1.id')
                ->join('stations as s2', 'txns.dest_id', '=', 's2.id')
                ->join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
                ->join('parcel_statuses', 'txns.parcel_status_id', '=', 'parcel_statuses.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_status_id', 'parcel_statuses.name as parcel_status_name', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.driver_id', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.awb_num', '=', $awb_num)
                ->limit(1)->get();

            $statusDet = DB::table('txn_logs as t')
                    ->join('parcel_statuses as p', 't.status_id', '=', 'p.id')
                    ->join('users as u', 't.updated_by', '=', 'u.id')
                    ->join('txns as tx', 't.awb_id', '=', 'tx.id')
                    ->select('t.awb_id', 't.status_id', 'p.name as status_name', 'p.description as description', 't.updated_by', 'u.username as username', 't.updated_at')
                    ->where('tx.awb_num', '=', $awb_num)
                    ->orderby('t.id', 'desc')
                    ->get();
        }

        return view('awbsearch.index', ['txn' => $txn, 'statusDet' => $statusDet]);
    }

    public function getAwb(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $awb_num = $request->input('awb_num');
        $txn = [];
        $statusDet = [];
        $error = '';

        if ($request->isMethod('POST')){
            $this->validate($request, [
                'awb_num' => 'required'
            ]);
            $txn = Txn::join('stations as s1', 'txns.origin_id', '=', 's1.id')
                ->join('stations as s2', 'txns.dest_id', '=', 's2.id')
                ->join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
                ->join('parcel_statuses', 'txns.parcel_status_id', '=', 'parcel_statuses.id')
                // ->join('users as u', 'txns.driver_id', '=', 'u.id')
                // ->join('vehicles as v', 'txns.vehicle_id', '=', 'v.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 's1.name as origin_name', 'txns.dest_id',  's2.name as dest_name', 'txns.parcel_status_id', 'txns.parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_status_id', 'parcel_statuses.description', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.receiver_sign', 'txns.driver_id', 'txns.pick_driver_sign', 'txns.vehicle_id', 'txns.updated_by')
                ->where('txns.awb_num', '=', $awb_num)
                ->where('txns.company_id', '=', $company_id)
                ->get();

            $statusDet = DB::table('txn_logs as t')
                ->join('parcel_statuses as p', 't.status_id', '=', 'p.id')
                ->join('users as u', 't.updated_by', '=', 'u.id')
                ->join('txns as tx', 't.awb_id', '=', 'tx.id')
                ->select('t.awb_id', 't.status_id', 'p.name as status_name', 'p.description as description', 't.updated_by', 'u.fullname as fullname', 't.updated_at')
                ->where('tx.awb_num', '=', $awb_num)
                ->where('tx.company_id', '=', $company_id)
                ->orderby('t.id', 'desc')
                ->get();

            if ($txn == null){
                $error = 'Please enter a valid AWB number';
            }
            
        }

        return view('shipments.awb', ['txn' => $txn, 'statusDet' => $statusDet, 'error' => $error]);
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $txn = Txn::where('company_id', '=', $company_id)->find($id);
        $origin_id = $txn->origin_id;
        $parcel_statuses = ParcelStatus::pluck('name','id')->all();
        $parcel_types = ParcelType::pluck('name','id')->all();
        $stations = Station::where('id', '!=', $origin_id)->pluck('name','id')->all();
        $drivers = User::where('usertype', '=', 'driver')->pluck('fullname','id')->all();
        $vehicles = Vehicle::pluck('name','id')->all();
        $statusDet = DB::table('txn_logs as t')
                ->join('parcel_statuses as p', 't.status_id', '=', 'p.id')
                ->join('users as u', 't.updated_by', '=', 'u.id')
                ->join('txns as tx', 't.awb_id', '=', 'tx.id')
                ->select('t.awb_id', 't.status_id', 'p.name as status_name', 'p.description as description', 't.updated_by', 'u.fullname as fullname', 't.updated_at')
                ->where('tx.id', '=', $id)
                ->orderby('t.id', 'desc')
                ->get();
        if ($txn == null){
            return redirect('/shipments')->with('error', 'Txn not found');
        }
        $companies = Company::pluck('name','id');
        return view('shipments.edit',['txn'=> $txn, 'companies' => $companies, 'parcel_statuses' => $parcel_statuses, 'parcel_types' => $parcel_types, 'stations' => $stations, 'drivers' => $drivers, 'vehicles' => $vehicles, 'statusDet' => $statusDet]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'parcel_status_id' => 'required',
            'dest_id' => 'required',
            'sender_name' => 'required',
            'sender_phone' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'price' => 'required'
        ]);

        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        $price = $request->input('price');
        $vat = 0.16 * $price;

        $txn = Txn::find($id);
        $txn->parcel_type_id = $request->input('parcel_type_id');
        $txn->price = $price;
        $txn->vat = $vat;
        $txn->sender_name = $request->input('sender_name');
        $txn->sender_phone = $request->input('sender_phone');
        $txn->sender_id_num = $request->input('sender_id_num');
        $txn->receiver_name = $request->input('receiver_name');
        $txn->receiver_phone = $request->input('receiver_phone');
        $txn->receiver_id_num = $request->input('receiver_id_num');
        $txn->updated_by = $user->id;

        if ($txn->parcel_status_id != $request->input('parcel_status_id')) {
            $txnlog = new TxnLog;
            $txnlog->awb_id = $txn->id;
            $txnlog->status_id = $request->input('parcel_status_id');
            $txnlog->origin_id = $txn->origin_id;
            if ($txn->dest_id != $request->input('dest_id')) {
                $txnlog->dest_id = $request->input('dest_id');
            }
            else {
                $txnlog->dest_id = $txn->dest_id; 
            }
            $txnlog->updated_by = $user->id;
            $txnlog->company_id = $company_id;
            $txnlog->save();
        }

        $txn->dest_id = $request->input('dest_id');
        $txn->parcel_status_id = $request->input('parcel_status_id');
        $txn->save();

        $userlog = new UserLog();
        $userlog->username = $user->username;
        $userlog->activity = "Updated txn ".$txn->awb_num;
        $userlog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlog->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlog->company_id = $company_id;
        $userlog->save();
        
        return redirect('/shipments')->with('success', 'Shipment details updated for '. $txn->awb_num);
    }

    public function resetDrivercode($id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        function randomDigits($length){
            $num = '';
            $numbers = range(0,9);
            shuffle($numbers);
            for($i = 0;$i < $length;$i++)
               $num .= $numbers[$i];
            return $num;
        }
        $driver_code = randomDigits(6);
        $driver_code_hash = Hash::make($driver_code);
        
        $txn = Txn::find($id);
        $txn->driver_code = $driver_code_hash;
        $txn->updated_by = $user->id;
        $txn->save();

        $userlog = new UserLog();
        $userlog->username = $user->username;
        $userlog->activity = "Driver code reset for ".$txn->awb_num;
        $userlog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlog->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlog->company_id = $company_id;
        $userlog->save();

        // $driver_phone = $txn->driver['phone'];
        // // Send password via SMS
        // if ($driver_phone != NULL)
        // {
        //     $atgusername   = env('ATGUSERNAME');
        //     $atgapikey     = env('ATGAPIKEY');
        //     $recipients = '+'.$driver_phone;
        //     $message    = "The driver code for ".$txn->awb_num." is ".$driver_code;
        //     $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
        //     try 
        //     { 
        //       $send_results = $gateway->sendMessage($recipients, $message);
        //     }
        //     catch ( AfricasTalkingGatewayException $e )
        //     {
        //       echo 'Encountered an error while sending: '.$e->getMessage();
        //     }
        // }

        return redirect()->back()->with('success', 'Driver code reset for shipment '. $txn->awb_num);
    }

    public function resetReceivercode($id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;
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
        
        $txn = Txn::find($id);
        $txn->receiver_code = $receiver_code_hash;
        $txn->updated_by = $user->id;
        $txn->save();

        $userlog = new UserLog();
        $userlog->username = $user->username;
        $userlog->activity = "Receiver code reset for ".$txn->awb_num;
        $userlog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlog->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlog->company_id = $company_id;
        $userlog->save();

        // $receiver_phone = '254'.$txn->receiver_phone;
        // // Send password via SMS
        // if ($receiver_phone != NULL)
        // {
        //     $atgusername   = env('ATGUSERNAME');
        //     $atgapikey     = env('ATGAPIKEY');
        //     $recipients = '+'.$receiver_phone;
        //     $message    = "The receiver code for ".$txn->awb_num." is ".$receiver_code;
        //     $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
        //     try 
        //     { 
        //       $send_results = $gateway->sendMessage($recipients, $message);
        //     }
        //     catch ( AfricasTalkingGatewayException $e )
        //     {
        //       echo 'Encountered an error while sending: '.$e->getMessage();
        //     }
        // }

        return redirect()->back()->with('success', 'Receiver code reset for shipment '.$txn->awb_num. ' ' .$receiver_code);
    }
}
