<?php

class EmailSendCest
{
    private $baseUrl = 'http://localhost:8001/api/v1';

    public function _before(ApiTester $I)
    {
        Artisan::call('migrate:refresh');
    }

    public function _after(ApiTester $I)
    {
    }

    public function tryToTestSuccessfulDelivery(ApiTester $I)
    {
        $payload = new stdClass();
        $payload->message = $this->getMessage(1);
        $payload->recipient = $this->getContact();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amHttpAuthenticated('test', 'test');
        $I->sendPOST($this->baseUrl . '/messages/email/send', json_encode($payload));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('queued');
    }

    public function tryToTestWithBccAndCc(ApiTester $I)
    {
        $payload = new stdClass();
        $payload->message = $this->getMessage(2);
        $payload->recipient = $this->getContact();

        $payload->bcc = [$this->getContact(), $this->getContact()];
        $payload->cc = [$this->getContact(), $this->getContact()];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amHttpAuthenticated('test', 'test');
        $I->sendPOST($this->baseUrl . '/messages/email/send', json_encode($payload));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('queued');
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


        $payload = new stdClass();
        $payload->message = $this->getMessage(1);
        $payload->recipient = $this->getContact();
        $payload->attachments = array($images, $text);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amHttpAuthenticated('test', 'test');
        $I->sendPOST(
            $this->baseUrl . '/messages/email/send',
            json_encode($payload)

        );

        $I->seeResponseIsJson();
        $I->seeResponseContains('queued');
    }

    /**
     * @param string $email
     * @return stdClass
     */
    private function getContact($email = 'antony@prizeless.net')
    {
        $contact = new stdClass();
        $contact->member_id = 1;
        $contact->full_name = 'Antony Masocha';
        $contact->email = $email;

        return $contact;
    }

    /**
     * @param $messageId
     * @return stdClass
     */
    private function getMessage($messageId)
    {
        $message = new \stdClass();
        $message->customer_id = 1;
        $message->message_id = $messageId;
        $message->html = '<body>Testing message successfully delivers <a href="http://prizeless.net">Click here</a> </body>';
        $message->subject = 'Testing email delivers';
        $message->from_email = 'antony@prizeless.net';
        $message->from_name = 'List administrator';

        return $message;
    }
}
