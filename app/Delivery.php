<?php

namespace App;

use App\Jobs\ProcessSendMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mail_id', 'to_email_id'];

    /**
     * @return BelongsTo
     */
    public function mail()
    {
        return $this->belongsTo(Mail::class, 'mail_id');
    }

    /**
     * @return HasMany
     */
    public function statuses()
    {
        return $this->hasMany(DeliveryStatus::class, 'delivery_id');
    }

    /**
     * @return BelongsTo
     */
    public function toEmail()
    {
        return $this->belongsTo(EmailAddress::class, 'to_email_id');
    }

    /**
     * @return mixed
     */
    public function getLatestStatus()
    {
        return optional($this->statuses()->orderByDesc('id')->first())->status;
    }
}
