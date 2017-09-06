<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class DataController extends Controller
{
    public function materials(){
        $data = DB::table('categorys')
            ->select('Category')
            ->groupBy('Category')
            ->get();

        if(count($data) > 0){
            return response(array(
                'materials' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No Material Data"
                ));
        }
    }

   
    public function getQuotations(){
        $data = DB::table('datas')
            ->select('created_at', 'Name', 'Category', 'InvoiceNo', 'Premium', 'Sum', 'Total')
            ->get();

        if(count($data) > 0){
            return response(array(
                'quotations' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No Quotation Data"
                ));
        }
    }



    public function description(Request $request){
        $category = $request->input('category');
        $data = DB::table('categorys')->select('SubCategory')->where(['Category' => $category])->get();
    
        if(count($data) > 0){
            return response(array(
                'description' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No description info"
                ));
        }
    }

    public function premium(Request $request){
        $id = $request->input('id');
        $data = DB::table('categorys')->select('Containerized')->where(['Category' => $id])->get();
        

        if(count($data) > 0){
            foreach ($data as $value) {
                $rate = $value->Containerized;
                $len = strlen($rate);
            }

            return response(array(
                "rate" => $rate,
                "length" => $len
                ));
        }else{
            return response(array(
                "rate" => "no rate for this item",
                "length" => $len
                ));
        }
    }

    public function duty(Request $request){
        $transport = $request->input('mode');
        $sum = $request->input('sum');
        $currency = $request->input('currency');
        if(count($transport) > 0){
            $airTest = "air";
            $airRoadTest = "airRoad";
            $seaRoadTest = "seaRoad";
            $currencyTest = "ksh";

            if((strcmp($transport, $airTest) == 0) && strcmp($currency, $currencyTest) == 0 ){
                return response(array(
                    "duty" => 40
                 ));
            }else if((strcmp($transport, $airTest) == 0) && strcmp($currency, $currencyTest) > 0 ){
                return response(array(
                    "duty" => 0.4
                 ));
            }
            else if((strcmp($transport, $airRoadTest) == 0) && strcmp($currency, $currencyTest) == 0){
                return response(array(
                    "duty" => 40
                 ));
            }else if((strcmp($transport, $airRoadTest) == 0) && strcmp($currency, $currencyTest) > 0){
                return response(array(
                    "duty" => 0.4
                 ));
            }
            else if((strcmp($transport, $seaRoadTest) == 0) && strcmp($currency, $currencyTest) == 0){
                $duty = (0.05/100)*$sum;
                return response(array(
                    "duty" => $duty
                 ));
                    }
            else if((strcmp($transport, $seaRoadTest) == 0) && strcmp($currency, $currencyTest) > 0){
                $duty = (0.0005/100)*$sum;
                return response(array(
                    "duty" => $duty
                 ));
                    }
            }else{
                return response(array(
                    "Message" => "Enter a valid transport mode",
                    "status" => "fail"
                 ));
            }
        }
    

    public function net(Request $request){
        $premium = $request->input('premium');
        $sum = $request->input('sum');
        if(count($premium) > 0 && count($sum) > 0){
            $net = ($premium/100) * $sum;
            return response(array(
                "net" => $net,
                "status" => "success"
                ));

        }else{
            return response(array(
                "Message" => "Please enter both premium and sum insured",
                "status" => "fail"
                ));
        }
    }

    public function levy(Request $request){
        $net = $request->input('net');
        if(count($net) > 0){
            $levy = ((0.45)/100) * $net;
            return response(array(
                "levy" => $levy,
                "status" => "success"
            ));
        }else{
            return response(array(
                "Message" => "Please enter both the net",
                "status" => "fail"
                ));
        }
    }

    public function total(Request $request){
        $net = $request->input('net');
        $duty = $request->input('duty');
        $levy = $request->input('levy');
        if(($net > -1) && ($duty > -1) && ($levy > -1)){
            $total = $net + $duty + $levy;
             return response(array(
                "total" => $total,
                "status" => "success"
        ));

        }else{
            return response(array(
                "Message" => "Please enter all values to proceed",
                "status" => "fail"
                ));
        }
    }
    public function saveData(Request $request){
        $currency = $request->input('currency');
        $transport = $request->input('transport');
        $transit = $request->input('transit');
        $containerized = $request->input('containerized');
        $category = $request->input('category');
        $description = $request->input('description');
        $sum = $request->input('sum');
        $premium = $request->input('premium');
        $net = $request->input('net');
        $duty = $request->input('duty');
        $levy = $request->input('levy');
        $total = $request->input('total');
        $personId = $request->input('personId');
        $date = $request->input('date');
        $type = $request->input('type');
        $name = $request->input('name');
        $phone = $request->input('phoneNumber');
        $to = $request->input('toFinal');
        $from = $request->input('fromFinal');
        $via = $request->input('viaFinal');
        $invoiceNo = $request->input('invoiceNo');
        $address = $request->input('addressFinal');
        $valuation = $request->input('valuationFinal');
        $conveyance = $request->input('conveyanceFinal');
        $billNo = $request->input('billFinal');
        $vessel = $request->input('vesselFinal');
        $depature = $request->input('depatureFinal');
        $arrival = $request->input('arrivalFinal');
        if(count($currency) > 0 && count($transport) > 0 && count($category) > 0 && count($transit) > 0 && count($containerized) > 0 && count($sum) > 0 && count($premium) > 0 && count($duty) > 0 && count($net) > 0 && count($levy) > 0 && count($total) > 0 && count($personId) > 0 && count($date) > 0){

        $data = DB::table('datas')->insert(['Currency' => $currency, 'Transport' => $transport, 'Transit' => $transit, 'Containerized' => $containerized, 'Category' => $category, 'Description' => $description, 'Sum'=> $sum, 'Premium' => $premium, 'Net' => $net, 'Duty'=> $duty, 'Levy' => $levy, 'Total' => $total, 'PersonId' => $personId, 'created_at' => $date, 'Type' => $type, 'Name'=>$name, 'PhoneNumber'=>$phone, 'ToPlace' => $to, 'FromPlace'=> $from, 'Via' => $via, 'InvoiceNo' => $invoiceNo, 'Address'=>$address, 'Valuation' => $valuation, 'Conveyance' => $conveyance, 'BillNo' => $billNo, 'Vessel' => $vessel, 'Depature' => $depature, 'Arrival' => $arrival]);
        if(count($data) > 0){
            return response(array(
                "Message" => "Your data has been saved successfuly",
                "code" => 200,
                "status" => "success"
                ));
        }else{
            return response(array(
                "Message" => "Data was not saved",
                "code" => 200,
                "status" => "fail"
                ));
        }
    }else{
        return response(array(
                "Message" => "Please enter all the details to save",
                "code" => 200,
                "status" => "fail"
                ));
    }
}
public function transactions(Request $request){

    $personId= $request->input('personId');
    $data = DB::table('datas')->select('Currency', 'Transport', 'Transit', 'Containerized', 'Category', 'Description', 'Sum', 'Premium', 'Net', 'Duty', 'Levy', 'Total', 'created_at')->where(['PersonId' => $personId])->get();
    
        if(count($data) > 0){
            return response(array(
                'transactions' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No data saved for this person"
                ));
        }
}




public function addCustomers(Request $request){
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
            try{  $customers = DB::table('customers')->insert(['FirstName' => $firstname, 'LastName' => $lastname, 'Phone' => $phone, 'Email' => $email, 'Type' => $type, 'Address' => $address, 'Postal'=> $postal, 'City' => $city, 'Country' => $country]);

            if($customers){
                 return response(array(
                    "Message" => "Customer has been added successfuly",
                    "code" => 200,
                    "status" => "success",
                ));
            }else{
                 return response(array(
                    "Message" => "Customer Registration failed",
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
//Getting Customers registered 
 public function getCustomers(){
        $data = DB::table('customers')
            ->select('FirstName', 'LastName', 'Email', 'Phone', 'Type', 'Address', 'Postal', 'City', 'Country')
            ->get();

        if(count($data) > 0){
            return response(array(
                'customers' =>$data-> toArray()
                ));
        }else{
               return response(array(
                 "No Customers registered yet"
                ));
        }
    }

public function searchUser(Request $request){
    $search = $request->input('search');
    $data = DB::table('customers')
            ->select('FirstName', 'LastName', 'Email', 'Phone', 'Type', 'Address', 'Postal', 'City', 'Country')
            ->where('Email' , 'like', "$search%")
            ->orWhere('FirstName' , 'like', "$search%")
            ->orWhere('Phone' , 'like', "$search%")
             ->orWhere('LastName' , 'like', "$search$")
            ->get();

    if(count($data) > 0){
        return response(array(
            'searchData' => $data -> toArray()
            ));
    }else
    return response(array(
           'Message' => 'This user does not exist',
           'status' => 'fail',
           'code' => 204,
        ));
}

}
