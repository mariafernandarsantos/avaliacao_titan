<?php

declare(strict_types=1);

// ------------------------------------------------------------------
//  Constantes de ambiente
// ------------------------------------------------------------------

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');

// BASE_URL é calculada dinamicamente para funcionar em qualquer subdiretório
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/') . '/');

// ------------------------------------------------------------------
//  Autoloader PSR-4
//  Mapeia o namespace app\ para a pasta app/
// ------------------------------------------------------------------

spl_autoload_register(function (string $class): void {
    $prefix = 'app\\';

    if (!str_starts_with($class, $prefix)) {
        return; // Ignora classes fora do namespace da aplicação
    }

    $relative = substr($class, strlen($prefix));
    $file     = APP_PATH . '/' . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// ------------------------------------------------------------------
//  Funções utilitárias
// ------------------------------------------------------------------

require_once APP_PATH . '/helpers/helpers.php';

// ------------------------------------------------------------------
//  Sessão e roteamento
// ------------------------------------------------------------------

use app\core\Session;
use app\core\Router;

Session::start();

$router = new Router();

// Autenticação
$router->get('login',   'AuthController@showLogin');
$router->post('login',  'AuthController@doLogin');
$router->get('logout',  'AuthController@doLogout');

// Cadastro de usuário
$router->get('register',  'AuthController@showRegister');
$router->post('register', 'AuthController@doRegister');

// Dashboard principal
$router->get('dashboard', 'DashboardController@index');

// Gerenciamento de serviços
$router->get('services/create',    'ServiceController@create');
$router->post('services/store',    'ServiceController@store');
$router->get('services/edit',      'ServiceController@edit');
$router->post('services/update',   'ServiceController@update');
$router->post('services/delete',   'ServiceController@delete');
$router->post('services/finalize', 'ServiceController@finalize');

$router->dispatch();