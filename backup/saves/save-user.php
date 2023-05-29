<?php
    include_once('../config.php');

    if(isset($_POST['update'])){

        $codUsuario = $_POST['id'];
        $nomeUsuario = $_POST['nome-user'];
        $cidade = $_POST['cidade'];
        $endereco = $_POST['endereco'];
        $email = $_POST['email'];

        $sqlUpdate = "UPDATE usuarios SET Nome = '$nomeUsuario', Cidade = '$cidade', Endereco = '$endereco', Email = '$email' WHERE CodUsuario = '$codUsuario'";

        $result = $conexao -> query($sqlUpdate);
        
    }
    header('Location: ../user.php');
?>