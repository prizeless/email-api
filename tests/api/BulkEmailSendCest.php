<?php

class BulkEmailSendCest
{
    private $baseUrl = 'http://localhost:8001/api/v1';

    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function tryToSendBulkEmails(ApiTester $I)
    {

        $contacts = [];
        for ($i = 0; $i < 5; $i++) {
            $contacts[] = $this->getContact($i);
        }
        $payload = new \stdClass();
        $payload->message = $this->getMessage();
        $payload->recipient = $contacts;
        $I->wantTo(' send bulk email');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amHttpAuthenticated('test', 'test');
        $I->sendPOST($this->baseUrl . '/messages/email/bulk/send', json_encode($payload));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('have been queued');
    }

    public function tryToSendWithAttachments(ApiTester $I)
    {

        $images = new \stdClass;

        $imgbinary = fread(fopen(codecept_data_dir('bob.png'), "r"), filesize(codecept_data_dir('bob.png')));
        $images->data = base64_encode($imgbinary);
        $images->name = 'testing';

        $text = new \stdClass;

        $textFile = fread(fopen(codecept_data_dir('test.txt'), "r"), filesize(codecept_data_dir('test.txt')));
        $text->data = base64_encode($textFile);
        $text->name = 'testing_text';

        $contacts = [];
        for ($i = 0; $i < 5; $i++) {
            $contacts[] = $this->getContact($i);
        }


        $payload = new stdClass();
        $payload->message = $this->getMessage();
        $payload->recipient = $contacts;
        $payload->attachments = array($images, $text);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amHttpAuthenticated('test', 'test');
        $I->sendPOST(
            $this->baseUrl . '/messages/email/bulk/send',
            json_encode($payload)

        );

        $I->seeResponseIsJson();
        $I->seeResponseContains('queued');
    }

    protected function getContact($count = 1)
    {
        $contact = new stdClass();
        $contact->member_id = $count;
        $contact->full_name = 'Antony Masocha' . $count;
        $contact->email = 'antony' . $count . '@prizeless.net';

        return $contact;
    }

    private function getMessage()
    {
        $message = new \stdClass();
        $message->message_id = 1;
        $message->customer_id = 1;
        $message->html = '<body>Testing message successfully delivers <a href="http://prizeless.net">Click here</a> </body>';
        $message->subject = 'Testing bulk email delivers';
        $message->from_email = 'antony@prizeless.net';
        $message->from_name = 'List administrator';

        return $message;
    }
}
