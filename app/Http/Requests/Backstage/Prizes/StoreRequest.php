<?php

namespace App\Http\Requests\Backstage\Prizes;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $rules = [
            'customerlevel_id'              => 'required',
            'points_band.*'                 => 'required',
            'message.*'                     => 'required',
            'redirect_desktop'              => 'required',
            'redirect_mobile'               => 'required',
            'win_popup_image'               => 'required',
            'nowin_popup_image'             => 'required',
            'customer_segmentation_id.*'    => 'sometimes|required|customer_segmentation_id|gt:0',
        ];

        return $rules;
    }

    /**
     * Get Custom Validation Messages
     *
     * @return array Custom Validation Messages
     */
    public function messages(): array
    {
        return [
            'points_band.*.required'    => 'Please select the points band',
            'message.*.required'        => 'Please fill in a message',
        ];
    }
}
