<?php
namespace Modules\Communication\Repositories;

use App\Modules\Communication\Repositories\EmailReport;
use \FunctionalTester;
use Artisan;

class EmailReportCest
{
    public function _before(FunctionalTester $I)
    {
        Artisan::call('migrate:refresh');
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function tryToGetOverviewReport(FunctionalTester $I)
    {
        $repo = new EmailReport(1);

        $I->amGoingTo('create test message in database');

        $date = strtotime('-20 days', strtotime(date('Y-m-d')));


        $I->haveInDatabase('email_messages', ['message_id' => 1, 'contact_identifier' => md5('antony@prizeless.net'), 'created_at' => $date]);

        $I->haveInDatabase('email_link_clicks', ['message_id' => 1, 'contact_identifier' => md5('antony@prizeless.net'), 'link' => 'http://prizeless.net']);

        $result = $repo->getOverView(0, 0);

        $I->assertEquals($date, $result[0]->created_at);
        $I->assertEquals('http://prizeless.net', $result[0]->linkClicks[0]->link);
    }

}
