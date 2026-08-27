<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\User;

class AuthController extends Controller
{
    /*
     * GET /login
     */
    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/login', [
            'error' => Session::getFlash('error'),
        ]);
    }

    /**
     * POST /login 
     */
    public function doLogin(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Ops, Email ou Senha inválido');
            $this->redirect('login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Ops, Email ou Senha inválido');
            $this->redirect('login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('error', 'Ops, Email ou Senha inválido');
            $this->redirect('login');
        }

        // Armazena os dados da sessão
        Session::set('user_id',    (int) $user['id_user']);
        Session::set('user_name',  $user['name']);
        Session::set('user_email', $user['email']);

        $this->redirect('dashboard');
    }

    /**
     * GET /register 
     */
    public function showRegister(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/register', [
            'error' => Session::getFlash('error'),
        ]);
    }

    /**
     * POST /register 
     */
    public function doRegister(): void
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($name)) {
            Session::flash('error', 'O campo Nome é obrigatório.');
            $this->redirect('register');
        }
        if (empty($email)) {
            Session::flash('error', 'O campo E-mail é obrigatório.');
            $this->redirect('register');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'O campo E-mail deve ser um e-mail válido.');
            $this->redirect('register');
        }
        if (empty($password)) {
            Session::flash('error', 'O campo Senha é obrigatório.');
            $this->redirect('register');
        }

        $userModel = new User();
        $criado    = $userModel->create($name, $email, $password);

        if (!$criado) {
            Session::flash('error', 'Ops, este e-mail já está em uso. Tente outro.');
            $this->redirect('register');
        }

        // Cadastro bem sucedido
        Session::flash('error', '');
        $this->redirect('login');
    }

    /**
     * GET /logout
     */
    public function doLogout(): void
    {
        Session::destroy();
        $this->redirect('login');
    }
}