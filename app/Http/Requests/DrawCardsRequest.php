<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DrawCardsRequest extends FormRequest
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
            'draw_number' => ['required', 'numeric', 'min:1', 'max:7'],
        ];
    }

    public function attributes()
    {
        return [
            'draw_number' => 'ドロー枚数',
        ];
    }
}
