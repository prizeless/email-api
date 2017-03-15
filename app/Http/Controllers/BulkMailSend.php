<?php

namespace App\Http\Controllers;

use App\Jobs\BulkEmailSend;
use App\Modules\Communication\Definitions\Contact;
use App\Modules\Communication\Definitions\Email;
use App\Modules\Communication\Utilities\Attachment;
use Illuminate\Http\JsonResponse;

class BulkMailSend extends Controller
{
    public function send()
    {
        $payload = $this->getRequest()->json()->all();

        $contacts = $this->getContacts($payload);

        $message = new Email($payload['message']);

        $attachments = $this->getAttachments($payload, $message->message_id);

        $this->dispatch(new BulkEmailSend($message, $contacts, $attachments));

        return new JsonResponse('You messages have been queued');
    }

    private function getContacts($message)
    {
        $contacts = [];

        foreach ($message['recipient'] as $contact) {
            array_push($contacts, new Contact($contact));
        }

        return $contacts;
    }

    private function getAttachments($payload, $messageId)
    {
        if (empty($payload['attachments']) === true || is_array($payload) !== true) {
            return [];
        }

        $attachmentUtil = new Attachment($payload['attachments']);
        $attachmentUtil->moveAllUploadedFiles($messageId);

        return $attachmentUtil->getAttachments();
    }
}
