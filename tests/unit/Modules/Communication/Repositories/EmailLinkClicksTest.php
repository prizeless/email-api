<?php
namespace Modules\Communication\Repositories;

use App\Modules\Communication\Repositories\EmailLinkClick;

class EmailLinkClicksTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testAddSpamReport_WithNewRecord_ShouldAddSpamReport()
    {
        $repo = new EmailLinkClick;

        $attributes = [
            'message_id' => 1,
            'email' => 'antony@prizeless.net',
            'event' => 'click',
            'timestamp' => '123456789',
            'url' => 'http://prizeless.net'
        ];

        $model = \Mockery::mock('App\Modules\Communication\Models\EmailLinkClick');

        $repo->setModel($model);

        $model->shouldReceive('addClickReport')
            ->with(
                ['message_id' => 1, 'contact_identifier' => md5('antony@prizeless.net'), 'link' => 'http://prizeless.net'],
                '123456789'
            )
            ->andReturn(true);


        $repo->addClickReport($attributes);
    }
}
