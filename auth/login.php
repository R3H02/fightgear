<?php
/**
 * FightGear - Página de Login / Cadastro
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../controllers/AuthController.php';

// AuthController define $action, $error, $success
$pageTitle = $action === 'register' ? 'Criar Conta' : 'Entrar';
$flash = flashMessage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | FightGear</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">
        <a href="<?= BASE_URL ?>/index.php" class="auth-logo">
            <span class="logo-fg">FIGHT</span><span class="logo-gear">GEAR</span>
        </a>

        <?php if ($action === 'login'): ?>

        <p class="auth-subtitle">Bem-vindo de volta! Entre na sua conta.</p>

        <?= $flash ?>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/auth/login.php?action=login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="seu@email.com"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">
                Entrar
            </button>
        </form>

        <div class="auth-footer">
            Não tem conta? <a href="<?= BASE_URL ?>/auth/login.php?action=register">Cadastre-se grátis</a>
        </div>

        <!-- Dica dev -->
        <div style="margin-top:28px;padding:16px;background:var(--dark3);border-radius:var(--radius);border:1px dashed var(--border);">
            <p style="font-size:0.76rem;color:var(--muted);font-family:monospace;line-height:1.7;">
                <strong style="color:var(--text-dim);">Admin demo:</strong><br>
                📧 admin@fightgear.com<br>
                🔑 password
            </p>
        </div>

        <?php else: ?>

        <p class="auth-subtitle">Crie sua conta e comece a explorar.</p>

        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/auth/login.php?action=register" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label class="form-label" for="nome">Nome completo</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    class="form-control"
                    placeholder="Seu nome"
                    value="<?= e($_POST['nome'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="seu@email.com"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="senha">Senha <span style="color:var(--muted);font-weight:300;">(mín. 6 caracteres)</span></label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    class="form-control"
                    placeholder="••••••••"
                    required
                    minlength="6"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="confirmar_senha">Confirmar senha</label>
                <input
                    type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">
                Criar Conta
            </button>
        </form>

        <div class="auth-footer">
            Já tem conta? <a href="<?= BASE_URL ?>/auth/login.php?action=login">Entrar</a>
        </div>

        <?php endif; ?>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
