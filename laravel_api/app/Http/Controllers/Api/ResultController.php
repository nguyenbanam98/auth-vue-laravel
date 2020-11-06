<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ResultService;
use Illuminate\Http\Request;


class ResultController extends Controller
{
    private $resultService;

    public function __construct(ResultService $resultService)
    {
        $this->resultService = $resultService;
    }

    public function store(Request $request)
    {
        try {
            $this->resultService->insertMultiResult($request->results);

            return response()->json([
                'status' => true,
                'code' => 200,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ]
            ], 500);
        }
    }

  
}
