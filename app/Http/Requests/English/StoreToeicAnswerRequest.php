<?php

namespace App\Http\Requests\English;

use Illuminate\Foundation\Http\FormRequest;

class StoreToeicAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id'        => ['required', 'integer', 'exists:toeic_questions,id'],
            'selected_option_id' => ['required', 'integer', 'exists:toeic_question_options,id'],
        ];
    }
}
