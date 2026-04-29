<?php
/**
 * FightGear - Controller de Produtos (Admin)
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Produto.php';

requireAdmin();

$action = $_GET['action'] ?? 'list';
$error  = '';
$success = '';
$produto = null;

// Paginação
$perPage = 10;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;
$busca   = trim($_GET['busca'] ?? '');

// ------- LISTAR -------
if ($action === 'list') {
    $total   = Produto::count($busca);
    $produtos = Produto::all($busca, '', $perPage, $offset);
    $totalPages = ceil($total / $perPage);
}

// ------- CRIAR -------
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        setFlash('error', 'Token inválido.');
        redirect(BASE_URL . '/admin/produtos.php?action=create');
    }

    $data = [
        'nome'      => trim($_POST['nome'] ?? ''),
        'descricao' => trim($_POST['descricao'] ?? ''),
        'preco'     => (float) str_replace(',', '.', $_POST['preco'] ?? '0'),
        'estoque'   => (int)($_POST['estoque'] ?? 0),
        'categoria' => trim($_POST['categoria'] ?? ''),
        'imagem'    => null,
    ];

    // Validações
    if (empty($data['nome'])) {
        $error = 'O nome do produto é obrigatório.';
    } elseif ($data['preco'] <= 0) {
        $error = 'O preço deve ser maior que zero.';
    } else {
        // Upload de imagem
        if (!empty($_FILES['imagem']['name'])) {
            $filename = Produto::uploadImagem($_FILES['imagem']);
            if ($filename === false) {
                $error = 'Erro no upload da imagem. Use JPG, PNG ou WEBP (máx 5MB).';
            } else {
                $data['imagem'] = $filename;
            }
        }

        if (empty($error)) {
            $id = Produto::create($data);
            if ($id) {
                setFlash('success', 'Produto cadastrado com sucesso!');
                redirect(BASE_URL . '/admin/produtos.php');
            } else {
                $error = 'Erro ao salvar o produto. Tente novamente.';
            }
        }
    }
}

// ------- EDITAR - carregar form -------
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    $produto = Produto::findById($id);
    if (!$produto) {
        setFlash('error', 'Produto não encontrado.');
        redirect(BASE_URL . '/admin/produtos.php');
    }
}

// ------- EDITAR - salvar -------
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        setFlash('error', 'Token inválido.');
        redirect(BASE_URL . '/admin/produtos.php');
    }

    $id = (int)($_POST['id'] ?? 0);
    $produto = Produto::findById($id);
    if (!$produto) {
        setFlash('error', 'Produto não encontrado.');
        redirect(BASE_URL . '/admin/produtos.php');
    }

    $data = [
        'nome'      => trim($_POST['nome'] ?? ''),
        'descricao' => trim($_POST['descricao'] ?? ''),
        'preco'     => (float) str_replace(',', '.', $_POST['preco'] ?? '0'),
        'estoque'   => (int)($_POST['estoque'] ?? 0),
        'categoria' => trim($_POST['categoria'] ?? ''),
    ];

    if (empty($data['nome'])) {
        $error = 'O nome do produto é obrigatório.';
    } elseif ($data['preco'] <= 0) {
        $error = 'O preço deve ser maior que zero.';
    } else {
        // Novo upload de imagem (opcional)
        if (!empty($_FILES['imagem']['name'])) {
            $filename = Produto::uploadImagem($_FILES['imagem']);
            if ($filename === false) {
                $error = 'Erro no upload da imagem. Use JPG, PNG ou WEBP (máx 5MB).';
            } else {
                // Remove imagem antiga
                if ($produto['imagem'] && file_exists(UPLOAD_DIR . $produto['imagem'])) {
                    unlink(UPLOAD_DIR . $produto['imagem']);
                }
                $data['imagem'] = $filename;
            }
        }

        if (empty($error)) {
            Produto::update($id, $data);
            setFlash('success', 'Produto atualizado com sucesso!');
            redirect(BASE_URL . '/admin/produtos.php');
        }
    }
}

// ------- DELETAR -------
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        setFlash('error', 'Token inválido.');
        redirect(BASE_URL . '/admin/produtos.php');
    }

    $id = (int)($_POST['id'] ?? 0);
    $produto = Produto::findById($id);
    if ($produto) {
        // Remove arquivo de imagem do servidor
        if ($produto['imagem'] && file_exists(UPLOAD_DIR . $produto['imagem'])) {
            unlink(UPLOAD_DIR . $produto['imagem']);
        }
        Produto::delete($id);
        setFlash('success', 'Produto removido com sucesso!');
    } else {
        setFlash('error', 'Produto não encontrado.');
    }
    redirect(BASE_URL . '/admin/produtos.php');
}
