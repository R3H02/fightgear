<?php
/**
 * FightGear - Listagem de Produtos
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Produto.php';

$pageTitle = 'Produtos';

$busca     = trim($_GET['busca'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');
$perPage   = 12;
$page      = max(1, (int)($_GET['page'] ?? 1));
$offset    = ($page - 1) * $perPage;

$total      = Produto::count($busca, $categoria);
$produtos   = Produto::all($busca, $categoria, $perPage, $offset);
$totalPages = (int)ceil($total / $perPage);
$categorias = Produto::categorias();

require_once __DIR__ . '/../views/partials/header.php';
?>

<!-- Page hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title">NOSSOS PRODUTOS</h1>
        <p class="page-hero-sub">Equipamentos de alta performance para todas as modalidades de luta</p>
    </div>
</div>

<section style="padding:60px 0 80px;">
    <div class="container">

        <!-- Filter bar -->
        <div class="filter-bar">
            <form method="GET" action="">
                <?php if ($categoria): ?>
                    <input type="hidden" name="categoria" value="<?= e($categoria) ?>">
                <?php endif; ?>
                <input
                    type="search"
                    name="busca"
                    class="search-input"
                    placeholder="Buscar produtos..."
                    value="<?= e($busca) ?>"
                >
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busca || $categoria): ?>
                    <a href="<?= BASE_URL ?>/products/list.php" class="btn btn-outline">Limpar</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Category pills -->
        <div class="category-pills">
            <button
                class="category-pill <?= $categoria === '' ? 'active' : '' ?>"
                data-cat=""
            >Todos</button>
            <?php foreach ($categorias as $cat): ?>
            <button
                class="category-pill <?= $categoria === $cat ? 'active' : '' ?>"
                data-cat="<?= e($cat) ?>"
            ><?= e($cat) ?></button>
            <?php endforeach; ?>
        </div>

        <!-- Results info -->
        <div style="margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <p style="color:var(--muted);font-size:0.88rem;">
                <?php if ($busca): ?>
                    <strong style="color:var(--text)"><?= $total ?></strong> resultado<?= $total !== 1 ? 's' : '' ?> para "<strong style="color:var(--red)"><?= e($busca) ?></strong>"
                <?php else: ?>
                    Exibindo <strong style="color:var(--text)"><?= count($produtos) ?></strong> de <strong style="color:var(--text)"><?= $total ?></strong> produto<?= $total !== 1 ? 's' : '' ?>
                <?php endif; ?>
            </p>
        </div>

        <!-- Grid -->
        <?php if (empty($produtos)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🔍</div>
            <h3>Nenhum produto encontrado</h3>
            <p>Tente ajustar sua busca ou filtro de categoria.</p>
            <a href="<?= BASE_URL ?>/products/list.php" class="btn btn-outline" style="margin-top:20px;">Ver todos</a>
        </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($produtos as $p): ?>
            <a href="<?= BASE_URL ?>/products/detail.php?id=<?= $p['id'] ?>" class="product-card" data-animate>
                <div class="product-image">
                    <img
                        src="<?= e(Produto::imagemUrl($p['imagem'])) ?>"
                        alt="<?= e($p['nome']) ?>"
                        loading="lazy"
                    >
                    <?php if ($p['categoria']): ?>
                    <div class="product-badge"><?= e($p['categoria']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="product-body">
                    <?php if ($p['categoria']): ?>
                    <div class="product-category"><?= e($p['categoria']) ?></div>
                    <?php endif; ?>
                    <h3 class="product-name"><?= e($p['nome']) ?></h3>
                    <p class="product-desc"><?= e($p['descricao']) ?></p>
                    <div class="product-footer">
                        <span class="product-price"><?= formatPrice((float)$p['preco']) ?></span>
                        <?php
                        $est = (int)$p['estoque'];
                        $cls = $est === 0 ? 'out' : ($est <= 5 ? 'low' : '');
                        $lbl = $est === 0 ? 'Esgotado' : ($est <= 5 ? "Só $est restante(s)" : "Em estoque");
                        ?>
                        <span class="product-stock <?= $cls ?>"><?= $lbl ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php
            $buildUrl = function(int $p) use ($busca, $categoria): string {
                $params = ['page' => $p];
                if ($busca) $params['busca'] = $busca;
                if ($categoria) $params['categoria'] = $categoria;
                return BASE_URL . '/products/list.php?' . http_build_query($params);
            };
            ?>
            <a href="<?= $buildUrl(max(1, $page - 1)) ?>"
               class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">‹</a>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= $buildUrl($i) ?>"
                   class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <a href="<?= $buildUrl(min($totalPages, $page + 1)) ?>"
               class="page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">›</a>
        </div>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
