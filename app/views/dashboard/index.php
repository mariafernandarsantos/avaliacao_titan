<!-- Alertas de feedback -->
<?php if ($success): ?>
    <div class="alert alert-success js-alert" role="alert">
        <?= e($success) ?>
        <button class="alert-close" aria-label="Fechar">×</button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger js-alert" role="alert">
        <?= e($error) ?>
        <button class="alert-close" aria-label="Fechar">×</button>
    </div>
<?php endif; ?>

<!-- Cabeçalho: título + data atual -->
<div class="dash-header">
    <h1 class="dashboard-title">DASHBOARD</h1>
    <span class="dash-date"><?= currentDate() ?></span>
</div>

<!-- Dois blocos superiores: Últimos Serviços | Serviços Pendentes -->
<div class="dash-top">

    <!-- Últimos serviços (3 mais recentes, qualquer status) -->
    <div class="dash-top-block">
        <h2>Ultimos Serviços</h2>
        <?php $recentes = array_slice($services, 0, 3); ?>
        <?php if (empty($recentes)): ?>
            <p class="dash-empty">Nenhum serviço cadastrado.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($recentes as $r): ?>
                    <li><?= $r['id_service'] ?> - <?= e($r['description']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Serviços pendentes do usuário logado -->
    <div class="dash-top-block">
        <h2>Serviços Pendentes</h2>
        <?php if (empty($pending)): ?>
            <p class="dash-empty">Nenhum serviço pendente.</p>
        <?php else: ?>
            <ul>
                <?php foreach (array_slice($pending, 0, 3) as $item): ?>
                    <li><?= $item['id_service'] ?> - <?= e($item['description']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>

<!-- Total em destaque -->
<div class="dash-total">
    <span class="dash-total-label">Meu Total em Serviços:</span>
    <span class="dash-total-value"><?= formataDinheiro($total) ?></span>
</div>

<!-- Filtros -->
<section class="filters-section">
    <form action="<?= BASE_URL ?>index.php" method="GET" class="filters-form">
        <input type="hidden" name="route" value="dashboard">

        <!-- Filtro por nome do serviço -->
        <div class="filter-group filter-group--wide">
            <input type="text" id="description" name="description"
                   placeholder="Nome"
                   value="<?= e($filters['description']) ?>">
        </div>

        <!-- Filtro por data inicial -->
        <div class="filter-group">
            <input type="date" id="date_from" name="date_from"
                   value="<?= e($filters['date_from']) ?>">
        </div>

        <!-- Filtro por data final -->
        <div class="filter-group">
            <input type="date" id="date_to" name="date_to"
                   value="<?= e($filters['date_to']) ?>">
        </div>

        <!-- Filtro por status -->
        <div class="filter-group">
            <select id="status" name="status">
                <option value="">Status</option>
                <option value="Pendente"   <?= $filters['status'] === 'Pendente'   ? 'selected' : '' ?>>Pendente</option>
                <option value="Finalizado" <?= $filters['status'] === 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
            </select>
        </div>

        <!-- Filtro por usuário -->
        <div class="filter-group">
            <select id="user_filter" name="user_filter">
                <option value="">Usuário</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id_user'] ?>"
                        <?= (string) $filters['user_id'] === (string) $u['id_user'] ? 'selected' : '' ?>>
                        <?= e($u['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="<?= BASE_URL ?>index.php?route=dashboard" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</section>

<!-- Tabela de serviços -->
<section class="table-section">
    <?php if (empty($services)): ?>
        <div class="empty-state">
            <p>Nenhum serviço encontrado com os filtros informados.</p>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DESCRIÇÃO</th>
                        <th>STATUS</th>
                        <th>VALOR</th>
                        <th>NOME USUÁRIO</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service):
                        $finalizado = $service['finished_at'] !== null;
                        $status     = $finalizado ? 'FINALIZADO' : 'PENDENTE';
                    ?>
                        <tr>
                            <td class="col-id"><?= $service['id_service'] ?></td>
                            <td class="col-desc"><?= e($service['description']) ?></td>
                            <td><strong><?= $status ?></strong></td>
                            <td class="col-money"><?= formataDinheiro((float) $service['price']) ?></td>
                            <td><?= e($service['user_name']) ?></td>
                            <td class="col-actions">

                                <!-- Editar -->
                                <a href="<?= BASE_URL ?>index.php?route=services/edit&id=<?= $service['id_service'] ?>"
                                   class="btn btn-sm btn-info"
                                   title="Editar serviço">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>

                                <!-- Excluir -->
                                <form action="<?= BASE_URL ?>index.php?route=services/delete" method="POST"
                                      class="form-inline js-confirm"
                                      data-message="Tem certeza que deseja excluir este serviço?">
                                    <input type="hidden" name="id" value="<?= $service['id_service'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Excluir serviço">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                            <path d="M10 11v6m4-6v6"/>
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Finalizar -->
                                <?php if (!$finalizado): ?>
                                    <form action="<?= BASE_URL ?>index.php?route=services/finalize" method="POST"
                                          class="form-inline js-confirm"
                                          data-message="Finalizar este serviço? Um e-mail será enviado ao funcionário.">
                                        <input type="hidden" name="id" value="<?= $service['id_service'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success" title="Finalizar serviço">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="btn btn-sm btn-ghost"
                                          title="Finalizado em <?= formatDateTime($service['finished_at']) ?>">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                    </span>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
