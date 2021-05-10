<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\customErrorMessage;
use App\Http\Traits\JsonResponse;

class MandoubLoginRequest extends FormRequest
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
            'phone' =>      ['required', 'string', 'exists:mandubs,phone'],
            'password' =>   ['required', 'string']
        ];
    }
}
