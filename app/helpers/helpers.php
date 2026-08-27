<?php

use app\core\Session;

// ------------------------------------------------------------------
//  Formatação
// ------------------------------------------------------------------

/**
 * Escapa uma string para exibição segura em HTML
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Formata um número como real
 */
function formataDinheiro(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/**
 * Formata data do banco (Y-m-d) para (d/m/Y)
 */
function formatDate(?string $date): string
{
    if (!$date) {
        return '—';
    }
    return date('d/m/Y', strtotime($date));
}

/**
 * Formata datetime do banco para exibição legível
 */
function formatDateTime(?string $datetime): string
{
    if (!$datetime) {
        return '—';
    }
    return date('d/m/Y \à\s H:i', strtotime($datetime));
}

/**
 * Retorna a data atual por extenso
 */
function currentDate(): string
{
    $dias   = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira',
               'Quinta-feira', 'Sexta-feira', 'Sábado'];
    $meses  = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
               'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    $diaSemana = $dias[(int) date('w')];
    $dia       = date('d');
    $mes       = $meses[(int) date('n') - 1];
    $ano       = date('Y');

    return "{$diaSemana}, {$dia} de {$mes} de {$ano}";
}

// ------------------------------------------------------------------
//  Regras de negócio
// ------------------------------------------------------------------

/**
 * Calcula a comissão conforme as regras:
 *   ≤ R$ 1.000,00  - 5%
 *   > R$ 1.000,00  - 10%
 *   > R$ 10.000,00 - 20%
 */
function calculaComissao(float $value): float
{
    if ($value > 10000.00) {
        return round($value * 0.20, 2);
    } elseif ($value > 1000.00) {
        return round($value * 0.10, 2);
    }

    return round($value * 0.05, 2);
}

// ------------------------------------------------------------------
//  Navegação
// ------------------------------------------------------------------

/**
 * Redireciona para uma rota interna e encerra a execução
 */
function redirect(string $route): void
{
    header('Location: ' . BASE_URL . 'index.php?route=' . $route);
    exit;
}