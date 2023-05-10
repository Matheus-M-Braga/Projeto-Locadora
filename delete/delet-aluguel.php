<?php
    if(!empty($_GET['id'])){
        include_once('../config.php');

        $codAluguel = $_GET['id'];

        $sqlSelect = "SELECT * FROM alugueis WHERE CodAluguel = $codAluguel";

        $result = $conexao -> query($sqlSelect);

        if($result -> num_rows > 0){
            $sqlDelete = "DELETE FROM alugueis WHERE CodAluguel = $codAluguel";
            $resultDelete= $conexao -> query($sqlDelete);
        }
        $sqlReset = "ALTER TABLE alugueis AUTO_INCREMENT = 1;";
        $resultReset = $conexao -> query($sqlReset);
    }
    header('Location: ../aluguel.php'); 
?>