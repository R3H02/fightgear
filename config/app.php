<?php
/**
 * FightGear - Constantes e Helpers Globais
 */

// URL base da aplicação (ajuste se necessário)
define('BASE_URL', 'http://localhost/fightgear');
define('UPLOAD_DIR', __DIR__ . '/../uploads/products/');
define('UPLOAD_URL', BASE_URL . '/uploads/products/');
define('DEFAULT_IMG', BASE_URL . '/public/images/no-image.png');

// Inicia sessão se ainda não iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redireciona para uma URL
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Verifica se usuário está logado
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se o usuário logado é admin/funcionário
 */
function isAdmin(): bool {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Exige que o usuário esteja logado
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect(BASE_URL . '/auth/login.php');
    }
}

/**
 * Exige que o usuário seja admin
 */
function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        redirect(BASE_URL . '/index.php?error=acesso_negado');
    }
}

/**
 * Sanitiza uma string para exibição segura
 */
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Formata valor monetário em BRL
 */
function formatPrice(float $price): string {
    return 'R$ ' . number_format($price, 2, ',', '.');
}

/**
 * Exibe e limpa mensagens flash da sessão
 */
function flashMessage(): string {
    if (isset($_SESSION['flash'])) {
        $msg = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $class = $msg['type'] === 'success' ? 'alert-success' : 'alert-error';
        return "<div class=\"alert {$class}\">" . e($msg['message']) . "</div>";
    }
    return '';
}

/**
 * Define mensagem flash na sessão
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Gera token CSRF e salva na sessão
 */
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida token CSRF enviado no formulário
 */
function validateCsrf(): bool {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
}
