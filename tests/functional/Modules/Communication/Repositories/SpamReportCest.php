<?php
namespace Modules\Communication\Repositories;

use App\Modules\Communication\Repositories\EmailSpamReport;
use \FunctionalTester;
use Artisan;

class SpamReportCest
{
    public function _before(FunctionalTester $I)
    {
        Artisan::call('migrate:refresh');
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function tryToAddSpamReportWithNoRecord(FunctionalTester $I)
    {
        $I->wantTo('add new spam report');
        $I->amGoingTo('create sent log message entry');
        $this->createTestRecord($I);
        $I->comment('l execute App\Modules\Communication\Repositories\EamilSpamReport::addSpamReport');
        $repo = new EmailSpamReport();
        $repo->addSpamReport(
            [
                'event' => 'spamreport',
                'email' => 'antony@prizeless.net',
                'message_id' => 1,
                'sg_message_id' => 1,
                'timestamp' => '123456789'
            ]
        );

        $I->seeInDatabase(
            'email_spam_reports',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'report_count' => 1,
                'reported_at' => '123456789'
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
            'email_spam_reports',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'report_count' => 1,
                'reported_at' => '123456789'
            ]
        );
        $I->comment('l execute App\Modules\Communication\Repositories\EamilSpamReport::addSpamReport');
        $repo = new EmailSpamReport();
        $repo->addSpamReport(
            [
                'event' => 'spamreport',
                'email' => 'antony@prizeless.net',
                'message_id' => 1,
                'sg_message_id' => 1,
                'timestamp' => '123456789'
            ]
        );
        $I->seeInDatabase(
            'email_spam_reports',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'report_count' => 2,
                'reported_at' => '123456789'
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
