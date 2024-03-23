<?php

namespace API\AbasteceFacil\Services;

use DateTime;
use DateTimeZone;

trait ConnectionHandler
{
    use PdfHandler;
    
    public function searchPrices($month = null, $year = null) {


        $pdfUrl = "https://www.joinville.sc.gov.br/wp-content/uploads/2024/{$numericMonth}/Pesquisa-Precos-Combustiveis-{$month}{$year}.pdf'";

    }



    private function removeHeaderPDF() {
        
    }
}
