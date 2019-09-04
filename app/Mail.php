<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subject', 'html_content', 'text_content', 'from_email_id', 'reply_to_email_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromEmailAddress()
    {
        return $this->belongsTo(EmailAddress::class, 'from_email_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replyToEmailAddress()
    {
        return $this->belongsTo(EmailAddress::class, 'reply_to_email_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
