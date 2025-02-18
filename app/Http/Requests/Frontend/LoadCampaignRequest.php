<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

final class LoadCampaignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'a' => 'required',
            'segment' => 'required',
        ];
    }
}
