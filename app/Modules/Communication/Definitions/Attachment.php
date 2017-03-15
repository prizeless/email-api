<?php

namespace App\Modules\Communication\Definitions;

class Attachment
{
    public $fileName;

    public $path;

    public $fileType;

    public function __construct($fileName, $path, $fileType)
    {
        $this->fileName = $fileName;

        $this->path = $path;

        $this->fileType = $fileType;
    }
}
