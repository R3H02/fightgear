# ⚔️ FightGear — Sistema de E-commerce de Equipamentos de Luta

Sistema completo de e-commerce desenvolvido em **PHP puro + MySQL**, com design moderno, responsivo e focado em produtos de esportes de luta.

---

## 📋 Índice

1. [Requisitos](#requisitos)
2. [Instalação com XAMPP](#instalação-com-xampp)
3. [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
4. [Estrutura de Pastas](#estrutura-de-pastas)
5. [Funcionalidades](#funcionalidades)
6. [Usuários do Sistema](#usuários-do-sistema)
7. [Segurança](#segurança)
8. [Screenshots das Telas](#screenshots-das-telas)

---

## ✅ Requisitos

| Tecnologia   | Versão mínima |
|:-------------|:--------------|
| PHP          | 8.0+          |
| MySQL        | 5.7+ / 8.0+   |
| Apache       | 2.4+          |
| XAMPP        | 8.0+          |

---

## 🚀 Instalação com XAMPP

### Passo 1 — Copiar o projeto

Coloque a pasta `fightgear` dentro do diretório `htdocs` do XAMPP:

```
C:\xampp\htdocs\fightgear\       (Windows)
/Applications/XAMPP/htdocs/fightgear/  (macOS)
/opt/lampp/htdocs/fightgear/    (Linux)
```

### Passo 2 — Iniciar serviços

Abra o **XAMPP Control Panel** e inicie:
- ✅ Apache
- ✅ MySQL

### Passo 3 — Criar o banco de dados no phpMyAdmin

1. Acesse `http://localhost/phpmyadmin`
2. Clique em **"Nova"** (barra lateral esquerda)
3. Nome do banco: `fightgear`
4. Cotejamento: `utf8mb4_unicode_ci`
5. Clique em **"Criar"**
6. Com o banco `fightgear` selecionado, clique na aba **"SQL"**
7. Cole o conteúdo do arquivo `database.sql` e clique **"Executar"**

> ✅ Isso cria as tabelas `usuarios` e `produtos`, além de inserir dados de exemplo.

### Passo 4 — Acessar o projeto

Abra o navegador e acesse:

```
http://localhost/fightgear/
```

---

## ⚙️ Configuração do Banco de Dados

Edite o arquivo `config/database.php` se suas credenciais forem diferentes:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // seu usuário MySQL
define('DB_PASS', '');       // sua senha MySQL (padrão XAMPP: vazia)
define('DB_NAME', 'fightgear');
```

---

## 📁 Estrutura de Pastas

```
fightgear/
├── config/
│   ├── app.php           # Constantes, helpers, sessão
│   └── database.php      # Conexão PDO com MySQL
│
├── models/
│   ├── Usuario.php       # CRUD de usuários
│   └── Produto.php       # CRUD de produtos
│
├── controllers/
│   ├── AuthController.php    # Login, cadastro, logout
│   └── ProdutoController.php # Gerenciamento de produtos (admin)
│
├── views/
│   └── partials/
│       ├── header.php        # Cabeçalho do site
│       ├── footer.php        # Rodapé do site
│       ├── admin_header.php  # Cabeçalho do painel admin
│       └── admin_footer.php  # Rodapé do painel admin
│
├── auth/
│   └── login.php         # Página de login e cadastro
│
├── products/
│   ├── list.php          # Listagem de produtos
│   └── detail.php        # Detalhe do produto
│
├── admin/
│   └── produtos.php      # Painel admin — CRUD de produtos
│
├── public/
│   ├── css/
│   │   ├── style.css     # CSS principal do site
│   │   └── admin.css     # CSS do painel admin
│   ├── js/
│   │   ├── main.js       # JS principal
│   │   └── admin.js      # JS do admin
│   └── images/
│       └── no-image.png  # Imagem padrão para produtos sem foto
│
├── uploads/
│   └── products/         # Imagens enviadas pelos admins (auto-criado)
│
├── index.php             # Página inicial
├── database.sql          # Script SQL para criação do banco
├── .htaccess             # Configuração Apache + segurança
└── README.md             # Este arquivo
```

---

## 🎯 Funcionalidades

### 🌐 Front-end (área pública)

| Página | URL |
|:-------|:----|
| Página inicial | `/` |
| Listagem de produtos | `/products/list.php` |
| Detalhe de produto | `/products/detail.php?id=N` |
| Login | `/auth/login.php?action=login` |
| Cadastro | `/auth/login.php?action=register` |

**Recursos:**
- ✅ Design dark moderno com paleta vermelho/preto
- ✅ Totalmente responsivo (mobile, tablet, desktop)
- ✅ Busca de produtos por nome/descrição
- ✅ Filtro por categoria (pills)
- ✅ Paginação
- ✅ Animações suaves ao scroll
- ✅ Menu mobile com toggle

### 🔐 Autenticação

- ✅ Cadastro de clientes com validação completa
- ✅ Login para clientes e admins
- ✅ Logout seguro (destrói sessão)
- ✅ Redirecionamento automático por tipo de usuário

### 🛠️ Painel Administrativo

| Ação | URL |
|:-----|:----|
| Listar produtos | `/admin/produtos.php` |
| Criar produto | `/admin/produtos.php?action=create` |
| Editar produto | `/admin/produtos.php?action=edit&id=N` |
| Excluir produto | POST para `/admin/produtos.php?action=delete` |

**Recursos:**
- ✅ Sidebar responsiva
- ✅ Tabela com imagem, nome, categoria, preço e estoque
- ✅ Busca rápida por nome
- ✅ Modal de confirmação antes de excluir
- ✅ Upload de imagem com preview em tempo real
- ✅ Badges coloridos de estoque (ok / baixo / esgotado)
- ✅ Paginação

---

## 👤 Usuários do Sistema

### Admin padrão (criado pelo `database.sql`)

| Campo | Valor |
|:------|:------|
| E-mail | `admin@fightgear.com` |
| Senha | `password` |
| Tipo | `admin` |

> ⚠️ **Importante:** Troque a senha do admin em produção!

Para gerar um novo hash de senha, execute no terminal PHP:
```php
echo password_hash('sua_nova_senha', PASSWORD_BCRYPT, ['cost' => 12]);
```

### Tipos de usuário

| Tipo | Permissões |
|:-----|:-----------|
| `cliente` | Visualizar produtos, página inicial |
| `admin` | Tudo acima + CRUD completo de produtos |

---

## 🔒 Segurança

O projeto implementa as seguintes práticas de segurança:

| Medida | Implementação |
|:-------|:-------------|
| Hash de senhas | `password_hash()` com bcrypt (cost 12) |
| SQL Injection | Prepared Statements PDO em todas as queries |
| XSS | `htmlspecialchars()` em toda saída de dados |
| CSRF | Token CSRF em todos os formulários POST |
| Controle de acesso | `requireLogin()` e `requireAdmin()` em rotas restritas |
| Upload seguro | Validação de tipo MIME e tamanho (máx 5MB) |
| Sessão | `session_regenerate_id()` após login |
| Listagem de diretórios | `Options -Indexes` no `.htaccess` |

---

## 🗄️ Estrutura do Banco de Dados

### Tabela `usuarios`

| Coluna | Tipo | Descrição |
|:-------|:-----|:----------|
| `id` | INT UNSIGNED AUTO_INCREMENT | Chave primária |
| `nome` | VARCHAR(120) | Nome completo |
| `email` | VARCHAR(180) UNIQUE | E-mail (login) |
| `senha` | VARCHAR(255) | Hash bcrypt |
| `tipo` | ENUM('cliente','admin') | Tipo de usuário |
| `criado_em` | DATETIME | Data de cadastro |

### Tabela `produtos`

| Coluna | Tipo | Descrição |
|:-------|:-----|:----------|
| `id` | INT UNSIGNED AUTO_INCREMENT | Chave primária |
| `nome` | VARCHAR(200) | Nome do produto |
| `descricao` | TEXT | Descrição completa |
| `preco` | DECIMAL(10,2) | Preço em R$ |
| `estoque` | INT UNSIGNED | Quantidade disponível |
| `imagem` | VARCHAR(255) | Nome do arquivo de imagem |
| `categoria` | VARCHAR(80) | Categoria (Luvas, Kimonos…) |
| `criado_em` | DATETIME | Data de criação |
| `atualizado_em` | DATETIME | Última atualização |

---

## 🧩 Tecnologias Utilizadas

- **Backend:** PHP 8.0+ puro (sem frameworks)
- **Banco de Dados:** MySQL com PDO
- **Frontend:** HTML5 + CSS3 + JavaScript vanilla
- **Fontes:** Bebas Neue, Syne, DM Sans (Google Fonts)
- **Servidor local:** XAMPP (Apache + MySQL)
- **Upload:** `move_uploaded_file()` nativo do PHP

---

## 🛠️ Possíveis Melhorias Futuras

- [ ] Carrinho de compras e checkout
- [ ] Histórico de pedidos por cliente
- [ ] Painel com estatísticas e gráficos
- [ ] Sistema de avaliações de produtos
- [ ] Integração com APIs de pagamento (Stripe, Mercado Pago)
- [ ] Gestão de usuários no painel admin
- [ ] Upload múltiplo de imagens por produto
- [ ] API REST para app mobile

---

## 📞 Suporte

Em caso de dúvidas durante a instalação, verifique:
1. Se o Apache e MySQL estão rodando no XAMPP
2. Se as credenciais em `config/database.php` estão corretas
3. Se o banco de dados foi criado e o script SQL foi executado
4. Se a pasta `fightgear` está dentro de `htdocs`

---

*FightGear — Desenvolvido com PHP puro e muito ❤️ para lutadores.*
