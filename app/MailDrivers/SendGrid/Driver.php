<?php


namespace App\MailDrivers\SendGrid;


use App\Attachment;
use App\Delivery;
use App\EmailAddress;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class Driver
{
    /** @var $apiKey String SendGrid API Key */
    private $apiKey;

    /** @var $apiKey String SendGrid API Send Address */
    private $sendAddress = "https://api.sendgrid.com/v3/mail/send";

    /**
     * Driver constructor.
     * @param $apiKey
     */
    public function __construct($apiKey = null)
    {
            $this->apiKey = $apiKey ?: env("SENDGRID_API_KEY");
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
            $result = $client->post($this->sendAddress, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->jsonHelperMail($delivery)
            ]);

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
                'details' => $e->getMessage(),
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
            array_filter([
                'from' => $mail->fromEmailAddress ? $this->jsonHelperEmailAddress($mail->fromEmailAddress) : null,
                'personalizations' => [
                    [
                        'to' => [
                            [
                                'email' => $delivery->toEmail->email,
                                'name' => $delivery->toEmail->name,
                            ]
                        ]
                    ]
                ],
                'reply_to' => $mail->replyToEmailAddress ? $this->jsonHelperEmailAddress($mail->replyToEmailAddress) : null,
                'subject' => $mail->subject ?: null,
                'content' => [
                    ['type' => "text/plain", 'value' => $mail->textContent],
                    ['type' => "text/html", 'value' => $mail->htmlContent],
                ],
                'attachments' => $mail->attachments ? array_map(array($this, 'jsonHelperAttachment'), $mail->attachments->all()) : null
            ]);
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
