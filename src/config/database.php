<?php
class Database {
    private $pdo;
    private static $instance = null;

    private function __construct() {
        // Cargar variables de entorno
        $dotenv = parse_ini_file(__DIR__ . '/../.env');
        $host = $dotenv['DB_HOST'];
        $dbname = $dotenv['DB_NAME'];
        $username = $dotenv['DB_USER'];
        $password = $dotenv['DB_PASS'];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>