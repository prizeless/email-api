<?php
namespace Modules\Communication\Repositories;

use App\Modules\Communication\Repositories\Email;

use Carbon\Carbon;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testAddLog_WithMessageAndEmail_ShouldAddLog()
    {
        $repository = new Email();

        Carbon::setTestNow(Carbon::create(2015, 01, 01, 01, 01, 01, 'GMT'));

        $attributes = ['message_id' => 1, 'contact_identifier' => md5('antony@prizeless.net'), 'customer_id' => 1, 'created_at' => Carbon::create(2015, 01, 01, 01, 01, 01, 'GMT')->timestamp];

        $model = \Mockery::mock('App\Modules\Communication\Models\Email');
        $model->shouldReceive('insert')->once()->with($attributes)->andReturn(true);

        $repository->setModel($model);
        $result = $repository->addSentLog(
            ['message_id' => 1, 'contact_identifier' => 'antony@prizeless.net', 'customer_id' => 1]
        );

        $this->assertTrue($result);
    }
}
