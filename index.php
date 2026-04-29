<?php
/**
 * FightGear - Página Inicial
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/models/Produto.php';

$pageTitle = 'Equipamentos de Luta Premium';
$destaques = Produto::destaques(8);
$categorias = Produto::categorias();

require_once __DIR__ . '/views/partials/header.php';
?>

<!-- ========== HERO ========== -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid-overlay"></div>
    <div class="hero-content container">
        <div class="hero-text">
            <div class="hero-tag">Performance Total</div>
            <h1 class="hero-title">
                EQUIPAMENTOS<br>
                DE <span class="accent">LUTA</span><br>
                PREMIUM
            </h1>
            <p class="hero-desc">
                Luvas, kimonos, protetores e acessórios de alto desempenho para atletas que exigem o melhor. Do treino à competição.
            </p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/products/list.php" class="btn btn-primary">Ver Produtos</a>
                <a href="<?= BASE_URL ?>/auth/login.php?action=register" class="btn btn-outline">Criar Conta</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card-stack">
                <div class="hero-stat-card">
                    <div class="hero-stat-num">500+</div>
                    <div class="hero-stat-label">Produtos cadastrados</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-num">8</div>
                    <div class="hero-stat-label">Categorias disponíveis</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-num">24h</div>
                    <div class="hero-stat-label">Suporte ao cliente</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FEATURES ========== -->
<section class="features-bar">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">🚚</div>
                <div class="feature-title">Frete Grátis</div>
                <div class="feature-desc">Acima de R$300 em compras</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🛡️</div>
                <div class="feature-title">Garantia</div>
                <div class="feature-desc">30 dias de garantia total</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💳</div>
                <div class="feature-title">Parcelamento</div>
                <div class="feature-desc">Em até 12x sem juros</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⭐</div>
                <div class="feature-title">Alta Performance</div>
                <div class="feature-desc">Produtos usados por atletas</div>
            </div>
        </div>
    </div>
</section>

<!-- ========== PRODUTOS EM DESTAQUE ========== -->
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Destaques</div>
            <h2 class="section-title">PRODUTOS EM DESTAQUE</h2>
            <p class="section-subtitle">Selecionados para máxima performance no treino e na competição</p>
        </div>

        <?php if (empty($destaques)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>Nenhum produto cadastrado ainda</h3>
                <p>Acesse o painel de admin para adicionar produtos.</p>
            </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($destaques as $p): ?>
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
                        $estoqueNum = (int)$p['estoque'];
                        $stockClass = $estoqueNum === 0 ? 'out' : ($estoqueNum <= 5 ? 'low' : '');
                        $stockLabel = $estoqueNum === 0 ? 'Esgotado' : ($estoqueNum <= 5 ? "Só $estoqueNum restante(s)" : "Em estoque");
                        ?>
                        <span class="product-stock <?= $stockClass ?>"><?= $stockLabel ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center; margin-top:48px;">
            <a href="<?= BASE_URL ?>/products/list.php" class="btn btn-outline">Ver todos os produtos →</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== CTA ========== -->
<?php if (!isLoggedIn()): ?>
<section style="padding:80px 0; background:var(--dark2); border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
    <div class="container" style="text-align:center;">
        <div class="section-tag">Junte-se a nós</div>
        <h2 class="section-title" style="margin-bottom:16px;">CRIE SUA CONTA AGORA</h2>
        <p style="color:var(--text-dim);font-weight:300;margin-bottom:40px;max-width:480px;margin-left:auto;margin-right:auto;">
            Cadastre-se gratuitamente e tenha acesso ao nosso catálogo completo de equipamentos de luta.
        </p>
        <a href="<?= BASE_URL ?>/auth/login.php?action=register" class="btn btn-primary" style="padding:16px 40px;font-size:1rem;">
            Cadastrar Grátis
        </a>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/views/partials/footer.php'; ?>
