<?php 

namespace App\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait customErrorMessage {
        
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $this->initResponse('', $this->errorBagFormat($validator->getMessageBag()), 422, 'error');
        throw new HttpResponseException(response()->json($this->response, $this->code));
    }
}
