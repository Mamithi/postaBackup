<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class UserController extends Controller
{
      public function accessSessionData(Request $request)
    {
    	if($request->session()->has('FirstName')){
    		$data=$request->session()->get('FirstName');
    		echo $data;
    	}
    	
    	else
    		echo 'No data in the session';
        
    }

    public function storeSessionData(Request $request){
    	$request->session()->put('FirstName', 'Lawrence');
    	echo "Data has been added to the session";
    }

    public function deleteSessionData(Request $request){
    	$request->session()->forget('FirstName');
    	echo "Data has been removed from session.";
    }
}
