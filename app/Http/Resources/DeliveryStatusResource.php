<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DeliveryStatusResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'status' => $this->status,
            'details' => $this->details,
            'driver' => $this->driver->name,
            'delivery_id' => $this->delivery_id,
            'created_at' => $this->created_at,
        ];
    }
}
