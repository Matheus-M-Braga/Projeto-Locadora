<?php
    if(!empty($_GET['id'])){
        include_once('../config.php');

        $codEditora = $_GET['id'];

        $sqlSelect = "SELECT * FROM editoras WHERE CodEditora = $codEditora";

        $result = $conexao -> query($sqlSelect);

        $editora_data = mysqli_fetch_assoc($result);
        $nomeEditora = $editora_data['nome'];

        // Conexão tabela livros
        $sqlLivro_conect = "SELECT * FROM livros WHERE editora = '$nomeEditora'";
        $sqlLivro_conect_result = $conexao -> query($sqlLivro_conect);

        $livro_data = mysqli_fetch_assoc($sqlLivro_conect_result);
        $editora_livro = $livro_data['editora'];
 
        if($editora_livro === $nomeEditora){
            echo "<script> alert('A editora possui livros cadastrados e, portanto, não pode ser deletada.'); </script>";
            header('Refresh:0; url=../editora.php', true, 303);
        }
        else{
            if($result -> num_rows > 0){
                $sqlDelete = "DELETE FROM editoras WHERE CodEditora = $codEditora";
                $resultDelete= $conexao -> query($sqlDelete);
            }
            $sqlReset = "ALTER TABLE editoras AUTO_INCREMENT = 1;";
            $resultReset = $conexao -> query($sqlReset);
            header('Location: ../editora.php');
        }
    } 
?>