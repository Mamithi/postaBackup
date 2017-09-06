<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
                    $remember = false;
                    $email = $request->input('email');
                    $phone = $request->input('phone');
                    $password = $request->input('password');
                    $remember_me = $request->input('remember');
                    $check = 0;
                    
                    
                    
                    if(count($phone) > 0){
                        $usePhone = DB::table('members')->where(['Phone'=>$phone, 'Password'=>$password], $remember)->get();
                        if(count($usePhone) > 0){
                            $check = 1;
                        }
                    }
                    if(count($email) > 0){
                        $useEmail = DB::table('members')->where(['Email'=>$email, 'Password'=>$password], $remember)->get();
                         if(count($useEmail) > 0){
                            $check = 1;
                        }
                    }
                    if($check > 0){
                        
                        $members = DB::table('members')->select('FirstName', 'LastName', 'id')->where(['Email'=>$email])->get();
                        $members2 = DB::table('members')->select('FirstName', 'LastName', 'id')->where(['Phone'=>$phone])->get();
                        if((count($members) > 0)){
                     
                        foreach ($members as $member)
                            {
                                $FirstName = $member->FirstName;
                                $LastName = $member->LastName;
                                $id = $member->id;
                            }

                        }else{
                         
                        foreach ($members2 as $member)
                            {
                                $FirstName = $member->FirstName;
                                $LastName = $member->LastName;
                               $id = $member->id;
                            } 
                        }

                        // $sessionValue = $request->session()->put('id', $id);
                        // if($request->session()->has('id')){
                        //     $sessionValue = $request->session()->get('id');
                           
                        // }
                                        
                        return response(array(
                            'Message' => 'Log in successful',
                            'status' => 'success',
                            'FirstName' => $FirstName,
                            'LastName' => $LastName,
                            'id' => $id,
                          

                           ),200);
                    }else{
                        return response(array(
                            "Message" => "Authentication failed, details provided are invalid",
                            'status' => 'fail'
                            ));
                        
                    }
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
    public function store(Request $request)
    {
        //
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
