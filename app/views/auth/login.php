<div class="login-wrapper">
    <div class="login-card">

        <div class="login-header">
            <h1>Sistema de Controle de Serviços</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= e($error) ?>
                <button class="alert-close" aria-label="Fechar">×</button>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>index.php?route=login" method="POST" class="login-form" novalidate>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="email@email.com"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                    autofocus
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
                    <button type="button" class="toggle-password" aria-label="Mostrar/ocultar senha">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="login-actions">
                <button type="submit" class="btn btn-primary">Entrar</button>
                <a href="<?= BASE_URL ?>index.php?route=register" class="link-register">Cadastrar usuário</a>
            </div>

        </form>

    </div>
</div>
