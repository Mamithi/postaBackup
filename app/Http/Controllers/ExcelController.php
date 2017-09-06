<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bulk;
use App\View;
use Excel;
use DB;
use Session;

class ExcelController extends Controller
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




    public function downloadExcel(){
          
            $all = DB::table('views')->select('name', 'box', 'code', 'town', 'status')->get();
            if(count($all) > 0){
            return response(array(
                'all' =>$all-> toArray()
                ),200);  
               }else{
                return response(array(
                    "message" => "You have no data to download",
                    "code" => 204,
                    "status" => "No content",
                    ));
               }  
                         
 }


   public function deleteExcel(){
    DB::table('views')->delete();
   }


    public function importExcel(Request $request){

         $data =$request->input('data');
         $length = count($data);
         for($i = 0; $i < $length; $i++){
            $id = $data[$length-1];
          }
        
        $dataId = DB::table('persons')->where(['id' => $id])->get(); 
        if(count($dataId) > 0){
            array_pop($data); 
            $check = DB::table('persons')->select('credits')->where(['id' => $id])->get();
            foreach($check as $val){
                $credits = $val->credits;
            }
            if($credits < 1){
                return response(array(
                            "Message" => "You have no credits. Please buy new credits to continue",
                            'status' => 'noCredits'
                    ));
            }else{
        
         $length = count($data);
         if($length > $credits){
            return response(array(
                     "Message" => "Your credits are not enough for this search. You are searching for ".$length." records and you have ". $credits. " credits. Please buy more credits to continue",
                     'status' => 'lessCredits'
                    ));
         }else{
                 
                 $creditsBal = $credits - $length;
                  $bal=DB::table('persons')
                    ->where('id', $id)
                    ->update(['credits' => $creditsBal]);
         if(count($data) >0){
            foreach($data as $values){ 
                    

                    $box = $values['box'];
                    $code = $values['code'];
                    $searchCode = DB::table('info')->where(['code' => $code])->get();
                    if(count($searchCode) > 0){
                         $searchBox = DB::table('info')->where(['box' => $box])->get();
                         if(count($searchBox)>0){
                            $status = "Verified";
                            $datas = DB::table('info')->select('name', 'box', 'code', 'town')->where(['box' => $box])->get(); 
                                    foreach ($datas as $data)
                                    {
                                        $boxVar =  $data->box;
                                        $codeVar = $data->code;
                                        $nameVar = $data->name;
                                        $townVar = $data->town;
                                        //$person_id = $personId;
                                        $status = "Verified";
                                        $add[] = ['name' => $nameVar, 'box' => $boxVar, 'code' => $codeVar, 'town' => $townVar,  'status' => $status];

                                      }
                                      }else{
                                        $status = "Does not exist";
                                        $boxVar =  $box;
                                        $codeVar = $code;
                                        $nameVar = "null";
                                        $townVar = "null";
                                        //$person_id = $personId;
                                       
                                        $add[] = ['name' => $nameVar, 'box' => $boxVar, 'code' => $codeVar, 'town' => $townVar,  'status' => $status];
                                         }
                                     }else{
                                        $status = "Not verified";
                                        $boxVar =  $box;
                                        $codeVar = $code;
                                        $nameVar = "null";
                                        $townVar = "null";
                                        //$person_id = $personId;
                                       
                                        $add[] = ['name' => $nameVar, 'box' => $boxVar, 'code' => $codeVar, 'town' => $townVar,  'status' => $status];
                    }
                    
                   
            }
              
         }
        
         
         if(!empty($add)){
            DB::table('views')->insert($add);
            return response(array(
                                "Message" => "Your search is successful",
                                "code" => 200,
                                "status" => "success",
                                
                   ));
             //$this->downloadExcel(); 
        }else{
            return response(array(
                                "Message" => "Data upload has failed",
                                "code" => 200,
                                "status" => "fail",
                                
                   ));
        }
    }
    }
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
