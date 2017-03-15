<?php

namespace App\Jobs;

use App\Modules\Communication\Exceptions\CommException;
use App\Modules\Communication\Transports\Email\SendGrid;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\HttpFoundation\JsonResponse;

class BulkEmailSend extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $message;

    private $contact;

    private $attachmenmts;

    public function __construct($message, $contact, $attachments)
    {
        $this->message = $message;

        $this->contact = $contact;

        $this->attachmenmts = $attachments;
    }

    public function handle()
    {
        try {
            (new SendGrid($this->message, $this->contact, $this->attachmenmts))->sendBulk();
        } catch (CommException $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
