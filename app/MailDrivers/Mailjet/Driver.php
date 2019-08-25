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
    /** @var $api_key String Mailjet Public API Key */
    private $api_pub_key;

    /** @var $api_key String Mailjet Private API Key */
    private $api_pri_key;

    /** @var $api_key String Mailjet API Send Address */
    private $send_address = "https://api.mailjet.com/v3.1/send";

    /**
     * Driver constructor.
     * @param $api_key
     */
    public function __construct($api_pub_key = null, $api_pri_key = null)
    {
        if (!$api_pub_key)
            $api_pub_key = env("MJ_APIKEY_PUBLIC");
        if (!$api_pri_key)
            $api_pri_key = env("MJ_APIKEY_PRIVATE");

        $this->setApiPubKey($api_pub_key);
        $this->setApiPriKey($api_pri_key);
    }

    /**
     * @param String $api_pub_key
     */
    public function setApiPubKey(String $api_pub_key): void
    {
        $this->api_pub_key = $api_pub_key;
    }

    /**
     * @param String $api_pri_key
     */
    public function setApiPriKey(String $api_pri_key): void
    {
        $this->api_pri_key = $api_pri_key;
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
            $result = $client->post($this->send_address, [
                'auth' => [$this->api_pub_key, $this->api_pri_key],
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->jsonHelperMail($delivery)
            ]);

            $res = json_decode($result->getBody());

            Log::debug($result->getBody());

            return [
                'status' => $res->Messages[0]->Status == "success" ? "Sent":"Error",
                'code' => $result->getStatusCode(),
                'message_id' => $res->Messages[0]->To[0]->MessageUUID,
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
                                'Email' => $delivery->to_email->email,
                                'Name' => $delivery->to_email->name,
                            ]
                        ],
                        //'ReplyTo' => $mail->replyToEmailAddress ? $this->jsonHelperEmailAddress($mail->replyToEmailAddress) : null,
                        'Subject' => $mail->subject ?: null,
                        'TextPart' => $mail->text_content,
                        'HTMLPart' => $mail->html_content,
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
