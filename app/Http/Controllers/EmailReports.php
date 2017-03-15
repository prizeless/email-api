<?php

namespace App\Http\Controllers;

use App\Modules\Communication\Repositories\EmailReport;
use Illuminate\Http\JsonResponse;

use Input;

class EmailReports extends Controller
{
    private $messageId;

    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    public function showReport($report_type = 'overView', $start_date = 0, $end_date = 0)
    {
        $report = $this->getReport($report_type, $start_date, $end_date);

        return new JsonResponse($report);
    }

    private function getReport($report_type, $start_date, $end_date)
    {
        $repoMethod = ucfirst($report_type);

        return (new EmailReport($this->messageId))->{'get' . $repoMethod}($start_date, $end_date);
    }
}
