<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Content
 *
 * @property-read \App\Mail $mail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $type
 * @property string $value
 * @property int $mail_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content whereMailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Content whereValue($value)
 */
class Content extends Model
{
    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
