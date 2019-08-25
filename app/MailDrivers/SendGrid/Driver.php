<?php


namespace App\MailDrivers\SendGrid;


use App\Attachment;
use App\Content;
use App\Delivery;
use App\EmailAddress;
use App\Mail;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

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
    public function __construct($api_key = null)
    {
        if (!$api_key)
            $api_key = env("SENDGRID_API_KEY");
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
     * @param Delivery $delivery
     * @return array
     */
    public function send(Delivery $delivery)
    {
        try {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->post($this->send_address, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->jsonHelperMail($delivery)
            ]);

            Log::debug($result->getBody());

            return [
                'status' => $result->getReasonPhrase() == "Accepted" ? "Sent":"Error",
                'code' => $result->getStatusCode(),
                'message_id' => $result->getHeader("X-Message-Id")[0],
            ];
        } catch (\Exception $e) {
            Log::error('Caught exception: ' . $e->getMessage() . "\n");

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
     * @param Delivery $delivery
     * @return array
     */
    public function jsonHelperMail(Delivery $delivery)
    {
        $mail = $delivery->mail;
        return
            [
                'from' => $mail->fromEmailAddress ? $this->jsonHelperEmailAddress($mail->fromEmailAddress) : null,
                'personalizations' => [
                    [
                        'to' => [
                            [
                                'email' => $delivery->to_email->email,
                                'name' => $delivery->to_email->name,
                            ]
                        ]
                    ]
                ],
                'reply_to' => $mail->replyToEmailAddress ? $this->jsonHelperEmailAddress($mail->replyToEmailAddress) : null,
                'subject' => $mail->subject ?: null,
                'content' => [
                    ['type' => "text/plain", 'value' => $mail->text_content],
                    ['type' => "text/html", 'value' => $mail->html_content],
                ],
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
