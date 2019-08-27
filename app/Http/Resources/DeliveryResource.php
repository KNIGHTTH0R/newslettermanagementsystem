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
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'mail_id' => $this->mail->id,
            'subject' => $this->mail->subject,
            'text_content' => $this->mail->text_content,
            'html_content' => $this->mail->html_content,
            'from_email' => $this->mail->fromEmailAddress->email,
            'reply_to_email' => optional($this->mail->replyToEmailAddress)->email,
            'to_email' => $this->to_email->email,
            'status' => $this->getLatestStatus() ?: "In queue",
            'has_attachment' => $this->mail->attachments()->exists(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
