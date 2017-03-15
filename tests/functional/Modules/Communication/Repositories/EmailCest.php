<?php
namespace Modules\Communication\Repositories;

use App\Modules\Communication\Repositories\Email;
use \FunctionalTester;
use Artisan;

class EmailCest
{
    public function _before(FunctionalTester $I)
    {
        Artisan::call('migrate:refresh');
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function tryToAddSentLog(FunctionalTester $I)
    {
        $I->wantTo('add an email sent log');
        $I->amGoingTo('instantiate email repository');
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::addSentLog');
        $email->addSentLog(['message_id' => 1, 'customer_id' => 1, 'contact_identifier' => 'antony@prizeless.net']);
        $I->amGoingTo('check if record was inserted in database');
        $I->seeInDatabase(
            'email_messages',
            ['message_id' => 1, 'contact_identifier' => md5('antony@prizeless.net'), 'customer_id' => 1, 'status' => 'sent']
        );
    }

    public function tryToSetProcessed(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to processed');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setProcessed');
        $email->setProcessed(
            ['event' => 'processed', 'email' => 'antony@prizeless.net', 'message_id' => 1, 'sg_message_id' => 1]
        );
        $I->seeInDatabase(
            'email_messages',
            ['contact_identifier' => md5('antony@prizeless.net'), 'message_id' => 1, 'status' => 'processed']
        );
    }

    public function tryToSetDropped(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to dropped');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setDropped');
        $email->setDropped(
            [
                'event' => 'dropped',
                'email' => 'antony@prizeless.net',
                'message_id' => 1, 'sg_message_id' => 1,
                'reason' => 'email invalid'
            ]
        );
        $I->seeInDatabase(
            'email_messages',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1, 'status' => 'dropped',
                'status_reason' => 'email invalid'
            ]
        );
    }

    public function tryToSetDelivered(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to dropped');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setDelivered');
        $email->setDelivered(
            [
                'event' => 'delivered',
                'email' => 'antony@prizeless.net',
                'message_id' => 1, 'sg_message_id' => 1,
                'timestamp' => '123456789'
            ]
        );
        $I->seeInDatabase(
            'email_messages',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'status' => 'delivered',
                'delivered_at' => '123456789'
            ]
        );
    }

    public function tryToSetDeferred(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to deferred');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setDeferred');
        $email->setDeferred(
            [
                'event' => 'deferred',
                'email' => 'antony@prizeless.net',
                'message_id' => 1, 'sg_message_id' => 1,
                'response' => 'Server not available'
            ]
        );
        $I->seeInDatabase(
            'email_messages',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'status' => 'deferred',
                'status_reason' => 'Server not available'
            ]
        );
    }

    public function tryToSetOpen(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to opened');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setOpen');
        $email->setOpen(
            [
                'event' => 'open',
                'email' => 'antony@prizeless.net',
                'message_id' => 1, 'sg_message_id' => 1,
                'timestamp' => '123456789',
                'ip' => '127.0.0.1',
                'useragent' => 'Thunderbird'
            ]
        );
        $I->seeInDatabase(
            'email_messages',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'status' => 'open',
                'opened_at' => '123456789',
                'status_reason' => json_encode(['ip_address' => '127.0.0.1', 'user_agent' => 'Thunderbird'])
            ]
        );
    }

    public function tryToSetBounce(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to bounced');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setBounce');
        $email->setBounce(
            [
                'event' => 'bounce',
                'email' => 'antony@prizeless.net',
                'message_id' => 1,
                'sg_message_id' => 1,
                'reason' => 'Server not available'
            ]
        );
        $I->seeInDatabase(
            'email_messages',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'status' => 'bounce',
                'status_reason' => 'Server not available'
            ]
        );
    }

    public function tryToSetSpamReport(FunctionalTester $I)
    {
        $I->amGoingTo('update message status to spam report');
        $I->comment(' l am going to create sample record in database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setSpamreport');
        $email->setSpamreport(
            [
                'event' => 'spamreport',
                'email' => 'antony@prizeless.net',
                'message_id' => 1,
                'sg_message_id' => 1,
                'timestamp' => '123456789'
            ]
        );
        $I->seeInDatabase(
            'email_messages',
            [
                'contact_identifier' => md5('antony@prizeless.net'),
                'message_id' => 1,
                'status' => 'spamreport',
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

    public function tryToSetClick(FunctionalTester $I)
    {
        $I->amGoingTo('link click entry');
        $I->comment(' l am going to create sample record in messages database');
        $this->createTestRecord($I);
        $email = new Email();
        $I->comment('l execute App\Modules\Communication\Repositories\Email::setClick');
        $email->setClick(
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
