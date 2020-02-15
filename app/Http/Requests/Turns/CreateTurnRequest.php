<?php

namespace App\Http\Requests\Turns;

use Illuminate\Foundation\Http\FormRequest;

class CreateTurnRequest extends FormRequest {

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
    public function rules()
    {
        return [
            'schedule' => 'required|date_format:H:i|unique:turns',
            'status'   => 'boolean',
        ];
    }
}
