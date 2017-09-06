<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;
use Session;
use Mail;
use Person;
use AfricasTalkingGateway;

class RegisterController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function verify(Request $request){
        $phone = $request->input('phone');

        if(strlen($phone) < 1){
            return response(array(
                "Message" => "Please enter number to verify",
                "code" => 209,
                "status" => "No content",
                ));
        }
        else if((strlen($phone) < 10) || (strlen($phone) > 13)){
             return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
                ));
        } 
        else if((strlen($phone) > 9) || (strlen($phone) < 14)){
            $code = rand ( 10000 , 99999 );
            $this->send($phone, $code);
            $current = time();
            $delete = $current+1;
            DB::table('codes')->insert(['phone'=>$phone, 'code'=>$code, 'created_at'=>$current, 'delete' => $delete]);
           // $codes = DB::table('codes')->where(('delete' - 'current' = 1))->delete();
            
             return response(array(
                "Message" => $phone,
                "code" => $code,
                "status" => "Success",
                ));
         
        }
        
    }

    public function valid(Request $request){
             $codeNum = $request->input('code');
             $phoneNum = $request->input('phone');
             $codes = DB::table('persons')->where(['Code'=>$codeNum, 'Phone' =>$phoneNum])->get();
             if(count($codes) > 0){
                return response(array(
                "Message" => "You have entered the right code",
                "code" => 200,
                "status" => "success",
                ));
            }else{
             return response(array(
                "Message" => "Please enter the code that was sent to you",
                "code" => 200,
                "status" => "fail",
                ));
         }
    }

    public function validCompany(Request $request){
             $phoneNum = $request->input('phone');
             $codeNum = $request->input('code');
             $codes = DB::table('companys')->where(['Code'=>$codeNum, 'Phone' =>$phoneNum])->get();
             if(count($codes) > 0){
                return response(array(
                "Message" => "You have entered the right code",
                "code" => 200,
                "status" => "success",
                ));
            }else{
             return response(array(
                "Message" => "Please enter the code that was sent to you",
                "code" => 200,
                "status" => "fail",
                ));
         }
    }



   public function sendEmail($email){
    $token = str_random(50);
            $user = array(
                    'email' => $email,
                    'subject' => 'Activate your account',
                    
                );
            $sent = Mail::raw('Please follow this link to activate your account http://localhost/Posta/activate.php?email='.$email. '&token='.$token, function($message) use ($user){

                $message->to($user['email']);
                $message->subject($user['subject']);
                
            });
   }

  

    public function register(Request $request)
    {
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $password = $request->input('password');
        $password2 = $request->input('password2');
        $sign = "+";
        if(strlen($firstName) < 1){
            return response(array(
                "Message" => "Please enter your first name",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($lastName) < 1){
            return response(array(
                "Message" => "Please enter your second name",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($phone) != 10 && strlen($phone) != 13 ){
            return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if((strlen($phone) == 10 && ($phone[0] != 0))){
               return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));  
        }
        else if((strlen($phone) == 13 && ($phone[0] != $sign))){
               return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));  
        }
        else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false ){
            return response(array(
                "Message" => "Please enter a valid Email",
                "code" => 209,
                "status" => "Fail",
             ));
        }
        else if(strcmp($password, $password2) ){
            return response(array(
                "Message" => "Your passwords dont match",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($password) < 1 || strlen($password2) < 1){
            return response(array(
                "Message" => "Please enter your password",
                "code" => 209,
                "status" => "Fail",
             ));
        }

        
        else{
            try{
            $code = rand ( 10000 , 99999 );
            $credits = 10;      
            
            $users = DB::table('persons')->insert(['FirstName' => $firstName, 'LastName' => $lastName, 'Phone' => $phone, 'Email' => $email, 'Password' => $password, 'Code' => $code, 'credits'=> $credits]);
           
            $this->sendEmail($email);
            $this->send($phone, $code);
            if($users){
            
            return response(array(
                    "Message" => "Registration successful",
                    "code" => 200,
                    "status" => "success",
                ));


            $user = array(
                    'email' => $email,
                    'subject' => 'Verification Code',
                    
                );
            $sent = Mail::raw('Please follow this link to verify your account  http://localhost/Posta/verify.php', function($message) use ($user){

                $message->to($user['email']);
                $message->subject($user['subject']);
                
            });


            }else{
            return response(array(
                    "Message" => "Registration failed",
                    "code" => 500,
                    "status" => "fail",
                    ));
            }
        }catch(\Illuminate\Database\QueryException  $e){
                return response(array(
                    'Message' => 'The Email or Phone Number already registered',
                    'status' => 'Failed',
                    'code' => 204,
                    ));
            }
        }
    }

     public function login(Request $request){
                    $remember = false;
                    $email = $request->input('email');
                    $phone = $request->input('phone');
                    $password = $request->input('password');
                    $remember_me = $request->input('remember');
                    
                    $check = 0;
                    
                    
                    
                    if(count($phone) > 0){
                        $usePhone = DB::table('persons')->where(['Phone'=>$phone, 'Password'=>$password], $remember)->get();
                        if(count($usePhone) > 0){
                            $check = 1;
                        }
                    }
                    if(count($email) > 0){
                        $useEmail = DB::table('persons')->where(['Email'=>$email, 'Password'=>$password], $remember)->get();
                         if(count($useEmail) > 0){
                            $check = 1;
                        }
                    }
                    if($check > 0){
                        
                        $persons = DB::table('persons')->select('FirstName', 'LastName', 'id', 'credits')->where(['Email'=>$email])->get();
                        $persons2 = DB::table('persons')->select('FirstName', 'LastName', 'id', 'credits')->where(['Phone'=>$phone])->get();
                        if((count($persons) > 0)){
                     
                        foreach ($persons as $member)
                            {
                                $FirstName = $member->FirstName;
                                $LastName = $member->LastName;
                                $credits = $member->credits;
                                $id = $member->id;
                            }

                        }else{
                         
                        foreach ($persons2 as $member)
                            {
                                $FirstName = $member->FirstName;
                                $LastName = $member->LastName;
                                $credits = $member->credits;
                                $id = $member->id;
                            } 
                        }

                        $sessionValue = $request->session()->put('id', $id);
                        if($request->session()->has('id')){
                            $sessionValue = $request->session()->get('id');
                           
                        }
                                        
                        return response(array(
                            'Message' => 'Log in successful',
                            'status' => 'success',
                            'FirstName' => $FirstName,
                            'LastName' => $LastName,
                            'credits' => $credits,
                            'id' => $id,
                            'session' => $sessionValue,

                           ),200);
                    }else{
                        return response(array(
                            "Message" => "Authentication failed, details provided are invalid",
                            'status' => 'fail'
                            ));
                        
                    }

        }

         public function logout(Request $request){
            $request->session()->forget('id');

           
        }

        public function sessions(Request $request){
            if($request->session()->has('id')){
             $sessionValue = $request->session()->get('id');
             return response(array(
                "Message" => "Session has been set",
                "sessionvalue" => $sessionValue,
                "status" => "sessionOn"
                ));              
                  }
              else{
                    return response(array(
                        "Message" => "Your session has expired please login to continue",
                        "status" => "sessionOff",
                        ));
              }
        }

        public function loginCompany(Request $request){
                    $email = $request->input('email');
                    $password = $request->input('password');

                    $checkLogin = DB::table('companys')->where(['Email'=>$email, 'Password'=>$password])->get();
                    if(count($checkLogin) > 0){
                        $count = count($checkLogin);
                        $persons = DB::table('companys')->select('name')->where(['Email'=>$email])->get();
                        session_start();
                        foreach ($persons as $member)
                            {
                                $_SESSION['name'] = $member->name;
                                 
                            }
                        return response(array(
                            'Message' => 'Log in successful',
                            'status' => 'success',
                            $_SESSION['name'] = $member->name,
                            'count' => $count,
                            

                           ),200);
                    }else{
                        return response(array(
                            "Message" => "Authentication failed, details provided are invalid",
                            'status' => 'fail'
                            ));
                        
                    }

        }

    public function forgot(Request $request){
        $email = $request->input('email');
        $phone = $request->input('phone');
        if(count($email) > 0){
        $token = str_random(255);
         if(filter_var($email, FILTER_VALIDATE_EMAIL) === false ){
            return response(array(
                "Message" => "Please enter a valid Email",
                "code" => 209,
                "status" => "invalid",
             ));
        }else{
            $user = array(
                    'email' => $email,
                    'subject' => 'Reset your password'
                );
            $sent = Mail::raw('Please follow this link to reset your password http://localhost/Posta/recovery.php?email='.$email. '&token='.$token, function($message) use ($user){

                $message->to($user['email']);
                $message->subject($user['subject']);
            });
            return response(array(
                "Message" => "A link has been sent to your email please use it to reset your password",
                "code" => 209,
                "status" => "success",
             ));

        }
          if(count($sent) < 0){
            return response(array(
                "Message" => "Message not sent to your email",
                "code" => 209,
                "status" => "fail",
             ));
          }else{
             return response(array(
                "Message" => "A link has been sent to your email please use it to reset your password",
                "code" => 209,
                "status" => "success",
             ));
          }
      }else if(count($phone) > 0){
        if(strlen($phone) != 10 && strlen($phone) != 13 ){
            return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if((strlen($phone) == 10 && ($phone[0] != 0))){
               return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));  
        }
        else if((strlen($phone) == 13 && ($phone[0] != $sign))){
               return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));  
        }else{
            $check = DB::table('persons')->select('FirstName')->where(['Phone' => $phone])->get();
            if(count($check) > 0){
                foreach($check as $data){
                    $name = $data->FirstName;
                }
                $code = $this->randomKey(8);
                $this->forgotPassword($phone, $code, $name);
                $updatePass=DB::table('persons')
                    ->where('Phone', $phone)
                    ->update(['Password' => $code]);
                    if(count($updatePass) > 0){
                      return response(array(
                        "Message" => "New password sent to your phone please use it to login",
                        "code" => 209,
                        "status" => "successPhone",
                     ));
                    }else{
                        return response(array(
                            "Message" => "Password not sent",
                            "code" => 209,
                            "status" => "fail",
                         ));
                    }

            }else{
              return response(array(
                "Message" => "This number is not registered with our services",
                "code" => 209,
                "status" => "fail",
             ));
            }
        }

      }
        }

            
    public function randomKey($length) {
            $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
            $key = "";
            for($i=0; $i < $length; $i++) {
                $key .= $pool[mt_rand(0, count($pool) - 1)];
            }
            return $key;
        }


