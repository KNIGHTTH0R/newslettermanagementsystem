<?php

namespace App\Console\Commands;

use App\Attachment;
use App\Delivery;
use App\EmailAddress;
use App\Mail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send 
    {--F|from= : From e-mail address e.g. example@example.com} 
    {--R|replyTo= : ReplyTo e-mail address e.g. example@example.com} 
    {--T|to=* : To e-mail address e.g. example@example.com} 
    {--S|subject= : Email subject} 
    {--C|content= : Email content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send e-mails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $data = [
            'from' => $this->option("from"),
            'replyTo' => $this->option("replyTo"),
            'to' => $this->option("to"),
            'subject' => $this->option("subject"),
            'content' => $this->option("content"),
        ];

        $validator = Validator::make($data, [
            'from' => 'required|max:255|email',
            'replyTo' => 'nullable|max:255|email',
            'to' => 'required',
            'to.*' => 'required|max:255|email',
            'subject' => 'required|max:255',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $errorMessages) {
                foreach ($errorMessages as $errorMessage) {
                    $this->error($errorMessage);
                }
            }
        } else {
            DB::transaction(function () use ($data) {
                $fromEmail = EmailAddress::create([
                    'email' => $data['from'],
                ]);

                $replyToEmail = null;
                if (isset($data['replyTo'])) {
                    $replyToEmail = EmailAddress::create([
                        'email' => $data['replyTo'],
                    ]);
                }

                $mail = Mail::create([
                    'subject' => $data['subject'],
                    'html_content' => strip_tags($data['content']),
                    'text_content' => $data['content'],
                    'from_email_id' => $fromEmail->id,
                    'reply_to_email_id' => $replyToEmail ? $replyToEmail->id : null,
                ]);

                foreach ($data['to'] as $to) {
                    $t = EmailAddress::create([
                        'email' => $to,
                    ]);

                    Delivery::create([
                        'mail_id' => $mail->id,
                        'to_email_id' => $t->id,
                    ]);
                }
            });

            $this->info('Mail sent!');
        }


    }
}
