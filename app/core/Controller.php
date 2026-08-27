<?php

namespace app\core;

abstract class Controller
{
    /**
     * Carrega uma view dentro do layout principal
     *
     * As chaves do array $data são extraídas como variáveis locais
     * e ficam disponíveis tanto no layout quanto na view
     *
     * @param string $view  Caminho relativo à pasta views 
     * @param array  $data  Variáveis a injetar na view
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = APP_PATH . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View não encontrada: {$view}");
        }

        require APP_PATH . '/views/layouts/main.php';
    }

    /**
     * Redireciona para uma rota interna
     */
    protected function redirect(string $route): void
    {
        header('Location: ' . BASE_URL . 'index.php?route=' . $route);
        exit;
    }

    /**
     * Bloqueia o acesso de usuários não autenticados
     * Redireciona para o login caso a sessão não esteja ativa
     */
    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('login');
        }
    }
}