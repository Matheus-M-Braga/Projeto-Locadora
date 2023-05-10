<?php
    include_once('../config.php');

    if(isset($_POST['update'])){

        $codEditora = $_POST['id'];
        $nomeEditora = $_POST['nome-editora'];
        $email = $_POST['email-editora'];
        $telefone = $_POST['telefone-editora'];
        $website = $_POST['site-editora'];

        $sqlUpdate = "UPDATE editoras SET nome = '$nomeEditora', email = '$email', telefone = '$telefone', website = '$website' WHERE CodEditora = '$codEditora'";

        $result = $conexao -> query($sqlUpdate);
        
    }
    header('Location: ../editora.php');
?>