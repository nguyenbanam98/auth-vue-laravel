<?php

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Exam::class, 30)->create();
    }
}
