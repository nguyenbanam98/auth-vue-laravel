<?php

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignQuestionToExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questionMaxId = 0;

        Exam::chunk(30, function ($exams) use (&$questionMaxId) {
            foreach ($exams as $exam) {
                $examQuestions = [];
                $questions = Question::where('id', '>', $questionMaxId)->take(25)->get();

                if ($questions->count() > 0) {
                    $questionMaxId = $questions->last()->id;

                    foreach ($questions as $question) {
                        $examQuestions[] = [
                            'exam_id' => $exam->id,
                            'question_id' => $question->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DB::table('exam_question')->insert($examQuestions);
                }
            }
        });
    }
}
