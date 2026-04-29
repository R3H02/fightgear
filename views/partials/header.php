<?php
/**
 * FightGear - Header global do site
 * @var string $pageTitle Título da página
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'FightGear') ?> | FightGear</title>
    <meta name="description" content="FightGear – Equipamentos de luta de alta performance. Luvas, kimonos, protetores e muito mais.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<header class="site-header" id="site-header">
    <nav class="nav-container">
        <a href="<?= BASE_URL ?>/index.php" class="nav-logo">
            <span class="logo-fg">FIGHT</span><span class="logo-gear">GEAR</span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="<?= BASE_URL ?>/index.php">Início</a></li>
            <li><a href="<?= BASE_URL ?>/products/list.php">Produtos</a></li>
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="<?= BASE_URL ?>/admin/produtos.php" class="nav-admin">Painel Admin</a></li>
                <?php endif; ?>
                <li class="nav-user">
                    <span class="nav-username">Olá, <?= e($_SESSION['user_nome'] ?? '') ?></span>
                    <a href="<?= BASE_URL ?>/auth/login.php?action=logout" class="btn btn-outline-sm">Sair</a>
                </li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>/auth/login.php?action=login" class="btn btn-outline-sm">Entrar</a></li>
                <li><a href="<?= BASE_URL ?>/auth/login.php?action=register" class="btn btn-primary-sm">Cadastrar</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main class="main-content">
