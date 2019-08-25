<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Delivery;
use App\EmailAddress;
use App\Http\Resources\DeliveryResource;
use App\Http\Resources\DeliveryStatusResource;
use App\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getDeliveries()
    {
        // Get deliveries
        $deliveries = Delivery::paginate(15);
        // Return collection of deliveries as a resource
        return DeliveryResource::collection($deliveries);
    }

    /**
     * @param $id
     * @return DeliveryResource
     */
    public function getDelivery($id)
    {
        // Get delivery
        $delivery = Delivery::findOrFail($id);
        // Return single delivery as a resource
        return new DeliveryResource($delivery);
    }

    /**
     * @param $id
     */
    public function getDeliveryStatuses($id)
    {
        // Get delivery
        $delivery = Delivery::findOrFail($id);
        // Return collection of delivery statuses as a resource
        return DeliveryStatusResource::collection($delivery->statuses);
    }

    /**
     * @param Request $request
     */
    public function createMail(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'from.email' => 'required|max:255|email',
            'from.name' => 'max:255',
            'replyTo.email' => 'max:255|email',
            'replyTo.name' => 'max:255',
            'to' => 'required',
            'to.*.email' => 'required|max:255|email',
            'to.*.name' => 'max:255',
            'subject' => 'required|max:255',
            'text' => 'required',
            'html' => 'required',
            'attachments.*.contentType' => 'required|max:50',
            'attachments.*.filename' => 'required|max:50',
            'attachments.*.base64Content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //dd($data['to']);

        DB::transaction(function() use ($data)
        {
            $from_email = EmailAddress::create([
                'name' => $data['from']['name'],
                'email' => $data['from']['email'],
            ]);

            $reply_to_email = null;
            if (isset($data['replyTo'])) {
                $reply_to_email = EmailAddress::create([
                    'name' => $data['replyTo']['name'],
                    'email' => $data['replyTo']['email'],
                ]);
            }

            $mail = Mail::create([
                'subject' => $data['subject'],
                'html_content' => $data['html'],
                'text_content' => $data['text'],
                'from_email_id' => $from_email->id,
                'reply_to_email_id' => $reply_to_email ? $reply_to_email->id : null,
            ]);

            foreach ($data['attachments'] as $attachment) {
                Attachment::create([
                    'filename' => $attachment['filename'],
                    'type' => $attachment['contentType'],
                    'content' => $attachment['base64Content'],
                    'mail_id' => $mail->id,
                ]);
            }

            foreach ($data['to'] as $to) {
                $t = EmailAddress::create([
                    'name' => $to['name'],
                    'email' => $to['email'],
                ]);

                $delivery = Delivery::create([
                    'mail_id' => $mail->id,
                    'to_email_id' => $t->id,
                ]);
            }
        });

        return response()->json(['status' => 'success'], 200);
    }
}
