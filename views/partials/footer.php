<?php
/**
 * FightGear - Footer global do site
 */
?>
</main><!-- /.main-content -->

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-logo"><span class="logo-fg">FIGHT</span><span class="logo-gear">GEAR</span></div>
            <p>Equipamentos de luta de alta performance para atletas de todos os níveis.</p>
        </div>
        <div class="footer-links">
            <h4>Navegação</h4>
            <ul>
                <li><a href="<?= BASE_URL ?>/index.php">Início</a></li>
                <li><a href="<?= BASE_URL ?>/products/list.php">Produtos</a></li>
                <li><a href="<?= BASE_URL ?>/auth/login.php?action=login">Entrar</a></li>
                <li><a href="<?= BASE_URL ?>/auth/login.php?action=register">Cadastrar</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>Categorias</h4>
            <ul>
                <li><a href="<?= BASE_URL ?>/products/list.php?categoria=Luvas">Luvas</a></li>
                <li><a href="<?= BASE_URL ?>/products/list.php?categoria=Kimonos">Kimonos</a></li>
                <li><a href="<?= BASE_URL ?>/products/list.php?categoria=Proteção">Proteção</a></li>
                <li><a href="<?= BASE_URL ?>/products/list.php?categoria=Equipamentos">Equipamentos</a></li>
            </ul>
        </div>
        <div class="footer-contact">
            <h4>Contato</h4>
            <p>contato@fightgear.com.br</p>
            <p>(11) 99999-0000</p>
            <div class="footer-social">
                <a href="#" aria-label="Instagram">IG</a>
                <a href="#" aria-label="Facebook">FB</a>
                <a href="#" aria-label="YouTube">YT</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> FightGear. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
