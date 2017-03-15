<?php

namespace App\Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class Email extends Model
{
    const ST_BOUNCE = 'bounce';

    const ST_OPEN = 'open';

    const ST_SPAM = 'spamreport';

    protected $table = 'email_messages';

    public $contactIdentifier = 'contact_identifier';

    public $messageId = 'message_id';

    public $customerId = 'customer_id';

    public $messageStatus = 'status';

    public $statusReason = 'status_reason';

    public $sendGridMessageId = 'sg_message_id';

    public $deliveredAt = 'delivered_at';

    public $openedAt = 'opened_at';

    public $createdAt = 'created_at';

    public $contactId = 'member_id';

    public $timestamps = false;

    private $dateFilters = [];


    protected $fillable
        = ['message_id', 'sg_message_id', 'contact_identifier', 'status', 'status_reason', 'delivered_at', 'opened_at'];


    public function getEnumOptions()
    {
        return explode(
            "','",
            substr(DB::select("SHOW COLUMNS FROM " . ($this->getTable() . " LIKE 'status'"))[0]->Type, 6, -2)
        );
    }

    public function updateByAttributes(array $attributes, array $values)
    {
        return $this->updateOrCreate($attributes, array_merge($attributes, $values));
    }

    public function getByAttributes(array $attributes = [], $limit = 10000)
    {
        $query = $this->where($attributes);
        $this->getDateFilters($query);

        return $query->get()->take($limit);
    }

    public function setDateFilters(array $dateOptions)
    {
        $this->dateFilters = $dateOptions;
    }

    private function getDateFilters(&$query)
    {
        foreach ($this->dateFilters as $date) {
            $query->where($date->column, $date->compareSign, $date->value);
        }

    }

    public function linkClicks()
    {
        return $this->hasMany('App\Modules\Communication\Models\EmailLinkClick', 'message_id', 'message_id');
    }
}
