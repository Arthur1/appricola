<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameRequest extends FormRequest
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
            'players_number' => ['required', 'numeric', 'min:2', 'max:6'],
            'pool_id' => ['required', 'numeric', 'exists:pools,id'],
            'cards_number' => ['required', 'numeric', 'min:7', 'max:10'],
            'users' => ['required', 'array', 'size:' . $this->players_number],
            'users.*' => ['required', 'exists:users,name', 'distinct']
        ];
    }

    public function attributes()
    {
        return [
            'players_number' => 'プレイヤー人数',
            'pool_id' => 'カードプール',
            'cards_number' => 'ドラフト初期枚数',
            'users' => 'ユーザ名',
            'users.0' => 'ユーザ名(1人目)',
            'users.1' => 'ユーザ名(2人目)',
            'users.2' => 'ユーザ名(3人目)',
            'users.3' => 'ユーザ名(4人目)',
            'users.4' => 'ユーザ名(5人目)',
            'users.5' => 'ユーザ名(6人目)',
        ];
    }
}
