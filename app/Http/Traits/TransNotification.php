<?php

namespace App\Http\Traits;

use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Auth;

trait TransNotification
{

    public $notificationTranslation = [
        1 => [1 => "لديك طلب جديدمن قبل", 2 => "You have a new order from : "], //mandub + client name
        2 => [1 => "تمت الموافقه على الرحله من قبل", 2 => "your orders approved by : "], //client mandub name
        3 => [1 => "تم السماح لك بالقيام بالرحله من قبل", 2 => "You are allowed to make the trip "],//mandub
        4 => [1 => "لقد بدأت الرحله من قبل", 2 => "start trip "], //client + mandub name
        5 => [1 => "وصل الى مكان التسليم  ", 2 => "arrived to destenation"], //client + mandub name + arrived code
        6 => [1 => "لقد تم تسليم البضاعه الى ", 2 => "order deliverd"], //client + reciver name
        7 => [1 => "تم الغاء تسليم الاوردر", 2 => "order canceld"], //client
        8 => [1 => "المندوب فى طريق الوصل اليك", 2 => "mandub come back"], // client
        9 => [1 => "لقد وصل المندوب بالفعل لديك ", 2 => "mandub arrived "], // client + arrived code
        10 => [1 => "العميل حصل حق المنتج ", 2 => "successfuly deliverd your mony"], //client


    ];

    /**
     * sendMultiNotification
     * @param @array of notification
     * @param array of device token
     * @return Response
     */
    public function transNotification($notificationSate)
    {
        $local = auth()->user()->local;
        // dd($local);
        return $this->notificationTranslation[$notificationSate][$local];
    }


}