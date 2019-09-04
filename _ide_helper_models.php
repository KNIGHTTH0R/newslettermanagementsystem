<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Mail
 *
 * @property int $id
 * @property string $subject
 * @property string|null $html_content
 * @property string|null $text_content
 * @property int $from_email_id
 * @property int|null $reply_to_email_id
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
 */
	class Mail extends \Eloquent {}
}

namespace App{
/**
 * App\DeliveryStatus
 *
 * @property int $id
 * @property string $status
 * @property string|null $details
 * @property int $driver_id
 * @property int $delivery_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Delivery $delivery
 * @property-read \App\Driver $driver
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryStatus whereUpdatedAt($value)
 */
	class DeliveryStatus extends \Eloquent {}
}

namespace App{
/**
 * App\Delivery
 *
 * @property int $id
 * @property string|null $message_id
 * @property int $mail_id
 * @property int $to_email_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Mail $mail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DeliveryStatus[] $statuses
 * @property-read \App\EmailAddress $toEmail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereMailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereToEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereUpdatedAt($value)
 */
	class Delivery extends \Eloquent {}
}

namespace App{
/**
 * App\Driver
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Delivery[] $deliveries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DeliveryStatus[] $deliveryStatuses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereUpdatedAt($value)
 */
	class Driver extends \Eloquent {}
}

namespace App{
/**
 * App\EmailAddress
 *
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Delivery[] $deliveries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mail[] $fromMails
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mail[] $replyToMails
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailAddress whereUpdatedAt($value)
 */
	class EmailAddress extends \Eloquent {}
}

namespace App{
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
 */
	class Attachment extends \Eloquent {}
}

namespace App{
/**
 * App\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BaseModel query()
 */
	class BaseModel extends \Eloquent {}
}

