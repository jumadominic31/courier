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
use App\Token;
use App\Token_bal;
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
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $stations = Station::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        return view('portal.users.create',['stations'=> $stations]);
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
            return redirect('/portal/users/profile')->with('error', 'User not found');
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
            'phone' => ['required', 'regex:/^[0-9]{12}$/']
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
        $company_details = Company::where('id', '=', $parent_company_id)->get();
        $cus_company_details = Company::where('id', '=', $company_id)->get();
        $curr_date = date('Y-m-d');
        
        $parcel_status = ParcelStatus::pluck('name', 'id')->all();
        // $clerks = User::where(function($q) { $q->where('usertype','=','cusclerk')->orWhere('usertype','=','cusadmin'); })->where('company_id', '=', $company_id)->pluck('fullname', 'id')->all();
        // $stations = Station::where('company_id', '=', $company_id)->pluck('name', 'id')->all();
        $zones = Zone::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        $tot_coll = 0;
        $tot_count = 0;

        $awb_num = $request->input('awb_num');
        $origin_id = $request->input('origin_id');
        $dest_id = $request->input('dest_id');
        $sender_name = $request->input('sender_name');
        $receiver_name = $request->input('receiver_name');
        $parcel_status_id = $request->input('parcel_status_id');
        $invoiced = $request->input('invoiced');
        $clerk_id = $request->input('clerk_id');

        if ($request->isMethod('POST')){
            $txns = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('date(created_at)'),'>=',$curr_date);
            $tot_coll = Txn::where(DB::raw('date(created_at)'),'>=',$curr_date)->select('sender_company_id', DB::raw('sum(price) as tot_coll'))->where('sender_company_id', '=', $company_id);

            if ($awb_num != NULL){
                $txns = $txns->where('awb_num','like','%'.$awb_num.'%');
                $tot_coll = $tot_coll->where('awb_num','like','%'.$awb_num.'%');
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
            if ($invoiced != NULL){
                $txns = $txns->where('invoiced','=', $invoiced);
                $tot_coll = $tot_coll->where('invoiced','=', $invoiced);      
            }

            $tot_count = $txns->count();
            $txns = $txns->orderBy('id','desc')->limit(50)->get();
            $tot_coll = $tot_coll->groupBy('sender_company_id')->pluck('tot_coll')->first();
            if ($tot_coll == NULL) {
                $tot_coll = 0;
            }
            
            //setting defaults for options
            if ($awb_num == NULL){
                $awb_num = 'All';
            }
            if ($sender_name == NULL) {
                $sender_name = 'All';
            } 
            if ($receiver_name == NULL) {
                $receiver_name = 'All';
            } 
            if ($parcel_status_id != NULL) {
                $parcel_status_name = ParcelStatus::where('id', '=', $parcel_status_id)->pluck('name')->first();
            } 
            else {
                $parcel_status_name = 'All';
            }

            if ($request->submitBtn == 'CreatePDF') {
                $pdf = PDF::loadView('portal.pdf.shipments', ['txns' => $txns, 'company_details' => $company_details, 'cus_company_details' => $cus_company_details, 'curr_date' => $curr_date, 'tot_coll' => $tot_coll, 'tot_count' => $tot_count, 'awb_num' => $awb_num, 'sender_name' => $sender_name, 'receiver_name' => $receiver_name, 'parcel_status_name' => $parcel_status_name]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('shipments.pdf');
            }
        }
        else {
            $tot_count = Txn::where('sender_company_id','=',$company_id)->where(DB::raw('date(created_at)'),'>=',$curr_date)->count();
            $txns = Txn::where('sender_company_id','=',$company_id)->where(DB::raw('date(created_at)'),'>=',$curr_date)->orderBy('id','desc')->get();
            $tot_coll = Txn::where(DB::raw('date(created_at)'),'>=',$curr_date)->select('sender_company_id', DB::raw('sum(price) as tot_coll'))->where('sender_company_id', '=', $company_id)->groupBy('sender_company_id')->pluck('tot_coll')->first();
            if ($tot_coll == NULL) {
                $tot_coll = 0;
            }
        }

        return view('portal.shipments.index', ['txns' => $txns, 'zones' => $zones,  'parcel_status' => $parcel_status, 'tot_coll' => $tot_coll, 'tot_count' => $tot_count]);
    }

    public function addShipment()
    {   
        $user = Auth::user();
        $company_id = $user->company_id;
        $company_addr = Company::select('address')->where('id', '=', $company_id)->pluck('address')->first();
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        // $token_bal = Token_bal::where('company_id', '=', $parent_company_id)->where('sender_company_id', '=', $company_id)->pluck('balance')->first();
        // if ($token_bal == NULL)
        // {
        //     $token_bal = 0;
        // }
        $parcel_types = ParcelType::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
        return view('portal.shipments.add', ['user' => $user, 'company_addr' => $company_addr, 'parcel_types' => $parcel_types]);
    }

    public function storeShipment(Request $request)
    {   
        $this->validate($request, [
            'sender_name' => 'required',
            'receiver_name' => 'required',
            'receiver_company' => 'required',
            'receiver_phone' => array('required', 'regex:/^[0-9]{9,14}$/'),
            'origin_addr_1' => 'required',
            'dest_addr_1' => 'required',
            'parcel_type_id' => 'required',
            // 'round' => 'required',
            'acknowledge' => 'required',
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

        $origin_addr_1 = $request->input('origin_addr_1');
        $origin_addr_2 = $request->input('origin_addr_2');
        $origin_addr_3 = $request->input('origin_addr_3');
        $origin_addr_4 = $request->input('origin_addr_4');
        
        $dest_addr_1 = $request->input('dest_addr_1');
        $dest_addr_2 = $request->input('dest_addr_2');
        $dest_addr_3 = $request->input('dest_addr_3');
        $dest_addr_4 = $request->input('dest_addr_4');
        
        $origin_addr = $origin_addr_1;
        $dest_addr = $dest_addr_1;
        
        if ($origin_addr_2 != NULL){
            $origin_addr = $origin_addr. ", " .$origin_addr_2;
        }
        if ($origin_addr_3 != NULL){
            $origin_addr = $origin_addr. ", " .$origin_addr_3;
        }
        if ($origin_addr_4 != NULL){
            $origin_addr = $origin_addr. ", " .$origin_addr_4;
        }

        if ($dest_addr_2 != NULL){
            $dest_addr = $dest_addr. ", " .$dest_addr_2;
        }
        if ($dest_addr_3 != NULL){
            $dest_addr = $dest_addr. ", " .$dest_addr_3;
        }
        if ($dest_addr_4 != NULL){
            $dest_addr = $dest_addr. ", " .$dest_addr_4;
        }
        
        $curr_awb = Txn::where('company_id', '=', $parent_company_id)->orderby('id', 'desc')->pluck('awb_num')->first();
        $awb = $curr_awb + 1;
        
        $parcel_desc = $request->input('parcel_desc');
        $receiver_phone = $request->input('receiver_phone');
        $receiver_code = randomDigits(6);
        $receiver_code_hash = Hash::make($receiver_code);

        $txn = new Txn;
        $txn->awb_num = $awb;
        $txn->clerk_id = $user_id;
        $txn->mode = '0';
        // $txn->round = $request->input('round');
        $txn->units = $request->input('units');
        $txn->company_id = $parent_company_id;
        $txn->parcel_status_id = '7';
        $txn->parcel_type_id = $request->input('parcel_type_id');
        $txn->acknowledge = $request->input('acknowledge');
        if ($parcel_desc != NULL){
            $txn->parcel_desc = $parcel_desc;
        }
        // $txn->price = $price;
        // $txn->vat = $vat;
        $txn->sender_name = $request->input('sender_name');
        $txn->sender_company_id = $user->company_id;
        $txn->sender_company_name = Company::select('name')->where('id', '=', $user->company_id)->pluck('name')->first();
        $txn->origin_addr = $origin_addr;
        $txn->sender_phone = $user->phone;
        $txn->receiver_name = $request->input('receiver_name');
        $txn->receiver_company_name = $request->input('receiver_company');
        $txn->receiver_phone = $receiver_phone;
        $txn->dest_addr = $dest_addr;
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
        $txnlog->sender_company_id = $company_id;
        $txnlog->save();

        //Create return AWB
        // if ($txn->round == 1)
        // {
        //     $returnawbnum = randomDigits(5);
        //     $returnawbnum = $prefix.date('ymd').$returnawbnum;
        //     // $price = $request->input('price');
        //     // $vat = 0.16 * $price;
            
        //     $parcel_desc = $request->input('parcel_desc');
        //     $receiver_phone = $request->input('receiver_phone');
        //     $receiver_code = randomDigits(6);
        //     $receiver_code_hash = Hash::make($receiver_code);

        //     $txn = new Txn;
        //     $txn->awb_num = $returnawbnum;
        //     $txn->clerk_id = $user_id;
        //     $txn->mode = $request->input('mode');
        //     $txn->round = $request->input('round');
        //     $txn->units = $request->input('units');
        //     $txn->company_id = $parent_company_id;
        //     $txn->parcel_status_id = '7';
        //     $txn->parcel_type_id = $request->input('parcel_type_id');
        //     $txn->acknowledge = $request->input('acknowledge');
        //     if ($parcel_desc != NULL){
        //         $txn->parcel_desc = $parcel_desc;
        //     }
        //     // $txn->price = $price;
        //     // $txn->vat = $vat;
        //     $txn->sender_name = $user->fullname;
        //     $txn->sender_company_id = $user->company_id;
        //     $txn->sender_company_name = Company::select('name')->where('id', '=', $user->company_id)->pluck('name')->first();
        //     $txn->origin_addr = $request->input('dest_addr');
        //     $txn->sender_phone = $user->phone;
        //     $txn->receiver_name = $request->input('receiver_name');
        //     $txn->receiver_company_name = $request->input('receiver_company');
        //     $txn->receiver_phone = $receiver_phone;
        //     $txn->dest_addr = $request->input('origin_addr');
        //     $txn->receiver_code = $receiver_code_hash;
        //     $txn->updated_by = $user->id;
        //     $txn->save();

        //     $txnlog = new TxnLog;
        //     $txnlog->awb_id = $txn->id;
        //     $txnlog->status_id = $txn->parcel_status_id;
        //     // $txnlog->origin_id = $txn->origin_id;
        //     // $txnlog->dest_id = $txn->dest_id;
        //     $txnlog->updated_by = $user->id;
        //     $txnlog->company_id = $parent_company_id;
        //     $txnlog->sender_company_id = $company_id;
        //     $txnlog->save();
        // }

        $awb_num = $txn->awb_num;
        $txn_id = $txn->id;

        $data = [
            'id'   => $txn_id,
            'success' => 'Shipment ID '. $awb_num .' added '
        ];

        return redirect('/portal/shipment/'.$txn_id.'/edit')->with($data);
        // return redirect('/portal/shipments')->with('success', 'Shipment ID '. $awb_num .' added ');
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $curr_date = date('Y-m-d');
        $txn = Txn::where('sender_company_id', '=', $company_id)->where(DB::raw('date(created_at)'),'>=',$curr_date)->find($id);
        if ($txn == null){
            return redirect('/portal/shipments')->with('error', 'Txn not found');
        }
        $origin_id = $txn->origin_id;
        $parcel_statuses = ParcelStatus::pluck('name','id')->all();
        $parcel_types = ParcelType::where('company_id', '=', $parent_company_id)->pluck('name','id')->all();
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

    public function print_awb($id)
    {
        $company_id = Auth::user()->company_id;
        $parent_company_id = Company::select('parent_company_id')->where('id', '=', $company_id)->pluck('parent_company_id')->first();
        $parent_company = Company::where('id', '=', $parent_company_id)->first();

        // $txn = Txn::where('sender_company_id', '=', $company_id)->find($id);
        $txn = Txn::join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
                ->select('txns.id as id', 'txns.awb_num as awb_num', 'txns.origin_addr as origin_addr', 'txns.dest_addr as dest_addr', 'txns.parcel_type_id as parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_desc as parcel_desc', 'txns.sender_name', 'txns.sender_company_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_company_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.receiver_sign', 'txns.units as units', 'txns.mode as mode', 'txns.round as round', 'txns.created_at', 'txns.acknowledge as acknowledge')
                ->where('txns.id', '=', $id)
                ->where('txns.sender_company_id', '=', $company_id)
                ->first();
        if ($txn == null){
            return redirect('/portal/shipments')->with('error', 'Txn not found');
        }
        
        // return view('portal.shipments.print',['txn'=> $txn, 'parent_company' => $parent_company]);
        // $pdf = PDF::loadView('pdf.shipment.print', ['txn' => $txn, 'parent_company' => $parent_company]);
        // return $pdf->stream('shipments.pdf');
        return view('pdf.shipment.print', ['txn' => $txn, 'parent_company' => $parent_company]);
    }

    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;

        $txn = Txn::find($id);
        $txn->parcel_status_id = '6';
        $txn->updated_by = $user->id;
        $txn->save();

        $sender_company_id = $txn->sender_company_id;

        $txnlog = new TxnLog();
        $txnlog->awb_id = $id;
        $txnlog->status_id = '6';
        $txnlog->updated_by = $user->id;
        $txnlog->company_id = $company_id;
        $txnlog->sender_company_id = $sender_company_id;
        $txnlog->save();

        $userlog = new UserLog();
        $userlog->username = $user->username;
        $userlog->activity = "Updated txn ".$txn->awb_num;
        $userlog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlog->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlog->company_id = $company_id;
        $userlog->save();

        return redirect('/portal/shipments');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'origin_addr' => 'required',
            'dest_addr' => 'required',
            // 'sender_name' => 'required',
            // 'sender_phone' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'acknowledge' => 'required'
        ]);

        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        // $price = $request->input('price');
        // $vat = 0.16 * $price;

        $txn = Txn::find($id);
        $txn->parcel_type_id = $request->input('parcel_type_id');
        // $txn->price = $price;
        // $txn->vat = $vat;
        $txn->mode = '0';
        $txn->round = $request->input('round');
        $txn->units = $request->input('units');
        $txn->acknowledge = $request->input('acknowledge');
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
            $txn = Txn::join('parcel_types', 'txns.parcel_type_id', '=', 'parcel_types.id')
                ->join('parcel_statuses', 'txns.parcel_status_id', '=', 'parcel_statuses.id')
                ->select('txns.id', 'txns.awb_num', 'txns.clerk_id', 'txns.origin_id', 'txns.origin_addr', 'txns.dest_id',  'txns.dest_addr', 'txns.parcel_status_id', 'txns.parcel_type_id', 'parcel_types.name as parcel_type_name', 'txns.parcel_status_id', 'parcel_statuses.description', 'txns.parcel_desc', 'txns.price', 'txns.vat', 'txns.sender_name', 'txns.sender_company_name', 'txns.sender_phone', 'txns.sender_id_num', 'txns.sender_sign', 'txns.receiver_name', 'txns.receiver_company_name', 'txns.receiver_phone', 'txns.receiver_id_num', 'txns.receiver_sign', 'txns.driver_id', 'txns.pick_driver_sign', 'txns.vehicle_id', 'txns.updated_by', 'txns.acknowledge', 'txns.mode', 'txns.round')
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
