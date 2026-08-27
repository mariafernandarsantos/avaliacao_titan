<?php if ($error): ?>
    <div class="alert alert-danger js-alert" role="alert">
        <?= e($error) ?>
        <button class="alert-close" aria-label="Fechar">×</button>
    </div>
<?php endif; ?>

<?php
$finalizado = $service['finished_at'] !== null;
$status     = $finalizado ? 'Finalizado' : 'Pendente';
?>

<div class="page-inner-form">

    <h1>Editar Serviço #<?= $service['id_service'] ?></h1>

    <!-- Metadados somente leitura -->
    <div class="service-meta">
        <div class="meta-item">
            <span class="meta-label">Funcionário</span>
            <span class="meta-value"><?= e($service['user_name']) ?></span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Status</span>
            <span class="meta-value"><?= $status ?></span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Cadastrado em</span>
            <span class="meta-value"><?= formatDateTime($service['created_at']) ?></span>
        </div>
        <?php if ($service['finished_at']): ?>
            <div class="meta-item">
                <span class="meta-label">Finalizado em</span>
                <span class="meta-value"><?= formatDateTime($service['finished_at']) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <form action="<?= BASE_URL ?>index.php?route=services/update" method="POST" novalidate>
        <input type="hidden" name="id" value="<?= $service['id_service'] ?>">

        <div class="form-group">
            <input
                type="text"
                id="description"
                name="description"
                placeholder="descrição"
                value="<?= e($service['description']) ?>"
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
                value="<?= number_format((float) $service['price'], 2, ',', '.') ?>"
                class="input-money"
                required
            >
        </div>

        <div class="login-actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>index.php?route=dashboard" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>
</div>
