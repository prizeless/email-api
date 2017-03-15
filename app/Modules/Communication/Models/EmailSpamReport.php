<?php

namespace App\Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSpamReport extends Model
{
    protected $table = 'email_spam_reports';

    public $timestamps = false;

    public $contactIdentifier = 'contact_identifier';

    public $messageId = 'message_id';

    public function addSpamReport(array $attributes, $reportTime)
    {
        $existing = $this->where($attributes)->first();

        if (empty($existing) === true) {
            return $this->insert(array_merge($attributes, ['reported_at' => $reportTime]));
        }
        return $existing->increment('report_count');
    }
}
