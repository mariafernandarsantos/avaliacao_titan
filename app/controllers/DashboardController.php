<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\Service;
use app\models\User;

class DashboardController extends Controller
{
    /**
     * GET /dashboard
     */
    public function index(): void
    {
        $this->requireAuth();

        $userId = (int) Session::get('user_id');

        $serviceModel = new Service();
        $userModel    = new User();

        // Filtros vindos da URL 
        $filters = [
            'date_from'   => $_GET['date_from']   ?? '',
            'date_to'     => $_GET['date_to']     ?? '',
            'description' => $_GET['description'] ?? '',
            'status'      => $_GET['status']      ?? '',
            'user_id'     => $_GET['user_filter'] ?? '',
        ];

        $services = $serviceModel->fetchFiltered($filters);
        $total    = $serviceModel->getTotalValueByUser($userId);
        $pending  = $serviceModel->getPendingByUser($userId);
        $users    = $userModel->all();

        $this->render('dashboard/index', [
            'services' => $services,
            'total'    => $total,
            'pending'  => $pending,
            'users'    => $users,
            'filters'  => $filters,
            'success'  => Session::getFlash('success'),
            'error'    => Session::getFlash('error'),
        ]);
    }
}