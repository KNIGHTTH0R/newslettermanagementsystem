<?php


namespace App\MailDrivers\Mailjet;


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
    /** @var $apiKey String Mailjet Public API Key */
    private $apiPubKey;

    /** @var $apiKey String Mailjet Private API Key */
    private $apiPriKey;

    /** @var $apiKey String Mailjet API Send Address */
    private $sendAddress = "https://api.mailjet.com/v3.1/send";

    /**
     * Driver constructor.
     * @param $apiKey
     */
    public function __construct($apiPubKey = null, $apiPriKey = null)
    {
        if (!$apiPubKey)
            $this->apiPubKey = env("MJ_APIKEY_PUBLIC");
        if (!$apiPriKey)
            $this->apiPriKey = env("MJ_APIKEY_PRIVATE");

    }

    /**
     * Sends an e-mail to Mailjet API
     *
     * @param Delivery $delivery
     * @return array
     */
    public function send(Delivery $delivery)
    {
        try {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->post($this->sendAddress, [
                'auth' => [$this->apiPubKey, $this->apiPriKey],
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->jsonHelperMail($delivery)
            ]);

            $res = json_decode($result->getBody());

            return [
                'status' => $res->Messages[0]->Status == "success" ? "Sent":"Error",
                'code' => $result->getStatusCode(),
                'message_id' => substr($res->Messages[0]->To[0]->MessageID, 0, 14),
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
     * Return an array representing a Mail object for Mailjet API
     *
     * @param Delivery $delivery
     * @return array
     */
    public function jsonHelperMail(Delivery $delivery)
    {
        $mail = $delivery->mail;
        return
            [
                'Messages' =>
                    [[
                        'From' => $mail->fromEmailAddress ? $this->jsonHelperEmailAddress($mail->fromEmailAddress) : null,
                        'To' => [
                            [
                                'Email' => $delivery->toEmail->email,
                                'Name' => $delivery->toEmail->name,
                            ]
                        ],
                        //'ReplyTo' => $mail->replyToEmailAddress ? $this->jsonHelperEmailAddress($mail->replyToEmailAddress) : null,
                        'Subject' => $mail->subject ?: null,
                        'TextPart' => $mail->textContent,
                        'HTMLPart' => $mail->htmlContent,
                        'Attachments' => $mail->attachments ? array_map(array($this, 'jsonHelperAttachment'), $mail->attachments->all()) : null
                    ]]
            ];
    }

    /**
     * Return an array representing a EmailAddress object for Mailjet API
     *
     * @return null|array
     */
    public function jsonHelperEmailAddress(EmailAddress $email_address)
    {
        return array_filter(
            [
                'Name' => $email_address->name,
                'Email' => $email_address->email
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
                'Base64Content' => $attachment->content,
                'ContentType' => $attachment->type,
                'Filename' => $attachment->filename
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }
}
