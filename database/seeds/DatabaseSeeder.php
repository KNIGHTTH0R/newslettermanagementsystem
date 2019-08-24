<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('DriverTableSeeder');
        $this->command->info('Drivers table seeded!');
        $this->call('EmailAddressTableSeeder');
        $this->command->info('EmailAddresses table seeded!');
        $this->call('MailTableSeeder');
        $this->command->info('Mails table seeded!');
        $this->call('AttachmentTableSeeder');
        $this->command->info('Attachments table seeded!');
        $this->call('ContentTableSeeder');
        $this->command->info('Contents table seeded!');
    }
}

class DriverTableSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('drivers')->delete();
        \Illuminate\Support\Facades\DB::table('drivers')->insert([
            ['id' => '1', 'name' => 'SendGrid', 'path' => "\App\MailDrivers\SendGrid\Driver", 'priority' => "1"],
            ['id' => '2', 'name' => 'Mailjet', 'path' => "\App\MailDrivers\Mailjet\Driver", 'priority' => "2"],
        ]);
    }
}

class EmailAddressTableSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('email_addresses')->delete();
        \Illuminate\Support\Facades\DB::table('email_addresses')->insert([
            [
                'id' => '1',
                'name' => 'Barış Çimen',
                'email' => 'barcimen@hotmail.com',
                'receiving_mail_id' => null,
            ],
            [
                'id' => '2',
                'name' => 'Barış Çimen',
                'email' => 'barcimen@gmail.com',
                'receiving_mail_id' => '1',
            ],
            [
                'id' => '3',
                'name' => 'Barış Çimen',
                'email' => 'baris.cimen@boun.edu.tr',
                'receiving_mail_id' => '1',
            ],
        ]);
    }
}

class MailTableSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('mails')->delete();
        \Illuminate\Support\Facades\DB::table('mails')->insert([
            [
                'id' => '1',
                'subject' => 'This Is A Mail Subject',
                'from_email_id' => '1',
                //'reply_to_email_id' => '1',
                'assigned_driver_id' => "1",
                'if_terminated' => "0",
            ]
        ]);
    }
}

class AttachmentTableSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('attachments')->delete();
        \Illuminate\Support\Facades\DB::table('attachments')->insert([
            ['id' => '1', 'mail_id' => '1', 'filename' => 'my-file.txt', 'type' => "application/text", 'content' => "TG9yZW0gaXBzdW0gZG9sb3Igc2l0IGFtZXQsIGNvbnNlY3RldHVyIGFkaXBpc2NpbmcgZWxpdC4gUXVpc3F1ZSBzaXQgYW1ldCBsaWd1bGEgc2VkIGxlbyB1bHRyaWNpZXMgdGluY2lkdW50IGEgbmVjIHJpc3VzLiBDdXJhYml0dXIgbWF4aW11cyBmaW5pYnVzIG9yY2kgdXQgZGFwaWJ1cy4gTW9yYmkgbmVjIGVsZWlmZW5kIGZlbGlzLiBBbGlxdWFtIHZ1bHB1dGF0ZSBkdWkgdmVsIGxhY3VzIGVsZW1lbnR1bSBsdWN0dXMuIFBlbGxlbnRlc3F1ZSBoYWJpdGFudCBtb3JiaSB0cmlzdGlxdWUgc2VuZWN0dXMgZXQgbmV0dXMgZXQgbWFsZXN1YWRhIGZhbWVzIGFjIHR1cnBpcyBlZ2VzdGFzLiBQZWxsZW50ZXNxdWUgdGVtcHVzIGhlbmRyZXJpdCBzYXBpZW4sIGV1IHZlaGljdWxhIGxlbyBmaW5pYnVzIGlkLiBOYW0gZWdlc3RhcyBhbnRlIG1hc3NhLCBlZ2V0IHBvcnRhIGFudGUgdmVzdGlidWx1bSBub24uIEZ1c2NlIGlkIGVuaW0gb3JjaS4gRG9uZWMgaW1wZXJkaWV0LCBlbmltIHF1aXMgdmVuZW5hdGlzIHBvcnR0aXRvciwgZXN0IHJpc3VzIGZpbmlidXMgb2Rpbywgc2l0IGFtZXQgc2VtcGVyIG51bGxhIHRlbGx1cyBpbiBuaXNpLiBQZWxsZW50ZXNxdWUgYXQgdGVsbHVzIHVybmEuIE51bGxhIHNpdCBhbWV0IHVybmEgc2NlbGVyaXNxdWUsIGxhY2luaWEgdG9ydG9yIGF0LCBmYXVjaWJ1cyB2ZWxpdC4gTW9yYmkgdWx0cmljaWVzIHNvbGxpY2l0dWRpbiBhcmN1IHZpdGFlIHByZXRpdW0uIEFsaXF1YW0gY29uZGltZW50dW0gaWQgbWkgaWQgcG9zdWVyZS4gTmFtIHF1aXMgbmliaCBkYXBpYnVzLCBlbGVpZmVuZCBsaWJlcm8gYWMsIGNvbnZhbGxpcyBuaXNsLiBEb25lYyBydXRydW0gdmVzdGlidWx1bSBmZWxpcywgaWQgcG9zdWVyZSB2ZWxpdCBwb3J0YSBlZ2V0Lgo="],
        ]);
    }
}

class ContentTableSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('contents')->delete();
        \Illuminate\Support\Facades\DB::table('contents')->insert([
            [
                'id' => '1',
                'mail_id' => '1',
                'type' => 'text/plain',
                'value' => "“Simplicity is the soul of efficiency.” – Austin Freeman",
            ],
            [
                'id' => '2',
                'mail_id' => '1',
                'type' => 'text/html',
                'value' => "<strong>“Simplicity is the soul of efficiency.”</strong> – Austin Freeman",
            ],
        ]);
    }
}

