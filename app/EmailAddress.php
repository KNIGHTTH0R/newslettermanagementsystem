<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EmailAddress
 *
 * @property-read \App\Mail $mail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property int|null $receiving_mail_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereReceivingMailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mail[] $fromMails
 * @property-read \App\Mail|null $receivingMail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mail[] $replyToMails
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Delivery[] $deliveries
 */
class EmailAddress extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email'];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'to_email_id');
    }

    public function replyToMails()
    {
        return $this->hasMany(Mail::class, 'reply_to_email_id');
    }

    public function fromMails()
    {
        return $this->hasMany(Mail::class, 'from_email_id');
    }
}
