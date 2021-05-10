<?php

namespace App\Http\Traits;

use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MulticastSendReport;

trait FcmNotification
{

    public function __construct(Messaging $messaging)
    {
        $this->messaging = app('firebase.messaging');
    }


    /**
     * sendMultiNotification
     * @param @array of notification
     * @param array of device token
     * @return Response
     */
    public function sendMultiNotification($notification, $deviceTokens,$data =[])
    {
        $notification = Notification::fromArray([
            'title' => $notification['title'],
            'body' => $notification['body'],
        ]);

        $message = CloudMessage::fromArray([
            'notification' => $notification
        ]);
        $message->withData($data);
        $validToken = $this->messaging->validateRegistrationTokens($deviceTokens);
        if(!$validToken){
            return [
                'success'=>faild,
                'message'=>'uncorrect token',
            ];
        }

        $report = $this->messaging->sendMulticast($message, $deviceTokens);
        return [
            'success'=>true,
            'message'=>'successfuly send notification',
            'success_count'=>$report->successes()->count(),
            'faild_count'=>$report->failures()->count(),
        ];

        if ($report->hasFailures()) {
            foreach ($report->failures()->getItems() as $failure) {
               return ['success'=>false,'message'=>'faild  send notification'];
            }
        }

    }

    /**
     * send Notification
     * @param @array of notification
     * @param array of device token
     * @return Response
     */
    public function sendNotification($notification, $deviceToken,$data=[])
    {
        //dd($notification);
        $notification = Notification::fromArray([
            'title' => $notification['title'],
            'body' => $notification['body'],
        ]);
        $validToken = $this->messaging->validateRegistrationTokens($deviceToken);
        if(!$validToken){
            return [
                'success'=>faild,
                'message'=>'uncorrect token',
            ];
        }
         $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => $notification
        ]);

         $message->withData($data);
        //$report = $this->messaging->send($message);
        $report = $this->messaging->sendMulticast($message, $deviceToken);
        return [
            'success'=>true,
            'message'=>'successfuly send notification',
            'success_count'=>$report->successes()->count(),
            'faild_count'=>$report->failures()->count(),
        ];

        if ($report->hasFailures()) {
            foreach ($report->failures()->getItems() as $failure) {
                return ['success'=>false,'message'=>'faild  send notification'];
            }
        }


    }
}