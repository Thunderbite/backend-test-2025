<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FlipRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gameId' => 'required|exists:games,id',
            'tileIndex' => 'required|integer|min:0'
        ];
    }
}
