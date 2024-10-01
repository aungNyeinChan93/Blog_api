<?php

namespace App\Helpers;

class ResponseHelper{
    public static function success($data = [], $message ="success"){
        return response()->json([
            "message" =>$message,
            "data" =>$data
        ]);
    }

    public static function fail($data = [], $message ="Fail"){
        return response()->json([
            "message" =>$message,
            "data" =>$data
        ]);
    }
}
