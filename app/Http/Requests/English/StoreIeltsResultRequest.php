<?php

namespace App\Http\Requests\English;

use Illuminate\Foundation\Http\FormRequest;

class StoreIeltsResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wpm'              => ['required', 'integer', 'min:0'],
            'accuracy'         => ['required', 'numeric', 'min:0', 'max:100'],
            'time_seconds'     => ['required', 'integer', 'min:0'],
        ];
    }
}
