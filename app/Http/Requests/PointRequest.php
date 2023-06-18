<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:50', 'unique:points'],
            'description' => ['required'],
            'category_id' => [''],
            'forestry_id' => [''],
            'geo' => ['required', 'json'],
            'view_360_link' => [],
            'online_cam_link' => [],
            'direction_link' => [],
//            'images' => ['size:5000']
        ];
    }
}
