<?php
/**
 * FightGear - Configuração do Banco de Dados
 * Altere as credenciais conforme seu ambiente local (XAMPP)
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Usuário padrão do XAMPP
define('DB_PASS', '');            // Senha padrão do XAMPP (vazia)
define('DB_NAME', 'fightgear');
define('DB_CHARSET', 'utf8mb4');

/**
 * Retorna uma conexão PDO com o banco de dados
 */
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Erro de conexão com o banco de dados: ' . $e->getMessage()]));
        }
    }

    return $pdo;
}
