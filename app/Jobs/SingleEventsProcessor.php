<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Modules\Communication\Repositories\Email;

class SingleEventsProcessor extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $dataPackets;

    public function __construct(array $dataPackets)
    {
        $this->dataPackets = $dataPackets;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->dataPackets as $packet) {
            $this->updateMessageLog($packet);
        }
    }

    private function updateMessageLog(array $packet)
    {

        $method = 'set' . ucfirst($packet['event']);

        $emailRepo = new Email();

        if (method_exists($emailRepo, $method) !== true) {
            return false;
        }

        return $emailRepo->{$method}($packet);
    }
}
