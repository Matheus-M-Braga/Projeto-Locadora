<link rel="stylesheet" href="estilos/style.css?<?php echo rand(1, 1000); ?>">
<?php
    if(!empty($_GET['id'])){
        include_once('../config.php');

        $codUsuario = $_GET['id'];

        $sqlSelect = "SELECT * FROM usuarios WHERE CodUsuario = $codUsuario";

        $result = $conexao -> query($sqlSelect);

        $user_data = mysqli_fetch_assoc($result);
        $nomeUsuario = $user_data['Nome'];

        // Conexão tabela alugueis
        $sqlAluguel_conect = "SELECT * FROM alugueis WHERE usuario = '$nomeUsuario'";
        $sqlAluguel_conectre_result = $conexao -> query($sqlAluguel_conect);

        $aluguel_data = mysqli_fetch_assoc($sqlAluguel_conectre_result);
        $nomeUserAluguel = $aluguel_data['usuario'];
        $data_devolucao = $aluguel_data['data_devolucao'];
        
        // Verificar se possui aluguel
        foreach($sqlAluguel_conectre_result as $linha[]){
            //echo var_dump($linha).'<br>';
        }
        if($sqlAluguel_conectre_result -> num_rows > 1){
            if($linha[0]['data_devolucao'] != 0 && $linha[1]['data_devolucao'] == 0){
                echo "<script> alert('Usuário não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../user.php', false, 303);
            }
            else if($linha[0]['data_devolucao'] == 0 && $linha[1]['data_devolucao'] == 0){
                echo "<script> alert('Usuário não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../user.php', false, 303);
            }
            else if($linha[0]['data_devolucao'] == 0 && $linha[1]['data_devolucao'] != 0){
                echo "<script> alert('Usuário não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../user.php', false, 303);
            }
            else{
                if($result -> num_rows > 0){
                    $sqlDelete = "DELETE FROM usuarios WHERE CodUsuario = $codUsuario";
                    $resultDelete= $conexao -> query($sqlDelete);
                }
                $sqlReset = "ALTER TABLE livros AUTO_INCREMENT = 1;";
                $resultReset = $conexao -> query($sqlReset);
                header('Location: ../user.php');
            }
        }
        else{
            if($nomeUsuario === $nomeUserAluguel && $data_devolucao == 0){
                echo "<script> alert('Usuário não pode ser deletado, pois possui aluguel ativo.'); </script>";
                header('Refresh:0; url=../user.php', false, 303);
            }
            else{
                if($result -> num_rows > 0){
                $sqlDelete = "DELETE FROM usuarios WHERE CodUsuario = $codUsuario";
                $resultDelete= $conexao -> query($sqlDelete);
                
                }
                $sqlReset = "ALTER TABLE usuarios AUTO_INCREMENT = 1;";
                $resultReset = $conexao -> query($sqlReset);
                header('Location: ../user.php');
            }
        }
    }
?>