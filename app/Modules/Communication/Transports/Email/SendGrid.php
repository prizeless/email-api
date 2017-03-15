<?php
namespace App\Modules\Communication\Transports\Email;

use App\Jobs\AddSentLog;
use App\Modules\Communication\Definitions\Contact;
use App\Modules\Communication\Exceptions\TransactMail;
use App\Modules\Communication\Transports\Transport;
use Illuminate\Support\Facades\Queue;

class SendGrid extends Transport
{
    private $sendGridEmail;

    /**
     * @param array $bcc Bcc contacts
     * @param array $cc Cc contacts
     * @return \stdClass
     * @throws TransactMail
     */
    public function send(array $bcc = [], array $cc = [])
    {
        try {
            $sendGrid = $this->getTransport();
            $email = $this->getSendGridEmail();

            $email->addTo($this->contact->email, $this->contact->full_name)
                ->setFrom($this->message->from_email)
                ->setFromName($this->message->from_name)
                ->setSubject($this->message->subject)
                ->setHtml($this->message->html)
                ->addUniqueArg('message_id', $this->message->message_id);

            $this->addBcc($bcc, $email);
            $this->addCc($cc, $email);

            $this->addAttachments($email);

            $sendGrid->send($email);

            return $this->addSentLog($this->contact);

        } catch (\SendGrid\Exception $e) {
            $this->processException($e, $this->contact->email);
        }
    }

    /**
     * @param array $bcc
     * @param $email \SendGrid\Email
     */
    private function addBcc(array $bcc, $email)
    {
        foreach ($bcc as $contact) {
            $email->addBcc($contact->email, $contact->full_name);
        }
    }

    /**
     * @param array $cc
     * @param $email \SendGrid\Email
     */
    private function addCc(array $cc, $email)
    {
        foreach ($cc as $contact) {
            $email->addCc($contact->email, $contact->full_name);
        }
    }

    /**
     * @throws TransactMail
     */
    public function sendBulk()
    {
        try {
            $contacts = array_chunk($this->contact, 1000);

            $this->dispatchEmails($contacts);
        } catch (\SendGrid\Exception $e) {
            $this->processException($e);
        }
    }

    public function setSendGridEmail($email)
    {
        $this->sendGridEmail = $email;
    }

    /**
     * @return \SendGrid\Email
     */
    private function getSendGridEmail()
    {
        if (empty($this->sendGridEmail) === true) {
            $this->sendGridEmail = new \SendGrid\Email();
        }

        return $this->sendGridEmail;
    }

    /**
     * @return \SendGrid
     */
    private function getTransport()
    {
        if (empty($this->transport) === true) {
            $this->transport = new \SendGrid('API_KEY');
        }

        return $this->transport;
    }

    /**
     * @param $contacts
     * @throws \SendGrid\Exception
     */
    private function dispatchEmails($contacts)
    {
        $sendGrid = $this->getTransport();
        $email = $this->getSendGridEmail();

        foreach ($contacts as $contact) {
            $this->buildMultipleRecipients($contact, $email);
            $email->setFrom($this->message->from_email)
                ->setFromName($this->message->from_name)
                ->setSubject($this->message->subject)
                ->setHtml($this->message->html);

            $email->addUniqueArg('message_id', $this->message->message_id);

            $this->addAttachments($email);

            $sendGrid->send($email);
        }
    }

    /**
     * @param $contact
     * @param $email \SendGrid\Email
     */
    private function buildMultipleRecipients($contact, $email)
    {
        foreach ($contact as $batch) {
            $email->addSmtpapiTo($batch->email, $batch->full_name);

            $this->addSentLog($batch);
        }
    }

    /**
     * @param $email \SendGrid\Email
     */
    private function addAttachments($email)
    {
        foreach ($this->attachments as $attachment) {
            $email->addAttachment($attachment->path);
        }
    }

    /**
     * @param $class \SendGrid\Exception
     * @param string $email
     * @throws TransactMail
     */
    private function processException($class, $email = '')
    {
        $code = empty($email) === true ? $class->getCode() : $email;
        foreach ($class->getErrors() as $error) {
            throw new TransactMail($code, $error);
        }
    }

    /**
     * @param $contact Contact
     */
    private function addSentLog(Contact $contact)
    {
        Queue::push(new AddSentLog($contact, $this->message));
    }
}
