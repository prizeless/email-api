<?php
namespace App\Modules\Communication\Transports;

abstract class Transport
{
    protected $message;

    protected $contact;

    protected $transport;

    protected $attachments;

    /**
     * @param $message
     * @param $contact
     * @param array $attachments
     */
    public function __construct($message, $contact, array $attachments = array())
    {
        $this->message = $message;

        $this->contact = $contact;

        $this->attachments = $attachments;
    }

    /**
     * @param $transport \SendGrid
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    abstract public function send();
}
