<?php
    include_once('../config.php');

    if(isset($_POST['update'])){

        $codLivro = $_POST['id'];
        $nomeLivro = $_POST['nome-livro'];
        $autor = $_POST['autor'];
        $editora = $_POST['editora'];
        $lancamento = $_POST['lancamento'];
        $quantidade = $_POST['quantidade'];

        $sqlUpdate = "UPDATE livros SET nome = '$nomeLivro', autor = '$autor', editora = '$editora', lancamento = '$lancamento', quantidade = '$quantidade' WHERE CodLivro = '$codLivro'";

        $result = $conexao -> query($sqlUpdate);
        
    }
    header('Location: ../livro.php');
?>