# JM Informática - Sistema de Ordem de Serviços

Este é um sistema de controle de ordens de serviços desenvolvido em **PHP puro**, utilizando a arquitetura **MVC**. O projeto foi construído do zero, sem a utilização de frameworks e sem Composer.

## Tecnologias Utilizadas
* **Backend:** PHP 8+ (PDO, MVC padrão Front Controller)
* **Banco de Dados:** MySQL
* **Frontend:** HTML5, CSS3 (variáveis CSS, sem frameworks), JavaScript Vanilla
* **Segurança:** Hashes de senha via `bcrypt` e proteção de diretórios com `.htaccess`

---

## Como executar o projeto

### Requisitos
* PHP 8.0+
* MySQL rodando localmente

### Configuração do Banco de Dados
```php
private string $host     = 'localhost';
private string $dbname   = 'jm_db'; // 
private string $user     = 'root';
private string $password = '';
```

### Criação das Tabelas
Para facilitar a avaliação, criei um script de setup que funciona como uma Migration. Ele cria o banco, as tabelas e preenche o sistema com funcionários e ordens de serviços para que o painel não inicie vazio.
Além dessa facilidade, o setup irá garantir que os usuários de teste sejam criados com o hash compatível.
Porém, caso prefira executar o SQL manualmente, o arquivo de schema está disponível em `database/schema.sql`.

Abra o seu terminal na raiz do projeto e execute:
```bash
php database/setup.php
```

### Iniciando o Servidor

Pelo terminal, inicie o servidor apontando para a pasta `public`:
```bash
php -S localhost:8000 -t public/
```
Em seguida, acesse no navegador: `http://localhost:8000`

---

## Acesso gerado no setup.php

Utilize os dados abaixo para logar no sistema e ver os relatórios e cadastros:

| Usuário | E-mail | Senha |
|---|---|---|
| Administrador | `admin@jminformatica.com` | `123456` |
| Maria Silva | `maria@jminformatica.com` | `123456` |
| João Santos | `joao@jminformatica.com` | `123456` |

---

