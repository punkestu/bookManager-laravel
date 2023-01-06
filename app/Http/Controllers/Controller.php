<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccess($data, $msg = null)
    {
        $result = [
            "success" => true,
            "data" => $data
        ];
        if ($msg != null) {
            $result["msg"] = $msg;
        }
        return response($result, 200);
    }

    public function sendError($msg, $code = 400)
    {
        return response([
            "success" => false,
            "error" => $msg
        ], $code);
    }
}
