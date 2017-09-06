<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
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
    public function search(Request $request)
    {
        $id = $request->input('id');
        $data = DB::table('persons')->where(['id' => $id])->get(); 
        if(count($data) > 0){
            $check = DB::table('persons')->select('credits')->where(['id' => $id])->get();
            foreach($check as $val){
                $credits = $val->credits;
            }
            if($credits < 1){
                return response(array(
                            "Message" => "You dont have enough credits for the search",
                            'status' => 'credits'
                    ));
            }else{ 
        $box = $request->input('box');
        $code = $request->input('code');

        if(strlen($box) > 0 && strlen($code) > 0){
             $creditsBal = $credits - 1;
             $bal=DB::table('persons')
                    ->where('id', $id)
                    ->update(['credits' => $creditsBal]);   
             
             $check = DB::table('info')->where(['box' => $box, 'code' => $code])->get();
             if(count($check) > 0){
                for($i=0; $i<count($check); $i++){
                $datas = DB::table('info')->select('name', 'box', 'code', 'town', 'category')->where(['box' => $box, 'code'=>$code])->get();
                
                foreach($datas as $data){
                        $name = $data->name;
                        $box = $data->box;
                        $code = $data->code;
                        $town = $data->town;
                       }
                if($request->session()->has('FirstName')){
                            $data=$request->session()->get('FirstName');  
                        }
                return response(array(
                    'results' =>$datas->toArray(), 
                    ),200);
                    }
             }else{
            return response(array(
                "Message" => "This combination of box number and posta code does not exist",
                "code" => 209,
                "status" => "no-match",
                
             ));
             }
        }else{
              return response(array(
                "Message" => "Please enter Both Box and Code number to verify",
                "code" => 209,
                "status" => "fail",
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
                
            }else{
                return response(array(
                            "Message" => "You have enough credits for the search",
                            'status' => 'credits'
                            ));
        }
    }
        }
    }


    public function bulk(Request $request){
        $input = $request->input('box');
        $box= array();
        for($i=0; $i<10; $i++){
            $box[$i] = $input;
        }
        
        for($i=0; $i<count($box); $i++){

           if(count($box[$i]) > 0){
            $check = DB::table('info')->select('name', 'box', 'code', 'town', 'category')->where(['box' => $box[$i]])->get();
            if(count($check) < 1){
                return response(array(
                "Message" => "P.O Box dont exist",
                "code" => 209,
                "status" => "fail",
                ));
            }else{
                 $datas = DB::table('info')->select('name', 'box', 'code', 'town', 'category')->where(['box' => $box[$i]])->get();
                foreach($datas as $data){
                        $name = $data->name;
                        $box = $data->box;
                        $code = $data->code;
                        $town = $data->town; 
                    }
            } 
        }
            return response(array(
                    "message" => "Verified",
                    "Name" => $name,
                    "Box" => $box,
                    "code" => $code,
                    "Town" => $town,
                ));
           }
       }
    

    public function private(Request $request)
    {
        $name = $request->input('name');
        $box = $request->input('box');
        $code = $request->input('code');
        $town = $request->input('town');

        if(strlen($name) < 1){
            return response(array(
                "Message" => "Please enter the name",
                "code" => 209,
                "status" => "No name",
                ));
            }else if(strlen($box) < 1){
                return response(array(
                "Message" => "Please enter the Box number",
                "code" => 209,
                "status" => "No Po Box",
                ));
            }else if(strlen($code) < 1){
                return response(array(
                "Message" => "Please enter the code",
                "code" => 209,
                "status" => "No Code number",
                ));
            }else if(strlen($town) < 1){
                return response(array(
                "Message" => "Please enter the town",
                "code" => 209,
                "status" => "No town entered",
                ));
            }else{
                
             $check = DB::table('info')->where(['box' => $box])->get();
             if(count($check) > 0){
                $datas = DB::table('info')->select('name', 'box', 'code', 'town', 'category')->where(['box' => $box])->get();
                foreach($datas as $data){
                        $name = $data->name;
                        $box = $data->box;
                        $code = $data->code;
                        $town = $data->town;
                       }
                   }
                return response(array(
                    "message" => "Verified",
                    "Name" => $name,
                    "Box" => $box,
                    "code" => $code,
                    "Town" => $town,
                    ),200);
             
        }
    }


    public function history(Request $request){
        $box = $request->input('box');
        $code = $request->input('code');
        $name = $request->input('name');
        $town = $request->input('town');
        $status = $request->input('status');
        $person_id = $request->input('person_id');
        $created_at = $request->input('created_at');

        $add[] = ['box' => $box, 'code' => $code, 'name' => $name, 'town' => $town, 'status' => $status, 'person_id' => $person_id, 'created_at' => $created_at];
        if(!empty($add)){
            DB::table('historys')->insert($add);
            return response(array(
                "Message" => "Data inserted correctly",
                "code" => 209,
                "status" => "success",
             ));
        }else{
            return response(array(
                "Message" => "Data not inserted",
                "code" => 209,
                "status" => "fail",
             ));
        }
    }

    public function historyData(Request $request){
        
        $person_id = $request->input('person_id');
        
        $values = DB::table('historys')->where(['person_id' => $person_id])->get();
        for($i=0; $i<count($values); $i++){
         $check = DB::table('historys')->select('box', 'name', 'town', 'code', 'status', 'created_at')->where(['person_id' => $person_id])->get();
        foreach($check as $checks){
            $box = $checks->box;
            $name = $checks->name;
            $town = $checks->town;
            $code = $checks->code;
            $status = $checks->status;

        }
        return response(array(
                    'results' =>$check->toArray(), 
                    ),200);

    }
       
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
