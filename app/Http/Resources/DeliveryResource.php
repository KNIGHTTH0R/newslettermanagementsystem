<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DeliveryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'mail_id' => $this->mail->id,
            'mailId' => $this->mail->id,
            'subject' => $this->mail->subject,
            'text_content' => $this->mail->textContent,
            'textContent' => $this->mail->textContent,
            'html_content' => $this->mail->htmlContent,
            'htmlContent' => $this->mail->htmlContent,
            'from_email' => $this->mail->fromEmailAddress->email,
            'fromEmail' => $this->mail->fromEmailAddress->email,
            'reply_to_email' => optional($this->mail->replyToEmailAddress)->email,
            'replyToEmail' => optional($this->mail->replyToEmailAddress)->email,
            'to_email' => $this->toEmail->email,
            'toEmail' => $this->toEmail->email,
            'status' => $this->getLatestStatus() ?: "In queue",
            'has_attachment' => $this->mail->attachments()->exists(),
            'hasAttachment' => $this->mail->attachments()->exists(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
