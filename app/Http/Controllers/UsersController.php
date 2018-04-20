<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Auth;
use Session;
use Validator;
use App\User;
use App\UserLog;
use App\Company;
use App\Station;

class UsersController extends Controller
{
    public function index()
    {
        if (Auth::user()->usertype == 'superadmin') {
            $users = User::orderBy('usertype','asc')->paginate(10);
        }
        else {
            $company_id = Auth::user()->company_id;
            $users = User::where('company_id', '=', $company_id)->orderBy('usertype','asc')->paginate(10);
        }
        return View('users.index')->with('users', $users);
    }

    //api signin
    public function signin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('username', 'password');
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

    public function getSignin()
    {
        return view('users.signin');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');

        $credentials1 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'superadmin', 'status' => 1);
        $credentials2 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'admin', 'status' => 1);
        $credentials3 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'cusadmin', 'status' => 1);
        $credentials4 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'cusclerk', 'status' => 1);
        $credentials5 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'clerk', 'status' => 1);

        if (Auth::attempt($credentials1)) {
            if (Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }
            //to log user signin to user_logs table
            $userlogin = new UserLog();
            $userlogin->username = $request->input('username');
            $userlogin->activity = "Login";
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();

            //Userdetails
            $userdetails = User::join('companies', 'users.company_id', '=', 'companies.id')->select('users.id', 'users.username','users.company_id', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email', 'companies.logo' )->where('users.username', '=', $username)->get();
            $companyname = $userdetails[0]->name;
            $companylogo = $userdetails[0]->logo;

            session(['courier.companyname' => $companyname]);
            session(['courier.companylogo' => $companylogo]);

            return redirect()->route('dashboard.index');
        }
        else if (Auth::attempt($credentials3 ) OR Auth::attempt($credentials4 )) {
            if (Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }
            //to log user signin to user_logs table
            $userlogin = new UserLog();
            $userlogin->username = $request->input('username');
            $userlogin->activity = "Login";
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();

            //Userdetails
            $userdetails = User::join('companies', 'users.company_id', '=', 'companies.id')->select('users.id', 'users.username','users.company_id', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email', 'companies.logo' )->where('users.username', '=', $username)->get();
            $companyname = $userdetails[0]->name;
            $companylogo = $userdetails[0]->logo;

            session(['courier.companyname' => $companyname]);
            session(['courier.companylogo' => $companylogo]);

            return redirect()->route('dashboard.customer');
        }
        else if (Auth::attempt($credentials2) OR Auth::attempt($credentials5 )) {
            if (Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }
            //to log user signin to user_logs table
            $userlogin = new UserLog();
            $userlogin->username = $request->input('username');
            $userlogin->activity = "Login";
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();

            //Userdetails
            $userdetails = User::join('companies', 'users.company_id', '=', 'companies.id')->select('users.id', 'users.username','users.company_id', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email', 'companies.logo' )->where('users.username', '=', $username)->get();
            $companyname = $userdetails[0]->name;
            $companylogo = $userdetails[0]->logo;

            session(['courier.companyname' => $companyname]);
            session(['courier.companylogo' => $companylogo]);

            return redirect()->route('dashboard.index', ['companyname' => $companyname, 'companylogo' => $companylogo]);
        }
        return redirect()->back()->with('error', 'Incorrect username/password');
    }

    public function getLogout() 
    {
        Auth::logout();
        return redirect()->route('users.signin');
    }

    public function getProfile() {
        $user = Auth::user();
        $company_id = Auth::user()->company_id;
        $company = Company::where('id', '=', $company_id)->get();
        return view('users.profile', ['user' => $user, 'company' => $company, 'company_id' => $company_id]);
        // return view('users.profile', ['user' => $user]);
    }

    public function getuserdetails($username)
    {
        $userdetails = DB::table('users')
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->join('stations', 'users.station_id', '=', 'stations.id')
            ->select('users.id', 'users.username', 'users.fullname', 'users.company_id', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email', 'users.station_id',  DB::raw('stations.name as station_name') )
            ->where('users.username', '=', $username)
            ->get();
        return response()->json($userdetails);
    }

    public function getdrivers()
    {
        $company_id = Auth::user()->company_id;
        $users = User::where('company_id', '=', $company_id)->where('usertype', '=', 'driver')->select('id','fullname')->get();
        return response()->json($users);
    }

    public function resetpass()
    {
        return view('users.resetpass');
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
            return redirect('/users/resetpass')->with('error', 'Current password incorrect');
        }
    }

    public function changePassword($username, Request $request) {
        $validator = Validator::make(($request->all()), [
            'curr_password' => 'required',
            'new_password' => 'required|same:new_password',
            'new_password_2' => 'required|same:new_password'
        ]);

        if ($validator->fails()){
            //$response = array('response' => $validator->messages(), 'success' => false);
            $response = array('message' => 'The 2 new passwords do not match');
            return $response;
        } else {

            $current_password = Auth::User()->password;

            if(Hash::check($request->input('curr_password'), $current_password)){
                $request->user()->fill([
                    'password' => Hash::make($request->input('new_password'))
                ])->save();
                return response()->json(['message' => 'Password changed'], 200);
            } else {
                return response()->json(['message' => 'Current password incorrect'], 400);
            }
        }
    }

    public function resetOtherpass($id)
    {
        $user_id = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        $email = User::select('email')->where('id', '=', $id)->pluck('email')->first();
        $phone = User::select('phone')->where('id', '=', $id)->pluck('phone')->first();

        //if email is null do through phone number

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

        $user = User::where('company_id','=',$company_id)->find($id);
        if ($user == null){
            return redirect('/users')->with('error', 'User not found');
        }
        if ($user->usertype == 'driver'){
            $user->password = bcrypt($password); //temporary for testing
            $user->updated_by = $user_id;
            $user->save();
        }
        else {
            $user->password = bcrypt($password);
            $user->updated_by = $user_id;
            $user->save();
        }

        // Send password via SMS
        /*if ($phone != NULL)
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
        
        return redirect('/users')->with('success', 'Password sent to email and/or phone');
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $companies = Company::pluck('name','id')->all();
        $stations = Station::where('company_id', '=', $company_id)->pluck('name','id')->all();
        return view('users.create', ['companies' => $companies, 'stations' => $stations]);
    }

    public function store(Request $request)
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
        $user->station_id = $request->input('station_id');
        $user->password = bcrypt($password);
        if (Auth::user()->usertype == 'superadmin') {
            $user->company_id = $request->input('company_id');;
        } 
        else {
            $user->company_id = $company_id;
        }
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

        return redirect('/users')->with('success', 'User Created');
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $companies = Company::pluck('name','id')->all();
        $stations = Station::where('company_id', '=', $company_id)->pluck('name','id')->all();
        $user = User::where('company_id','=',$company_id)->find($id);
        if ($user == null){
            return redirect('/users')->with('error', 'User not found');
        }

        return view('users.edit',['user'=> $user, 'companies' => $companies, 'stations' => $stations]);
    }

    public function update(Request $request, $id)
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
        
        return redirect('/users')->with('success', 'User details updated');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('/users')->with('success', 'User Removed');
    }

}
