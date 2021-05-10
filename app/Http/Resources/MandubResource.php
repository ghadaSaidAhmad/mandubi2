<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MandubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'email' => $this->email,
            'profile_image'=> $this->profile_image,
            'national_id_front_image'=> $this->national_id_front_image,
            'national_id_back_image'=> $this->national_id_back_image,
            'phone'=> $this->phone,
            'location_lang'=> $this->location_lang,
            'location_lat'=> $this->location_lat,
            'active_now'=> $this->active_now,
            'whats_number'=> $this->whats_number,
            'gender'=> $this->gender,
            'shipping_type_id'=> $this->shipping_type_id,
            'governorate_id'=> $this->governorate_id,
            'payment_type'=> $this->payment_type,
            'shipping_method'=> $this->shipping_method,
            'admin_agree'=> $this->admin_agree,
            'complete_register'=> $this->complete_register,
            'balance'=> $this->balance,
            'fcm_token'=> $this->fcm_token,
            'local'=> $this->local,
        ];
    }
}
