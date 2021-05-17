<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mandub;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Client;
use App\Models\Setting;
use App\Models\MandubBalance;
use App\Models\ClientRate;
use App\Models\Message;
use App\Models\Notification;
use App\Http\Requests\SetActiveRequest;
use App\Http\Requests\ClientRateRequest;
use App\Http\Resources\MandubResource;
use Auth;

class MandubController extends Controller
{
    /**
     * updateFcm accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function updateFcmToken(Request $request)
    {
        try {
            $user = auth('mandubs')->user();
            $user->update([
                'fcm_token' => $request->fcm_token
            ]);
            $this->initResponse('', 'token updated successfully ', 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * set mandub active and update his location
     * @return \Illuminate\Http\Response
     */

    public function getAll()
    {
        try {

            $mandubs = Mandub::where('active_now', '1');
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
     * set mandub active and update his location
     * @return \Illuminate\Http\Response
     */

    public function setActive(SetActiveRequest $request)
    {
        //set mandub active and update his location
        try {
            $user = auth('mandubs')->user();
            $user->update([
                'location_lang' => $request->location_lang,
                'location_lat' => $request->location_lat,
                'active_now' => $request->active_now
            ]);
            $this->initResponse('', $user, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
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

            $user = auth('mandubs')->user();

            $newOrdersIds = $user->newOrders->pluck('id');
            $orders = $user->orders;
            $newOrdersIds = $user->newOrders->pluck('order_id');
            $newOrders = Order::whereIn('id', $newOrdersIds)->get();
            if ($newOrders) {

                $orders = $orders->merge($newOrders);
            }
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

            $user = auth('mandubs')->user();
            $orders = Order::where('mandub_id', $user->id);//$user->orders;
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

            //append new ordder
            $newOrdersIds = $user->newOrders->pluck('order_id');
            $newOrders = Order::whereIn('id', $newOrdersIds);
            //dd($newOrdersIds);
            //start date
            if ($request->start_date) {
                $start = date($request->start_date);
                $newOrders->where('created_at', '>=', $start);
            }
            //end date
            if ($request->end_date) {
                $end = date($request->end_date);
                $newOrders->where('created_at', '<=', $end);
            }
            $newOrders = $newOrders->get();

            if ($newOrders) {

                $orders = $orders->merge($newOrders);
            }
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
     * get state of mandub.
     *
     * @return \Illuminate\Http\Response
     */
    public function getState()
    {
        try {
            $user = auth('mandubs')->user();
            // dd($user->orders()->count());
            $order = $user->orders()->orderBy('updated_at', 'desc')->first();
            $data = [
                'orderId' => $order->id,
                'order_state_type' => $order->order_state_type->description_ar
            ];
            if ($order) {
                $this->initResponse('success ', $data, 200, 'data');
            } else {
                $this->initResponse('no orders for this mandub', null, 200, 'data');
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
    public function acceptOrder(Request $request)
    {
        try {
            $user = auth('mandubs')->user();
            $order = Order::find($request->orderId);
            $order->where('id', $request->orderId)->update(['order_state' => 2]);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Client::where('id', $order->client_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(2);
            //insert notification in database
            Notification::insert([
                'order_id' => $order->id,

                'mandub_id' => $user->id,
                'client_id' => $order->client_id,

                'title' => 'accept order',
                'content' => $this->transNotification(2)
            ]);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 2,
                'order_code' => $order->code
            ];
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);
            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            if ($order) {
                $this->initResponse('state updatd success  ', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
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
    public function startTrip(Request $request)
    {
        //dd('sdsd');
        try {
            $user = auth('mandubs')->user();
            $order = Order::find($request->orderId);
            if(!$order){
                $this->initResponse('faild','no order with this id', 400, 'error');
                return response()->json($this->response, $this->code);
            }
            $order->update(['order_state' => 4]);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Client::where('id', $order->client_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            if(!$deviceTokens){
                $this->initResponse('faild','no client with this id', 400, 'error');
                return response()->json($this->response, $this->code);
            }
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(4);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 4,
                'order_code' => $order->code
            ];
            //insert notification in database
            Notification::insert([
                'order_id' => $order->id,
                'mandub_id' => $user->id,
                'client_id' => $order->client_id,
                'title' => 'mandub strat trip',
                'content' => $this->transNotification(4)
            ]);
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);
            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            if ($order) {
                $this->initResponse('success ', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
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
    public function arrived(Request $request)
    {
        try {
            $user = auth('mandubs')->user();
            $order = Order::find($request->orderId);
            $order->update(['order_state' => 5]);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Client::where('id', $order->client_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(5);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 5,
                'order_code' => $order->code
            ];
            //insert notification in database
            $user = auth('mandubs')->user();
            Notification::insert([
                'order_id' => $order->id,

                'mandub_id' => $user->id,
                'client_id' => $order->client_id,
                'title' => 'mandub strat trip',
                'content' => $this->transNotification(5)
            ]);
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);
            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            if ($order) {
                $this->initResponse('success ', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
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
    public function enterArrivedCode(Request $request)
    {
        try {
            $order = Order::find($request->orderId);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Mandub::where('id', $order->mandub_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(6);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 6,
                'order_code' => $order->code
            ];
            //insert notification in database
            $user = auth('mandubs')->user();
            Notification::insert([
                'order_id' => $order->id,

                'mandub_id' => $user->id,
                'client_id' => $order->client_id,
                'title' => $this->transNotification(6),
                'content' => $this->transNotification(6)
            ]);
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);

            //update order state if ccode is correct
            if ($order->arrived_code == $request->arrived_code) {
                $updateOrder = Order::where('id', $request->orderId)->update(['order_state' => 6]);
                $data = [
                    'order' => $order,
                    'notification_report' => $report
                ];
                $this->initResponse('correct code  ', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
            }
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }

        return response()->json($this->response, $this->code);
    }

    /**
     * mandub cancel order
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTrip(Request $request)
    {
        try {
            $order = Order::find($request->orderId);
            $order->update(['order_state' => 7]);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Client::where('id', $order->client_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(7);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 7,
            ];
            $user = auth('mandubs')->user();
            Notification::insert([
                'order_id' => $order->id,

                'mandub_id' => $user->id,
                'client_id' => $order->client_id,
                'title' => $this->transNotification(7),
                'content' => $this->transNotification(7)
            ]);
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);
            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            if ($order) {
                $this->initResponse('success', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
            }
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }

        return response()->json($this->response, $this->code);
    }

    /**
     * mandub cancel order
     *
     * @return \Illuminate\Http\Response
     */
    public function backToClient(Request $request)
    {
        try {
            $order = Order::find($request->orderId);
            $order->update(['order_state' => 8]);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Client::where('id', $order->client_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(8);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 8,
            ];
            $user = auth('mandubs')->user();
            Notification::insert([
                'order_id' => $order->id,

                'mandub_id' => $user->id,
                'client_id' => $order->client_id,
                'title' => $this->transNotification(8),
                'content' => $this->transNotification(8)
            ]);
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);
            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            if ($order) {
                $this->initResponse('success', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
            }
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }

        return response()->json($this->response, $this->code);
    }

    /**
     * mandub cancel order
     *
     * @return \Illuminate\Http\Response
     */
    public function mandubArrived(Request $request)
    {
        try {
            $order = Order::find($request->orderId);
            $order->update(['order_state' => 8]);
            //TODO:: send notification
            //get tokens
            //send notification to client
            $deviceTokens = Client::where('id', $order->client_id)
                ->where('fcm_token', '!=', null)
                ->pluck('fcm_token');
            //notification
            $notification['title'] = 'new notification';
            $notification['body'] = $this->transNotification(9);
            $data = [
                'order_id' => $order->id,
                'order_price' => $order->price,
                'order_state' => 9,
            ];
            $user = auth('mandubs')->user();
            Notification::insert([
                'order_id' => $order->id,

                'mandub_id' => $user->id,
                'client_id' => $order->client_id,
                'title' => $this->transNotification(9),
                'content' => $this->transNotification(9)
            ]);
            $report = $this->sendNotification($notification, $deviceTokens[0], $data);
            $data = [
                'order' => $order,
                'notification_report' => $report
            ];
            if ($order) {
                $this->initResponse('success', $data, 200, 'data');
            } else {
                $this->initResponse('faild to update state', null, 200, 'data');
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
    public function enterDeliveryCode(Request $request)
    {
        try {
            $orderobj = Order::where('id', $request->orderId)->first();

            $setting = Setting::first();
            //update order state if ccode is correct
            if ($orderobj->delivery_code == $request->delivery_code) {
                $order = Order::where('id', $request->orderId)->update(['order_state' => 9]);
                $commisson = ($orderobj->price * ($setting->commission / 100));
                //insert in mandub balance new
                $balance = MandubBalance::create([
                    'order_id' => $request->orderId,
                    'date' => $orderobj->created_at,
                    'sender' => $orderobj->client->name,
                    'location_from' => $orderobj->from_title,
                    'location_to' => $orderobj->to_title,
                    'product_price' => $orderobj->product_price,
                    'shipping_cost' => $orderobj->price,  //100
                    'mandub_id' => $orderobj->mandub_id,
                    'commission_ratio' => $setting->commission, //10%
                    'commission' => $commisson, //10
                    'net_profit' => $orderobj->price - $commisson,
                ]);
                //TODO:: increse mandun balanace
                $user =auth('mandubs')->user();
                $mandub = Mandub::where('id', $user->id)->first();
                $mandub->update([
                    'balance' => $mandub->balance + $commisson
                ]);
                //TODO:: send notification
                //get tokens
                //send notification to client
                $deviceTokens = Mandub::where('id', $orderobj->client_id)
                    ->where('fcm_token', '!=', null)
                    ->pluck('fcm_token');
                //notification
                $notification['title'] = 'new notification';
                $notification['body'] = $this->transNotification(10);
                $data = [
                    'order_id' => $orderobj->id,
                    'order_price' => $orderobj->price,
                    'order_state' => 10,
                ];
                $user = auth('mandubs')->user();
                Notification::insert([
                    'order_id' => $orderobj->id,


                    'mandub_id' => $user->id,
                    'client_id' => $orderobj->client_id,
                    'title' => $this->transNotification(10),
                    'content' => $this->transNotification(10)
                ]);
                $report = $this->sendNotification($notification, $deviceTokens[0], $data);
                $data = [
                    'order' => $orderobj,
                    'notification_report' => $report
                ];
                $this->initResponse('correct code  ', $data, 200, 'data');
            } else {
                $this->initResponse('uncorrect code', null, 200, 'data');
            }
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }

        return response()->json($this->response, $this->code);
    }

    /**
     * mandub show order
     *
     * @return \Illuminate\Http\Response
     */
    public function showOrder(Request $request)
    {
        try {
            //TODO:: check if mandun recive order
            $order = Order::where('id', $request->orderId)->first();
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
     * filter  client orders .
     *
     * @return \Illuminate\Http\Response
     */
    public function getMandubBalance(Request $request)
    {
        try {
            $user = auth('mandubs')->user();

            $orders = MandubBalance::join('orders', 'mandub_balance.order_id', '=', 'orders.id')->where('mandub_balance.mandub_id', $user->id);//$user->orders;
            if ($request->state) {
                $orders->where('order_state', $request->state);
            }
            //start date
            if ($request->start_date) {
                $start = date($request->start_date);
                $orders->where('mandub_balance.created_at', '>=', $start);
            }
            //end date
            if ($request->end_date) {
                $end = date($request->end_date);
                $orders->where('mandub_balance.created_at', '<=', $end);
            }
            //start end
            $orders = $orders->get(['mandub_balance.*']);
            if (count($orders) > 0) {
                $data = ['status' => true, 'msg' => 'Success.', 'mandub_balbces' => $orders];
            } else {
                $data = ['status' => false, 'msg' => 'No orders available.'];
            }
            $this->initResponse('suessfulley listed', $data, 200, 'mandub_balbces');
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
    public function show($id)
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
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function showNotification($id)
    {
        try {
            $manubNotifiction = Mandub::find($id)->notifications()->get();
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
    public function rateClient(ClientRateRequest $request)
    {
        try {
            $user = auth('mandubs')->user();
            $data = ClientRate::create([
                'mandub_id' => $user->id,
                'client_id' => $request->client_id,
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
    public function showClient($id)
    {
        try {
            $client = client::find($id);
            $this->initResponse('success', $client, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * updateFcm accecept order
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(Request $request)
    {
        try {
            $user = auth('mandubs')->user();
            if(!$user){
                $this->initResponse('', 'no mandub with this token ', 404, 'data');
                return response()->json($this->response, $this->code);
            }
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function messages()
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