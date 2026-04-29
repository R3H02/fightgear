<?php
/**
 * FightGear - Admin: Gerenciamento de Produtos
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../controllers/ProdutoController.php';

// ProdutoController define: $action, $error, $produto, $produtos, $total, $totalPages, $page, $busca

$pageTitle = match ($action) {
    'create' => 'Novo Produto',
    'edit'   => 'Editar Produto',
    default  => 'Gerenciar Produtos',
};

require_once __DIR__ . '/../views/partials/admin_header.php';
?>

<?= flashMessage() ?>
<?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<!-- ==================== LIST VIEW ==================== -->
<?php if ($action === 'list'): ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Produtos (<?= $total ?>)</h2>
        <div class="admin-search">
            <form method="GET" action="">
                <input type="hidden" name="action" value="list">
                <input
                    type="search"
                    name="busca"
                    class="admin-search-input"
                    placeholder="Buscar produto..."
                    value="<?= e($busca) ?>"
                >
                <button type="submit" class="btn btn-outline btn-sm">Buscar</button>
                <?php if ($busca): ?>
                    <a href="<?= BASE_URL ?>/admin/produtos.php" class="btn btn-outline btn-sm">Limpar</a>
                <?php endif; ?>
            </form>
            <a href="<?= BASE_URL ?>/admin/produtos.php?action=create" class="btn btn-primary btn-sm">
                + Novo Produto
            </a>
        </div>
    </div>

    <?php if (empty($produtos)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">📦</div>
        <h3>Nenhum produto encontrado</h3>
        <p>Clique em "+ Novo Produto" para começar.</p>
    </div>
    <?php else: ?>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <td>
                        <img
                            class="table-product-img"
                            src="<?= e(Produto::imagemUrl($p['imagem'])) ?>"
                            alt="<?= e($p['nome']) ?>"
                        >
                    </td>
                    <td>
                        <div class="table-product-name"><?= e($p['nome']) ?></div>
                        <div class="table-product-cat" style="margin-top:2px;color:var(--muted);font-size:0.78rem;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <?= e(mb_strimwidth($p['descricao'], 0, 60, '…')) ?>
                        </div>
                    </td>
                    <td><?= $p['categoria'] ? e($p['categoria']) : '<span style="color:var(--muted)">—</span>' ?></td>
                    <td style="font-family:var(--font-display);font-size:1.05rem;">
                        <?= formatPrice((float)$p['preco']) ?>
                    </td>
                    <td>
                        <?php
                        $est = (int)$p['estoque'];
                        $cls = $est === 0 ? 'out' : ($est <= 5 ? 'low' : 'ok');
                        $lbl = $est === 0 ? 'Esgotado' : "$est un.";
                        ?>
                        <span class="badge-stock <?= $cls ?>"><?= $lbl ?></span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= BASE_URL ?>/products/detail.php?id=<?= $p['id'] ?>"
                               class="btn btn-outline btn-sm" title="Ver">👁</a>
                            <a href="<?= BASE_URL ?>/admin/produtos.php?action=edit&id=<?= $p['id'] ?>"
                               class="btn btn-outline btn-sm" title="Editar">✏️</a>
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    data-delete-id="<?= $p['id'] ?>"
                                    title="Excluir">🗑</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination" style="margin-top:32px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?action=list&page=<?= $i ?><?= $busca ? '&busca='.urlencode($busca) : '' ?>"
           class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Delete modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3 class="modal-title">Excluir Produto</h3>
        <p class="modal-desc">Tem certeza que deseja remover este produto? Esta ação não pode ser desfeita.</p>
        <form method="POST" action="<?= BASE_URL ?>/admin/produtos.php?action=delete">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" id="deleteIdInput" value="">
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" id="modalCancel">Cancelar</button>
                <button type="submit" class="btn btn-danger">Sim, excluir</button>
            </div>
        </form>
    </div>
</div>

<?php endif; // end list ?>

<!-- ==================== CREATE / EDIT FORM ==================== -->
<?php if ($action === 'create' || $action === 'edit'): ?>

<?php
$isEdit    = $action === 'edit' && $produto;
$formAction = BASE_URL . '/admin/produtos.php?action=' . $action;
$categoriasOpcoes = ['Luvas', 'Kimonos', 'Proteção', 'Equipamentos', 'Acessórios', 'Roupas', 'Calçados', 'Outros'];
?>

<div style="margin-bottom:16px;">
    <a href="<?= BASE_URL ?>/admin/produtos.php" class="btn btn-outline btn-sm">← Voltar</a>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><?= $isEdit ? 'Editar Produto' : 'Novo Produto' ?></h2>
    </div>

    <form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        <?php endif; ?>

        <div class="form-grid">

            <!-- Nome -->
            <div class="form-group">
                <label class="form-label" for="nome">Nome do Produto *</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    class="form-control"
                    placeholder="Ex: Luva de Boxe Pro 14oz"
                    value="<?= e($produto['nome'] ?? $_POST['nome'] ?? '') ?>"
                    required
                    maxlength="200"
                >
            </div>

            <!-- Categoria -->
            <div class="form-group">
                <label class="form-label" for="categoria">Categoria</label>
                <select id="categoria" name="categoria" class="form-control">
                    <option value="">— Selecione —</option>
                    <?php
                    $catAtual = $produto['categoria'] ?? $_POST['categoria'] ?? '';
                    foreach ($categoriasOpcoes as $cat):
                    ?>
                    <option value="<?= e($cat) ?>" <?= $catAtual === $cat ? 'selected' : '' ?>>
                        <?= e($cat) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Preço -->
            <div class="form-group">
                <label class="form-label" for="preco">Preço (R$) *</label>
                <input
                    type="text"
                    id="preco"
                    name="preco"
                    class="form-control"
                    placeholder="0,00"
                    value="<?= e(isset($produto['preco']) ? number_format((float)$produto['preco'], 2, ',', '.') : ($_POST['preco'] ?? '')) ?>"
                    required
                >
                <span class="form-hint">Use vírgula como separador decimal. Ex: 149,90</span>
            </div>

            <!-- Estoque -->
            <div class="form-group">
                <label class="form-label" for="estoque">Quantidade em Estoque *</label>
                <input
                    type="number"
                    id="estoque"
                    name="estoque"
                    class="form-control"
                    placeholder="0"
                    value="<?= e($produto['estoque'] ?? $_POST['estoque'] ?? '0') ?>"
                    min="0"
                    required
                >
            </div>

            <!-- Descrição -->
            <div class="form-group form-group-full">
                <label class="form-label" for="descricao">Descrição *</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    class="form-control"
                    placeholder="Descreva o produto: material, características, tamanho disponível..."
                    required
                    rows="5"
                ><?= e($produto['descricao'] ?? $_POST['descricao'] ?? '') ?></textarea>
            </div>

            <!-- Imagem -->
            <div class="form-group form-group-full">
                <label class="form-label" for="imagem">
                    Imagem do Produto
                    <?= $isEdit ? '<span style="color:var(--muted);font-weight:300;">(deixe em branco para manter a atual)</span>' : '' ?>
                </label>
                <input
                    type="file"
                    id="imagem"
                    name="imagem"
                    class="form-control"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    data-preview="imgPreview"
                    style="padding:10px;"
                >
                <span class="form-hint">JPG, PNG ou WEBP • Máximo 5MB</span>
                <div class="image-preview-wrap" style="<?= $isEdit ? '' : 'display:none;' ?>" id="imgPreviewWrap">
                    <img
                        id="imgPreview"
                        src="<?= $isEdit ? e(Produto::imagemUrl($produto['imagem'])) : '' ?>"
                        alt="Preview"
                        style="<?= $isEdit ? '' : 'display:none;' ?>"
                    >
                </div>
            </div>

        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? '💾 Salvar Alterações' : '+ Adicionar Produto' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/produtos.php" class="btn btn-outline">Cancelar</a>
        </div>

    </form>
</div>

<script>
// Show preview wrap when file selected
document.getElementById('imagem').addEventListener('change', function() {
    if (this.files[0]) {
        document.getElementById('imgPreviewWrap').style.display = 'flex';
    }
});
</script>

<?php endif; // end create/edit ?>

<?php require_once __DIR__ . '/../views/partials/admin_footer.php'; ?>
