<?php
/**
 * FightGear - Detalhe do Produto
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Produto.php';

$id = (int)($_GET['id'] ?? 0);
$produto = Produto::findById($id);

if (!$produto) {
    setFlash('error', 'Produto não encontrado.');
    redirect(BASE_URL . '/products/list.php');
}

$pageTitle = $produto['nome'];
$relacionados = Produto::all('', $produto['categoria'] ?? '', 4, 0);
$relacionados = array_filter($relacionados, fn($p) => $p['id'] !== $produto['id']);

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="container">

    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Navegação">
        <a href="<?= BASE_URL ?>/index.php">Início</a>
        <span class="breadcrumb-sep">›</span>
        <a href="<?= BASE_URL ?>/products/list.php">Produtos</a>
        <?php if ($produto['categoria']): ?>
        <span class="breadcrumb-sep">›</span>
        <a href="<?= BASE_URL ?>/products/list.php?categoria=<?= urlencode($produto['categoria']) ?>">
            <?= e($produto['categoria']) ?>
        </a>
        <?php endif; ?>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current"><?= e($produto['nome']) ?></span>
    </nav>

</div>

<!-- ========== PRODUCT DETAIL ========== -->
<section class="product-detail">
    <div class="container">
        <div class="detail-grid">

            <!-- Image -->
            <div class="detail-image-wrap">
                <img
                    src="<?= e(Produto::imagemUrl($produto['imagem'])) ?>"
                    alt="<?= e($produto['nome']) ?>"
                >
            </div>

            <!-- Info -->
            <div class="detail-info">
                <?php if ($produto['categoria']): ?>
                <div class="detail-category"><?= e($produto['categoria']) ?></div>
                <?php endif; ?>

                <h1 class="detail-title"><?= e($produto['nome']) ?></h1>
                <div class="detail-price"><?= formatPrice((float)$produto['preco']) ?></div>

                <p class="detail-desc"><?= nl2br(e($produto['descricao'])) ?></p>

                <!-- Meta -->
                <div class="detail-meta">
                    <div class="detail-meta-item">
                        <div class="detail-meta-label">Disponibilidade</div>
                        <?php $est = (int)$produto['estoque']; ?>
                        <div class="detail-meta-value"
                             style="color:<?= $est === 0 ? '#f87171' : ($est <= 5 ? '#fbbf24' : '#4ade80') ?>">
                            <?= $est === 0 ? 'Esgotado' : ($est <= 5 ? "Apenas $est un." : 'Em estoque') ?>
                        </div>
                    </div>
                    <div class="detail-meta-item">
                        <div class="detail-meta-label">Quantidade</div>
                        <div class="detail-meta-value"><?= $est ?> un.</div>
                    </div>
                    <?php if ($produto['categoria']): ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-label">Categoria</div>
                        <div class="detail-meta-value"><?= e($produto['categoria']) ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-label">Código</div>
                        <div class="detail-meta-value">#<?= str_pad($produto['id'], 4, '0', STR_PAD_LEFT) ?></div>
                    </div>
                </div>

                <?php if ($est > 0): ?>
                <a href="<?= BASE_URL ?>/products/list.php" class="btn btn-primary" style="padding:16px 36px;">
                    ← Ver mais produtos
                </a>
                <?php else: ?>
                <div class="alert alert-error">Este produto está temporariamente esgotado.</div>
                <a href="<?= BASE_URL ?>/products/list.php" class="btn btn-outline">Ver outros produtos</a>
                <?php endif; ?>

                <?php if (isAdmin()): ?>
                <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
                    <a href="<?= BASE_URL ?>/admin/produtos.php?action=edit&id=<?= $produto['id'] ?>"
                       class="btn btn-outline btn-sm">✏️ Editar produto</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ========== PRODUTOS RELACIONADOS ========== -->
<?php if (!empty($relacionados)): ?>
<section style="padding:60px 0 80px;border-top:1px solid var(--border);">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Veja também</div>
            <h2 class="section-title">PRODUTOS RELACIONADOS</h2>
        </div>
        <div class="products-grid" style="grid-template-columns:repeat(auto-fill,minmax(240px,1fr));">
            <?php foreach (array_slice($relacionados, 0, 4) as $p): ?>
            <a href="<?= BASE_URL ?>/products/detail.php?id=<?= $p['id'] ?>" class="product-card" data-animate>
                <div class="product-image">
                    <img src="<?= e(Produto::imagemUrl($p['imagem'])) ?>" alt="<?= e($p['nome']) ?>" loading="lazy">
                </div>
                <div class="product-body">
                    <h3 class="product-name"><?= e($p['nome']) ?></h3>
                    <div class="product-footer">
                        <span class="product-price"><?= formatPrice((float)$p['preco']) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
