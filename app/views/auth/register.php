<div class="login-wrapper">
    <div class="login-card">

        <div class="login-header">
            <h1>Cadastrar Novo Usuário</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= e($error) ?>
                <button class="alert-close" aria-label="Fechar">×</button>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>index.php?route=register" method="POST" class="login-form" novalidate>

            <div class="form-group">
                <label for="name">Nome</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="nome completo"
                    value="<?= e($_POST['name'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="email@email.com"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <div class="input-password">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="***************"
                        required
                    >
                </div>
            </div>

            <div class="login-actions">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <a href="<?= BASE_URL ?>index.php?route=login" class="link-register">Voltar ao login</a>
            </div>

        </form>

    </div>
</div>
