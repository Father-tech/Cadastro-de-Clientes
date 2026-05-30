<?php 
    $host = 'localhost'; // local onde o mysql está rodando
    $dbname = 'dev_crud'; // nome do banco de dados
    $user = 'root'; //usuario que irá entrar no banco de dados, nesse caso é o root
    $pass = ''; //senha padrão é uma senha vazia

    try{
        // 2. CRIANDO A CONEXÃO PDO
        // A string DSN (Data Source Name) informa o tipo do BD (mysql), o host e o dbname.
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

        // Configura o PDO para lançar exceções em caso de erros SQL
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e){
        // 3. TRATAMENTO DE ERROS
        // Se a conexão falhar, mostra o erro e impede a execução do resto do código.
        echo "Erro de Conexão " . $e->getMessage();
        die();
    }
    // Quando outros arquivos PHP incluírem (require) este arquivo, eles terão acesso à variável $pdo, que é a nossa conexão ativa.
?>