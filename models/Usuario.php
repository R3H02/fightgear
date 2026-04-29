<?php
/**
 * FightGear - Model: Usuario
 * Todas as operações de banco relacionadas a usuários
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';

class Usuario {

    /**
     * Busca usuário por e-mail
     */
    public static function findByEmail(string $email): ?array {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Busca usuário por ID
     */
    public static function findById(int $id): ?array {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, nome, email, tipo, criado_em FROM usuarios WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Cria novo usuário cliente
     * Retorna o ID inserido ou false
     */
    public static function create(string $nome, string $email, string $senha, string $tipo = 'cliente'): int|false {
        $db = getDB();
        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $db->prepare(
            "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$nome, $email, $hash, $tipo]);
        return (int) $db->lastInsertId() ?: false;
    }

    /**
     * Verifica credenciais e retorna dados do usuário (sem senha)
     */
    public static function authenticate(string $email, string $senha): ?array {
        $user = self::findByEmail($email);
        if ($user && password_verify($senha, $user['senha'])) {
            unset($user['senha']); // nunca retorna o hash
            return $user;
        }
        return null;
    }

    /**
     * Verifica se um e-mail já está cadastrado
     */
    public static function emailExists(string $email): bool {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Lista todos os usuários (somente admin)
     */
    public static function all(): array {
        $db = getDB();
        $stmt = $db->query("SELECT id, nome, email, tipo, criado_em FROM usuarios ORDER BY criado_em DESC");
        return $stmt->fetchAll();
    }
}
