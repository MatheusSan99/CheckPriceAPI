<?php

namespace API\CheckPrice\Services\PdfHandler;

use Smalot\PdfParser\Document;

trait PdfHandler
{
    public function searchDataPDF(string $pdfUrl) : Document 
    {
        $parser = new \Smalot\PdfParser\Parser();

        return $parser->parseFile($pdfUrl);
    }
}