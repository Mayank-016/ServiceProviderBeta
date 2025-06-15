<?php

namespace App\Http\Requests;


class BookServiceRequest extends BaseFormRequest
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
            'service_id' => 'required|numeric|exists:services,id',
            'supplier_id' => 'required|numeric|exists:users,id',
            'start_time' => ['required', 'string', 'date_format:H:i:s'],
            'end_time' => ['required', 'string', 'date_format:H:i:s', 'after:start_time'],
            'date' => ['required', 'string', 'date_format:Y-m-d']
        ];
    }
}
