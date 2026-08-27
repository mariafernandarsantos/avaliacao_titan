<?php
use app\core\Session;

$isLogged     = Session::isLoggedIn();
$currentRoute = trim($_GET['route'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JM Informática — Ordem de Serviços</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/app.css">
</head>
<body class="<?= $isLogged ? 'app-layout' : 'auth-layout' ?>">

<?php if ($isLogged): ?>

<aside class="sidebar">

    <div class="sidebar-user">
        <span class="user-label">Logado como:</span>
        <span class="user-name"><?= e(Session::get('user_name', '')) ?></span>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>index.php?route=services/create"
           class="nav-link <?= str_starts_with($currentRoute, 'services') ? 'active' : '' ?>">
            Cadastrar Serviço
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>index.php?route=logout" class="btn-logout">Sair</a>
    </div>

</aside>

<main class="main-content">
    <div class="page-body">
        <?php require $viewFile; ?>
    </div>
</main>

<?php else: ?>

    <?php require $viewFile; ?>

<?php endif; ?>

<script src="<?= BASE_URL ?>assets/app.js"></script>
</body>
</html>
