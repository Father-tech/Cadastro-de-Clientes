<?php 
    require 'conexao.php';

    $mensagem = '';
    $cliente = null;

    //1- BUSCAR OS DADOS ATUAIS 

    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    try{
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente){
            die("Cliente não encontrado!");
        }
        
    }catch(PDOException $e){
        die("Erro ao buscar dados: " . $e->getMessage());
    }
    //2 - SALVAR AS ALTERAÇÕES (UPDATE)

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];

        try{
            // O comando SQL para atualizar. IMPORTANTE: O "WHERE id = :id" é obrigatório, 
            // senão você atualiza TODOS os clientes do banco com o mesmo nome!
            $sql = "UPDATE clientes SET nome = :nome, email=:email, telefone=:telefone WHERE id=:id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome',$nome);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':telefone',$telefone);
            $stmt->bindParam(':id',$id);

            if($stmt->execute()){
                $mensagem = "Tarefa atualizada com sucesso!";
                //Atualiza os dados da variável $cliente para mostrar no formulário
                $cliente['nome'] = $nome;
                $cliente['email'] = $email;
                $cliente['telefone'] = $telefone;
            }
            }catch(PDOException $e){
                $mensagem = "Erro ao atualizar: " . $e->getMessage();
            }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        .mensagem {
            padding: 10px;
            margin: 15px auto;
            max-width: 500px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
        .msg-sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .msg-erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <h1>✏️ Editar Informações do Cliente</h1>

    <div class="form-container">

        <?php if(!empty($mensagem)): ?>
            <?php $classe_msg = (strpos($mensagem, 'sucesso') !== false || strpos($mensagem, 'alterado') !== false || strpos($mensagem, 'atualizado') !== false) ? 'msg-sucesso' : 'msg-erro'; ?>
            <div class="mensagem <?= $classe_msg ?>"> 
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="editar_cliente.php?id=<?= $cliente['id'] ?>" method="POST">

            <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
            
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= $cliente['nome'] ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= $cliente['email'] ?>" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?= $cliente['telefone'] ?>">
            </div>

            <div class="container-botoes">
                <button type="submit" class="btn-add">Salvar Alterações</button>
                <a href="index.php" class="btn-add btn-voltar">Voltar para a Lista</a>
            </div>
        </form>
    </div>

</body>
</html>