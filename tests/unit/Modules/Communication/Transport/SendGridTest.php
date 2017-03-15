<?php
namespace Modules\Transport;

use App\Modules\Communication\Definitions\Contact;
use App\Modules\Communication\Definitions\Email;
use App\Modules\Communication\Transports\Email\SendGrid;

use Illuminate\Support\Facades\Queue;

class SendGridTest extends \PHPUnit_Framework_TestCase
{
    private $message;

    public function setUp()
    {
        $this->message = $this->makeTestMessage();
    }


    public function testSendTransactional_WithValidMessageAndContact_ShouldSendMessage()
    {
        $contactAttributes = ['full_name' => 'Antony Masocha', 'email' => 'antony@prizeless.net'];

        $contact = new Contact($contactAttributes);


        $sendGrid = new SendGrid($this->message, $contact);

        $this->setSendGridLibraryMocks($sendGrid, $sendGridLib, $sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addTo')
            ->once()
            ->with('antony@prizeless.net', 'Antony Masocha')
            ->andReturn($sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addUniqueArg')
            ->once()
            ->with('message_id', 1)
            ->andReturn($sendGridEmailLibMock);

        $this->messageExpectations($sendGridEmailLibMock, $sendGridLib);

        Queue::shouldReceive('push')->once()->andReturn(true);

        $sendGrid->send();
    }

    public function testSend_WithBccAndCc_ShouldAddBccAndCc()
    {
        $contactAttributes = ['full_name' => 'Antony Masocha', 'email' => 'antony@prizeless.net'];

        $contact = new Contact($contactAttributes);


        $sendGrid = new SendGrid($this->message, $contact);

        $this->setSendGridLibraryMocks($sendGrid, $sendGridLib, $sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addTo')
            ->once()
            ->with('antony@prizeless.net', 'Antony Masocha')
            ->andReturn($sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addBcc')
            ->times(2)
            ->andReturn($sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addCc')
            ->times(2)
            ->andReturn($sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addUniqueArg')
            ->once()
            ->with('message_id', 1)
            ->andReturn($sendGridEmailLibMock);

        $this->messageExpectations($sendGridEmailLibMock, $sendGridLib);

        $bcc = $this->makeTestContacts(2);
        $cc = $this->makeTestContacts(3);

        Queue::shouldReceive('push')->once()->andReturn(true);

        $sendGrid->send($bcc, $cc);
    }

    public function testSendBulk_WithValidRecipients_ShouldSendMessage()
    {
        $contacts = $this->makeTestContacts();

        $sendGrid = new SendGrid($this->message, $contacts);

        $this->setSendGridLibraryMocks($sendGrid, $sendGridLib, $sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addSmtpapiTo')
            ->times(2000)
            ->andReturn($sendGridEmailLibMock);

        $sendGridEmailLibMock->shouldReceive('addUniqueArg')
            ->times(2000)
            ->andReturn($sendGridEmailLibMock);

        $this->messageExpectations($sendGridEmailLibMock, $sendGridLib);

        $sendGridLib->shouldReceive('sendBulk')->once()->with($sendGridEmailLibMock)->andReturn(true);

        $sendGridLib->shouldReceive('send')->times(2)->with($sendGridEmailLibMock)->andReturn(true);

        Queue::shouldReceive('push')->once()->andReturn(true);

        $sendGrid->sendBulk();
    }


    protected function makeTestContacts($contactCount = 2000)
    {
        $contacts = [];
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $contactCount; $i++) {
            $contact = new Contact(
                [
                    'full_name' => $faker->name,
                    'email' => $faker->safeEmail
                ]
            );
            $contacts[] = $contact;
        }
        return $contacts;
    }

    /**
     * @return Email
     */
    protected function makeTestMessage()
    {
        $messageAttributes = [
            'message_id' => 1,
            'from_name' => 'Antony Masocha',
            'from_email' => 'antony@prizeless.net',
            'html' => '<body>Testing</body>',
            'subject' => 'Test Subject'
        ];

        return (new Email($messageAttributes));
    }

    /**
     * @param $sendGridEmailLibMock
     * @param $sendGridLib
     */
    private function messageExpectations($sendGridEmailLibMock, $sendGridLib)
    {
        $sendGridEmailLibMock->shouldReceive('setFrom')->once()
            ->with('antony@prizeless.net')->andReturn($sendGridEmailLibMock);
        $sendGridEmailLibMock->shouldReceive('setFromName')->once()
            ->with('Antony Masocha')->andReturn($sendGridEmailLibMock);
        $sendGridEmailLibMock->shouldReceive('setSubject')->once()
            ->with('Test Subject')->andReturn($sendGridEmailLibMock);
        $sendGridEmailLibMock->shouldReceive('setHtml')->once()
            ->with('<body>Testing</body>')->andReturn($sendGridEmailLibMock);

        $sendGridLib->shouldNotReceive('send')->once()->with($sendGridEmailLibMock)->andReturn(true);
    }

    /**
     * @param $sendGrid
     * @param $sendGridLib
     * @param $sendGridEmailLibMock
     */
    private function setSendGridLibraryMocks($sendGrid, &$sendGridLib, &$sendGridEmailLibMock)
    {
        $sendGridLib = \Mockery::mock('\Sendgrid');
        $sendGrid->setTransport($sendGridLib);

        $sendGridEmailLibMock = \Mockery::mock('\SendGrid\Email');
        $sendGrid->setSendGridEmail($sendGridEmailLibMock);
    }
}
