<?php

namespace App\Exceptions;

use Exception;

class OutOfCardsException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'errors' => [
                    'pool_id' => ['この設定ではカードが足りません'],
                ]
            ],
            422
        );
    }
}
