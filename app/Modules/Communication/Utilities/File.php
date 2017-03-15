<?php

namespace App\Modules\Communication\Utilities;

class File
{
    public function nameMakeSafe($name)
    {
        return preg_replace('/[^a-zA-Z0-9-_\.]/', '', $name);
    }

    public function getMimeFromString($string)
    {
        $fileHandle = finfo_open();

        return finfo_buffer($fileHandle, $string, FILEINFO_MIME_TYPE);
    }
}
