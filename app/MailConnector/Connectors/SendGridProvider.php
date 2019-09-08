<?php


namespace App\MailConnector\Connectors;

use App\Delivery;
use GuzzleHttp\RequestOptions;

class SendGridProvider extends AbstractProvider implements ProviderInterface
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
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->privateKey,
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::JSON => $this->delivery2array($delivery)
            ]);

            return [
                'status' => $result->getReasonPhrase() == "Accepted" ? "Sent" : "Error",
                'code' => $result->getStatusCode(),
                'message_id' => $result->getHeader("X-Message-Id")[0],
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
     * Return an array representing a Mail object for SendGrid API
     *
     * @param Delivery $delivery
     * @return array
     */
    public function delivery2array(Delivery $delivery)
    {
        return
            array_filter([
                'from' => $delivery->getFromEmail() ? $this->jsonHelperEmailAddress($delivery->getFromEmail()) : null,
                'personalizations' => [
                    [
                        'to' => [
                            [
                                'email' => $delivery->getToEmail()['email'],
                                'name' => $delivery->getToEmail()['name'],
                            ]
                        ]
                    ]
                ],
                'reply_to' => $delivery->getReplyToEmail() ? $this->jsonHelperEmailAddress($delivery->getReplyToEmail()) : null,
                'subject' => $delivery->getSubject() ?: null,
                'content' => [
                    ['type' => "text/plain", 'value' => $delivery->getTextContent()],
                    ['type' => "text/html", 'value' => $delivery->getHtmlContent()],
                ],
                'attachments' => (count($delivery->getAttachments()) > 0) ? array_map(array($this, 'jsonHelperAttachment'),
                    $delivery->getAttachments()) : null
            ]);
    }

    /**
     * Return an array representing a EmailAddress object for SendGrid API
     *
     * @param array $email_address
     * @return null|array
     */
    public function jsonHelperEmailAddress($email_address)
    {
        return array_filter(
            [
                'name' => $email_address['name'],
                'email' => $email_address['email']
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
                'content' => $attachment['content'],
                'type' => $attachment['type'],
                'filename' => $attachment['filename']
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }
}
