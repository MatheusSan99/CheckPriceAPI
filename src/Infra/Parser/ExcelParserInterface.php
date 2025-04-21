<?php

namespace API\CheckPrice\Infra\Parser;

interface ExcelParserInterface
{
    public function parse(string $file): array;
}