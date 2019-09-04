<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Mail
 *
 * @property int $id
 * @property string $subject
 * @property string|null $html_content
 * @property string|null $text_content
 * @property int $fromEmail_id
 * @property int|null $replyToEmail_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Delivery[] $deliveries
 * @property-read \App\EmailAddress $fromEmailAddress
 * @property-read \App\EmailAddress|null $replyToEmailAddress
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereFromEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereHtmlContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereReplyToEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereTextContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mail extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subject', 'html_content', 'text_content', 'from_email_id', 'reply_to_email_id'];

    public function fromEmailAddress()
    {
        return $this->belongsTo(EmailAddress::class, 'from_email_id');
    }

    public function replyToEmailAddress()
    {
        return $this->belongsTo(EmailAddress::class, 'reply_to_email_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
