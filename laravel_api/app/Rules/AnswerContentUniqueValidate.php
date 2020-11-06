<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AnswerContentUniqueValidate implements Rule
{
    private $answers;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($answers)
    {
        $this->answers = $answers;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $results = [];

        foreach ($this->answers as $key => $answer) {
            array_push($results, $answer['content']);
        }

        return count(array_unique($results)) === count($this->answers);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nội dung câu trả lời không được trùng nhau';
    }
}
