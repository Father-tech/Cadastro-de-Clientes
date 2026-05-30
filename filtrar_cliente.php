<?php
// 1. CONEXÃO: Traz a ponte com o MySQL
require 'conexao.php';

// Inicializa a variável de busca e o array de resultados
$termo_busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$clientes = [];

// 2. LÓGICA DE BUSCA: Só faz a consulta se o usuário tiver digitado algo
if ($termo_busca !== '') {
    try {
        // O SQL usa LIKE para buscar por parte do nome ou do e-mail
        // O % significa "qualquer texto antes ou depois"
        $sql = "SELECT id, nome, email, telefone FROM clientes 
                WHERE nome LIKE :busca OR email LIKE :busca 
                ORDER BY nome ASC";
        
        $stmt = $pdo->prepare($sql);
        
        // Vincula o termo de busca com as porcentagens do LIKE
        $stmt->execute([':busca' => '%' . $termo_busca . '%']);
        
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Erro ao buscar clientes: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>🔍 Filtrar Clientes</h1>

    <div class="form-container" style="margin-bottom: 10px;">
        <form action="filtrar_cliente.php" method="GET">
            <div class="form-group">
                <label for="busca">Digite o nome ou e-mail do cliente:</label>
                <input type="text" name="busca" id="busca" 
                       value="<?= htmlspecialchars($termo_busca) ?>" 
                       placeholder="Ex: João ou joao@email.com..." required>
            </div>
            
            <div class="container-botoes">
                <button type="submit" class="btn-add btn-filtro">Buscar</button>
                <a href="index.php" class="btn-add btn-voltar">Limpar / Voltar</a>
            </div>
        </form>
    </div>

    <?php if ($termo_busca !== ''): ?>
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
                        <td colspan="5" style="color: #dc3545; font-weight: bold;">
                            Nenhum cliente encontrado para: "<?= htmlspecialchars($termo_busca) ?>"
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>