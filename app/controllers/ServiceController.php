<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Mailer;
use app\core\Session;
use app\models\Service;
use app\models\User;

class ServiceController extends Controller
{
    /**
     * GET /services/create 
     */
    public function create(): void
    {
        $this->requireAuth();

        $this->render('services/create', [
            'error' => Session::getFlash('error'),
        ]);
    }

    /**
     * POST /services/store 
     */
    public function store(): void
    {
        $this->requireAuth();

        $description = trim($_POST['description'] ?? '');
        $priceRaw    = trim($_POST['price'] ?? '');
        $priceVal    = str_replace(',', '.', $priceRaw);

        if (empty($description)) {
            Session::flash('error', 'O campo Descrição é obrigatório.');
            $this->redirect('dashboard');
        }
        if (empty($priceRaw)) {
            Session::flash('error', 'O campo Valor é obrigatório.');
            $this->redirect('dashboard');
        }
        if (!is_numeric($priceVal)) {
            Session::flash('error', 'O campo Valor deve ser numérico.');
            $this->redirect('dashboard');
        }

        $data = [
            'description'  => $description,
            'price'        => (float) $priceVal,
            'user_id_user' => (int) Session::get('user_id'),
        ];

        $serviceModel = new Service();

        if ($serviceModel->create($data)) {
            Session::flash('success', 'Serviço cadastrado com sucesso!');
        } else {
            Session::flash('error', 'Falha ao cadastrar o serviço. Tente novamente.');
        }

        $this->redirect('dashboard');
    }

    /**
     * GET /services/edit?id=X 
     */
    public function edit(): void
    {
        $this->requireAuth();

        $id           = (int) ($_GET['id'] ?? 0);
        $serviceModel = new Service();
        $service      = $serviceModel->findById($id);

        if (!$service) {
            Session::flash('error', 'Serviço não encontrado.');
            $this->redirect('dashboard');
        }

        $this->render('services/edit', [
            'service' => $service,
            'error'   => Session::getFlash('error'),
        ]);
    }

    /**
     * POST /services/update 
     */
    public function update(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);

        $description = trim($_POST['description'] ?? '');
        $priceRaw    = trim($_POST['price'] ?? '');
        $priceVal    = str_replace(',', '.', $priceRaw);

        if (empty($description)) {
            Session::flash('error', 'O campo Descrição é obrigatório.');
            $this->redirect('services/edit&id=' . $id);
        }
        if (empty($priceRaw)) {
            Session::flash('error', 'O campo Valor é obrigatório.');
            $this->redirect('services/edit&id=' . $id);
        }
        if (!is_numeric($priceVal)) {
            Session::flash('error', 'O campo Valor deve ser numérico.');
            $this->redirect('services/edit&id=' . $id);
        }

        $data = [
            'description' => $description,
            'price'       => (float) $priceVal,
        ];

        $serviceModel = new Service();

        if ($serviceModel->update($id, $data)) {
            Session::flash('success', 'Serviço atualizado com sucesso!');
            $this->redirect('dashboard');
        } else {
            Session::flash('error', 'Falha ao atualizar. Tente novamente.');
            $this->redirect('services/edit&id=' . $id);
        }
    }

    /**
     * POST /services/delete
     */
    public function delete(): void
    {
        $this->requireAuth();

        $id           = (int) ($_POST['id'] ?? 0);
        $serviceModel = new Service();

        if ($serviceModel->delete($id)) {
            Session::flash('success', 'Serviço excluído com sucesso!');
        } else {
            Session::flash('error', 'Não foi possível excluir o serviço.');
        }

        $this->redirect('dashboard');
    }

    /**
     * POST /services/finalize
     */
    public function finalize(): void
    {
        $this->requireAuth();

        $id           = (int) ($_POST['id'] ?? 0);
        $serviceModel = new Service();
        $service      = $serviceModel->findById($id);

        if (!$service) {
            Session::flash('error', 'Serviço não encontrado.');
            $this->redirect('dashboard');
        }

        // Valida se o serviço existe e ainda está pendente 
        if ($service['finished_at'] !== null) {
            Session::flash('error', 'Este serviço já foi finalizado anteriormente.');
            $this->redirect('dashboard');
        }

        // Calcula a comissão
        $commission = calculaComissao((float) $service['price']);

        // Persiste a finalização
        if (!$serviceModel->finalize($id, $commission)) {
            Session::flash('error', 'Falha ao finalizar o serviço. Tente novamente.');
            $this->redirect('dashboard');
        }

        // Recupera novamente para obter finished_at já preenchido
        $service = $serviceModel->findById($id);

        $userModel = new User();
        $user      = $userModel->findById((int) $service['user_id_user']);

        // Envia e-mail de notificação ao usuário
        if ($user) {
            $mailer = new Mailer();
            $mailer->sendServiceFinalized($user, $service);
        }

        $comissaoFormatada = formataDinheiro($commission);
        Session::flash('success', "Serviço finalizado! Comissão calculada: {$comissaoFormatada}");
        $this->redirect('dashboard');
    }
}