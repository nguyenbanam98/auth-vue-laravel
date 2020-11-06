<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Question;

class ResultService
{
    /**
     * param array $data
     * $data key: content, questionId: integer, correct: boolean
     * int $id | null
     * @param array $data
     * @param int|null $id
     * @return
     */
    public function save(array $data, int $id = null)
    {
        return Result::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'exam_id'     => $data['exam_id'],
                'question_id' => $data['question_id'],
                'answer_id'   => $data['answer_id'],
                'user_id'     => $data['user_id'],
            ]
        );
    }

    public function insertMultiResult($results)
    {
        $user = auth()->user();

        $data = [];

        foreach ($results as $result) {
            $result['user_id'] = $user->id;
            $result['created_at'] = now();
            $result['updated_at'] = now();
            $data[] = $result;
        }
        return Result::insert($data);
    }
}
