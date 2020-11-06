<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExamRequest;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    private $examService;

    public function __construct(ExamService $examService)
    {
        return $this->examService = $examService;
    }

    public function index(Request $request)
    {
        try {

            $limit = $request->get('limit') ?? config('app.paginate.per_page');

            $ordersBy = [];

            if ($request->get('column') && $request->get('sort')) {
                $ordersBy = [
                    'column' => $request->get('column'),
                    'sort' => $request->get('sort'),
                ];
            }
            $examPaginate = $this->examService->getAll($ordersBy, $limit);


            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $examPaginate->items(),
                'meta' => [
                    'total' => $examPaginate->total(),
                    'per_page' => $examPaginate->perPage(),
                    'currentPage' => $examPaginate->currentPage(),
                ],
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // create exam
    public function store(ExamRequest $request)
    {
        try {
            DB::beginTransaction();
            $exam = $this->examService->save(['name' => $request->name]);

            $questions = $request->questions;

            $this->examService->attachQuestion($questions, $exam);

            DB::commit();

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $exam
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // update exam
    public function update(ExamRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $exam = $this->examService->save(['name' => $request->name], $id);

            $questions = $request->questions;

            $this->examService->syncQuestion($questions, $exam);

            DB::commit();

            return response()->json([
                'status' => true,
                'code' => 200,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // show exam with id

    public function show($id)
    {
        try {
            $exam = $this->examService->findById($id);

            $exam->questions;

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $exam
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // delete by id
    public function destroy($id)
    {
        try {
            $exam = $this->examService->deleteById($id);
            return response()->json([
                'status' => true,
                'code' => 200,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
