<?php

namespace App\Tests\Message;

use App\Entity\Message;
use App\Enum\MessageStatusType;
use App\Message\SendMessage;
use App\Message\SendMessageHandler;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\PersisterException;
use PHPUnit\Framework\TestCase;

class SendMessageHandlerTest extends TestCase
{
    /**
     * @return void
     */
    public function testHandleMessageSend(): void
    {
        $text = 'Test message';
        $status = MessageStatusType::SENT->value;
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (Message $message) use ($text, $status) {
                return $message->getUuid() !== null
                    && $message->getText() === $text
                    && $message->getStatus() === $status
                    && $message->getCreatedAt() instanceof DateTime;
            }));

        $entityManager->expects($this->once())
            ->method('flush');

        $sendMessage = new SendMessage($text);

        $handler = new SendMessageHandler($entityManager);

        $handler($sendMessage);
    }

    /**
     * @return void
     */
    public function testHandleMessageSendWithError(): void
    {
        $text = 'Test message';

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method('persist')
            ->willThrowException(new PersisterException('Error occurred during persisting'));

        $entityManager->expects($this->never())
            ->method('flush');

        $sendMessage = new SendMessage($text);

        $handler = new SendMessageHandler($entityManager);

        $this->expectException(PersisterException::class);
        $this->expectExceptionMessage('Error occurred during persisting');

        $handler($sendMessage);
    }
}
