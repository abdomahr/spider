<?php

namespace App\Http\helpers;

class ApiRseponse 
{
  
    static function sendresponse($code=200,$message=null,$data=null)
    {
     $response=[
        'code'=>$code,
        'message'=>$message,
        'data'=>$data,
     ];
     return response()->json($response , $code);
    }
}
