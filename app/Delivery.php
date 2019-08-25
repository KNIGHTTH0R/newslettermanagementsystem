<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Delivery
 *
 * @property int $id
 * @property int $if_terminated
 * @property string|null $message_id
 * @property int $mail_id
 * @property int $to_email_id
 * @property int $assigned_driver_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Driver $driver
 * @property-read \App\Mail $mail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DeliveryStatus[] $statuses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereAssignedDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereIfTerminated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereMailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereToEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\EmailAddress $to_email
 */
class Delivery extends Model
{
    public function mail()
    {
        return $this->belongsTo(Mail::class, 'mail_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }

    public function statuses()
    {
        return $this->hasMany(DeliveryStatus::class);
    }

    public function to_email()
    {
        return $this->belongsTo(EmailAddress::class, 'to_email_id');
    }
}
