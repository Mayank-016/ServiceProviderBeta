<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => ['required', 'string', 'date_format:H:i:s'],
            'end_time' => ['required', 'string', 'date_format:H:i:s', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'min:5', 'max:120'],
            'weekend_available' => ['required', 'boolean'],
        ];
    }
}
