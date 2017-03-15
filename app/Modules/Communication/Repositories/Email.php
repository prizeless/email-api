<?php

namespace App\Modules\Communication\Repositories;

use App\Modules\Communication\Models\Email as EmailModel;
use App\Modules\Communication\Utilities\Encode;

use Carbon\Carbon;

class Email
{
    private $model;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addSentLog(array $attributes)
    {
        $model = $this->getModel();

        $attributes[$model->contactIdentifier] = (new Encode)->md5($attributes[$model->contactIdentifier]);

        $attributes[$model->createdAt] = Carbon::now()->timestamp;

        return $this->getModel()->insert($attributes);
    }

    /**
     * @param array $dataPacket
     * @return static
     */
    public function setProcessed(array $dataPacket)
    {
        return $this->updateMessageLog($dataPacket);
    }

    /**
     * @param array $dataPacket
     * @return bool|static
     */
    public function setDropped(array $dataPacket)
    {
        return $this->updateMessageLog($dataPacket, [$this->getModel()->statusReason => $dataPacket['reason']]);
    }

    /**
     * @param array $dataPacket
     * @return bool|static
     */
    public function setDelivered(array $dataPacket)
    {
        return $this->updateMessageLog($dataPacket, [$this->getModel()->deliveredAt => $dataPacket['timestamp']]);
    }

    /**
     * @param array $dataPacket
     * @return bool|static
     */
    public function setDeferred(array $dataPacket)
    {
        return $this->updateMessageLog($dataPacket, [$this->getModel()->statusReason => $dataPacket['response']]);
    }

    /**
     * @param array $dataPacket
     * @return bool|static
     */
    public function setOpen(array $dataPacket)
    {
        $deliveryInfo = ['ip_address' => $dataPacket['ip'], 'user_agent' => $dataPacket['useragent']];

        $additional = [
            $this->getModel()->openedAt => $dataPacket['timestamp'],
            $this->getModel()->statusReason => (new Encode)->jsonEncode($deliveryInfo)
        ];
        return $this->updateMessageLog($dataPacket, $additional);
    }

    /**
     * @param array $dataPacket
     * @return bool|static
     */
    public function setBounce(array $dataPacket)
    {
        return $this->updateMessageLog($dataPacket, [$this->getModel()->statusReason => $dataPacket['reason']]);
    }

    public function setSpamreport(array $dataPacket)
    {
        $this->updateMessageLog($dataPacket);

        return (new EmailSpamReport)->addSpamReport($dataPacket);
    }

    public function setClick(array $dataPacket)
    {
        return (new EmailLinkClick)->addClickReport($dataPacket);
    }

    private function updateMessageLog(array $dataPacket, array $additional = [])
    {
        $model = $this->getModel();

        $possibleStatus = $model->getEnumOptions();

        if (in_array($dataPacket['event'], $possibleStatus) === false) {
            return false;
        }

        $optionsToSet = array_merge(
            $additional,
            [
                $model->messageStatus => $dataPacket['event'],
                $model->sendGridMessageId => $dataPacket[$model->sendGridMessageId]
            ]
        );

        $emailAddress = (new Encode)->md5($dataPacket['email']);

        $condition = [
            $model->messageId => $dataPacket[$model->messageId],
            $model->contactIdentifier => $emailAddress,
        ];

        return $model->updateByAttributes(
            $condition,
            $optionsToSet
        );
    }

    /**
     * @param $model
     */
    public function setModel(EmailModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return EmailModel
     */
    public function getModel()
    {
        if (empty($this->model) === true) {
            $this->model = new EmailModel;
        }

        return $this->model;
    }
}
