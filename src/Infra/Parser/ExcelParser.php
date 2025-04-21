<?php

namespace API\CheckPrice\Infra\Parser;

use API\CheckPrice\Infra\Exceptions\ParserException;
use API\CheckPrice\Infra\Parser\ExcelParserInterface;

class ExcelParser implements ExcelParserInterface
{
    public function parse(string $fileContent): array
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_') . '.xlsx';
        file_put_contents($tempFile, $fileContent);
    
        $command = escapeshellcmd("python3 ../../../python/gas-parser.py {$tempFile}");
        $output = shell_exec($command);
    
        unlink($tempFile); 
    
        if (!$output) {
            throw new ParserException("Erro ao executar o comando de parsing.", ParserException::ERROR_PARSER_FAILED);
        }
    
        $decoded = json_decode($output, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParserException("Erro ao decodificar o JSON: " . json_last_error_msg(), ParserException::ERROR_INVALID_JSON);
        }
    
        return $decoded;
    }
}