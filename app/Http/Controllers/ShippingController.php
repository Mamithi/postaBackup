<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ShippingController extends Controller
{
     public function countries(){
       $data = DB::table('ports')
            ->select('Country')
            ->groupBy('Country')
            ->get();

        if(count($data) > 0){
            return response(array(
                'countries' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No Country Data"
                ));
        } 
    }

     public function ports(Request $request){
        $country = $request->input('country');
        $data = DB::table('ports')->select('Port')->where(['Country' => $country])->get();
    
        if(count($data) > 0){
            return response(array(
                'ports' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No ports for this country"
                ));
        }
    }

    public function saveData(Request $request){
    	$invoiceNo = $request->input('invoiceNo');
    	$invoiceDate = $request->input('invoiceDate');
    	$depatureDate = $request->input('depatureDate');
    	$arrivalDate = $request->input('arrivalDate');
    	$conveyance = $request->input('conveyance');
    	$blNo = $request->input('blNo');
    	$countryTo = $request->input('countryTo');
    	$portTo = $request->input('portTo');
    	$countryFrom = $request->input('countryFrom');
    	$portFrom = $request->input('portFrom');
    	$viaPort = $request->input('viaPort');
    	$portNum = $request->input('portNumber');
    	$portNumber =  (int)$portNum;
    	$countryVia1 = $request->input('countryVia1');
    	$portVia1 = $request->input('portVia1');
    	$countryVia2 = $request->input('countryVia2');
    	$portVia2 = $request->input('portVia12');
    	$countryVia3 = $request->input('countryVia3');
    	$portVia3 = $request->input('portVia3');
    	$countryVia4 = $request->input('countryVia4');
    	$portVia4 = $request->input('portVia4');
    	$countryVia5 = $request->input('countryVia5');
    	$portVia5 = $request->input('portVia5');
    	$basisValuation = $request->input('basisValuation');
    	$consignee = $request->input('consignee');
    	$vessel = $request->input('vessel');
    	$personId = $request->input('personId');
    	$checkPort = "yes";
    	$test = strcmp($checkPort, $viaPort);

    	if(strlen($invoiceNo) < 1){
            return response(array(
                "Message" => "Please enter the shipping invoice number",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($invoiceDate) < 1){
            return response(array(
                "Message" => "Please enter shipping invoice date",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($arrivalDate) < 1){
            return response(array(
                "Message" => "Please enter shipping arrival date",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($depatureDate) < 1){
            return response(array(
                "Message" => "Please enter shipping depature date",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($conveyance) < 1){
            return response(array(
                "Message" => "Please enter means of conveyance",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($blNo) < 1){
            return response(array(
                "Message" => "Please enter bill of landing number",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($countryTo) < 1){
            return response(array(
                "Message" => "Please enter the destination country",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($portTo) < 1){
            return response(array(
                "Message" => "Please enter the destination port name",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($countryFrom) < 1){
            return response(array(
                "Message" => "Please enter the country of origin",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($portFrom) < 1){
            return response(array(
                "Message" => "Please enter origin's port name",
                "code" => 209,
                "status" => "Fail",
             ));
        }
       else if(strlen($basisValuation) < 1){
            return response(array(
                "Message" => "Please enter the basis of your valuation",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($vessel) < 1){
            return response(array(
                "Message" => "Please enter vessel of transport",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($consignee) < 1){
            return response(array(
                "Message" => "Please enter the consignee",
                "code" => 209,
                "status" => "Fail",
             ));
        } else {
        	$data = DB::table('shippings')->insert(['InvoiceNo' => $invoiceNo, 'InvoiceDate' => $invoiceDate, 'ArrivalDate' => $arrivalDate, 'DepatureDate' => $depatureDate, 'Conveyance' => $conveyance, 'BlNo' => $blNo, 'CountryTo' => $countryTo, 'PortTo' => $portTo, 'CountryFrom' => $countryFrom, 'PortFrom' => $portFrom, 'CountryVia1' => $countryVia1, 'PortVia1' => $portVia1, 'CountryVia2' => $countryVia2, 'PortVia2' => $portVia2, 'CountryVia3' => $countryVia3, 'PortVia3' => $portVia3, 'CountryVia4' => $countryVia4, 'PortVia4' => $portVia4, 'CountryVia5' => $countryVia5, 'PortVia5' => $portVia5, 'BasisValuation' => $basisValuation, 'Consignee' => $consignee, 'Vessel' => $vessel, 'PortNumber' => $portNumber, 'ViaPort' => $viaPort, 'PersonId' => $personId]);

        	if(count($data) > 0){
        		return response(array(
		                "Message" => "Shipping data has been added successfully",
		                "code" => 209,
		                "status" => "success",
		             ));
        	}else{
        		return response(array(
		                "Message" => "Shipping data was not added",
		                "code" => 209,
		                "status" => "Fail",
		             ));
        	}
        } 
    }
    public function viewCustomer(Request $request){
        $email = $request->input('email');
        if(count($email > 0)){
            $data = DB::table('customers')->select('FirstName', 'LastName', 'Email', 'Phone', 'Type', 'Address', 'Postal', 'City', 'Country')->where(['Email'=>$email])->get(); 
             if((count($data) > 0)){
                     
                   foreach ($data as $datas){
                                $FirstName = $datas->FirstName;
                                $LastName = $datas->LastName;
                                $Email = $datas->Email;
                                $Phone = $datas->Phone;
                                $Type = $datas->Type;
                                $Address = $datas->Address;
                                $Postal = $datas->Postal;
                                $City = $datas->City;
                                $Country = $datas->Country;
                            }
                     return response(array(
                                'FirstName' => $FirstName,
                                'LastName' => $LastName,
                                'Email' => $Email,
                                'Phone' => $Phone,
                                'Type' => $Type,
                                'Address' => $Address,
                                'Postal' => $Postal,
                                'City' => $City,
                                'Country' => $Country
                           ));
        }else{
            return response(array(
                        "Message" => "This email does not exist",
                        "code" => 209,
                        "status" => "Fail",
                     ));
        }
    }else{
            return response(array(
                        "Message" => "Please enter the email to proceed",
                        "code" => 209,
                        "status" => "Fail",
                     ));
        }

    }
   public function updateCustomers(Request $request){
        $type = $request->input('type');
        $typeTest = "individual";
        $x = strcmp($type, $typeTest);
        if($x == 0){
                 $firstname = $request->input('firstname');
                 $lastname = $request->input('lastname'); 
                 if(strlen($firstname) < 1){
                    return response(array(
                        "Message" => "Please enter your first name",
                        "code" => 209,
                        "status" => "Fail",
                     ));
                }else if(strlen($lastname) < 1){
                    return response(array(
                        "Message" => "Please enter your second name",
                        "code" => 209,
                        "status" => "Fail",
                     ));
                }
        }else{
                 $firstname = $request->input('companyName');
                 $lastname = $request->input('contactPerson'); 
                  if(strlen($firstname) < 1){
                    return response(array(
                        "Message" => "Please enter the company's name",
                        "code" => 209,
                        "status" => "Fail",
                     ));
                }else if(strlen($lastname) < 1){
                    return response(array(
                        "Message" => "Please enter contact person name",
                        "code" => 209,
                        "status" => "Fail",
                     ));
                }
        }

        $phone = $request->input('phone');
        $email = $request->input('email');
        $setEmail = $request->input('setEmail');
        $address = $request->input('address');
        $postal = $request->input('postal');
        $city = $request->input('city');
        $country = $request->input('country');
        $sign = "+";
        if(strlen($type) < 1){
                    return response(array(
                        "Message" => "Please enter the type of the customer",
                        "code" => 209,
                        "status" => "Fail",
                     ));
                }
        else if(strlen($phone) != 10 && strlen($phone) != 13 ){
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
        }else if(strlen($type) < 1){
            return response(array(
                "Message" => "Please enter the type of the customer",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($address) < 1){
            return response(array(
                "Message" => "Please enter the address",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($postal) < 1){
            return response(array(
                "Message" => "What is the postal code",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($city) < 1){
            return response(array(
                "Message" => "Please enter the city",
                "code" => 209,
                "status" => "Fail",
             ));
        }else if(strlen($country) < 1){
            return response(array(
                "Message" => "Please enter the country",
                "code" => 209,
                "status" => "Fail",
             ));
        }else{
            try{  $customers = DB::table('customers')
                ->where(['Email' => $setEmail])
                ->update(['FirstName' => $firstname, 'LastName' => $lastname, 'Phone' => $phone, 'Email' => $email, 'Type' => $type, 'Address' => $address, 'Postal'=> $postal, 'City' => $city, 'Country' => $country]);

            if($customers){
                 return response(array(
                    "Message" => "Customer has been updated successfuly",
                    "code" => 200,
                    "status" => "success",
                ));
            }else{
                 return response(array(
                    "Message" => "Customer update failed",
                    "code" => 200,
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
}
