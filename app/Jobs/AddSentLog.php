<?php

namespace App\Jobs;

use App\Modules\Communication\Definitions\Contact;
use App\Modules\Communication\Repositories\Email;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddSentLog extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $message;

    private $contact;

    public function __construct(Contact $contact, \App\Modules\Communication\Definitions\Email $message)
    {
        $this->contact = $contact;

        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repository = new Email();
        $attributes = [
            $repository->getModel()->messageId => $this->message->message_id,
            $repository->getModel()->customerId => $this->message->customer_id,
            $repository->getModel()->contactIdentifier => $this->contact->email,
            $repository->getModel()->contactId => $this->contact->member_id
        ];


        return $repository->addSentLog($attributes);

    }
}
