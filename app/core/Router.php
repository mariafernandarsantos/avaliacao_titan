<?php

namespace app\core;

class Router
{
    /** @var array<string, array<string, string>> */
    private array $routes = [];

    public function get(string $route, string $handler): void
    {
        $this->routes['GET'][$route] = $handler;
    }

    public function post(string $route, string $handler): void
    {
        $this->routes['POST'][$route] = $handler;
    }

    /**
     * Resolve a rota atual e executa o controller correspondente.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $route  = trim($_GET['route'] ?? 'login');

        $handler = $this->routes[$method][$route] ?? null;

        if ($handler === null) {
            http_response_code(404);
            $this->notFound();
            return;
        }

        [$controllerName, $action] = explode('@', $handler, 2);

        $fqcn = "app\\controllers\\{$controllerName}";

        if (!class_exists($fqcn)) {
            http_response_code(500);
            error_log("[Router] Controller não encontrado: {$fqcn}");
            die('Erro interno do servidor.');
        }

        $controller = new $fqcn();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            error_log("[Router] Ação não encontrada: {$fqcn}::{$action}");
            die('Erro interno do servidor.');
        }

        $controller->$action();
    }

    private function notFound(): void
    {
        echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">
              <title>Página não encontrada</title></head>
              <body style="font-family:sans-serif;text-align:center;padding:4rem">
              <h1>404 — Página não encontrada</h1>
              <a href="index.php?route=dashboard">Voltar ao início</a>
              </body></html>';
    }
}