<?php


namespace App\MailConnector\Contracts;


interface DeliveryInterface
{
    /**
     * Get the from email.
     *
     * @return array
     */
    public function getFromEmail();

    /**
     * Get the reply-to email.
     *
     * @return array
     */
    public function getReplyToEmail();

    /**
     * Get the to email address.
     *
     * @return array
     */
    public function getToEmail();

    /**
     * Get the mail subject.
     *
     * @return string
     */
    public function getSubject();

    /**
     * Get the text content of the mail.
     *
     * @return string
     */
    public function getTextContent();

    /**
     * Get the html content of the mail.
     *
     * @return string
     */
    public function getHtmlContent();

    /**
     * Get the attachments of the mail.
     *
     * @return array
     */
    public function getAttachments();
}
