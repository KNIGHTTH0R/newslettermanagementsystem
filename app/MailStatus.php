<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MailStatus
 *
 * @property-read \App\Mail $mail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $status
 * @property string|null $details
 * @property int $driver_id
 * @property int $mail_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereMailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailStatus whereUpdatedAt($value)
 * @property-read \App\Driver $driver
 */
class MailStatus extends Model
{
    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
