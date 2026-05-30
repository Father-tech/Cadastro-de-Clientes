<?php
// 1. CONEXÃO: Traz o arquivo que faz a ponte com o MySQL
require 'conexao.php';

try {
    // 2. O COMANDO: Prepara a pergunta que faremos ao banco
    $sql = "SELECT id, nome, email, telefone FROM clientes ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);

    // 3. A EXECUÇÃO: O MySQL processa a pergunta e guarda a resposta num buffer
    $stmt->execute();

    // 4. A CAPTURA: O fetchAll organiza os dados em uma lista (array) associativa
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao carregar lista: " . $e->getMessage();
    $clientes = []; // Se der erro, a lista fica vazia para não quebrar o HTML
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>📋 Clientes Cadastrados</h1>

    <div class="container-botoes">
        <a href="adicionar_cliente.php" class="btn-add">➕ Novo Cliente</a>
        <a href="filtrar_cliente.php" class="btn-add btn-filtro">🔍 Filtrar</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id'] ?></td>
                        <td><?= $cliente['nome'] ?></td>
                        <td><?= $cliente['email'] ?></td>
                        <td><?= $cliente['telefone'] ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?= $cliente['id'] ?>">✏️ Editar</a> | 
                            <a href="excluir_cliente.php?id=<?= $cliente['id'] ?>" 
                               onclick="return confirm('Tem certeza?')">🗑️ Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>