<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Delivery;
use App\EmailAddress;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\DeliveryResource;
use App\Http\Resources\DeliveryStatusResource;
use App\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class APIController extends Controller
{
    /**
     * Get deliveries as a collection
     *
     * @return AnonymousResourceCollection
     */
    public function getDeliveries()
    {
        // Get deliveries
        $deliveries = Delivery::orderByDesc('id')->paginate(5);
        // Return collection of deliveries as a resource
        return DeliveryResource::collection($deliveries);
    }

    /**
     * Get a delivery as a collection
     *
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
     * Get delivery statuses of a delivery as a collection
     *
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function getDeliveryStatuses($id)
    {
        // Get delivery
        $delivery = Delivery::findOrFail($id);
        // Return collection of delivery statuses as a resource
        return DeliveryStatusResource::collection($delivery->statuses()->orderByDesc('id')->get());
    }

    /**
     * Get mail attachments of an mail as a collection
     *
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function getMailAttachments($id)
    {
        // Get mail
        $mail = Mail::findOrFail($id);
        // Return collection of delivery statuses as a resource
        return AttachmentResource::collection($mail->attachments);
    }

    /**
     * Create a mail and deliveries to be send
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function createMail(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'from.email' => 'required|max:255|email',
            'from.name' => 'max:255',
            'replyTo.email' => 'nullable|max:255|email',
            'replyTo.name' => 'max:255',
            'to' => 'required',
            'to.*.email' => 'required|max:255|email',
            'to.*.name' => 'max:255',
            'subject' => 'required|max:255',
            'text' => 'required',
            'html' => 'required',
            'attachments.*.contentType' => 'required|max:150',
            'attachments.*.filename' => 'required|max:150',
            'attachments.*.base64Content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::transaction(function() use ($data)
        {
            $fromEmail = EmailAddress::create([
                'name' => isset($data['from']['name']) ?: null,
                'email' => $data['from']['email'],
            ]);

            $replyToEmail = null;
            if (isset($data['replyTo'])) {
                $replyToEmail = EmailAddress::create([
                    'name' => isset($data['replyTo']['name']) ?: null,
                    'email' => $data['replyTo']['email'],
                ]);
            }

            $mail = Mail::create([
                'subject' => $data['subject'],
                'html_content' => $data['html'],
                'text_content' => $data['text'],
                'from_email_id' => $fromEmail->id,
                'reply_to_email_id' => $replyToEmail ? $replyToEmail->id : null,
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
                    'name' => isset($to['name']) ?: null,
                    'email' => $to['email'],
                ]);

                Delivery::create([
                    'mail_id' => $mail->id,
                    'to_email_id' => $t->id,
                ]);
            }
        });

        return response()->json(['status' => 'success'], 201);
    }
}
