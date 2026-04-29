<?php
/**
 * FightGear - Header do Painel Admin
 * @var string $pageTitle
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> | FightGear Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-logo">
            <a href="<?= BASE_URL ?>/admin/produtos.php">
                <span class="logo-fg">FIGHT</span><span class="logo-gear">GEAR</span>
            </a>
            <span class="sidebar-badge">ADMIN</span>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/admin/produtos.php"
               class="sidebar-link <?= strpos($_SERVER['PHP_SELF'], 'produtos') !== false ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                Produtos
            </a>
            <a href="<?= BASE_URL ?>/index.php" class="sidebar-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Ver Site
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <div class="sidebar-avatar"><?= strtoupper(substr($_SESSION['user_nome'] ?? 'A', 0, 1)) ?></div>
                <div>
                    <div class="sidebar-user-name"><?= e($_SESSION['user_nome'] ?? '') ?></div>
                    <div class="sidebar-user-role">Administrador</div>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/auth/login.php?action=logout" class="sidebar-logout">Sair</a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <span></span><span></span><span></span>
            </button>
            <h1 class="admin-page-title"><?= e($pageTitle ?? 'Painel') ?></h1>
        </header>

        <div class="admin-content">
