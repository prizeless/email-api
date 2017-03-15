<?php

namespace App\Modules\Communication\Definitions;

class FileMimeTypes
{
    private $mimeTypes = array(
        'application/pdf' => 'pdf',
        'application/zip' => 'zip',
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'text/css' => 'css',
        'text/html' => 'html',
        'text/plain' => 'txt',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/msword' => 'doc',
        'application/vnd.oasis.opendocument.text' => 'odt'
    );

    public function getExtension($mimeType)
    {
        return $this->mimeTypes[$mimeType];
    }
}
