<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends BaseModel
{
    /**
     * @return HasMany
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * @return HasMany
     */
    public function deliveryStatuses()
    {
        return $this->hasMany(DeliveryStatus::class);
    }
}
