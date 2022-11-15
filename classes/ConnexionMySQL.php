<?php

declare(strict_types=1);

namespace classes;
use PDO;
use PDOException;

final class ConnexionMySQL
{
    private const DB_NAME = 'acme';
    private const DB_HOST = 'localhost';
    private const DB_PORT = 3306;
    private const DB_CHARSET = 'utf8mb4';
    private const DB_LOG = 'root';
    private const DB_PWD = '';
    private const DB_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    // Instance PDO encapsulée
    private ?PDO $pdo = null;
    // Instance Singleton
    private static ?self $instance = null;

    private function __construct() {
        $dsn  = "mysql:dbname=" . self::DB_NAME . "; 
                    host = " . self::DB_HOST . ";
                    port = " . self::DB_PORT . ";
                    charset = " . self::DB_CHARSET;
        try {
            $this->pdo = new PDO($dsn, self::DB_LOG, self::DB_PWD, self::DB_OPTIONS);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public static function getInstance(): self {
        return self::$instance?: self::$instance = new self();
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }
}
