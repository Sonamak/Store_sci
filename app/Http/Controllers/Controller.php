<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseJson($success = false, $data = null, $message = null, $httpStatus = 200) {
        return response()->json([
            'success' => empty($success) ? false : true,
            'data' => $data ?? null,
            'message' => $message
        ], $httpStatus);
    }
}
