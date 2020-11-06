<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueAnswerCorrectValidate implements Rule
{
    private $answers;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($answers)
    {
        return $this->answers = $answers;
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
        $corrects = [];
        foreach ($this->answers as $answer) {
            if($answer['correct'] === true) {
                array_push($corrects, true);
            }
        }

        return count($corrects) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cau tra loi chi co duy nhat mot cau dung';
    }
}
