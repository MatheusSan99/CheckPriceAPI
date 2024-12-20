<?php

namespace API\CheckPrice\Infra\ArtificialIntelligence\TextGeneration;

interface TextGenerationInterface
{
    public function askAI(string $question) : string;
}