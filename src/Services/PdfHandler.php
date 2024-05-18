<?php

namespace API\CheckPrice\Services;

trait PdfHandler
{
    public function searchDataPDF($pdfUrl) {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($pdfUrl);
        $arrText = explode("\n", $pdf->getText());
        $content = $this->defineContent($arrText);

        return $content;
    }

    private function defineContent($arrText) {
        $returnFormatted = [];
        $contContent = 0;      
        $contNameAddress = 0;      

        $arrText = array_slice($arrText, 8);

        foreach($arrText as $index => $content) {
            if (substr($content, 0, 1) == ' ') {
                break;
            }

            if ($contNameAddress == 2) {
                $contNameAddress = 0;
                continue;
            }

            if (is_numeric(substr($content, 0, 1))) {
                $arrContent = explode("\t", $content);
                $arrContent = explode(' ', $arrContent[1]);

                if (count($arrContent) != 6) {
                    continue;
                }

                $returnFormatted[] = [
                    'Posto' => '',
                    'Endereço' => '',
                    'Bandeira' => $arrContent[0],
                    'Gasolina Comum' => $arrContent[1],
                    'Gasolina Aditivada' => $arrContent[2],
                    'Diesel' => $arrContent[3],
                    'Etanol' => $arrContent[4],
                    'GNV' => $arrContent[5]
                ];
            } else {
                $returnFormatted[$contContent]['Posto'] = $content;
                $contNameAddress++;
                $returnFormatted[$contContent]['Endereço'] = $arrText[$index + 1];
                $contNameAddress++;
                $contContent++;
            }
        }
        return $returnFormatted;
    }

}
