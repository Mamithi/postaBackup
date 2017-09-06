<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class QuoteController extends Controller
{
    public function addQuote(Request $request){
    	$invoiceNo = $request->input('invoiceNo');
    	$invoiceDate = $request->input('invoiceDate');
    	$depatureDate = $request->input('depatureDate');
    	$arrivalDate = $request->input('arrivalDate');
    	$toPort = $request->input('toPort');
    	$fromPort = $request->input('fromPort');
    	$vessel = $request->input('vessel');
    	$personId = $request->input('personId');
    	$type = $request->input('type');
    	$name = $request->input('name');
    	$phone = $request->input('phone');
    	$email = $request->input('email');
    	$address = $request->input('address');
    	$currency = $request->input('currency');
    	$sum  = $request->input('sum');
    	$premium = $request->input('premium');
    	$net = $request->input('net');
    	$duty = $request->input('duty');
    	$levy = $request->input('levy');
    	$total = $request->input('total');

    	$data = DB::table('quotes')->insert(['InvoiceNo' => $invoiceNo, 'InvoiceDate' => $invoiceDate, 'ArrivalDate' => $arrivalDate, 'DepatureDate' => $depatureDate, 'ToPort' => $toPort, 'FromPort' => $fromPort, 'Vessel' =>$vessel, 'Type' => $type, 'Name' => $name, 'Phone' => $phone, 'Email' => $email, 'Address' => $address, 'Currency' => $currency, 'Sum' => $sum, 'Premium' => $premium, 'Net' => $net, 'Duty' => $duty,'Levy' => $levy, 'Total' => $total, 'PersonId' => $personId]);
    	if(count($data) > 0){
    		return response(array(
		                "Message" => "Quote added successfully",
		                "code" => 209,
		                "status" => "success",
		    ));
    	}else{
    		return response(array(
		                "Message" => "Quote not added",
		                "code" => 209,
		                "status" => "fail",
		        ));
    	}
    }


     public function getQuotes(){
        $data = DB::table('quotes')
            ->select('InvoiceNo', 'Sum', 'Total', 'Name', 'Phone', 'Currency')
            ->get();

        if(count($data) > 0){
            return response(array(
                'quotes' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No quotes entered yet"
                ));
        }
    }

    public function getQuote(Request $request){
        $phone = $request->input('phone');
        if(count($phone > 0)){
            $data = DB::table('quotes')->select('Name', 'Email', 'Phone', 'Total', 'Currency', 'InvoiceNo')->where(['Phone'=>$phone])->get(); 
             if((count($data) > 0)){
                     
                   foreach ($data as $datas){
                                $Name = $datas->Name;
                                $Email = $datas->Email;
                                $Phone = $datas->Phone;
                                $Total = $datas->Total;
                                $InvoiceNo = $datas->InvoiceNo;
                                $Currency = $datas->Currency;
                            }
                     return response(array(
                                'Name' => $Name,
                                'Email' => $Email,
                                'Total' => $Total,
                                'Phone' => $Phone,
                                'InvoiceNo' => $InvoiceNo,
                                'Currency' => $Currency

                           ));
        }else{
            return response(array(
                        "Message" => "This phone does not exist",
                        "code" => 209,
                        "status" => "Fail",
                     ));
        }
    }else{
            return response(array(
                        "Message" => "Please enter the phone to proceed",
                        "code" => 209,
                        "status" => "Fail",
                     ));
        }

    }

    public function addPayment(Request $request){
    	$invoiceNo = $request->input('invoiceNo');
    	$name= $request->input('name');
    	$total = $request->input('total');
    	$phone = $request->input('phone');
    	$currency = $request->input('currency');
    	$trackingId = $request->input('trackingId');
    	$status = $request->input('status');
    	

    	$data = DB::table('payments')->insert(['InvoiceNo' => $invoiceNo, 'Name' => $name, 'Total' => $total, 'Phone' => $phone, 'Currency' => $currency, 'TrackingId' => $trackingId, 'Status' => $status]);
    	if(count($data) > 0){
    		return response(array(
		                "Message" => "Payment of invoice number " . $invoiceNo . " of ". $currency ."". $total  ." have been made. Please wait for confirmation",
		                "code" => 209,
		                "status" => "success",
		    ));
    	}else{
    		return response(array(
		                "Message" => "Payment not added",
		                "code" => 209,
		                "status" => "fail",
		        ));
    	}
    }
         public function viewPayments(){
        $data = DB::table('payments')
            ->select('InvoiceNo',  'Total', 'Name', 'Phone', 'Currency', 'Status')
            ->get();

        if(count($data) > 0){
            return response(array(
                'payments' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No payments have been made"
                ));
        }
    }
}
