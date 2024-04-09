<?php
declare(strict_types=1);

// namespace Controller;
// namespace is incorrect, you need set correct path to this class, to be visible for whole project, like this
namespace App\Tests\Controller;

use App\Controller\MessageController;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class MessageControllerTest extends WebTestCase
{
    use InteractsWithMessenger;

    /**
     * @dataProvider createMessagesProvider
     *
     * @param Message[] $messages
     * @return void
     */
    function test_list(array $messages): void
    {
        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
        }
        $messageRepositoryMock = $this->createMock(MessageRepository::class);

        $messageRepositoryMock->expects($this->once())
            ->method('by')
            ->willReturn($messages);

        $requestMock = $this->createMock(Request::class);

        $controller = new MessageController();
        $response = $controller->list($requestMock, $messageRepositoryMock);

        $this->assertInstanceOf(Response::class, $response);

        $responseContent = $response->getContent();
        $this->assertIsString($responseContent, 'Response content is not a string.');

        if (!$responseContent) {
            $this->fail('Failed to retrieve response content.');
        }

        $responseData = json_decode($responseContent, true);

        if (!is_array($responseData)) {
            $this->fail('Decoded response data is not an array.');
        }

        if (!isset($responseData['messages']) || !is_array($responseData['messages'])) {
            $this->fail('Key "messages" does not exist or is not an array in response data.');
        }

        $this->assertCount(count($messages), $responseData['messages'], 'Number of messages mismatch.');

        foreach ($responseData['messages'] as $message) {
            $this->assertArrayHasKey('uuid', $message, 'Key "uuid" does not exist in a message.');
            $this->assertArrayHasKey('text', $message, 'Key "text" does not exist in a message.');
            $this->assertArrayHasKey('status', $message, 'Key "status" does not exist in a message.');
        }
    }

    /**
     * @return Message[]
     */
    function createMessagesProvider(): array
    {
        $messages = [];

        $message1 = new Message();
        $message1->setUuid('uuid1');
        $message1->setText('Hello');
        $message1->setStatus('sent');
        $messages[] = $message1;

        $message2 = new Message();
        $message2->setUuid('uuid2');
        $message2->setText('World');
        $message2->setStatus('read');
        $messages[] = $message2;

        return [[$messages]];
    }

//    function test_that_it_sends_a_message(): void
//    {
//        $client = static::createClient();
//        $client->request('GET', '/messages/send', [
//            'text' => 'Hello World',
//        ]);
//
//        $this->assertResponseIsSuccessful();
//        // This is using https://packagist.org/packages/zenstruck/messenger-test
//        $this->transport('sync')
//            ->queue()
//            ->assertContains(SendMessage::class, 1);
//    }
}