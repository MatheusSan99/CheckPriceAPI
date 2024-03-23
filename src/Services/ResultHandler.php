<?php

namespace API\AbasteceFacil\Services;

use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response;

trait ResultHandler
{
    public static function databaseHandleResult(
        bool $result,
        $stmt,
        string $typeOfManipulation,
        int $lastInsertedId = null
    ): array {
        switch ($typeOfManipulation) {
            case 'insert':
                $successMsg = [
                    'Info' => 'Dados inseridos com sucesso',
                    'idInserido' => $lastInsertedId, 'status' => 200
                ];

                break;
            case 'update':
                $successMsg = ['Info' => 'Dados atualizados com sucesso', 'status' => 200];
                break;
            case 'delete':
                $successMsg = ['Info' => 'Dados deletados com sucesso', 'status' => 200];
                break;
            default:
                $successMsg = ['Info' => 'Operação executada com sucesso'];
        }

        if ($result === false) {
            $successMsg = ['Erro' => 'Erro ao executar query: ' . $typeOfManipulation . " " . $stmt->errorInfo()[2]];
        }

        return $successMsg;
    }

    public function jsonResponse(array $data, int $statusCode): ResponseInterface
    {
        if (empty($data)) {
            $data = ['Erro' => 'Nenhum dado foi encontrado'];
            $statusCode = 404;
        }

        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }
}
