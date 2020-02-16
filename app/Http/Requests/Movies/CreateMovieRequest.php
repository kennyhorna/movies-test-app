<?php

namespace App\Http\Requests\Movies;

use App\Models\Turn;
use Illuminate\Foundation\Http\FormRequest;

class CreateMovieRequest extends FormRequest {

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
            'name'         => 'required|string|unique:movies',
            'release_date' => 'required|date_format:d/m/Y',
            'image'        => 'required|image|dimensions:min_width=400,min_height=300',
            'status'       => 'boolean',
            'turns'        => [
                'array',
                function ($attr, $turns, $fail) {
                    if (count($turns) !== Turn::whereIn('id', $turns)->count())
                    {
                        $fail('Not all the specified turns exists in the system. Check the values again.');
                    };
                }
            ],
        ];
    }
}
