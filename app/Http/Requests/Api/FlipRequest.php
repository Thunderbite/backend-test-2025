<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class FlipRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gameId' => 'required|exists:games,id',
            'tileIndex' => 'required|integer|min:0',
        ];
    }
}
