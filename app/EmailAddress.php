<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAddress extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'to_email_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replyToMails()
    {
        return $this->hasMany(Mail::class, 'reply_to_email_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fromMails()
    {
        return $this->hasMany(Mail::class, 'from_email_id');
    }
}
