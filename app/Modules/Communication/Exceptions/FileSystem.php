<?php

namespace App\Modules\Communication\Exceptions;

class FileSystem extends CommException
{
    public function __construct($operation)
    {
        parent::__construct('There was an error completing ' . $operation . ' operation');
    }
}
