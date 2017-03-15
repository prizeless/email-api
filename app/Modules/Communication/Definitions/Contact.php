<?php
namespace App\Modules\Communication\Definitions;

class Contact extends Definition
{
    public $first_name;
    public $last_name;
    public $full_name;
    public $nickname;
    public $email;
    public $mobile;
    public $member_id;

    public function __construct(array $contact)
    {
        $this->assignAttributes($contact);
    }
}
