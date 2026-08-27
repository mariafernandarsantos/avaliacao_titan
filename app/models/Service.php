<?php

namespace app\models;

use app\core\Model;
use PDO;

class Service extends Model
{
    /**
     * Cadastra um novo serviço.
     * finished_at e commission_user ficam nulos
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO service (description, price, user_id_user)
             VALUES (?, ?, ?)'
        );

        return $stmt->execute([
            $data['description'],
            $data['price'],
            $data['user_id_user'],
        ]);
    }

    /**
     * Atualiza descrição e preço de um serviço
     * update_at é preenchido automaticamente pelo MySQL
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE service SET description = ?, price = ? WHERE id_service = ?'
        );

        return $stmt->execute([$data['description'], $data['price'], $id]);
    }

    /**
     * Remove um serviço pelo ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM service WHERE id_service = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Busca um serviço pelo ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT s.*,
                    u.name  AS user_name,
                    u.email AS user_email
               FROM service s
               JOIN user u ON u.id_user = s.user_id_user
              WHERE s.id_service = ?
              LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Lista serviços com filtros opcionais
     *
     * Filtros aceitos:
     *   date_from   — data inicial de criação 
     *   date_to     — data final de criação   
     *   description — busca parcial na descrição
     *   status      — 'Pendente' ou 'Finalizado' (deriva de fineshed_at)
     *   user_id     — ID do usuário responsável
     */
    public function fetchFiltered(array $filters = []): array
    {
        $conditions = [];
        $params     = [];

        if (!empty($filters['date_from'])) {
            $conditions[] = 'DATE(s.created_at) >= ?';
            $params[]     = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'DATE(s.created_at) <= ?';
            $params[]     = $filters['date_to'];
        }

        if (!empty($filters['description'])) {
            $conditions[] = 's.description LIKE ?';
            $params[]     = '%' . $filters['description'] . '%';
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Finalizado') {
                $conditions[] = 's.finished_at IS NOT NULL';
            } else {
                $conditions[] = 's.finished_at IS NULL';
            }
        }

        if (!empty($filters['user_id'])) {
            $conditions[] = 's.user_id_user = ?';
            $params[]     = (int) $filters['user_id'];
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT s.id_service,
                       s.description,
                       s.price,
                       s.commission_user,
                       s.finished_at,
                       s.created_at,
                       s.update_at,
                       u.name AS user_name
                  FROM service s
                  JOIN user u ON u.id_user = s.user_id_user
                {$where}
                 ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Retorna o valor total somado dos serviços de um usuário
     */
    public function getTotalValueByUser(int $userId): float
    {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(price), 0) FROM service WHERE user_id_user = ?'
        );
        $stmt->execute([$userId]);

        return (float) $stmt->fetchColumn();
    }

    /**
     * Últimos serviços pendentes de um usuário (finished_at IS NULL)
     */
    public function getPendingByUser(int $userId, int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT id_service, description, price, created_at
               FROM service
              WHERE user_id_user = ? AND finished_at IS NULL
              ORDER BY created_at DESC
              LIMIT ?'
        );

        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit,  PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Finaliza um serviço: grava a data e a comissão calculada
     * O campo update_at é atualizado automaticamente pelo MySQL
     */
    public function finalize(int $id, float $commission): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE service
                SET finished_at = NOW(), commission_user = ?
              WHERE id_service = ?'
        );

        return $stmt->execute([$commission, $id]);
    }
}