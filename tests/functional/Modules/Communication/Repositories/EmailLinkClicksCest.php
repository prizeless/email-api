<?php
namespace Modules\Communication\Repositories;
use App\Modules\Communication\Repositories\EmailLinkClick;
use \FunctionalTester;

use Artisan;

class EmailLinkClicksCest
{
    public function _before(FunctionalTester $I)
    {
        Artisan::call('migrate:refresh');
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function tryToAddLickClickReportWithNoRecord(FunctionalTester $I)
    {
        $I->wantTo('add new click report');
        $I->amGoingTo('create sent log message entry');
        $this->createTestRecord($I);
        $I->comment('l execute App\Modules\Communication\Repositories\EmailLinkClick::addClickReport');
        $repo = new EmailLinkClick();
        $repo->addClickReport(
            [
                'event' => 'click',
                'email' => 'antony@prizeless.net',
                'message_id' => 1,
                'sg_message_id' => 1,
                'timestamp' => '123456789',
                'url' => 'http://prizeless.net'
            ]
        );

        $I->seeInDatabase(
            'email_link_clicks',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'click_count' => 1,
                'clicked_at' => '123456789',
                'link' => 'http://prizeless.net'
            ]
        );

    }

    public function tryToAddSpamReportWithExistingRecordIcrementsCounter(FunctionalTester $I)
    {
        $I->wantTo('add new spam report');
        $I->amGoingTo('create sent log message entry');
        $this->createTestRecord($I);
        $I->amGoingTo('create entry in spam reports database');
        $I->haveInDatabase(
            'email_link_clicks',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'click_count' => 1,
                'clicked_at' => '123456789',
                'link' => 'http://prizeless.net'
            ]
        );
        $I->comment('l execute App\Modules\Communication\Repositories\EamilSpamReport::addSpamReport');
        $repo = new EmailLinkClick;
        $repo->addClickReport(
            [
                'event' => 'click',
                'email' => 'antony@prizeless.net',
                'message_id' => 1,
                'sg_message_id' => 1,
                'timestamp' => '123456789',
                'url' => 'http://prizeless.net',
            ]
        );
        $I->seeInDatabase(
            'email_link_clicks',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'click_count' => 2,
                'clicked_at' => '123456789',
                'link' => 'http://prizeless.net'
            ]
        );



    }

    /**
     * @param FunctionalTester $I
     */
    private function createTestRecord(FunctionalTester $I)
    {
        $I->haveInDatabase(
            'email_messages',
            ['message_id' => 1, 'customer_id' => 1, 'contact_identifier' => md5('antony@prizeless.net'), 'status' => 'sent']
        );
    }
}
