<?php

namespace App\Http\Requests\Backstage\Prizes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

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
            'name' => 'required|max:255',
            'description' => 'sometimes',
            'weight' => 'required|numeric|between:0.01,99.99',
            'starts_at' => 'required|date_format:d-m-Y H:i:s',
            'ends_at' => 'required|date_format:d-m-Y H:i:s',
            'segment' => 'required|in:low,med,high',
            'image_src' => [File::image()->max('500kb')]
        ];
    }

    public function messages()
    {
        return [
            'image_src.image' => 'The uploaded file must be an image.',
            'image_src.max' => 'Please provide a valid image file < 500kb',
        ];
    }
}
