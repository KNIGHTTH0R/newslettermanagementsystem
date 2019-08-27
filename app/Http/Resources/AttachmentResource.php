<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AttachmentResource extends Resource
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
            'filename' => $this->filename,
            'type' => $this->type,
            'content' => $this->content,
            'mail_id' => $this->mail_id,
            'created_at' => $this->created_at->format("Y-m-d H:i:s"),
        ];
    }
}
