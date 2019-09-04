<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'type', 'content', 'mail_id'];

    /**
     * @return BelongsTo
     */
    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
