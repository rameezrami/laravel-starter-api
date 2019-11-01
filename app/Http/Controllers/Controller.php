<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function successResponse($data,$message='Success',$status=200)
    {
        return response()->json(["data"=>$data,"message"=>$message,"status"=>true],$status);
    }

    public function errorResponse($data,$message='Request Failed',$status=400)
    {
        return response()->json(["data"=>$data,"message"=>$message,"status"=>false],$status);
    }
}
