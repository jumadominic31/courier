<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
use App\SmsApi;
use JWTAuth;
use Validator;
use Auth;
use Session;
use PDF;

class CusportalController extends Controller
{
    //Users
    public function cususers()
    {
        $company_id = Auth::user()->company_id;
        $users = User::where('company_id', '=', $company_id)->get();
        return view('portal.users.index',['users'=> $users]);
    }

    public function cuscreate()
    {
        $company_id = Auth::user()->company_id;
        return view('portal.users.create');
    }

    public function cusstore(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $user_id = Auth::user()->id;

        $this->validate($request, [
            'username' => 'required|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => array('required', 'regex:/^[0-9]{12}$/'),
            'status' => 'required',
            'usertype' => 'required' 
        ]);

        //Set new random password
        function randomPassword() {
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass); //turn the array into a string
        }
        
        //$password = randomPassword();
        $password = 'courier123';
        $email = $request->input('email');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');

        $user = new User;
        $user->username = $request->input('username');
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->fullname = $firstname.' '.$lastname;
        $user->phone = $request->input('phone');
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->company_id = $company_id;
        $user->status = $request->input('status');
        $user->usertype = $request->input('usertype');
        $user->updated_by = $user_id;
        $user->save();

        //get new user email

        /*$email = $user->email;
        $phone = $user->phone;
        // Send password via SMS
        if ($phone != NULL)
        {
            $atgusername   = env('ATGUSERNAME');
            $atgapikey     = env('ATGAPIKEY');
            $recipients = '+'.$phone;
            $message    = "Your password is ".$password;
            $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
            try 
            { 
              $results = $gateway->sendMessage($recipients, $message);
                        
              foreach($results as $result) {
                // status is either "Success" or "error message"
                echo ' Number: ' .$result->number;
                echo ' Status: ' .$result->status;
                echo ' MessageId: ' .$result->messageId;
                echo ' Cost: '   .$result->cost.'\n';
              }
            }
            catch ( AfricasTalkingGatewayException $e )
            {
              echo 'Encountered an error while sending: '.$e->getMessage();
            }
        }

        if ($email != NULL)
        {
            Mail::to($email)->send(new GeneratePassword($password)); 
        }*/

