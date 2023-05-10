<?php
    include_once('../config.php');

    if(isset($_POST['update'])){
        $entrada = new DateTime(date("Y/m/d", strtotime($_POST['dat_aluguel'])));
        $saida = new DateTime(date("Y/m/d", strtotime($_POST['prev_devolucao'])));

        $intervalo = $entrada -> diff($saida);
        $dias = $intervalo -> days;

        if($dias > 30){
            header('Location: ../aluguel.php');
        }
        else{
            $codAluguel = $_POST['id'];
            $nomeLivro = $_POST['nome-livro'];
            $usuario = $_POST['usuario'];
            $dat_aluguel = $_POST['dat_aluguel'];
            $prev_devolucao = $_POST['prev_devolucao'];

            $sqlUpdate = "UPDATE alugueis SET livro = '$nomeLivro', usuario = '$usuario', data_aluguel = '$dat_aluguel', prev_devolucao = '$prev_devolucao' WHERE CodAluguel = '$codAluguel'";

            $result = $conexao -> query($sqlUpdate);
        }
        
    }
    header('Location: ../aluguel.php');
?>