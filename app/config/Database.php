<?php

namespace app\config;

use PDO;
use PDOException;

/**
 * Conexão com o banco de dados via PDO
 * Para alterar as credenciais, edite as propriedades abaixo
 */
class Database
{
    private static ?PDO $instance = null;

    // Credenciais
    private string $host     = 'localhost';
    private string $dbname   = 'jm_db';
    private string $user     = 'root';
    private string $password = '';
    private string $charset  = 'utf8mb4';

    private function __construct() {}
    private function __clone() {}

    /**
     * Retorna a instância única da conexão PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $db  = new self();
            $dsn = "mysql:host={$db->host};dbname={$db->dbname};charset={$db->charset}";

            try {
                self::$instance = new PDO($dsn, $db->user, $db->password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log('[DB] Falha na conexão: ' . $e->getMessage());
                die('Serviço temporariamente indisponível. Tente novamente mais tarde.');
            }
        }

        return self::$instance;
    }
}