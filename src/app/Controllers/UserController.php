<?php

namespace App\Controllers;

use App\Utils\ValidationUtil;
use App\Models\User;

class UserController
{

    private $userModel;

    public function __construct(User $userModel) {

        $this->userModel = $userModel;
    }

    // Lista todos os usuários
    public function index()
    {
        $users = $this->userModel->getAll();

        include_once __DIR__ . '/../Views/UserList.php';
    }

    // Exibe o formulário de criação
    public function add() {

        include_once __DIR__ .  '/../Views/UserCreate.php';

    }

    // Salva um novo usuário
    public function create() {

        // Verifica se o método da requisição é POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nome = $_POST['nome'];
            $cpf = $_POST['cpf'];
            $email = $_POST['email'];
            $dataNascimento = $_POST['data_nascimento'];
            $telefone = $_POST['telefone'];
            $senha = $_POST['senha'];


            // Valida o CPF
            if (!ValidationUtil::validaCpf($cpf)) {
                echo "CPF inválido!";
                return;
            }

            // Valida o e-mail
            if (!ValidationUtil::validaEmail($email)) {
                echo "E-mail inválido!";
                return;
            }

            // Verifica se o CPF já existe (exceto para o mesmo usuário)
            if ($this->userModel->isCpfExist($cpf)) {
                echo "O CPF já está cadastrado.";
                return;
            }

            // Verifica se o e-mail já existe (exceto para o mesmo usuário)
            if ($this->userModel->isEmailExist($email)) {
               echo "O e-mail já está cadastrado.";
               return;
            }

            // Salva o novo usuário no banco
            $this->userModel->create($nome, $cpf, $email, $dataNascimento, $telefone, $senha);

            // Redireciona para a lista de usuários
            header('Location: /users');
            exit();

        } else {
           echo "Método HTTP não permitido.";
        }


    }

    // Exibe o formulário de edição
    public function edit($id)
    {
            // Busca o usuário pelo ID
            $user = $this->userModel->getById($id);

            // Se o usuário não existir, redireciona ou exibe um erro
            if (!$user) {
                echo "Usuário não encontrado!";
                return;
            }

            // Inclui a view para edição, passando os dados do usuário
            include_once __DIR__ . '/../Views/UserEdit.php';
    }

    public function update() {

    // Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];
        $dataNascimento = $_POST['data_nascimento'];
        $telefone = $_POST['telefone'];
        $senha = $_POST['senha'];

        // Valida o CPF
        if (!ValidationUtil::validaCpf($cpf)) {
            echo "CPF inválido!";
            return;
        }

         // Valida o e-mail
         if (!ValidationUtil::validaEmail($email)) {
             echo "E-mail inválido!";
             return;
         }

         // Verifica se o CPF já existe (exceto para o mesmo usuário)
         if ($this->userModel->isCpfExist($cpf, $id)) {
             echo "O CPF já está cadastrado.";
             return;
         }

         // Verifica se o e-mail já existe (exceto para o mesmo usuário)
         if ($this->userModel->isEmailExist($email, $id)) {
             echo "O e-mail já está cadastrado.";
             return;
         }

        // Verifica se o campo senha está vazio
        if (empty($senha)) {
                // Atualiza sem modificar a senha
                $this->userModel->updateWithoutPassword($id, $nome, $cpf, $email, $dataNascimento, $telefone);
        } else {
                // Atualiza com nova senha
                $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);
                $this->userModel->updateWithPassword($id, $nome, $cpf, $email, $dataNascimento, $telefone, $senhaCriptografada);
        }

        // Redireciona para a lista de usuários
        header('Location: /users');
        exit();

    } else {
        echo "Método HTTP não permitido.";
    }

    }

     public function delete($id)
        {
            // Verifica se o ID foi passado e é válido
            if (!isset($id) || empty($id)) {
                http_response_code(400);
                echo "ID de usuário inválido.";
                return;
            }

            try {
                // Tenta excluir o usuário pelo ID
                $this->userModel->delete($id);
                // Redireciona para a listagem de usuários após a exclusão
                header('Location: /users');
                exit;
            } catch (\Exception $e) {
                // Em caso de erro, retorna uma mensagem
                http_response_code(500);
                echo "Erro ao excluir o usuário: " . $e->getMessage();
            }
       }

}

?>