public function forgotPassword($phone, $code, $name){
                require_once(app_path(). '/functions/AfricasTalkingGateway.php');
                $username   = "LOGIC";
                $apikey     = "281c99416f61911e3294fe14ee23a6dee60cddbf10729bff819174ea0430b9fa";
                $recipients = $phone;
                $message    = "Hi ". $name. " Your new login password for Posta Account is ". $code;
                $gateway    = new AfricasTalkingGateway($username, $apikey);
                $results = $gateway->sendMessage($recipients, $message);
          
    }
    public function resetPassword(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $password2 = $request->input('password2');
       if(strcmp($password, $password2) ){
            return response(array(
                "Message" => "Your passwords dont match",
                "code" => 209,
                "status" => "No password",
             ));
        }else if(strlen($password) < 1 || strlen($password2) < 1){
            return response(array(
                "Message" => "Please enter your password",
                "code" => 209,
                "status" => "No password",
             ));
        }else{
            $check = DB::table('persons')->select('FirstName')->where(['Email' => $email])->get();
           
            if(count($check) > 0){
            $update=DB::table('persons')
                    ->where('Email', $email)
                    ->update(['Password' => $password]);
            $persons = DB::table('persons')->select('FirstName', 'LastName', 'id', 'credits')->where(['Email'=>$email])->get();
            session_start();
                        foreach ($persons as $member)
                            {
                                $FirstName = $member->FirstName;
                                $LastName = $member->LastName;
                                $credits = $member->credits;
                                $id = $member->id;
                            }
            
            return response(array(
                    'Message' => 'Password Reset successfully',
                            'status' => 'success',
                            'FirstName' => $FirstName,
                            'LastName' => $LastName,
                            'credits' => $credits,
                            'id' => $id,
                ));
            }else{
            return response(array(
                    "Message" => "Password reset failed",
                    "code" => 500,
                    "status" => "fail",
                    ));
            }

        }
     }

     public function activate(Request $request){
      
        $password = $request->input('password');
      
      
            $checkEmail = DB::table('persons')->where(['Password' => $password])->get();
            if(count($checkEmail) > 0){
            $update = DB::table('persons')->where('Password', $password)->update(['activationE' => "1"]);
            $persons = DB::table('persons')->select('FirstName', 'LastName', 'credits', 'id')->where(['Password'=>$password])->get();
            session_start();
                        foreach ($persons as $member)
                            {
                                $FirstName = $member->FirstName;
                                $LastName = $member->LastName;
                                $credits = $member->credits;
                                $id = $member->id;
                            }
            
            return response(array(
                    'Message' => 'Your account have been updated successfully',
                            'status' => 'success',
                            'FirstName' => $FirstName,
                            'LastName' => $LastName,
                            'credits' => $credits,
                            'id' => $id,

                ));
            }else{
            return response(array(
                    "Message" => "Please enter the right password",
                    "code" => 500,
                    "status" => "fail",
                    ));
            }
     }


