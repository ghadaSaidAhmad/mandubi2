<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethods;

class ListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaymentMethods()
    {
        try{
            $data = PaymentMethods::get();
            
            $this->initResponse('suessfulley listed',$data, 200, 'data');
        }
        catch(Exception $e){
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }
}
