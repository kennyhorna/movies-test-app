<?php

namespace App\Http\Requests\Movies;

use App\Models\Turn;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'         => 'string|unique:movies,name,' . $this->movie->id,
            'release_date' => 'date_format:d/m/Y',
            'image'        => 'image|dimensions:min_width=400,min_height=300',
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
