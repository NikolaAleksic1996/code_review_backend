<?php
declare(strict_types=1);

//namespace Repository;
// namespace is incorrect, you need set correct path to this class, to be visible for whole project, like this

namespace App\Tests\Repository;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageRepositoryTest extends KernelTestCase
{
    private MessageRepository $messageRepository;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->messageRepository = $this->createMock(MessageRepository::class);
    }

    public function test_it_has_connection(): void
    {
        $this->assertInstanceOf(MessageRepository::class, $this->messageRepository);
    }

    public function test_find_all_returns_empty_array(): void
    {
        $messages = $this->messageRepository->findAll();

        $this->assertIsArray($messages);
        $this->assertEmpty($messages);
    }
}