<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Mail
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $subject
 * @property int $if_terminated
 * @property int $from_email_id
 * @property int|null $reply_to_email_id
 * @property int $assigned_driver_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereAssignedDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereFromEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereIfTerminated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereReplyToEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereUpdatedAt($value)
 * @property-read \App\Driver $assignedDriver
 * @property-read \App\EmailAddress $fromEmailAddress
 * @property-read \App\EmailAddress $replyToEmailAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Content[] $contents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MailStatus[] $statuses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmailAddress[] $toEmailAddresses
 */
class Mail extends Model
{
    public function fromEmailAddress()
    {
        return $this->belongsTo(EmailAddress::class, 'from_email_id');
    }

    public function replyToEmailAddress()
    {
        return $this->belongsTo(EmailAddress::class, 'reply_to_email_id');
    }

    public function assignedDriver()
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }

    public function statuses()
    {
        return $this->hasMany(MailStatus::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function toEmailAddresses()
    {
        return $this->hasMany(EmailAddress::class, 'receiving_mail_id');
    }
}
