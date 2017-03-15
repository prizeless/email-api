<?php

namespace App\Modules\Communication\Utilities;

class Encode
{
    public function md5($string)
    {
        return md5($string);
    }

    public function jsonEncode($string)
    {
        return json_encode($string);
    }

    public function base64Decode($string)
    {
        return base64_decode($string);
    }
}
