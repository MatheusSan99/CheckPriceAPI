<?php

namespace API\AbasteceFacil\Services;

trait PdfHandler
{
    public function searchDataPDF($pdfUrl) {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($pdfUrl);
        
        $text = $pdf->getText();
        $pages = $pdf->getPages()[0]->getDataTm();

        var_dump($pages);
        die();

    }

}
