<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function save(array $data, int $id = null)
    {
        
        return Question::updateOrCreate(           
            [
                'id' => $id
            ],
            [
                'content' => $data['content']
            ]
        );
    }

    // List question
    public function getAll(array $ordersBy = [], $limit = 10)
    {
        $query = Question::query()->with('answers');

        if($ordersBy) {

            $query->orderBy($ordersBy['column'], $ordersBy['sort']);
        }

        return $query->paginate($limit);
    }

    public function findById(int $id)
    {
        return Question::find($id);
    }

    public function delete($ids = [])
    {
        return Question::destroy($ids);
    }



}