<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class User
{

    private $pdo;

    private $id;
    private $nome;
    private $cpf;
    private $email;
    private $data_nascimento;
    private $telefone;
    private $senha;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    // Cria um novo usuário
    public function create($nome, $cpf, $email, $data_nascimento, $telefone, $senha) {

        $sql = "INSERT INTO users (nome, cpf, email, data_nascimento, telefone, senha) VALUES(:nome, :cpf, :email, :data_nascimento, :telefone, :senha)";
        $stmt = $this->pdo->prepare($sql);

        // Criptografa a senha antes de salvar
        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':senha', $hashedPassword);

        return $stmt->execute();

    }

    // Busca todos os usuários
    public function getAll() {

        $sql = "SELECT id, nome, cpf, email, DATE_FORMAT(data_nascimento, '%d/%m/%Y') AS data_nascimento_formatada, telefone, senha FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Retorna todos os usuários em um Array Associativo
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formata o CPF para cada usuário
        foreach ($users as &$user) {
           $user['cpf'] = preg_replace("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", "$1.$2.$3-$4", $user['cpf']);
        }

        return $users;

    }

    // Busca um usuário pelo ID
    public function getById($id) {
        $query = "SELECT id, nome, cpf, email, data_nascimento, telefone FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $users = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Formata o CPF para um usuário específico
        $users['cpf'] = preg_replace("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", "$1.$2.$3-$4", $users['cpf']);

        return $users;
    }

    // Método para atualizar um usuário
    public function update($id, $nome, $cpf, $email, $dataNascimento, $telefone, $senha) {

        $stmt = $this->pdo->prepare("
            UPDATE users
            SET nome = :nome, cpf = :cpf, email = :email, data_nascimento = :dataNascimento, telefone = :telefone, senha = :senha
            WHERE id = :id
        ");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':dataNascimento', $dataNascimento);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':senha', $senha);

        return $stmt->execute();
    }

    public function updateWithPassword($id, $nome, $cpf, $email, $dataNascimento, $telefone, $senha)
    {
        $sql = "UPDATE users SET nome = ?, cpf = ?, email = ?, data_nascimento = ?, telefone = ?, senha = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nome, $cpf, $email, $dataNascimento, $telefone, $senha, $id]);
    }

    public function updateWithoutPassword($id, $nome, $cpf, $email, $dataNascimento, $telefone)
    {
        $sql = "UPDATE users SET nome = ?, cpf = ?, email = ?, data_nascimento = ?, telefone = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nome, $cpf, $email, $dataNascimento, $telefone, $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Executa a query e verifica se a exclusão foi bem-sucedida
        if (!$stmt->execute()) {
            throw new \Exception("Erro ao excluir o usuário.");
        }
    }

        // Verifica se o CPF já existe no banco
        public function isCpfExist($cpf, $id = null) {
            $sql = "SELECT COUNT(*) FROM users WHERE cpf = :cpf";
            if ($id) {
                $sql .= " AND id != :id"; // Exclui o usuário atual da verificação
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            if ($id) {
                $stmt->bindParam(':id', $id);
            }
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        }

        // Verifica se o e-mail já existe no banco
        public function isEmailExist($email, $id = null) {
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            if ($id) {
                $sql .= " AND id != :id"; // Exclui o usuário atual da verificação
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            if ($id) {
                $stmt->bindParam(':id', $id);
            }
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        }

}

?>
