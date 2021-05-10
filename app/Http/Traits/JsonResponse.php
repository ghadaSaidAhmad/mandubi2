<?php

namespace App\Http\Traits;

trait JsonResponse {
    /**
     * @var array $response is a keyed array
     */
    public $response;

    /**
     * @var int $code is the status of the current response
     */
    public $code;

    /**
     * Response function.
     *
     * @param $messageKey
     * @param $data,
     * @param $code,
     * @param $type
     *
     */
    public function initResponse($messageKey = '', $data = '', $code = 200, $type = '')
    {
        $this->code = $code;
        if($messageKey)
            $this->response["message"] = __($messageKey);
        
        if($type)
            $this->response[$type] = $data;

        $this->response["code"] = $code;
    }

    protected function errorBagFormat($errors)
    {
        $errorBagFormat = [];

        foreach ($errors->getMessages() as $fieldName => $errorMessage) {
            $errorBagFormat[$fieldName] = $errorMessage[0];
        }

        return $errorBagFormat;
    }
}
