<?php

namespace App\Http\Controllers;

abstract class Controller
{

    protected function apiSuccess($data = null, string $message = 'success', int $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
