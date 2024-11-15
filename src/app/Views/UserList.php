<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Lista de Usuários</h2>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Data de Nascimento</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['cpf']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['data_nascimento_formatada']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['telefone']) . "</td>";
                    echo "<td>
                            <a href='/user/edit/" . $user['id'] . "' class='btn btn-sm btn-warning'>Editar</a>
                            <a href='/user/delete/" . $user['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="/user/add" class="btn btn-success">Adicionar Novo Usuário</a>
    </div>
</body>
</html>
