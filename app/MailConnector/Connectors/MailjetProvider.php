<?php


namespace App\MailConnector\Connectors;

use App\Delivery;
use GuzzleHttp\RequestOptions;
use \Illuminate\Http\Request;

class MailjetProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Sends an e-mail to SendGrid API
     *
     * @param Delivery $delivery
     * @return array
     */
    public function send(Delivery $delivery)
    {
        try {
            $result = $this->getHttpClient()->post($this->endpoint, [
                'auth' => [$this->publicKey, $this->privateKey],
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->delivery2array($delivery)
            ]);

            $res = json_decode($result->getBody());

            return [
                'status' => $res->Messages[0]->Status == "success" ? "Sent" : "Error",
                'code' => $result->getStatusCode(),
                'message_id' => substr($res->Messages[0]->To[0]->MessageID, 0, 14),
            ];
        } catch (\Exception $e) {
            \Log::error('Caught exception: ' . $e->getMessage() . "\n");

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
    public function delivery2array(Delivery $delivery)
    {
        return
            [
                'Messages' =>
                    [
                        [
                            'From' => $delivery->getFromEmail() ? $this->jsonHelperEmailAddress($delivery->getFromEmail()) : null,
                            'To' => [
                                [
                                    'Email' => $delivery->getToEmail()['email'],
                                    'Name' => $delivery->getToEmail()['name'],
                                ]
                            ],
                            'ReplyTo' => $delivery->getReplyToEmail() ? $this->jsonHelperEmailAddress($delivery->getReplyToEmail()) : null,
                            'Subject' => $delivery->getSubject() ?: null,
                            'TextPart' => $delivery->getTextContent(),
                            'HTMLPart' => $delivery->getHtmlContent(),
                            'Attachments' => (count($delivery->getAttachments()) > 0) ? array_map(array(
                                $this,
                                'jsonHelperAttachment'
                            ),
                                $delivery->getAttachments()) : null
                        ]
                    ]
            ];
    }

    /**
     * Return an array representing a EmailAddress object for Mailjet API
     *
     * @param array $email_address
     * @return null|array
     */
    public function jsonHelperEmailAddress($email_address)
    {
        return array_filter(
            [
                'Name' => $email_address['name'],
                'Email' => $email_address['email']
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }

    /**
     * Return an array representing a Attachment object
     *
     * @param array $attachment
     * @return null|array
     */
    public function jsonHelperAttachment($attachment)
    {
        return array_filter(
            [
                'Base64Content' => $attachment['content'],
                'ContentType' => $attachment['type'],
                'Filename' => $attachment['filename']
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }


}
