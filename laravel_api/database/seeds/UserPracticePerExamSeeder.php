<?php

use App\Models\Exam;
use App\Models\Result;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserPracticePerExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::take(20)->get();

        foreach ($users as $user) {
            $exam = Exam::find(1);
            $results = [];

            if (!empty($exam) && $exam->questions->count() > 0) {
                foreach ($exam->questions as $question) {
                    $answer = $question->answers()->inRandomOrder()->first();

                    if (!empty($answer)) {
                        $results[] = [
                            'exam_id' => $exam->id,
                            'question_id' => $answer->id,
                            'answer_id' => $answer->id,
                            'user_id' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                Result::insert($results);
            }
        }

    }
}
