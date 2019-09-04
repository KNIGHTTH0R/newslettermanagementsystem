<?php

namespace Tests\Unit;

use App\Attachment;
use App\Delivery;
use App\EmailAddress;
use App\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $fromEmail = EmailAddress::create([
            'name' => 'Test Test',
            'email' => "test@test.com",
        ]);

        $this->assertEquals('Test Test', $fromEmail->name);
        $this->assertEquals('test@test.com', $fromEmail->email);

        $replyToEmail = EmailAddress::create([
            'name' => 'Test Test',
            'email' => "test@test.com",
        ]);

        $this->assertEquals('Test Test', $replyToEmail->name);
        $this->assertEquals('test@test.com', $replyToEmail->email);

        $mail = Mail::create([
            'subject' => 'Subject',
            'html_content' => '<strong>Hi</strong>',
            'text_content' => 'Hi',
            'from_email_id' => $fromEmail->id,
            'reply_to_email_id' => $replyToEmail->id,
        ]);

        $this->assertEquals('Subject', $mail->subject);
        $this->assertEquals('<strong>Hi</strong>', $mail->htmlContent);
        $this->assertEquals('Hi', $mail->textContent);

        $this->assertEquals($fromEmail->email, $mail->fromEmailAddress->email);
        $this->assertEquals($replyToEmail->email, $mail->replyToEmailAddress->email);

        $attachment = Attachment::create([
            'filename' => "file.txt",
            'type' => "text/plain",
            'content' => "VGhpcyBpcyB5b3VyIGF0dGFjaGVkIGZpbGUhISEK",
            'mail_id' => $mail->id,
        ]);

        $this->assertEquals("file.txt", $mail->attachments[0]->filename);
        $this->assertEquals("text/plain", $mail->attachments[0]->type);
        $this->assertEquals("VGhpcyBpcyB5b3VyIGF0dGFjaGVkIGZpbGUhISEK", $mail->attachments[0]->content);

        $to = EmailAddress::create([
            'name' => 'Test Test',
            'email' => "test@test.com",
        ]);

        $this->assertEquals('Test Test', $to->name);
        $this->assertEquals('test@test.com', $to->email);

        $delivery = Delivery::create([
            'mail_id' => $mail->id,
            'to_email_id' => $to->id,
        ]);

        $this->assertEquals('Test Test', $mail->deliveries[0]->toEmail->name);
        $this->assertEquals('test@test.com', $mail->deliveries[0]->toEmail->email);

        $this->assertTrue($mail->delete());
        $this->assertTrue($to->delete());
        $this->assertTrue($fromEmail->delete());
        $this->assertTrue($replyToEmail->delete());


    }
}
