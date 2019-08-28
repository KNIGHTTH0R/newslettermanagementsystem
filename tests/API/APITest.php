<?php

namespace Tests\API;

use App\Delivery;
use App\EmailAddress;
use App\Mail;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends TestCase
{
    /**
     * Mail sending test
     *
     * @return void
     */
    public function testCanSendMail()
    {
        $payload = '{
            "from": {
                "email": "from@example.com",
                "name": "From Email"
            },
            "replyTo": {
                "email": "replyTo@example.com",
                "name": "ReplyTo Email"
            },
            "to": [
                {
                    "email": "user1@example.com",
                    "name": "User 1"
                },
                {
                    "email": "user2@example.com",
                    "name": "User 2"
                }
            ],
            "subject": "Test subject!",
            "text": "Dear user, May the force be with you!",
            "html": "<h3>Dear user,</h3> May the delivery force be with you!",
            "attachments": [
                {
                    "contentType": "text/plain",
                    "filename": "test.txt",
                    "base64Content": "VGhpcyBpcyB5b3VyIGF0dGFjaGVkIGZpbGUhISEK"
                }
            ]
        }';

        $payload = json_decode($payload, true);

        $this->json('POST',"api/mail", $payload)
            ->assertStatus(201);

    }

    /**
     * Test listing deliveries
     *
     * @return void
     */
    public function testCanListDeliveries()
    {
        $this->json('GET',"api/deliveries")
            ->assertStatus(200)
            ->assertSeeText("Test subject");
    }

    /**
     * Test listing single delivery
     *
     * @return void
     */
    public function testCanListDelivery()
    {
        $this->json('GET',"api/deliveries")
            ->assertStatus(200)
            ->assertSeeText("Test subject");
    }

    /**
     * Test listing delivery statuses
     *
     * @return void
     */
    public function testCanListDeliveryStatus()
    {
        $delivery_id = Delivery::first()->id;
        $this->json('GET',"api/delivery/" . $delivery_id . "/status")
            ->assertStatus(200)
            ->assertSeeText("data");
    }

    /**
     * Test listing mail attachments
     *
     * @return void
     */
    public function testCanListMailAttachment()
    {
        $mail_id = Mail::first()->id;
        $this->json('GET',"api/mail/" . $mail_id . "/attachment")
            ->assertStatus(200)
            ->assertSeeText("data");
    }

    /**
     * Test SendGrid webhook
     *
     * @return void
     */
    public function testSendGridWebhook()
    {
        $payload = '[
                      {
                        "email": "user2@example.com",
                        "event": "dropped",
                        "reason": "Invalid",
                        "sg_event_id": "ZHJvcC0xMjExNjE2My0tUFpqcC1pVFFRV3E1NlQ0NUd2Q09nLTA",
                        "sg_message_id": "-PZjp-iTQQWq56T45GvCOg.filter0067p3las1-29112-5D655833-18.0",
                        "smtp-id": "<-PZjp-iTQQWq56T45GvCOg@ismtpd0002p1lon1.sendgrid.net>",
                        "timestamp": 1566922803
                      }
                    ]';

        $payload = json_decode($payload, true);

        $this->json('POST',"api/webhook/sendgrid", $payload)
            ->assertStatus(200);
    }

    /**
     * Test Mailjet webhook
     *
     * @return void
     */
    public function testMailjetWebhook()
    {
        $payload = '[
                      {
                        "event": "sent",
                        "time": 1566904402,
                        "MessageID": 1152921506127706600,
                        "Message_GUID": "11735e75-7445-487c-a90b-52c18c856b72",
                        "email": "baris.cimen@boun.edu.tr",
                        "mj_campaign_id": 0,
                        "mj_contact_id": 26071287,
                        "customcampaign": "",
                        "smtp_reply": "250 2.0.0 Ok: queued as 30F88223032",
                        "CustomID": "",
                        "Payload": ""
                      }
                    ]';

        $payload = json_decode($payload, true);

        $this->json('POST',"api/webhook/mailjet", $payload)
            ->assertStatus(200);
    }

    /**
     * Clean DB records.
     *
     * @return void
     */
    public function testCleanDB()
    {
        $this->assertIsNumeric(DB::table('mails')->delete());
        $this->assertIsNumeric(DB::table('email_addresses')->delete());
        $this->assertIsNumeric(DB::table('jobs')->delete());

    }
}
