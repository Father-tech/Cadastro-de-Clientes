<?php 
    require 'conexao.php';

    //1 - Pegamos o ID via GET (vindo do link na lista)
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        try{
            //2 - Preparamos o comando.
            //AVISO: NUNCA esqueça o WHERE no DELETE, ou você apaga a tabela TODA;
            $sql = "DELETE FROM clientes WHERE id =:id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id',$id);

            if($stmt->execute()){
                // 3. Se deu certo, voltamos para a lista com uma mensagem de sucesso
                header("Location: index.php?msg=excluido");
                exit;
            }
        }catch(PDOException $e){
            die("Erro ao excluir: ". $e->getMessage());
        }
    }else{
        //Se tentarem acessar o arquivo sem um ID, mandamos de volta
        header("Location: index.php");
        exit;
    }
?>