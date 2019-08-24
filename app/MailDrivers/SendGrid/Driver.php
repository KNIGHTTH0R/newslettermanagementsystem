<?php


namespace App\MailDrivers\SendGrid;


use App\Attachment;
use App\Content;
use App\EmailAddress;
use App\Mail;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Driver
{
    /** @var $api_key String SendGrid API Key */
    private $api_key;

    /** @var $api_key String SendGrid API Send Address */
    private $send_address = "https://api.sendgrid.com/v3/mail/send";

    /**
     * Driver constructor.
     * @param $api_key
     */
    public function __construct($api_key)
    {
        $this->setApiKey($api_key);
    }

    /**
     * @param String $api_key
     */
    public function setApiKey(String $api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Sends an e-mail to SendGrid API
     *
     * @param Mail $mail
     * @return array
     */
    public function send(Mail $mail)
    {
        try {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->post($this->send_address, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->jsonHelperMail($mail)
            ]);

            return [
                'status' => $result->getReasonPhrase(),
                'code' => $result->getStatusCode(),
                'message_id' => $result->getHeader("X-Message-Id")[0],
            ];
        } catch (\Exception $e) {
            //echo 'Caught exception: ' . $e->getMessage() . "\n";

            return [
                'status' => "Fatal Error",
                'code' => 999,
                'message_id' => null,
            ];
        }
    }

    /**
     * Return an array representing a Mail object for SendGrid API
     *
     * @param Mail $mail
     * @return array
     */
    public function jsonHelperMail(Mail $mail)
    {
        return
            [
                'from' => $mail->fromEmailAddress ? $this->jsonHelperEmailAddress($mail->fromEmailAddress) : null,
                'personalizations' => [
                    ['to' => $mail->toEmailAddresses ? array_map(array($this, 'jsonHelperEmailAddress'), $mail->toEmailAddresses->all()) : null]
                ],
                'reply_to' => $mail->replyToEmailAddress ? $this->jsonHelperEmailAddress($mail->replyToEmailAddress) : null,
                'subject' => $mail->subject ?: null,
                'content' => $mail->contents ? array_map(array($this, 'jsonHelperContent'), $mail->contents->all()) : null,
                'attachments' => $mail->attachments ? array_map(array($this, 'jsonHelperAttachment'), $mail->attachments->all()) : null
            ];
    }

    /**
     * Return an array representing a EmailAddress object for SendGrid API
     *
     * @return null|array
     */
    public function jsonHelperEmailAddress(EmailAddress $email_address)
    {
        return array_filter(
            [
                'name' => $email_address->name,
                'email' => $email_address->email
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }

    /**
     * Return an array representing a Content object SendGrid API
     *
     * @return array
     */
    public function jsonHelperContent(Content $content)
    {
        return array_filter(
            [
                'type' => $content->type,
                'value' => $content->value
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }

    /**
     * Return an array representing a Attachment object
     *
     * @return null|array
     */
    public function jsonHelperAttachment(Attachment $attachment)
    {
        return array_filter(
            [
                'content' => $attachment->content,
                'type' => $attachment->type,
                'filename' => $attachment->filename
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }
}
