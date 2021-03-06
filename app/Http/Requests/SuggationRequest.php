<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\customErrorMessage;
use App\Http\Traits\JsonResponse;

class SuggationRequest extends FormRequest
{
    use customErrorMessage,	JsonResponse;
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
            'suggation_type'=>'required',
            'description' => 'required',
        ];
    }
}
