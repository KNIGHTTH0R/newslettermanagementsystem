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
        return [
            'id' => $this->id,
            'status' => $this->status,
            'details' => $this->details,
            'driver' => $this->driver->name,
            'delivery_id' => $this->deliveryId,
            'created_at' => $this->createdAt->format("Y-m-d H:i:s"),
            'deliveryId' => $this->deliveryId,
            'createdAt' => $this->createdAt->format("Y-m-d H:i:s"),
        ];
    }
}
