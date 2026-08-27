<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
    /**
     * Busca um usuário pelo e-mail
     * Retorna null se não encontrado ou se o usuário estiver inativo
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM user WHERE email = ? AND ativo = 1 LIMIT 1'
        );
        $stmt->execute([trim($email)]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Busca um usuário ativo pelo ID
     * Não retorna a senha
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id_user, name, email, created_at, update_at, ativo
               FROM user
              WHERE id_user = ? AND ativo = 1
              LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Lista todos os usuários ativos para popular selects de filtro
     */
    public function all(): array
    {
        return $this->db
            ->query('SELECT id_user, name FROM user WHERE ativo = 1 ORDER BY name ASC')
            ->fetchAll();
    }

    /**
     * Cadastra um novo usuário.
     * Retorna false se o e-mail já estiver em uso (UNIQUE KEY).
     */
    public function create(string $name, string $email, string $password): bool
    {
        // Verifica se o e-mail já existe antes de tentar inserir
        if ($this->findByEmail($email)) {
            return false;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO user (name, email, password) VALUES (?, ?, ?)'
        );

        return $stmt->execute([
            trim($name),
            trim($email),
            password_hash($password, PASSWORD_BCRYPT),
        ]);
    }
}