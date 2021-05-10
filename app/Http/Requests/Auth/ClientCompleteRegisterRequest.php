<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\customErrorMessage;
use App\Http\Traits\JsonResponse;
use App\Rules\AgreeRule;

class ClientCompleteRegisterRequest extends FormRequest
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
            'name' => 'required|string',
            'gender' => 'required',
            'profile_image' => ['nullable', 'file', 'max:2048', 'mimetypes:image/jpeg,image/png'],
            'national_id_front_image' => ['nullable', 'file', 'max:2048', 'mimetypes:image/jpeg,image/png'],
            'national_id_back_image' => ['nullable', 'file', 'max:2048', 'mimetypes:image/jpeg,image/png'],
           
        ];
    }
}
