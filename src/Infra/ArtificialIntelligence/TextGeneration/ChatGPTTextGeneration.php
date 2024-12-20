<?php

namespace API\CheckPrice\Infra\ArtificialIntelligence\TextGeneration;

use API\CheckPrice\Infra\Connection\ConnectionInterface;

class ChatGPTTextGeneration implements TextGenerationInterface
{
    private ConnectionInterface $ConnectionInterface;

    public function __construct(ConnectionInterface $ConnectionInterface)
    {
        $this->ConnectionInterface = $ConnectionInterface;
    }

    public function askAI(string $question) : string
    {
        $responseData = $this->ConnectionInterface->getResponse($question);
        return $responseData['choices'][0]['text'] ?? 'No response';
    }
}