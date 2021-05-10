<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\customErrorMessage;
use App\Http\Traits\JsonResponse;
use App\Rules\AgreeRule;

class MandoubRegisterRequest extends FormRequest
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
            'national_id_front_image' => ['nullable', 'file', 'max:2048', 'mimetypes:image/jpeg,image/png'],
            'national_id_back_image' => ['nullable', 'file', 'max:2048', 'mimetypes:image/jpeg,image/png'],
            "phone" => 'required|min:10|numeric|unique:mandubs,phone,'.\Auth::id(),
            "whats_number" => 'required|min:10|numeric|unique:mandubs,whats_number,'.\Auth::id(),
            'email' =>  'required|email|string|unique:mandubs,email,'.\Auth::id(),
            'password' =>   'required|string',
            'rpassword' => 'required|same:password',
            'shipping_type_id' => 'required',
            'governorate_id' => 'required',
            'gender' => 'required',
            'agree' => new AgreeRule,
        ];
    }
}
