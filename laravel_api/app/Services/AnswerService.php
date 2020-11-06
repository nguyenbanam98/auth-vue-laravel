<?php

namespace App\Services;

use App\Models\Answer;

class AnswerService
{
    /**
     * param array $data
     * $data key: content, questionId: integer, correct: boolean
     * int $id | null
     */
    public function save(array $data, int $id = null)
    {
        return Answer::updateOrCreate(           
            [
                'id' => $id
            ],
            [
                'question_id' => $data['question_id'],
                'content'     => $data['content'],
                'correct'     => $data['correct'],
            ]
        );
    }

    public function deleteByQuestion($questionId)
    {
        return Answer::where('question_id', $questionId)->delete();
    }

    public function delete($ids = [])
    {
        return Answer::destroy($ids);
    }
}
