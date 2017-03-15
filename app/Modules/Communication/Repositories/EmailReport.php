<?php

namespace App\Modules\Communication\Repositories;

use App\Modules\Communication\Definitions\QueryDateFilter;
use App\Modules\Communication\Models\Email as EmailModel;
use Carbon\Carbon;

class EmailReport
{
    private $model;

    private $messageId;

    public function __construct($messageId)
    {
        $this->model = $this->getModel();

        $this->messageId = $messageId;
    }

    /**
     * @param int $startDate
     * @param int $endDate
     * @return mixed
     */
    public function getOverView($startDate, $endDate)
    {
        $data = $this->getDataFromModel($startDate, $endDate);

        foreach ($data as $stats) {
            $data->link_clicks = $stats->linkClicks;
        }

        return $data;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getOpens($startDate, $endDate)
    {
        return $this->getDataFromModel($startDate, $endDate, [$this->model->messageStatus => EmailModel::ST_OPEN]);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getClicks($startDate, $endDate)
    {
        $data = $this->getOverView($startDate, $endDate);

        return empty($data->link_clicks) === true ? $data : $data->link_clicks;
    }

    public function getSpamReport($startDate, $endDate)
    {
        return $this->getDataFromModel($startDate, $endDate, [$this->model->messageStatus => EmailModel::ST_SPAM]);
    }

    /**
     * @param int $startDate
     * @param int $endDate
     * @return mixed
     */
    public function getBounces($startDate, $endDate)
    {
        return $this->getDataFromModel($startDate, $endDate, [$this->model->messageStatus => EmailModel::ST_BOUNCE]);
    }

    private function getDataFromModel($startDate, $endDate, array $additionalFilters = [])
    {
        $this->addDateFilters($startDate, $endDate);

        $filters = array_merge($additionalFilters, [$this->model->messageId => $this->messageId]);

        return $this->model->getByAttributes($filters);
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
            $this->model = new EmailModel();
        }

        return $this->model;
    }

    private function addDateFilters($startDate, $endDate)
    {
        $startDate = $startDate <= 0 ? Carbon::now()->subMonth()->timestamp : $startDate;
        $endDate = $endDate <= 0 ? Carbon::now()->timestamp : $endDate;

        $startDate = new QueryDateFilter($this->model->createdAt, '>=', $startDate);
        $endDate = new QueryDateFilter($this->model->createdAt, '<=', $endDate);

        $this->model->setDateFilters([$startDate, $endDate]);
    }
}
