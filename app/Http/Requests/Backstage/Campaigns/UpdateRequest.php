<?php

namespace App\Http\Requests\Backstage\Campaigns;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'timezone' => 'required',
            'starts_at' => 'required|date_format:d-m-Y H:i:s',
            'totalspins' => 'required',
            'spin_schedule' => 'required',
            'ends_at' => 'required|date_format:d-m-Y H:i:s|after:starts_at',
            'targeting' => 'required',
            'segmentation' => 'required',
            'games_allowed' => 'required|numeric',
            'games_frequency' => 'required',
        ];
    }
}
