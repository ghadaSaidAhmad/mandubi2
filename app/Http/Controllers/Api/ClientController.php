<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Mandub;
use App\Models\Client;
use App\Models\Notification;
use App\Models\MandubOrders;
use App\Models\MandubRate;
use App\Models\Message;
use App\Models\Image;
use Auth;
use App\Http\Requests\Auth\ClientCompleteRegisterRequest;
use App\Http\Requests\AddOrderRequest;
//use App\Events\OrderStatusChanged;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Http\Requests\MandubRateRequest;
use File;

class ClientController extends Controller
{

    /**
     * updateFcm accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(Request $request)
    {
        try {
            $user = auth('clients')->user();
            $result = $user->update($request->except('_token'));
            if ($result) {
                $this->initResponse('', 'info updated successfully ', 200, 'data');
            } else {
                $this->initResponse('', 'faild to update info ', 200, 'data');
            }

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * updateFcm accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function updateFcmToken(Request $request)
    {
        try {
            $user = auth('clients')->user();
            $result = $user->update($request->except('_token'));
            if ($result) {
                $this->initResponse('', $user, 200, 'data');
            } else {
                $this->initResponse('', 'faild token updated', 200, 'data');
            }

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * user  complete registration
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @return Response
     */
    public function addOrder(AddOrderRequest $request)
    {
        try {
            $user = auth('clients')->user();
            //  $orderObj = Order::create($request->except(['token']));
            $request['order_state'] = 1;
            $request['arrived_code'] = rand(111111, 999999);
            $request['delivery_code'] = rand(111111, 999999);

            //$orderobj = Order::new($request->except(['token']));
            $order = $user->orders()->create($request->except(['token']));

            $allowedfileExtension = ['pdf', 'jpg', 'png'];
            if ($request->hasFile('images')) {
                foreach ($request->images as $mediaFiles) {

                    $destination = public_path() . '/images/orders';  // upload images in public path images/events

                    File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                    $imageName = microtime(time()) . "_" . $mediaFiles->getClientOriginalName();
                    $image = new Image();
                    $image->url = $destination;
                    $mediaFiles->move($destination, $imageName);
                    $order->image()->save($image);
                }

            }
            //fir notification to all mandubs match cartireia
            //TODO:: get all mandubs match  cartiraia
            $mandubsIDs = Mandub::where('shipping_type_id', $request->shipping_type_id)
                ->where('gender', $request->mandub_gender)
                ->where('payment_type', $request->payment_type)
                ->where('active_now', 1)
                ->pluck('id');

            if(!count($mandubsIDs)>0){
                $this->initResponse('no mandubs match carteria', null, 200, 'data');
                return response()->json($this->response, $this->code);
            }
            if ($mandubsIDs) {

                foreach ($mandubsIDs as $mandubId) {
                    MandubOrders::create([
                        'order_id' => $order->id,
                        'order_state' => 1,
                        'mandub_id' => $mandubId
                    ]);
                    //insert notification in database
                    Notification::insert([
                        'order_id' => $order->id,
                        'mandub_id' => $mandubId,
                        'client_id' => $user->id,
                        'title' => 'new order*',
                        'content' => $this->transNotification(1).$user->name
                    ]);
                }
            }
            //TODO:: send notification
            //get tokens
            $deviceTokens = Mandub::whereIn('id', $mandubsIDs->toArray())
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token')->toArray();
            //notification
            $notification['title'] = 'new order';
            $notification['body'] = $this->transNotification(1).$user->name;
            $data = [
                'order_id' => $order->id,
                'client_name' => $user->name,
                'order_price' => $order->price,
                'order_code' => $order->code
            ];
            $report = $this->sendMultiNotification($notification, $deviceTokens, $data);

            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            $this->initResponse('', $data, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * user  complete registration
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @return Response
     */
    public function completeRegister(ClientCompleteRegisterRequest $request)
    {
        try {
            $user = auth('clients')->user();
            $user->update($request->except(['token']));
            //upload profile_image if exist
            if ($request->hasFile('profile_image')) {
                $destination = public_path() . '/images/clients';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->profile_image->getClientOriginalName();

                $user->update(['profile_image' => $imageName]);

                $request->profile_image->move($destination, $imageName);
            }
            //upload national_id_front_image if exist
            if ($request->hasFile('national_id_front_image')) {
                $destination = public_path() . '/images/clients';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->national_id_front_image->getClientOriginalName();

                $user->update(['national_id_front_image' => $imageName]);

                $request->national_id_front_image->move($destination, $imageName);
            }
            //upload national_id_back_image if exist
            if ($request->hasFile('national_id_back_image')) {
                $destination = public_path() . '/images/clients';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->national_id_back_image->getClientOriginalName();

                $user->update(['national_id_back_image' => $imageName]);

                $request->national_id_back_image->move($destination, $imageName);
            }
            //set complete register flag true if user updated
            $user->update(['complete_register' => 1]);
            //TODO send sms to verify mobile number

            $this->initResponse('', $user, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * Display a listing of the client orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrders()
    {
        try {

            $user = auth('clients')->user();
            $orders = $user->orders->all();
            if ($orders) {
                $data = ['status' => true, 'msg' => 'Success.', 'orders' => $orders];
            } else {
                $data = ['status' => false, 'msg' => 'No orders available for shipping.'];
            }
            $this->initResponse('suessfulley listed', $data, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * filter  client orders .
     *
     * @return \Illuminate\Http\Response
     */
    public function filterOrders(Request $request)
    {
        try {
            $user =  auth('clients')->user();
            $orders = Order::where('client_id', $user->id);//$user->orders;
            if ($request->state) {
                $orders->where('order_state', $request->state);
            }
            //start date
            if ($request->start_date) {
                $start = date($request->start_date);
                $orders->where('created_at', '>=', $start);
            }
            //end date
            if ($request->end_date) {
                $end = date($request->end_date);
                $orders->where('created_at', '<=', $end);
            }

            //start end
            $orders = $orders->get();

            if ($orders) {
                $data = ['status' => true, 'msg' => 'Success.', 'orders' => $orders];
            } else {
                $data = ['status' => false, 'msg' => 'No orders available.'];
            }
            $this->initResponse('suessfulley listed', $data, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * filter  client orders .
     *
     * @return \Illuminate\Http\Response
     */
    public function Mandubs($orderId)
    {
        try {
            $order = Order::where('id', $orderId)->first();
            //gender
            //return mandub match order cartira
            $mandubs = Mandub::orderBy('id', 'DESC');
            if ($order->mandub_gender) {
                $mandubs->where('gender', $order->mandub_gender);
            }
            if ($order->shipping_type_id) {
                $mandubs->where('shipping_type_id', $order->shipping_type_id);
            }
            if ($order->payment_type) {
                $mandubs->where('payment_type', $order->payment_type);
            }
            $mandubs = $mandubs->get();
            if ($mandubs) {
                $data = ['status' => true, 'msg' => 'Success.', 'data' => $mandubs];
            } else {
                $data = ['status' => false, 'msg' => 'No mandubs available.'];
            }
            $this->initResponse('suessfulley listed', $data, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * mandub accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptMandub(Request $request)
    {
        try {
            $mandub = Mandub::find($request->mandubId);
            $user =  auth('clients')->user();
            if (!$mandub) {
                $this->initResponse('no mandub with this id' . $request->mandubId, null, 200, 'data');
            } else {
                $order = Order::find($request->orderId);
                $order->update(['order_state' => 3,
                    'mandub_id' => $request->mandubId
                ]);

                //TODO:: send notification
                //get token
                //send notification to client
                $deviceTokens = Mandub::where('id', $request->mandubId)
                    ->where('fcm_token', '!=', null)
                    ->pluck('fcm_token');
                //notification
                $notification['title'] = 'new notification';
                $notification['body'] = $this->transNotification(3);
                $data = [
                    'order_id' => $order->id,
                    'order_price' => $order->price,
                    'order_state' => 3,
                    'order_code' => $order->code
                ];
                //insert notification in database
                Notification::insert([
                    'order_id' => $order->id,

                    'mandub_id' => $request->mandubId,
                    'client_id' => $user->id,
                    'title' => 'client accept mandub',
                    'content' => $this->transNotification(3)
                ]);
                $report = $this->sendNotification($notification, $deviceTokens[0], $data);
                $data = [
                    'order' => $order,
                    'notification_report' => $report
                ];
                //TODO:: remove all new order requests for another mandubs
                MandubOrders::where('order_id', $order->id)->delete();
                if ($order) {
                    $this->initResponse('success ', $data, 200, 'data');
                } else {
                    $this->initResponse('faild to update state', null, 200, 'data');
                }
            }

        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * mandub accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function showOrder(Request $request)
    {
        try {
            $order = Order::where('id', $request->orderId)->where('client_id', auth('clients')->user()->id)->first();
            if ($order) {
                $this->initResponse('success ', $order, 200, 'data');
            } else {
                $this->initResponse('no order with this code', null, 200, 'data');
            }
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * mandub accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function trackOrder(Request $request)
    {
        try {
            $order = Order::find($request->orderId);

            if ($order) {
                $mandub = $order->mandub;
                $data = [
                    'order_state' => $order->order_state_type,
                    'mandub' => $mandub ? $mandub : null
                ];
                $this->initResponse('success ', $data, 200, 'data');
            } else {
                $this->initResponse('no order with this code', null, 200, 'data');
            }
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * Display the specified resource.
     *
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $manub = Client::find($id);
            $this->initResponse('success', $manub, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * Display the specified resource.
     *
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function showNotification($id)
    {
        try {
            $manubNotifiction = Client::find($id)->notifications()->get();
            if (count($manubNotifiction) < 0) {
                $this->initResponse('no data', null, 200, 'data');
            } else {
                $this->initResponse('success', $manubNotifiction, 200, 'data');
            }

        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * Display the specified resource.
     *
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function rateMandub(MandubRateRequest $request)
    {
        try {
            $user = auth('clients')->user();
            $data = MandubRate::create([
                'client_id' => $user->id,
                'mandub_id' => $request->mandub_id,
                'rate' => $request->rate,
                'comment' => $request->comment
            ]);
            if ($data) {
                $this->initResponse('success', $data, 200, 'data');
            }

        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }
    /**
     * Display the specified resource.
     *
     * @param \App\Models\Api\Area $area
     * @return \Illuminate\Http\Response
     */
    public function showMandub($id)
    {
        try {
            $manub = Mandub::find($id);
            $this->initResponse('success', $manub, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }
    /**
     * Display the specified resource.
     *
     * @param \App\Models\Api\Area $area
     * @return \Illuminate\Http\Response
     */
    public function getMandubs()
    {
        try {
            $manubs = Mandub::get();
            $this->initResponse('success', $manubs, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessages()
    {
        try{
            $data = Message::get();

            $this->initResponse('suessfulley listed',$data, 200, 'data');
        }
        catch(Exception $e){
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }



}
