<?php
namespace App\Modules\Communication\Repositories;

use App\Modules\Communication\Models\EmailLinkClick as LinkClickModel;

use App\Modules\Communication\Utilities\Encode;

class EmailLinkClick
{
    private $model;

    public function addClickReport(array $dataPacket)
    {
        $model = $this->getModel();
        $attributes = [
            $model->contactIdentifier => (new Encode)->md5($dataPacket['email']),
            $model->messageId => $dataPacket['message_id'],
            $model->linkCol => $dataPacket['url']
        ];

        return $this->getModel()->addClickReport($attributes, $dataPacket['timestamp']);
    }

    /**
     * @param $model
     */
    public function setModel(LinkClickModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return LinkClickModel
     */
    public function getModel()
    {
        if (empty($this->model) === true) {
            $this->model = new LinkClickModel;
        }

        return $this->model;
    }
}
