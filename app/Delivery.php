<?php

namespace App;

use App\Jobs\ProcessSendMail;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Delivery
 *
 * @property int $id
 * @property string|null $messageId
 * @property int $mail_id
 * @property int $to_email_id
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
class Delivery extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mail_id', 'to_email_id'];

    public static function boot()
    {
        parent::boot();

        //once created/inserted successfully, send the delivery to API services
        static::created(function ($model) {
            ProcessSendMail::dispatch($model);
        });
    }

    public function mail()
    {
        return $this->belongsTo(Mail::class, 'mail_id');
    }

    public function statuses()
    {
        return $this->hasMany(DeliveryStatus::class, 'delivery_id');
    }

    public function to_email()
    {
        return $this->belongsTo(EmailAddress::class, 'to_email_id');
    }

    public function getLatestStatus()
    {
        return optional($this->statuses()->orderByDesc('id')->first())->status;
    }
}
