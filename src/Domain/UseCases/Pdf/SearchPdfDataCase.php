<?php

namespace API\CheckPrice\Domain\UseCases\PdfHandler;

class SearchPdfDataCase
{
    public function execute(string $pdfUrl): string
    {
        $temporaryPdfFile = $this->downloadPdf($pdfUrl);
        return $this->convertPdfToText($temporaryPdfFile);
    }

    private function downloadPdf(string $pdfUrl): string
    {
        $pdf = file_get_contents($pdfUrl);
        $temporaryPdfFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($temporaryPdfFile, $pdf);
        return $temporaryPdfFile;
    }

    private function convertPdfToText(string $pdfFilePath): string
    {
        $outputFilePath = tempnam(sys_get_temp_dir(), 'txt');

        $command = sprintf(
            'pdftotext -layout %s %s',
            escapeshellarg($pdfFilePath),
            escapeshellarg($outputFilePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \RuntimeException('Error executing pdftotext command.');
        }

        $text = file_get_contents($outputFilePath);

        unlink($outputFilePath);

        return $text;
    }
}
