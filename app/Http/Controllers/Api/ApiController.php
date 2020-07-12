<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function responseOk($result,$message)
    {
        $response=[
            'success'=>true,
            'data'=>$result,
            'message'=>$message,
            'alert'=>"success"
        ];
        return response()->json($response,200);
    }

    public function responseError($error,$code=404,$errorMessages=[])
    {
        $response=[
            'success'=>false,
            'error'=>$error,
            'error_messages'=>$errorMessages,
            'alert'=>"error"
        ];
        return response()->json($response,$code);
    }
}
