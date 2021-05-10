<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\customErrorMessage;
use App\Http\Traits\JsonResponse;

class AddOrderRequest extends FormRequest
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

            'from_lang' => 'required',
            'to_lang' => 'required',
            'from_lat' => 'required',
            'to_lat' => 'required',
            'from_title' => 'required',
            'to_title' => 'required',
            'shipping_type_id' =>  'required',
            'mandub_gender' => 'required',
        ];
    }
}
