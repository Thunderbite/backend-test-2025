<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class LoadCampaignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'a' => 'required',
            'segment' => 'required'
        ];
    }
}
