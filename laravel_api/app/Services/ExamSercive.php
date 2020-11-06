<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Question;

class ExamService
{
    // create or update

    public function save(array $data, int $id = null)
    {
        return Exam::updateOrCreate(
            ['id' => $id],
            ['name' => $data['name']]
        );
    }

    public function syncQuestion($questions, $exam)
    {

        foreach ($questions as $question) {

            $questionInstance  = Question::firstOrCreate(['content' => $question['content']]);
            $questionIds[] = $questionInstance->id;
        }

        return $exam->questions()->sync($questionIds);
    }

    public function attachQuestion($questions, $exam)
    {

        foreach ($questions as $question) {

            $questionInstance  = Question::firstOrCreate(['content' => $question['content']]);
            $questionIds[] = $questionInstance->id;
        }

        return $exam->questions()->attach($questionIds);
    }


    // List exam
    public function getAll($ordersBy = [], $limit = 10)
    {
        $query = Exam::query()->with('questions');

        if($ordersBy) {

            $query->orderBy($ordersBy['column'], $ordersBy['sort']);
        }

        return $query->paginate($limit);
    }

    // show
    public function findById($id)
    {
        return Exam::findOrFail($id);
    }


    // delete

    public function deleteById($id)
    {
        $exam = Exam::findOrFail($id);

        return $exam->delete($id);
    }

}
