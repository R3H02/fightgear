-- ============================================================
--  FightGear - Script de criação do banco de dados
--  Execute este script no phpMyAdmin ou MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `fightgear`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `fightgear`;

-- ----------------------------
-- Tabela de usuários
-- ----------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nome`       VARCHAR(120)    NOT NULL,
  `email`      VARCHAR(180)    NOT NULL UNIQUE,
  `senha`      VARCHAR(255)    NOT NULL,   -- password_hash (bcrypt)
  `tipo`       ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  `criado_em`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Tabela de produtos
-- ----------------------------
CREATE TABLE IF NOT EXISTS `produtos` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(200)    NOT NULL,
  `descricao`   TEXT            NOT NULL,
  `preco`       DECIMAL(10,2)   NOT NULL,
  `estoque`     INT UNSIGNED    NOT NULL DEFAULT 0,
  `imagem`      VARCHAR(255)             DEFAULT NULL,
  `categoria`   VARCHAR(80)              DEFAULT NULL,
  `criado_em`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Usuário admin padrão
-- Senha: admin123  (gerada com password_hash)
-- ----------------------------
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `tipo`) VALUES
('Administrador', 'admin@fightgear.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Nota: a senha acima é "password" — use o script PHP abaixo para gerar sua própria:
-- echo password_hash('suasenha', PASSWORD_BCRYPT, ['cost' => 12]);


-- ----------------------------
-- Produtos de exemplo
-- ----------------------------
INSERT INTO `produtos` (`nome`, `descricao`, `preco`, `estoque`, `categoria`) VALUES
('Luva de Boxe Pro Combat 12oz', 'Luva profissional de couro sintético de alta densidade, ideal para treinos intensos e competições. Palmeira em camurça para melhor aderência.', 189.90, 25, 'Luvas'),
('Kimono Jiu-Jitsu A2 Azul', 'Kimono feito em tecido ripstop de alta resistência, ótimo para treinos e competições. Inclui faixa branca. Aprovado IBJJF.', 349.00, 18, 'Kimonos'),
('Bandagem de Mão 4,5m', 'Bandagem elástica semi-rígida para proteção dos punhos e mãos. Comprimento de 4,5 metros, com velcro duplo. Par.', 39.90, 60, 'Proteção'),
('Protetor Bucal Duplo', 'Protetor bucal de dupla camada em EVA termomoldável. Proteção superior e inferior. Acompanha estojo.', 49.90, 40, 'Proteção'),
('Cotoveleira Elasticada', 'Cotoveleira de compressão para suporte e estabilização durante treinos. Material: neoprene. Tamanho: M.', 59.90, 30, 'Proteção'),
('Caneleira MMA Premium', 'Caneleira de MMA com espuma de alta densidade e neoprene premium. Proteção para canela e pé. Par.', 129.90, 22, 'Proteção'),
('Saco de Pancada 25kg', 'Saco de pancada preenchido com areia e tecido, suporte de corrente incluso. Ideal para treinos de soco e chute.', 299.00, 8, 'Equipamentos'),
('Corda de Pular Speed', 'Corda de pular profissional com rolamentos de aço e cabo de aço revestido. Ajustável até 3 metros.', 79.90, 35, 'Acessórios');
