<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;

class ChatbotController extends AbstractController
{
    /**
     * @Route("/handle-user-query", name="handle_user_query", methods={"POST"})
     */
    public function handleUserQuery(Request $request): JsonResponse
    {
        $query = $request->get('query');

        $sessionClient = new SessionsClient(['credentials' => 'C:\config\insighter.json']);
        $session = $sessionClient->sessionName('newagent-xahj', uniqid());

        $textInput = new TextInput();
        $textInput->setText($query);
        $textInput->setLanguageCode('en-US');

        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        $response = $sessionClient->detectIntent($session, $queryInput);

        $fulfillmentText = $response->getQueryResult()->getFulfillmentText();

        return new JsonResponse(['response' => $fulfillmentText]);
    }
}
