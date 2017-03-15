<?php

class EmailReportsCest
{
    private $baseUrl = 'http://localhost:8001/api/v1';

    public function _before(ApiTester $I)
    {

    }

    public function _after(ApiTester $I)
    {
    }


    public function tryToGetOverViewReport(ApiTester $I)
    {

        $I->amHttpAuthenticated('test', 'test');
        $I->sendGET($this->baseUrl . '/messages/email/report/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryToGetBouncesReport(ApiTester $I)
    {
        $I->amHttpAuthenticated('test', 'test');
        $I->sendGET($this->baseUrl . '/messages/email/report/1/bounces');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryToGetOpensReport(ApiTester $I)
    {
        $I->amHttpAuthenticated('test', 'test');
        $I->sendGET($this->baseUrl . '/messages/email/report/1/opens');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryToGetClicksReport(ApiTester $I)
    {
        $I->amHttpAuthenticated('test', 'test');
        $I->sendGET($this->baseUrl . '/messages/email/report/1/clicks');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryToGetSpamReports(ApiTester $I)
    {
        $I->amHttpAuthenticated('test', 'test');
        $I->sendGET($this->baseUrl . '/messages/email/report/1/spamreport');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
