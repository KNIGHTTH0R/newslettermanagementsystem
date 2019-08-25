<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Attachment
 *
 * @property int $id
 * @property string $filename
 * @property string $type
 * @property string $content
 * @property int $mail_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Mail $mail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereMailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'type', 'content', 'mail_id'];

    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
