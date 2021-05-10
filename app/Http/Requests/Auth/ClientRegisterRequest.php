<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\customErrorMessage;
use App\Http\Traits\JsonResponse;
use App\Rules\AgreeRule;

class ClientRegisterRequest extends FormRequest
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
            "phone" => 'required|min:10|numeric|unique:clients,phone,'.\Auth::id(),
            'email' =>  'required|email|string|unique:clients,email,'.\Auth::id(),
            'password' =>   'required|string',
            'rpassword' => 'required|same:password',
            'agree' => new AgreeRule,
           
        ];
    }
}
