<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Driver
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MailStatus[] $mailStatuses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mail[] $mails
 */
class Driver extends Model
{
    public function mails()
    {
        return $this->hasMany(Mail::class, 'assigned_driver_id');
    }

    public function mailStatuses()
    {
        return $this->hasMany(MailStatus::class, 'assigned_driver_id');
    }
}
