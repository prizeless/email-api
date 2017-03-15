<?php

namespace App\Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLinkClick extends Model
{
    protected $table = 'email_link_clicks';

    public $timestamps = false;

    public $contactIdentifier = 'contact_identifier';

    public $messageId = 'message_id';

    public $linkCol = 'link';

    public function addClickReport(array $attributes, $clickTime)
    {
        $existing = $this->where($attributes)->first();

        if (empty($existing) === true) {
            return $this->insert(array_merge($attributes, ['clicked_at' => $clickTime]));
        }

        return $existing->increment('click_count');
    }

    public function email()
    {
        return $this->belongsTo('App\Modules\Communication\Models\Email', 'message_id', 'message_id');
    }
}
