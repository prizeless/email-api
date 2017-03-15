<?php
namespace App\Modules\Communication\Transports\Email;

use App\Modules\Communication\Transports\Transport;

class MailGun extends Transport
{
    public function send()
    {
        $mg = new \Mailgun\Mailgun("key-ef90075f123025ba4f8fa2c8228b674c");
        $domain = "sandbox4ae5f24038a942dd961311c0482538bc.mailgun.org";

        $mg->sendMessage($domain, array('from' => $this->message->from,
            'to' => $this->contact->email,
            'subject' => $this->message->subject.'MailGun',
            'html' => $this->message->html));
    }
}
