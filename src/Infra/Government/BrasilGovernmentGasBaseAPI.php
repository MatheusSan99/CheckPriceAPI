<?php

namespace API\CheckPrice\Infra\Government;

use API\CheckPrice\Domain\Government\Services\GovernmentAPIInterface;
use API\CheckPrice\Infra\Exceptions\UrlException;

class BrasilGovernmentGasBaseAPI implements GovernmentAPIInterface
{
    public function getBaseUrl(): string
    {
        // URL da página a ser passada para o Python
        $pageUrl = 'https://www.gov.br/anp/pt-br/assuntos/precos-e-defesa-da-concorrencia/precos/levantamento-de-precos-de-combustiveis-ultimas-semanas-pesquisadas';

        // Alteração para chamar o Python diretamente no container pythonworker
        // pythonworker é o nome do container no Docker
        $command = escapeshellcmd("docker exec pythonworker python3 /app/python/gas-scraper.py $pageUrl");
        $output = shell_exec($command);
    
        // Decodifica a saída JSON
        $result = json_decode($output, true);
    
        if (!isset($result['url']) || empty($result['url'])) {
            throw new \RuntimeException("Não foi possível localizar o link do arquivo Excel.");
        }
    
        return $result['url'];
        
        throw new UrlException("Nenhuma URL válida encontrada", UrlException::ERROR_URL_NOT_FOUND);
    }
}
