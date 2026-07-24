<?php

namespace App\Http\Requests\English;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers'            => ['required', 'array', 'min:1'],
            'answers.*.word_id'  => ['required', 'integer', 'exists:vocabulary_words,id'],
            'answers.*.answer'   => ['required', 'string', 'max:100'],
            'duration_seconds'   => ['nullable', 'integer', 'min:0'],
        ];
    }
}
