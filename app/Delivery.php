<?php

namespace App;

use App\Jobs\ProcessSendMail;
use App\MailConnector\Contracts\DeliveryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends BaseModel implements DeliveryInterface
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

    /**
     * Get the from email.
     *
     * @return array
     */
    public function getFromEmail()
    {
        return $this->mail->fromEmailAddress ? [
            'name' => $this->mail->fromEmailAddress->name,
            'email' => $this->mail->fromEmailAddress->email
        ] : null;
    }

    /**
     * Get the reply-to email.
     *
     * @return array
     */
    public function getReplyToEmail()
    {
        return $this->mail->replyToEmailAddress ? [
            'name' => $this->mail->replyToEmailAddress->name,
            'email' => $this->mail->replyToEmailAddress->email
        ] : null;
    }

    /**
     * Get the to email address.
     *
     * @return array
     */
    public function getToEmail()
    {
        return $this->toEmail ? [
            'name' => $this->toEmail->name,
            'email' => $this->toEmail->email
        ] : null;
    }

    /**
     * Get the mail subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->mail->subject;
    }

    /**
     * Get the text content of the mail.
     *
     * @return string
     */
    public function getTextContent()
    {
        return $this->mail->textContent;
    }

    /**
     * Get the html content of the mail.
     *
     * @return string
     */
    public function getHtmlContent()
    {
        return $this->mail->htmlContent;
    }

    /**
     * Get the attachments of the mail.
     *
     * @return array
     */
    public function getAttachments()
    {
        $attachments = [];
        foreach ($this->mail->attachments as $attachment) {

            $attachments[] = [
                'content' => $attachment->content,
                'type' => $attachment->type,
                'filename' => $attachment->filename,
            ];

        }
        return $attachments;
    }
}
