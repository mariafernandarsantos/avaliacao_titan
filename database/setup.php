<?php

/**
 * Script auxiliar 
 *
 *
 * Uso via terminal:
 *   php database/setup.php
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');

require_once APP_PATH . '/config/Database.php';

use app\config\Database;

$db = Database::getInstance();

//  Tabela: user
$db->exec("CREATE TABLE IF NOT EXISTS user (
    id_user    BIGINT(20)   NOT NULL AUTO_INCREMENT,
    name       VARCHAR(150) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at  DATETIME     NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ativo      TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id_user),
    UNIQUE KEY uq_user_email (email)
)");

echo "Tabela 'user' verificada/criada.\n";

//  Tabela: service
$db->exec("CREATE TABLE IF NOT EXISTS service (
    id_service      BIGINT(20)    NOT NULL AUTO_INCREMENT,
    description     VARCHAR(45)   NOT NULL,
    price           DECIMAL(11,3) NOT NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at       DATETIME      NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    finished_at     DATETIME      NULL     DEFAULT NULL,
    commission_user DECIMAL(11,3) NULL     DEFAULT NULL,
    user_id_user    BIGINT(20)    NOT NULL,
    PRIMARY KEY (id_service),
    CONSTRAINT fk_service_user
        FOREIGN KEY (user_id_user) REFERENCES user (id_user)
        ON DELETE RESTRICT ON UPDATE CASCADE
)");

echo "Tabela 'service' verificada/criada.\n\n";

// ------------------------------------------------------------------
//  Usuários de teste (senha padrão: 123456)
// ------------------------------------------------------------------
$hash = password_hash('123456', PASSWORD_BCRYPT);

$usuarios = [
    ['Admin JM',        'admin@jminformatica.com'],
    ['Maria Rodrigues', 'maria@jminformatica.com'],
    ['Fernanda Santos', 'fernanda@jminformatica.com'],
];

$stmtU = $db->prepare(
    'INSERT IGNORE INTO user (name, email, password) VALUES (?, ?, ?)'
);

foreach ($usuarios as [$nome, $email]) {
    $stmtU->execute([$nome, $email, $hash]);
    echo "Usuário: {$nome} ({$email})\n";
}

// ------------------------------------------------------------------
//  Serviços de exemplo
// ------------------------------------------------------------------
$adminId    = $db->query("SELECT id_user FROM user WHERE email = 'admin@jminformatica.com' LIMIT 1")->fetchColumn();
$mariaId    = $db->query("SELECT id_user FROM user WHERE email = 'maria@jminformatica.com' LIMIT 1")->fetchColumn();
$fernandaId = $db->query("SELECT id_user FROM user WHERE email = 'fernanda@jminformatica.com' LIMIT 1")->fetchColumn();

$servicos = [
    ['Servidor',            2500.000,  $adminId,    '2026-07-10 14:30:00', 250.000],
    ['Suporte remoto',       350.000,  $mariaId,    null,                  null   ],
    ['Site institucional',  8000.000,  $adminId,    null,                  null   ],
    ['Manut. computadores',  500.000,  $fernandaId, '2026-07-15 09:00:00',  25.000],
    ['Rede Wi-Fi',          1200.000,  $mariaId,    null,                  null   ],
    ['Migração para nuvem',15000.000,  $adminId,    null,                  null   ],
];

$stmtS = $db->prepare(
    'INSERT INTO service (description, price, user_id_user, finished_at, commission_user)
     VALUES (?, ?, ?, ?, ?)'
);

foreach ($servicos as [$desc, $price, $uid, $fin, $com]) {
    $stmtS->execute([$desc, $price, $uid, $fin, $com]);
    echo "Serviço: {$desc}\n";
}

echo "\nSetup concluído\n";
echo "  Login  : admin@jminformatica.com\n";
echo "  Senha  : 123456\n";
