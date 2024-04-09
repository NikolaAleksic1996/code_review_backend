<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\SendMessage;
use App\Repository\MessageRepository;
use App\Tests\Controller\MessageControllerTest;
// - Please remove unused import
use Doctrine\ORM\EntityManagerInterface;
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
     * - Method docblock is missed, you need add it like this
     *
     * @param Request $request
     * @param MessageRepository $messages
     * @return Response
     *
     * - You can explicitly specify the HTTP method(s) allowed for the endpoint.
     * This improves readability and maintainability by clearly indicating the intended usage of the endpoint.
     * You can use the methods attribute of the Route annotation to achieve this, and also you can add name of route
     * and use this method by name when is method call needed
     */
    #[Route('/messages')]
    public function list(Request $request, MessageRepository $messages): Response
    {
        $messages = $messages->by($request);

        /**
         * - You can move the logic of fetching messages and transforming them into an array of specific properties.
         *  This will help improve code maintainability and readability.
         */
        foreach ($messages as $key=>$message) {
            $messages[$key] = [
                'uuid' => $message->getUuid(),
                'text' => $message->getText(),
                'status' => $message->getStatus(),
            ];
        }
        /**
         * - You can use Symfony's JsonResponse class for better readability and handling of JSON responses
         *  instead of constructing the response manually using json_encode() and creating a new Response instance
         */
        return new Response(json_encode([
            'messages' => $messages,
        ], JSON_THROW_ON_ERROR), headers: ['Content-Type' => 'application/json']);


//        My code version
//        $formattedMessages = [];
//        foreach ($messages as $message) {
//            $formattedMessages[] = [
//                'uuid' => $message->getUuid(),
//                'text' => $message->getText(),
//                'status' => $message->getStatus(),
//            ];
//        }
//
//        return new JsonResponse(['messages' => $formattedMessages]);
    }

    /**
     * - Method docblock is missed
     * - By RESTfull principle you should change this method to be POST, because this method send data and dispatch event and return only status message for this request,
     * also message body can be too long, and it can't stay in url search parameter
     * - You can add name of route using name attribute of the Route annotation and use this method by name when is method call needed
     */
//    #[Route('/messages/send', methods: ['POST'])]
    #[Route('/messages/send', methods: ['GET'])]
    public function send(Request $request, MessageBusInterface $bus): Response
    {
        $text = $request->query->get('text');

        /**
         * - Please throw Symfony built-in exception "BadRequestHttpException" with message instead of manual creating Response
         */
        if (!$text) {
            return new Response('Text is required', 400);
        }

        $bus->dispatch(new SendMessage((string)$text));

        /**
         * - Please return JsonResponse with the appropriate content and a 200 OK status code if content is needed
         * status code 204 use for response with no content
         */
        return new Response('Successfully sent', 204);

//        My code version
        /**
         * - If we use POST method we need to decode body content
         */
//        $bodyContent = json_decode($request->getContent(), true);
//
//        if (empty($bodyContent['text'])) {
//            throw new BadRequestHttpException('Text is required');
//        }
//
//        $bus->dispatch(new SendMessage($bodyContent['text']));
//
//        // Return a JsonResponse with the desired content and a 200 OK status code
//        return new JsonResponse(['message' => 'Successfully sent'], 200);

    }
}