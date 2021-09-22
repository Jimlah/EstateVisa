<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function response_success(string $message)
    {
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function response_error(string $message)
    {
        return response()->json(['status' => 'error', 'message' => $message]);
    }

    public function response_info(string $message)
    {
        return response()->json(['status' => 'info', 'message' => $message]);
    }

    public function response_warning(string $message)
    {
        return response()->json(['status' => 'warning', 'message' => $message]);
    }

    public function response_data(JsonResource|array $data)
    {
        return response()->json(['data' => $data]);
    }
}
