<?php
/**
 * FightGear - Controller de Autenticação
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Usuario.php';

$action = $_GET['action'] ?? 'login';
$error  = '';
$success = '';

// Redireciona se já logado
if (isLoggedIn()) {
    redirect(isAdmin() ? BASE_URL . '/admin/produtos.php' : BASE_URL . '/index.php');
}

// --- PROCESSAR LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        $error = 'Token inválido. Tente novamente.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $error = 'Preencha todos os campos.';
        } else {
            $user = Usuario::authenticate($email, $senha);
            if ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_type'] = $user['tipo'];
                session_regenerate_id(true);

                if ($user['tipo'] === 'admin') {
                    redirect(BASE_URL . '/admin/produtos.php');
                } else {
                    redirect(BASE_URL . '/index.php');
                }
            } else {
                $error = 'E-mail ou senha incorretos.';
            }
        }
    }
}

// --- PROCESSAR REGISTRO ---
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        $error = 'Token inválido. Tente novamente.';
    } else {
        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $conf  = $_POST['confirmar_senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            $error = 'Preencha todos os campos.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'E-mail inválido.';
        } elseif (strlen($senha) < 6) {
            $error = 'A senha deve ter pelo menos 6 caracteres.';
        } elseif ($senha !== $conf) {
            $error = 'As senhas não coincidem.';
        } elseif (Usuario::emailExists($email)) {
            $error = 'Este e-mail já está cadastrado.';
        } else {
            $id = Usuario::create($nome, $email, $senha, 'cliente');
            if ($id) {
                setFlash('success', 'Cadastro realizado! Faça login para continuar.');
                redirect(BASE_URL . '/auth/login.php?action=login');
            } else {
                $error = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}

// --- LOGOUT ---
if ($action === 'logout') {
    session_destroy();
    redirect(BASE_URL . '/index.php');
}
