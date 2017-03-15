<?php

namespace App\Jobs;

use App\Modules\Communication\Exceptions\CommException;
use App\Modules\Communication\Transports\Email\SendGrid;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTransactEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $message;

    private $contact;

    private $bcc;

    private $cc;

    private $attachments;

    public function __construct($message, $contact, array $bcc = [], array $cc = [], array $attachments = [])
    {
        $this->message = $message;

        $this->contact = $contact;

        $this->bcc = $bcc;

        $this->cc = $cc;

        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            (new SendGrid($this->message, $this->contact, $this->attachments))->send($this->bcc, $this->cc);

        } catch (CommException $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
