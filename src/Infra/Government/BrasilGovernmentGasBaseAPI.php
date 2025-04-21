<?php

namespace API\CheckPrice\Infra\Government;

use API\CheckPrice\Domain\Government\Services\GovernmentAPIInterface;
use API\CheckPrice\Infra\Exceptions\UrlException;

class BrasilGovernmentGasBaseAPI implements GovernmentAPIInterface
{
    public function getBaseUrl(): string
    {
        $pageUrl = 'https://www.gov.br/anp/pt-br/assuntos/precos-e-defesa-da-concorrencia/precos/levantamento-de-precos-de-combustiveis-ultimas-semanas-pesquisadas';

        $command = escapeshellcmd("python3 ../../../python/link-scraper.py $pageUrl");
        $output = shell_exec($command);
    
        $result = json_decode($output, true);
    
        if (!isset($result['url']) || empty($result['url'])) {
            throw new \RuntimeException("Não foi possível localizar o link do arquivo Excel.");
        }
    
        return $result['url'];
        
        throw new UrlException("Nenhuma URL válida encontrada", UrlException::ERROR_URL_NOT_FOUND);
    }


}