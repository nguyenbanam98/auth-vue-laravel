<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;
use App\Rules\AnswerNumberValidate;
use App\Rules\AnswerContentUniqueValidate;
use App\Rules\UniqueAnswerCorrectValidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\RequireHaveCorrectAnswerValidate;
use Illuminate\Http\Exceptions\HttpResponseException;


class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'content' => [
                'required',
                'min:12',
                new AnswerNumberValidate($request->answers),
                new RequireHaveCorrectAnswerValidate($request->answers),
                new AnswerContentUniqueValidate($request->answers),
                new UniqueAnswerCorrectValidate($request->answers),
            ],
            'answers.*.content' => ['required', 'min:3'],
            'answers.*.correct' => ['boolean'],
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => [
                'status'   => false,
                'code'     => 422,
                'messages' => $validator->errors()
            ]
        ], 422));
    }

    public function messages()
    {
        return [
            'content.required'  => 'Vui lòng nhập nội dung câu hỏi',
            'content.min'       => 'Nội dung câu hỏi không được nhỏ hơn 12 ký tự',
            'answers.*.content.required' => 'Nội dung câu trả lời không được bỏ trống',
            'answers.*.content.min' => 'Nội dung câu trả không được nhỏ hơn 3 ký tự',
            'answers.*.correct.boolean' => 'Câu trả lời đúng chỉ được phép là true hoặc false'

        ];
    }
}
