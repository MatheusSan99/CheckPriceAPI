<?php

declare(strict_types=1);

$dbPath = __DIR__ . '/database.db';
$dbDir = __DIR__; 

if (!file_exists($dbDir)) {
    mkdir($dbDir, 0777, true); 
}

if (!file_exists($dbPath)) {
    touch($dbPath); 
    chmod($dbPath, 0777);
}

chmod($dbDir, 0777); 

$pdo = new PDO("sqlite:$dbPath");

$createAccountsTable = 
    'CREATE TABLE IF NOT EXISTS accounts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    );';

$createLogTable =
    'CREATE TABLE IF NOT EXISTS monolog (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        channel TEXT NOT NULL,
        level INTEGER NOT NULL,
        message TEXT NOT NULL,
        time INTEGER NOT NULL
    );';

$createGasTable =
    'CREATE TABLE IF NOT EXISTS combustiveis (
        CNPJ TEXT,
        RAZAO TEXT,
        FANTASIA TEXT,
        ENDERECO TEXT,
        NUMERO TEXT,
        COMPLEMENTO TEXT,
        BAIRRO TEXT,
        CEP TEXT,
        MUNICIPIO TEXT,
        ESTADO TEXT,
        BANDEIRA TEXT,
        PRODUTO TEXT,
        UNIDADE_MEDIDA TEXT,
        PRECO_REVENDA REAL,
        DATA_COLETA TEXT,
        PRIMARY KEY (CNPJ, PRODUTO, DATA_COLETA)
    );';

$pdo->exec($createAccountsTable);
$pdo->exec($createLogTable);
$pdo->exec($createGasTable);

$checkUserAdmin = 'SELECT * FROM accounts WHERE email = "admin@gmail.com";';

$statement = $pdo->query($checkUserAdmin);

if (!empty($statement->fetchAll())) {
    return;
}

$password = password_hash('123456', PASSWORD_ARGON2ID);

$inserirUsuarioPadrao = 'INSERT INTO accounts (name, email, password, role) VALUES ("Administrador", "admin@gmail.com", ?, "admin");';

$statement = $pdo->prepare($inserirUsuarioPadrao);

$statement->bindValue(1, $password);

$statement->execute();