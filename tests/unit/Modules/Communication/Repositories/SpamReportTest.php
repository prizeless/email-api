<?php
namespace Modules\Communication\Repositories;

use App\Modules\Communication\Repositories\EmailSpamReport;

class SpamReportTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testAddSpamReport_WithNewRecord_ShouldAddSpamReport()
    {
        $repo = new EmailSpamReport();

        $attributes = ['message_id' => 1, 'email' => 'antony@prizeless.net', 'event' => 'spamreport', 'timestamp' => '123456789'];

        $model = \Mockery::mock('App\Modules\Communication\Models\EmailSpamReport');

        $repo->setModel($model);

        $model->shouldReceive('addSpamReport')
            ->with(['message_id' => 1, 'contact_identifier' => md5('antony@prizeless.net')], '123456789')
            ->andReturn(true);


        $repo->addSpamReport($attributes);
    }
}
