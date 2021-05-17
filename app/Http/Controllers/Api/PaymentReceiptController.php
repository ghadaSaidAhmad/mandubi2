<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentReceipt;
use File;

class PaymentReceiptController extends Controller
{
    public function uploadReceipt(Request $request)
    {
        try {
            $user = auth('mandubs')->user();
            // $user->update($request->except(['token']));
            //upload profile_image if exist

            $payment = PaymentReceipt::create([
                'mandub_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'payment_method_id'=>$request->payment_method_id

            ]);
            if ($request->hasFile('receipt_image')) {
                $destination = public_path() . '/images/receipt';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->receipt_image->getClientOriginalName();

                $request->receipt_image->move($destination, $imageName);
                $payment->update(['receipt_image' => $imageName]);
            }

            $this->initResponse('', $payment, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }
}
