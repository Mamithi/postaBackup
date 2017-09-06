<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTAuth;
use DB;
//use JWTAuth;

class AuthController extends Controller
{
    private $user;
    private $jwtauth;

    public function __construct (User $user, JWTAuth $jwtauth){
    	$this->user = $user;
    	$this->jwtauth = $jwtauth;
    }

    public function apiRegister(Request $request){
    	$name = $request->input('name');
    	$email = $request->input('email');
    	$password =bcrypt($request->input('password'));
    	$newUser = $this->user->create([
    		'name' => $name,
    		'email' => $email,
    		'password' => $password,
     		]);

    	if(!$newUser){
    		return response(array(
    			'Message' => 'Failed to create new user',
    			'code' => 209,
    			'status' => 'fail',
    			));
    	}else{
    		return response(array(
    			'Message' => 'User created successfully',
    			'code' => 200,
    			'status' => 'success',
    			'token' => $this->jwtauth->fromUser($newUser),
    			));
    	}
    }
    	

    	public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = $this->jwtauth->attempt($credentials)) {
                
                return response(array(
    			'Message' => 'Invalid Email or password',
    			'code' => 401,
    			'status' => 'fail',
    			));

            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response(array(
    			'Message' => 'Could not create token',
    			'code' => 500,
    			'status' => 'fail',
    			));
        }

        // all good so return the token
        return response()->json(compact('token'));
        
    }

    }

