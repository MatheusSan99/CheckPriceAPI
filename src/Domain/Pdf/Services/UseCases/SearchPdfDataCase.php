<?php

namespace API\CheckPrice\Domain\Pdf\Services\UseCases;

class SearchPdfDataCase
{
    public function execute(string $pdfUrl): array
    {
        $temporaryPdfFile = $this->downloadPdf($pdfUrl);
        $pyScript = './src/Domain/Pdf/Services/extractPdfData.py';
    
        $command = sprintf(
            'python3 %s %s',
            escapeshellarg($pyScript),
            escapeshellarg($temporaryPdfFile)
        );
    
        exec($command, $output, $returnVar);
    
        if ($returnVar !== 0) {
            throw new \RuntimeException('Error: ' . implode("\n", $output));
        }
    
        $jsonOutput = implode('', $output);
        $data = json_decode($jsonOutput, true); 
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON returned from Python script.');
        }
    
        return $data;
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