public function send($phone, $code){
                require_once(app_path(). '/functions/AfricasTalkingGateway.php');
                $username   = "LOGIC";
                $apikey     = "281c99416f61911e3294fe14ee23a6dee60cddbf10729bff819174ea0430b9fa";
                $recipients = $phone;
                $message    = "Please use this code ".$code. " to activate your Posta account  ";
                $gateway    = new AfricasTalkingGateway($username, $apikey);
                $results = $gateway->sendMessage($recipients, $message);
          
    }
    public function company(Request $request){
        $name = $request->input('name');
        $email = $request->input('email');
        $cert = $request->input('cert');
        $pin = $request->input('pin');
        $email = $request->input('email');
        $password = $request->input('password');
        $password2 = $request->input('password2');
        $location = $request->input('location');
        $box = $request->input('box');
        $phone = $request->input('phone');
        $landline = $request->input('landline');
        $contact = $request->input('contact');
        $firstName = $request->input('firstName');
        $secondName = $request->input('secondName');
        $lastName = $request->input('lastName');
        $position = $request->input('position');
        if(strlen($name) < 1){
            return response(array(
                "Message" => "Please enter the business name",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false ){
            return response(array(
                "Message" => "Please enter a valid Email",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strcmp($password, $password2) ){
            return response(array(
                "Message" => "Your passwords dont match",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($password) < 1 || strlen($password2) < 1){
            return response(array(
                "Message" => "Please enter your password",
                "code" => 209,
                "status" => "Fail",
             ));
        }
        else if(strlen($box) < 1){
            return response(array(
                "Message" => "Please enter your Box Number",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($phone) != 10 && strlen($phone) != 13 ){
            return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if((strlen($phone) == 10 && ($phone[0] != 0))){
               return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));  
        }
        else if((strlen($phone) == 13 && ($phone[0] != $sign))){
               return response(array(
                "Message" => "Please enter a valid phone number",
                "code" => 209,
                "status" => "Fail",
             ));  
        }else{
            try{
            $code = rand ( 10000 , 99999 );
            
            $company = DB::table('companys')->insert(['Name'=>$name, 'Cert'=>$cert, 'Pin'=>$pin, 'Email' => $email, 'Password' => $password, 'Location' => $location, 'Box' => $box, 'Phone' => $phone, 'Landline'=> $landline, 'FirstName' => $firstName, 'SecondName' => $secondName, 'LastName' => $lastName, 'Position' => $position, 'Code' => $code]);

            if($company){
            $this->send($phone, $code);
            return response(array(
                    "Message" => "Registration successful, Please use the code sent to your phone to verify your account",
                    "code" => 200,
                    "status" => "success",
                ));
            }else{
            return response(array(
                    "Message" => "Registration failed",
                    "code" => 500,
                    "status" => "fail",
                    ));
            }
        }catch(\Illuminate\Database\QueryException  $e){
                return response(array(
                    'Message' => 'The Email or Phone Number already registered',
                    'status' => 'Failed',
                    'code' => 204,
                    ));
            }
        }

     }


     public function lockScreen(Request $request){
        $name = $request->input('firstName');
        $password = $request->input('password');
      
        if(count($name) > 0 && count($password) > 0){

           $checkLogin = DB::table('persons')->where(['FirstName'=>$name, 'Password'=>$password])->get();
                    if(count($checkLogin) > 0){
                       
                        $persons = DB::table('persons')->select('FirstName')->where(['FirstName'=>$name])->get();
                        session_start();
                        foreach ($persons as $member)
                            {
                                $fname = $member->FirstName;
                                 
                            }
                        return response(array(
                            'Message' => 'Welcome back '.$member->FirstName,
                            'status' => 'success',
                             
                           
                            

                           ),200);
                    }else{
                        return response(array(
                            "Message" => "Authentication failed, details provided are invalid",
                            'status' => 'fail'
                            ));
                        
                    }
                
     }
 }

    public function credits(Request $request){
        $id = $request->input('id');
        $data = DB::table('persons')->where(['id' => $id])->get();
        if(count($data) > 0){
            $check = DB::table('persons')->select('credits')->where(['id' => $id])->get();
            foreach($check as $val){
                $credits = $val->credits;
            }
            if($credits < 1){
                return response(array(
                            "Message" => "You have no credits to make this search",
                            'status' => 'noCredits'
                            ));
            }else{
            $creditsBal = $credits - 1;

            $bal=DB::table('persons')
                    ->where('id', $id)
                    ->update(['credits' => $creditsBal]);
        }
    }
}

    public function creditsCheck(Request $request){
        $id = $request->input('id');
        $data = DB::table('persons')->where(['id' => $id])->get();
        if(count($data) > 0){
            $check = DB::table('persons')->select('credits')->where(['id' => $id])->get();
            foreach($check as $val){
                $credits = $val->credits;
            }
            if($credits < 1){
                return response(array(
                            "Message" => "You have no credits to make this search",
                            'status' => 'nocredits'
                            ));
            }else{
                return response(array(
                            "Message" => "You have enough credits for the search",
                            'status' => 'credits'
                            ));
        }
    }
}



    public function creditsBulk(Request $request){
        $id = $request->input('id');
        $length = $request->input('length');
        $data = DB::table('persons')->where(['id' => $id])->get();
        if(count($data) > 0){
            $check = DB::table('persons')->select('credits')->where(['id' => $id])->get();
            foreach($check as $val){
                $credits = $val->credits;
            }
            if($length > $credits){
               return response(array(
                            "Message" => "Your credit balance is insufficient for this search. You have " .$credits. " credits and you are searching for " .$length. " records",
                            'status' => 'noCredits'
                            )); 
            }else{

            $creditsBal = $credits - $length;

            $bal=DB::table('persons')
                    ->where('id', $id)
                    ->update(['credits' => $creditsBal]);
            if(count($bal) > 0){
                return response(array(
                            "Message" => "Balance updated successfully",
                            'status' => 'success'
                            ));
            }
        }
        }
    }


    public function getId(Request $request){
       $name = $request->input('name');
        $data = DB::table('persons')->where(['FirstName' => $name])->get();
        if(count($data) > 0){
            $check = DB::table('persons')->select('id')->where(['FirstName' => $name])->get();
            foreach($check as $val){
                $id = $val->id;
            }
         
        $data = DB::table('persons')->where(['id' => $id])->get();
        if(count($data) > 0){
            $check = DB::table('persons')->select('credits')->where(['id' => $id])->get();
            foreach($check as $val){
                $credits = $val->credits;
            }
            if($credits < 1){
                return response(array(
                            "Message" => "You have no credits to make this search",
                            'status' => 'nocredits'
                            ));
            }else{
                return response(array(
                            "Message" => "You have enough credits for the search",
                            'status' => 'credits'
                            ));
        }
    }
        }
    }


    public function creditsBal(Request $request){
        $id = $request->input('id');
        $bal = DB::table('persons')->select('credits')->where(['id' => $id])->get();
        foreach($bal as $balance){
            $credits = $balance->credits;
        }
        return response(array(
             "credits" => $credits
            ));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
