<?php
namespace App\Modules\Communication\Definitions;

class Email extends Definition
{
    public $from_email;
    public $from_name;
    public $html;
    public $text;
    public $subject;
    public $message_id;
    public $customer_id;
    public $category;

    public function __construct(array $email)
    {
        $this->assignAttributes($email);
    }
}
