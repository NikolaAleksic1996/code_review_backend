<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\SendMessage;
use App\Repository\MessageRepository;
use App\Tests\Controller\MessageControllerTest;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @see MessageControllerTest
 * TODO: review both methods and also the `openapi.yaml` specification
 *       Add Comments for your Code-Review, so that the developer can understand why changes are needed.
 *
 */
class MessageController extends AbstractController
{
    /**
     * TODO: cover this method with tests, and refactor the code (including other files that need to be refactored)
     */

    /**
     * Method docblock is missed, you need add it like this
     *
     * @param Request $request
     * @param MessageRepository $messages
     * @return Response
     * @throws JsonException
     */
    /** you can explicitly specify the HTTP method(s) allowed for the endpoint.
     * This improves readability and maintainability by clearly indicating the intended usage of the endpoint.
     * You can use the methods attribute of the Route annotation to achieve this
     */
    #[Route('/messages')]
    public function list(Request $request, MessageRepository $messages): Response
    {
        $messages = $messages->by($request);
  
        foreach ($messages as $key=>$message) {
            $messages[$key] = [
                'uuid' => $message->getUuid(),
                'text' => $message->getText(),
                'status' => $message->getStatus(),
            ];
        }
        
        return new Response(json_encode([
            'messages' => $messages,
        ], JSON_THROW_ON_ERROR), headers: ['Content-Type' => 'application/json']);
    }

    /**
     * Method docblock is missed, you need add it like this
     *
     * @param Request $request
     * @param MessageBusInterface $bus
     * @return Response
     */
    #[Route('/messages/send', methods: ['GET'])]
    public function send(Request $request, MessageBusInterface $bus): Response
    {
        $text = $request->query->get('text');
        
        if (!$text) {
            return new Response('Text is required', 400);
        }

        $bus->dispatch(new SendMessage($text));
        
        return new Response('Successfully sent', 204);
    }
}