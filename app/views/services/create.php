<?php if ($error): ?>
    <div class="alert alert-danger js-alert" role="alert">
        <?= e($error) ?>
        <button class="alert-close" aria-label="Fechar">×</button>
    </div>
<?php endif; ?>

<div class="page-inner-form">

    <h1>Cadastrar Novo Serviço</h1>

    <form action="<?= BASE_URL ?>index.php?route=services/store" method="POST" novalidate>

        <div class="form-group">
            <input
                type="text"
                id="description"
                name="description"
                placeholder="descrição"
                value="<?= e($_POST['description'] ?? '') ?>"
                maxlength="45"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <input
                type="text"
                id="price"
                name="price"
                placeholder="preço"
                value="<?= e($_POST['price'] ?? '') ?>"
                class="input-money"
                required
            >
        </div>

        <div class="login-actions">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="<?= BASE_URL ?>index.php?route=dashboard" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>

</div>