        return redirect('/portal/users')->with('success', 'User Created');
    }

    public function getProfile() 
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        $company = Company::where('id', '=', $company_id)->get();
        return view('portal.users.profile', ['user' => $user, 'company' => $company, 'company_id' => $company_id]);
        // return view('users.profile', ['user' => $user]);
    }

    public function editUser($id)
    {
        $company_id = Auth::user()->company_id;
        $user_id = Auth::user()->id;
        $companies = Company::pluck('name','id')->all();
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $stations = Station::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        $user = User::where('company_id','=',$company_id)->find($id);
        if ($user == null){
            return redirect('/portal/users')->with('error', 'User not found');
        }

        return view('portal.users.edit',['user'=> $user, 'companies' => $companies, 'stations' => $stations]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $company_id = Auth::user()->company_id;

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => ['required', 'regex:/^[0-9]{12}$/'],
            'status' => 'required',
            'usertype' => 'required'
        ]);
        
        
        $user = User::find($id);
        
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $station_id = $request->input('station_id');
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->fullname = $firstname.' '.$lastname;
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        if ($station_id != NULL) {
            $user->station_id = $station_id;
        }
        $user->status = $request->input('status');
        $user->usertype = $request->input('usertype');
        if (Auth::user()->usertype == 'superadmin') {
            $user->company_id = $request->input('company_id');;
        } 
        else {
            $user->company_id = $company_id;
        }
        $user->updated_by = $user_id;
        $user->save();
        
        return redirect('/portal/users/profile')->with('success', 'User details updated');
    }

    public function resetpass()
    {
        return view('portal.users.resetpass');
    }

    public function postResetpass(Request $request) 
    {
        $this->validate($request, [
            'curr_password' => 'required',
            'new_password_1' => 'required|same:new_password_1',
            'new_password_2' => 'required|same:new_password_1'
        ]);

        $current_password = Auth::User()->password;
        $user_id = Auth::user()->id;
        $new_password = $request->input('new_password_1');

        if(Hash::check($request->input('curr_password'), $current_password)){
            $request->user()->fill([
                'password' => Hash::make($request->input('new_password_1'))
            ])->save();

            //to log user password reset to user_logins table
            $username = Auth::user()->username;
            $userlogin = new UserLog();
            $userlogin->username = $username;
            $userlogin->activity = "Password reset for ".$username;
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();


            /*$email = User::select('email')->where('id', '=', $user_id)->pluck('email')->first();
            $phone = User::select('phone')->where('id', '=', $user_id)->pluck('phone')->first();
            // Send password via SMS
            if ($phone != NULL)
            {
                $atgusername   = env('ATGUSERNAME');
                $atgapikey     = env('ATGAPIKEY');
                $recipients = '+'.$phone;
                $message    = "Your password is ".$new_password;
                $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
                try 
                { 
                  $results = $gateway->sendMessage($recipients, $message);
                            
                  foreach($results as $result) {
                    // status is either "Success" or "error message"
                    echo ' Number: ' .$result->number;
                    echo ' Status: ' .$result->status;
                    echo ' MessageId: ' .$result->messageId;
                    echo ' Cost: '   .$result->cost.'\n';
                  }
                }
                catch ( AfricasTalkingGatewayException $e )
                {
                  echo 'Encountered an error while sending: '.$e->getMessage();
                }
            }

            if ($email != NULL)
            {
                Mail::to($email)->send(new GeneratePassword($new_password)); 
            }*/

            return redirect('/users/logout')->with('success', 'Password Changed. Login again');
        } else {
            return redirect('/portal/users/resetpass')->with('error', 'Current password incorrect');
        }
    }

    //shipment transactions
    public function getShipments(Request $request)
    {

        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $company_details = Company::where('id', '=', $company_id)->get();
        $curr_date = date('Y-m-d');
        
        $parcel_status = ParcelStatus::pluck('name', 'id')->all();
        $clerks = User::where(function($q) { $q->where('usertype','=','cusclerk')->orWhere('usertype','=','cusadmin'); })->where('company_id', '=', $company_id)->pluck('fullname', 'id')->all();
        // $stations = Station::where('company_id', '=', $company_id)->pluck('name', 'id')->all();
        $zones = Zone::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
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
            $txns = Txn::where('sender_company_id', '=', $company_id);
            $tot_coll = Txn::select('sender_company_id', DB::raw('sum(price) as tot_coll'))->where('sender_company_id', '=', $company_id);

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
            $tot_coll = $tot_coll->groupBy('sender_company_id')->pluck('tot_coll')->first();
            if ($tot_coll == NULL) {
                $tot_coll = 0;
            }
            
            if ($request->submitBtn == 'CreatePDF') {
                $pdf = PDF::loadView('portal.pdf.shipments', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'tot_coll' => $tot_coll]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('shipments.pdf');
            }
        }
        else {
            $txns = Txn::where('sender_company_id','=',$company_id)->orderBy('id','desc')->limit(50)->get();
            $tot_coll = Txn::select('sender_company_id', DB::raw('sum(price) as tot_coll'))->where('sender_company_id', '=', $company_id)->groupBy('sender_company_id')->pluck('tot_coll')->first();
            if ($tot_coll == NULL) {
                $tot_coll = 0;
            }
        }

        return view('portal.shipments.index', ['txns' => $txns, 'zones' => $zones, 'clerks' => $clerks, 'parcel_status' => $parcel_status, 'tot_coll' => $tot_coll]);
    }

    public function addShipment()
    {   
        $user = Auth::user();
        $company_id = $user->company_id;
        $company_addr = Company::select('address')->where('id', '=', $company_id)->pluck('address')->first();
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $zone_id = Company::select('zone_id')->where('id', '=', $company_id)->pluck('zone_id')->first();
        $zone_name = Company::join('zones as z1', 'companies.zone_id', '=', 'z1.id')->select('z1.name')->where('companies.id', '=', $company_id)->pluck('z1.name')->first();
        $parcel_types = ParcelType::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        $zones = Zone::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        return view('portal.shipments.add', ['user' => $user, 'company_addr' => $company_addr, 'zone_id' => $zone_id, 'zone_name' => $zone_name, 'parcel_types' => $parcel_types, 'zones' => $zones]);
    }

    public function storeShipment(Request $request)
    {   
        $this->validate($request, [
            'receiver_name' => 'required',
            'receiver_company' => 'required',
            'receiver_phone' => array('required', 'regex:/^[0-9]{12}$/'),
            'origin_addr' => 'required',
            'dest_addr' => 'required',
            'parcel_type_id' => 'required',
            'mode' =>'required',
            'round' => 'required',
            'units' => 'required|numeric' //,
            // 'price' => 'required'            
        ]);

        $user = Auth::user();
        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        $company_name = Company::select('name')->where('id', '=', $company_id)->pluck('name')->first();
        $zone_id = Company::select('zone_id')->where('id', '=', $company_id)->pluck('zone_id')->first();
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $parent_company_phone = Company::select('phone')->where('id', '=', $parent_company_id)->pluck('phone')->first();

        function randomDigits($length){
            $num = '';
            $numbers = range(0,9);
            shuffle($numbers);
            for($i = 0;$i < $length;$i++)
               $num .= $numbers[$i];
            return $num;
        }
        
        $prefix = Company::where('id', '=', $parent_company_id)->pluck('name')->first();
        $prefix = strtoupper($prefix);
        $prefix = substr($prefix, 0, 3);
        
        $newawbnum = randomDigits(5);
        $newawbnum = $prefix.date('ymd').$newawbnum;
        // $price = $request->input('price');
        // $vat = 0.16 * $price;
        
        $parcel_desc = $request->input('parcel_desc');
        $receiver_phone = $request->input('receiver_phone');
        $receiver_code = randomDigits(6);
        $receiver_code_hash = Hash::make($receiver_code);

        $txn = new Txn;
        $txn->awb_num = $newawbnum;
        $txn->clerk_id = $user_id;
        $txn->mode = $request->input('mode');
        $txn->round = $request->input('round');
        $txn->units = $request->input('units');
        $txn->company_id = $parent_company_id;
        $txn->parcel_status_id = '7';
        $txn->parcel_type_id = $request->input('parcel_type_id');
        if ($parcel_desc != NULL){
            $txn->parcel_desc = $parcel_desc;
        }
        // $txn->price = $price;
        // $txn->vat = $vat;
        $txn->sender_name = $user->fullname;
        $txn->sender_company_id = $user->company_id;
        $txn->sender_company_name = Company::select('name')->where('id', '=', $user->company_id)->pluck('name')->first();
        $txn->origin_addr = $request->input('origin_addr');
        $txn->sender_phone = $user->phone;
        $txn->receiver_name = $request->input('receiver_name');
        $txn->receiver_company_name = $request->input('receiver_company');
        $txn->receiver_phone = $receiver_phone;
        $txn->dest_addr = $request->input('dest_addr');
        $txn->receiver_code = $receiver_code_hash;
        $txn->updated_by = $user->id;
        $txn->save();

        if ($txn->round == 0)
        {
            $round = "NO";
        }
        else if ($txn->round == 1)
        {
            $round = "YES";
        }

        if ($txn->mode == 0)
        {
            $mode = " Normal service";
        }
        else if ($txn->mode == 1)
        {
            $mode = " Express service";
        }

        $txnlog = new TxnLog;
        $txnlog->awb_id = $txn->id;
        $txnlog->status_id = $txn->parcel_status_id;
        // $txnlog->origin_id = $txn->origin_id;
        // $txnlog->dest_id = $txn->dest_id;
        $txnlog->updated_by = $user->id;
        $txnlog->company_id = $parent_company_id;
        $txnlog->save();

        //Create return AWB
        if ($txn->round == 1)
        {
            $returnawbnum = randomDigits(5);
            $returnawbnum = $prefix.date('ymd').$returnawbnum;
            // $price = $request->input('price');
            // $vat = 0.16 * $price;
            
            $parcel_desc = $request->input('parcel_desc');
            $receiver_phone = $request->input('receiver_phone');
            $receiver_code = randomDigits(6);
            $receiver_code_hash = Hash::make($receiver_code);

            $txn = new Txn;
            $txn->awb_num = $returnawbnum;
            $txn->clerk_id = $user_id;
            $txn->mode = $request->input('mode');
            $txn->round = $request->input('round');
            $txn->units = $request->input('units');
            $txn->company_id = $parent_company_id;
            $txn->parcel_status_id = '7';
            $txn->parcel_type_id = $request->input('parcel_type_id');
            if ($parcel_desc != NULL){
                $txn->parcel_desc = $parcel_desc;
            }
            // $txn->price = $price;
            // $txn->vat = $vat;
            $txn->sender_name = $user->fullname;
            $txn->sender_company_id = $user->company_id;
            $txn->sender_company_name = Company::select('name')->where('id', '=', $user->company_id)->pluck('name')->first();
            $txn->origin_addr = $request->input('dest_addr');
            $txn->sender_phone = $user->phone;
            $txn->receiver_name = $request->input('receiver_name');
            $txn->receiver_company_name = $request->input('receiver_company');
            $txn->receiver_phone = $receiver_phone;
            $txn->dest_addr = $request->input('origin_addr');
            $txn->receiver_code = $receiver_code_hash;
            $txn->updated_by = $user->id;
            $txn->save();

            $txnlog = new TxnLog;
            $txnlog->awb_id = $txn->id;
            $txnlog->status_id = $txn->parcel_status_id;
            // $txnlog->origin_id = $txn->origin_id;
            // $txnlog->dest_id = $txn->dest_id;
            $txnlog->updated_by = $user->id;
            $txnlog->company_id = $parent_company_id;
            $txnlog->save();
        }

        // $atgusername   = SmsApi::select('atgusername')->where('company_id', '=', $parent_company_id)->pluck('atgusername')->first();
        // $atgapikey     = SmsApi::select('atgapikey')->where('company_id', '=', $parent_company_id)->pluck('atgapikey')->first();
        // $atgsender_id  = SmsApi::select('atgsender_id')->where('company_id', '=', $parent_company_id)->pluck('atgsender_id')->first();
        
        // if (($atgusername == NULL) || ($atgapikey == NULL))
        // {
        //     $atgusername   = env('ATGUSERNAME');
        //     $atgapikey     = env('ATGAPIKEY');
        // }
            
        // // Send password via SMS
        // if ($atgusername != NULL){
        //     if ($parent_company_phone != NULL)
        //     {
        //         $recipients = '+'.$parent_company_phone;
        //         $message    = "A parcel has been booked by " .$company_name. " under AWB ".$txn->awb_num." to ".$txn->receiver_addr;
        //         $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
        //         try 
        //         { 
        //             if ($atgsender_id != NULL){
        //                     $send_results = $gateway->sendMessage($recipients, $message, $atgsender_id);
        //                 } 
        //                 else {
        //                     $send_results = $gateway->sendMessage($recipients, $message);
        //                 }
        //         }
        //         catch ( AfricasTalkingGatewayException $e )
        //         {
        //           echo 'Encountered an error while sending: '.$e->getMessage();
        //         }
        //     }
        // }

        return redirect('/portal/shipments')->with('success', "Shipment added");
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $txn = Txn::where('sender_company_id', '=', $company_id)->find($id);
        if ($txn == null){
            return redirect('/portal/shipments')->with('error', 'Txn not found');
        }
        $origin_id = $txn->origin_id;
        $parcel_statuses = ParcelStatus::pluck('name','id')->all();
        $parcel_types = ParcelType::pluck('name','id')->all();
        // $stations = Station::where('id', '!=', $origin_id)->pluck('name','id')->all();
        $zones = Zone::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        $origin_addr = $txn->origin_addr;
        $dest_addr = $txn->dest_addr;
        $drivers = User::where('company_id', '=', $parent_company_id)->where('usertype', '=', 'driver')->pluck('fullname','id')->all();
        $vehicles = Vehicle::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        $statusDet = DB::table('txn_logs as t')
                ->join('parcel_statuses as p', 't.status_id', '=', 'p.id')
                ->join('users as u', 't.updated_by', '=', 'u.id')
                ->join('txns as tx', 't.awb_id', '=', 'tx.id')
                ->select('t.awb_id', 't.status_id', 'p.name as status_name', 'p.description as description', 't.updated_by', 'u.fullname as fullname', 't.updated_at')
                ->where('tx.id', '=', $id)
                ->orderby('t.id', 'desc')
                ->get();
       
        $companies = Company::pluck('name','id');
        return view('portal.shipments.edit',['txn'=> $txn, 'companies' => $companies, 'parcel_statuses' => $parcel_statuses, 'parcel_types' => $parcel_types, 'zones' => $zones, 'origin_addr' => $origin_addr, 'dest_addr' => $dest_addr, 'drivers' => $drivers, 'vehicles' => $vehicles, 'statusDet' => $statusDet]);
    }

    public function cancel(Request $request, $id)
    {
        return redirect('/portal/shipments');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'parcel_status_id' => 'required',
            'origin_addr' => 'required',
            'dest_addr' => 'required',
            // 'sender_name' => 'required',
            // 'sender_phone' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required'
        ]);

        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        // $price = $request->input('price');
        // $vat = 0.16 * $price;

        $txn = Txn::find($id);
        $txn->parcel_type_id = $request->input('parcel_type_id');
        // $txn->price = $price;
        // $txn->vat = $vat;
        $txn->mode = $request->input('mode');
        $txn->round = $request->input('round');
        $txn->units = $request->input('units');
        // $txn->sender_name = $request->input('sender_name');
        // $txn->sender_phone = $request->input('sender_phone');
        $txn->sender_id_num = $request->input('sender_id_num');
        $txn->receiver_name = $request->input('receiver_name');
        $txn->receiver_phone = $request->input('receiver_phone');
        $txn->receiver_id_num = $request->input('receiver_id_num');
        $txn->updated_by = $user->id;

        // if ($txn->parcel_status_id != $request->input('parcel_status_id')) {
        //     $txnlog = new TxnLog;
        //     $txnlog->awb_id = $txn->id;
        //     $txnlog->status_id = $request->input('parcel_status_id');
        //     $txnlog->origin_id = $txn->origin_id;
        //     if ($txn->dest_id != $request->input('dest_id')) {
        //         $txnlog->dest_id = $request->input('dest_id');
        //     }
        //     else {
        //         $txnlog->dest_id = $txn->dest_id; 
        //     }
        //     $txnlog->updated_by = $user->id;
        //     $txnlog->company_id = $company_id;
        //     $txnlog->save();
        // }

        $txn->origin_addr = $request->input('origin_addr');
        $txn->dest_addr = $request->input('dest_addr');
        // $txn->parcel_status_id = $request->input('parcel_status_id');
        $txn->save();

        $userlog = new UserLog();
        $userlog->username = $user->username;
        $userlog->activity = "Updated txn ".$txn->awb_num;
        $userlog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlog->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlog->company_id = $company_id;
        $userlog->save();

        if ($request->submitBtn == 'Cancel Booking') {
            return redirect('/portal/shipments');
        }
        
        return redirect('/portal/shipments')->with('success', 'Shipment details updated for '. $txn->awb_num);
    }

    //Rates
    public function getRates()
    {
        return view('portal.rates.index');
    }

    public function getAwb(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
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
                ->where('txns.sender_company_id', '=', $company_id)
                ->get();

            $statusDet = DB::table('txn_logs as t')
                ->join('parcel_statuses as p', 't.status_id', '=', 'p.id')
                ->join('users as u', 't.updated_by', '=', 'u.id')
                ->join('txns as tx', 't.awb_id', '=', 'tx.id')
                ->select('t.awb_id', 't.status_id', 'p.name as status_name', 'p.description as description', 't.updated_by', 'u.fullname as fullname', 't.updated_at')
                ->where('tx.awb_num', '=', $awb_num)
                ->where('tx.sender_company_id', '=', $company_id)
                ->orderby('t.id', 'desc')
                ->get();

            if ($txn == null){
                $error = 'Please enter a valid AWB number';
            }
            
        }

        return view('portal.shipments.awb', ['txn' => $txn, 'statusDet' => $statusDet, 'error' => $error]);
    }

    //Parcels
    public function getParcels()
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $parceltype = ParcelType::where('company_id', '=', $parent_company_id)->get();
        $parcelstatus = ParcelStatus::get();

        return view('portal.parcel.index', ['parceltype' => $parceltype, 'parcelstatus' => $parcelstatus]);
    }

    //Company edit
    public function editCompany($id)
    {
        $company_id = Auth::user()->company_id;
        if (Auth::user()->usertype != 'superadmin'){
            $company = Company::findOrFail($company_id);
        }
        else {
            $company = Company::find($id);
        }
        return view('portal.company.edit',['company'=> $company]);
    }

    public function updateCompany(Request $request, $id)
    {
        $this->validate($request, [
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

        return redirect('/portal/users/profile')->with('success', 'Company details updated');
       
    }
}
