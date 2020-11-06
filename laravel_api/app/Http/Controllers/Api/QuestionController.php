<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\QuestionService;
use App\Http\Requests\Api\QuestionRequest;
use App\Services\AnswerService;
use Illuminate\Support\Facades\DB;


class QuestionController extends Controller
{
    private $questionService;

    public function __construct(QuestionService $questionService, AnswerService $answerService)
    {
        $this->questionService = $questionService;
        $this->answerService = $answerService;
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
            $questionPaginate = $this->questionService->getAll($ordersBy, $limit);


            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $questionPaginate->items(),
                'meta' => [
                    'total' => $questionPaginate->total(),
                    'per_page' => $questionPaginate->perPage(),
                    'currentPage' => $questionPaginate->currentPage(),
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


    public function store(QuestionRequest $request)
    {
        try {
            DB::beginTransaction();

            $question = $this->questionService->save(['content' => $request->content]);

            foreach ($request->answers as $answer) {
                $answer['question_id'] = $question->id;
                $this->answerService->save($answer);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'code' => 200,
                'question' => $question,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ]
            ], 500);
        }
    }

    public function update(QuestionRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $question = $this->questionService->findById($id);

            $oldAnswerIds = $question->answers->pluck('id')->toArray();

            $newAnswerIds = [];

            foreach ($request->answers as $item) {
                if (!empty($item['id'])) {
                    array_push($newAnswerIds, $item['id']);
                }
            }

            $answerIdsNeedDelete = array_diff($oldAnswerIds, $newAnswerIds);

            $this->answerService->delete($answerIdsNeedDelete);

            $question = $this->questionService->save(['content' => $request->content], $id);

            foreach ($request->answers as $answer) {

                $answer['question_id'] = $id;
                $this->answerService->save($answer, $answer['id'] ?? null);
            }

            $question->answers;

            DB::commit();

            return response()->json([
                'status' => true,
                'code' => 200,
                'question' => $question,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ]
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $question = $this->questionService->findById($id);

            if (!$question) {
                return response()->json([
                    'status' => false,
                    'code' => 404,
                    'message' => 'Question dose not exists'
                ], 404);
            }

            $question->answers;

            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'question' => $question,
            ]);
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

    public function destroy($id)
    {
        try {

            DB::beginTransaction();
            $this->questionService->delete([$id]);
            $this->answerService->deleteByQuestion($id);
            DB::commit();

            return response()->json([
                'status' => true,
                'code' => 200,
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

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
