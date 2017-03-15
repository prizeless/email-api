<?php
namespace App\Modules\Communication\Exceptions;

class CommException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
