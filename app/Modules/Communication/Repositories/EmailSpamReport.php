<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 2015/09/16
 * Time: 2:28 PM
 */

namespace App\Modules\Communication\Repositories;

use App\Modules\Communication\Models\EmailSpamReport as SpamReportModel;
use App\Modules\Communication\Utilities\Encode;

class EmailSpamReport
{
    private $model;

    public function addSpamReport(array $dataPacket)
    {
        $model = $this->getModel();
        $attributes = [
            $model->contactIdentifier => (new Encode)->md5($dataPacket['email']),
            $model->messageId => $dataPacket['message_id']
        ];
        return $this->getModel()->addSpamReport($attributes, $dataPacket['timestamp']);
    }

    /**
     * @param $model
     */
    public function setModel(SpamReportModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return SpamReportModel
     */
    public function getModel()
    {
        if (empty($this->model) === true) {
            $this->model = new SpamReportModel;
        }

        return $this->model;
    }
}
