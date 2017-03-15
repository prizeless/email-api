<?php

namespace App\Http\Controllers;

use App\Jobs\SingleEventsProcessor;
use Illuminate\Http\JsonResponse;

class SendGridEventProcessor extends Controller
{
    public function process()
    {
        $post = $this->getRequest()->json()->all();

        $this->dispatch(new SingleEventsProcessor($post));

        return new JsonResponse('success');
    }
}
