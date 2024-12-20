<?php

namespace API\CheckPrice\Domain\ArtificialIntelligence\UseCases;

use API\CheckPrice\Infra\ArtificialIntelligence\TextGeneration\TextGenerationInterface;

class ChatService
{
    private TextGenerationInterface $TextGenerationInterface;

    public function __construct(TextGenerationInterface $TextGenerationInterface)
    {
        $this->TextGenerationInterface = $TextGenerationInterface;
    }
    
    public function execute(string $question) : string
    {
        return $this->TextGenerationInterface->askAI($question);
    } 
}
