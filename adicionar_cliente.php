<?php 
    require 'conexao.php';

    //Variavel para armazenar mensagem de sucesso ou erro
    $mensagem = '';

    //1 - CHECAR SE O FORMULARIO FOI ENVIADO
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //2 - RECEBER E SANITIZAR OS DADOS
        //// Sanitizar é a boa prática de limpar dados para evitar XSS e garantir integridade.
        // filter_input_array lê todos os campos de uma vez.
        $dados = filter_input_array(INPUT_POST, [
            'nome' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
            'telefone' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
        ]);

        //Extrair variaveis
        $nome = $dados['nome'];
        $email = $dados['email'];
        $telefone = $dados['telefone'];

        //3 - Validação básica
        if(empty($nome) || empty($email)){
          $mensagem = "<p style='color:red;'> Erro: Nome e Email são <strong>OBRIGATORIOS</strong></p>";
        } else {
            try{
                //4 - PREPARAR O COMANDO SQL (insert)
                $sql = "INSERT INTO clientes (nome, email, telefone) VALUES (:nome, :email, :telefone)";

                $stmt = $pdo->prepare($sql);

                //5 - VINCULAR PARÂMETROS (EVITAR SQL INJECTION)
                // Esta é a segurança do PDO. Os valores são passados separados do comando SQL.
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);

                //6 - EXECUTAR A INSERÇÃO
                $stmt->execute();

                $mensagem = "<p style='color:green;'>Cliente <strong>$nome</strong> cadastrado com sucesso!</p>";
            } catch (PDOException $e){
                // Tratamento de erro de banco de dados (ex: email duplicado, se a coluna for UNIQUE)
                if($e->getCode() == 23000){ //CÓDIGO MySQL para DUPLICATE ENTRY
                    $mensagem = "<p style='color:red;'>Erro: Este email ($email) já está cadastrado.</p>";
                }else{
                    $mensagem = "<p style='color:red;'>Erro de Inserção: " . $e->getMessage() . "</p>";
                }
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Cliente</title>
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

    <h1>📋 Cadastrar Novo Cliente</h1>

    <div class="form-container">

        <?php if(!empty($mensagem)): ?>
            <?php $classe_msg = (strpos($mensagem, 'sucesso') !== false) ? 'msg-sucesso' : 'msg-erro'; ?>
            <div class="mensagem <?= $classe_msg ?>"> 
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="adicionar_cliente.php" method="post">
            
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone">
            </div>

            <div class="container-botoes">
                <button type="submit" class="btn-add">Cadastrar</button>
                <a href="index.php" class="btn-add btn-voltar">Voltar para a Lista</a>
            </div>
        </form>
    </div>

</body>
</html>