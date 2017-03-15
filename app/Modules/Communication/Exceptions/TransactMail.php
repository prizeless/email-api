<?php
namespace App\Modules\Communication\Exceptions;

use Log;

class TransactMail extends CommException
{
    public function __construct($recipient, $reason = 'unknown')
    {
        $message = 'Error sending transaction email to ' . $recipient . ' reason ' . $reason;
        Log::error($message);

        parent::__construct($message);
    }
}
