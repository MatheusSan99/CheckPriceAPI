<?php

namespace API\CheckPrice\Services\PdfHandler;

use Smalot\PdfParser\Document;

interface PdfHandlerInterface
{
    public function searchDataPDF(string $pdfUrl) : Document;
}