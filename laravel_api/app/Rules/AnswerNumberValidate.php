<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AnswerNumberValidate implements Rule
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
        return count($this->answers) >= 2;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Phai nhap it nhat 2 cau tra loi';
    }
}
