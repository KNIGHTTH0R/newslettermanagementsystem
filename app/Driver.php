<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Driver
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Delivery[] $deliveries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DeliveryStatus[] $deliveryStatuses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Driver extends Model
{
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function deliveryStatuses()
    {
        return $this->hasMany(DeliveryStatus::class);
    }
}
