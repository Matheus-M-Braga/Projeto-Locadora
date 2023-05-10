<?php
    if(!empty($_GET['id'])){
        include_once('../config.php');

        $codLivro = $_GET['id'];

        $sqlSelect = "SELECT * FROM livros WHERE CodLivro = $codLivro";

        $result = $conexao -> query($sqlSelect);

        $livro_data = mysqli_fetch_assoc($result);
        $nomeLivro = $livro_data['nome'];

        // Conexão tabela alugueis
        $sqlAluguelConect = "SELECT livro, data_devolucao FROM alugueis WHERE livro = '$nomeLivro'";
        $sqlAluguelConect_result = $conexao -> query($sqlAluguelConect);

        $aluguel_data = mysqli_fetch_assoc($sqlAluguelConect_result);
        $livro_alugado = $aluguel_data['livro'];
        $data_devolucao = $aluguel_data['data_devolucao'];

        // Verificar se possui aluguel
        foreach($sqlAluguelConect_result as $linha[]){
            //echo var_dump($linha).'<br>';
        }
        if($sqlAluguelConect_result -> num_rows > 1){
            if($linha[0]['data_devolucao'] != 0 && $linha[1]['data_devolucao'] == 0){
                echo "<script> alert('Livro não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../livro.php', false, 303);
            }
            else if($linha[0]['data_devolucao'] == 0 && $linha[1]['data_devolucao'] == 0){
                echo "<script> alert('Livro não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../livro.php', false, 303);
            }
            else if($linha[0]['data_devolucao'] == 0 && $linha[1]['data_devolucao'] != 0){
                echo "<script> alert('Livro não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../livro.php', false, 303);
            }
            else{
                if($result -> num_rows > 0){
                    $sqlDelete = "DELETE FROM livros WHERE CodLivro = $codLivro";
                    $resultDelete= $conexao -> query($sqlDelete);
                }
                $sqlReset = "ALTER TABLE livros AUTO_INCREMENT = 1;";
                $resultReset = $conexao -> query($sqlReset);
                header('Location: ../livro.php');
            }
        }
        else{
            if($nomeLivro === $livro_alugado && $data_devolucao == 0){
                echo "<script> alert('Livro não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../livro.php', false, 303);
            }
            else{
                if($result -> num_rows > 0){
                    $sqlDelete = "DELETE FROM livros WHERE CodLivro = $codLivro";
                    $resultDelete= $conexao -> query($sqlDelete);
                
                }
                $sqlReset = "ALTER TABLE livros AUTO_INCREMENT = 1;";
                $resultReset = $conexao -> query($sqlReset);
                header('Location: ../livro.php');
            }
        }
    }
?>