<?php

namespace App\Http\Controllers;

use App\Jobs\SendTransactEmail;
use App\Modules\Communication\Definitions\Email;
use App\Modules\Communication\Utilities\Attachment;
use Illuminate\Http\JsonResponse;
use App\Modules\Communication\Definitions\Contact;

class TransactEmailSend extends Controller
{

    public function send()
    {
        $payload = $this->getRequest()->json()->all();

        $contact = new Contact($payload['recipient']);

        $message = new Email($payload['message']);

        $bcc = empty($payload['bcc']) === true ? [] : $this->getContactDetails($payload['bcc']);

        $cc = empty($payload['cc']) === true ? [] : $this->getContactDetails($payload['cc']);

        $attachments = $this->getAttachments($payload, $message->message_id);

        $this->dispatch(new SendTransactEmail($message, $contact, $bcc, $cc, $attachments));

        return new JsonResponse('You message has been queued');
    }

    private function getContactDetails(array $contacts)
    {
        $contactsObjects = [];

        foreach ($contacts as $contact) {
            $contactsObjects[] = new Contact($contact);
        }

        return $contactsObjects;
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
