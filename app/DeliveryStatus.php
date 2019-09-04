<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DeliveryStatus
 *
 * @property int $id
 * @property string $status
 * @property string|null $details
 * @property int $driver_id
 * @property int $delivery_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Delivery $delivery
 * @property-read \App\Driver $driver
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeliveryStatus extends BaseModel
{
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
