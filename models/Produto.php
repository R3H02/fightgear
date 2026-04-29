<?php
/**
 * FightGear - Model: Produto
 * Todas as operações de banco relacionadas a produtos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';

class Produto {

    /**
     * Retorna todos os produtos, com filtro e paginação opcional
     */
    public static function all(string $busca = '', string $categoria = '', int $limit = 0, int $offset = 0): array {
        $db = getDB();
        $sql = "SELECT * FROM produtos WHERE 1=1";
        $params = [];

        if ($busca !== '') {
            $sql .= " AND (nome LIKE ? OR descricao LIKE ?)";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
        }
        if ($categoria !== '') {
            $sql .= " AND categoria = ?";
            $params[] = $categoria;
        }

        $sql .= " ORDER BY criado_em DESC";

        if ($limit > 0) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Conta total de produtos (para paginação)
     */
    public static function count(string $busca = '', string $categoria = ''): int {
        $db = getDB();
        $sql = "SELECT COUNT(*) FROM produtos WHERE 1=1";
        $params = [];

        if ($busca !== '') {
            $sql .= " AND (nome LIKE ? OR descricao LIKE ?)";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
        }
        if ($categoria !== '') {
            $sql .= " AND categoria = ?";
            $params[] = $categoria;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Busca produto por ID
     */
    public static function findById(int $id): ?array {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM produtos WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $produto = $stmt->fetch();
        return $produto ?: null;
    }

    /**
     * Cria novo produto
     */
    public static function create(array $data): int|false {
        $db = getDB();
        $stmt = $db->prepare(
            "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, categoria)
             VALUES (:nome, :descricao, :preco, :estoque, :imagem, :categoria)"
        );
        $stmt->execute([
            ':nome'      => $data['nome'],
            ':descricao' => $data['descricao'],
            ':preco'     => $data['preco'],
            ':estoque'   => $data['estoque'],
            ':imagem'    => $data['imagem'] ?? null,
            ':categoria' => $data['categoria'] ?? null,
        ]);
        return (int) $db->lastInsertId() ?: false;
    }

    /**
     * Atualiza produto existente
     */
    public static function update(int $id, array $data): bool {
        $db = getDB();

        // Monta SET dinamicamente para suportar imagem opcional
        $fields = ['nome = :nome', 'descricao = :descricao', 'preco = :preco',
                   'estoque = :estoque', 'categoria = :categoria'];
        $params = [
            ':nome'      => $data['nome'],
            ':descricao' => $data['descricao'],
            ':preco'     => $data['preco'],
            ':estoque'   => $data['estoque'],
            ':categoria' => $data['categoria'] ?? null,
            ':id'        => $id,
        ];

        if (isset($data['imagem'])) {
            $fields[] = 'imagem = :imagem';
            $params[':imagem'] = $data['imagem'];
        }

        $sql = "UPDATE produtos SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Remove produto pelo ID
     */
    public static function delete(int $id): bool {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna categorias distintas cadastradas
     */
    public static function categorias(): array {
        $db = getDB();
        $stmt = $db->query("SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL ORDER BY categoria");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Retorna os N produtos mais recentes (para destaque)
     */
    public static function destaques(int $limit = 8): array {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM produtos ORDER BY criado_em DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Faz upload da imagem e retorna o nome do arquivo salvo
     */
    public static function uploadImagem(array $file): string|false {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) return false;
        if ($file['size'] > $maxSize) return false;
        if ($file['error'] !== UPLOAD_ERR_OK) return false;

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_', true) . '.' . strtolower($ext);
        $dest = UPLOAD_DIR . $filename;

        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }
        return false;
    }

    /**
     * Retorna URL da imagem do produto
     */
    public static function imagemUrl(?string $imagem): string {
        if ($imagem && file_exists(UPLOAD_DIR . $imagem)) {
            return UPLOAD_URL . $imagem;
        }
        return DEFAULT_IMG;
    }
}
