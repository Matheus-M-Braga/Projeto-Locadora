<?php
    session_start();
    $pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_NUMBER_INT);
    include_once('../config.php');

    
    if(!empty($pagina)){
        //Calcular o inicio da visualização
        $result_per_pg = 5; 
        $inicio = ($pagina * $result_per_pg) - $result_per_pg;

        // inserção dos dados na tebela
        if(isset($_POST['submit'])){
            include_once('../config.php');

            date_default_timezone_set('America/Sao_Paulo');

            $entrada = new DateTime(date("Y/m/d", strtotime($_POST['dat_aluguel'])));
            $saida = new DateTime(date("Y/m/d", strtotime($_POST['prev_devolucao'])));

            $intervalo = $entrada -> diff($saida);
            $dias = $intervalo -> days;

            $hoje = date("Y/m/d");
            $aluguel = $_POST['dat_aluguel'];

            
            if(strtotime($aluguel) <= strtotime($hoje)){
                if($dias > 30){
                    echo "<script> window.alert('O prazo de aluguel tem um limite de até 30 dias.') </script>";
                }
                else{
                    $dat_prev = $_POST['prev_devolucao'];
                    $dat_aluga = $_POST['dat_aluguel'];

                    if(strtotime($dat_prev) < strtotime($dat_aluga)){
                        echo "<script> alert('Convenhamos que não há sentido em a data de previsão ser anterior a data de aluguel.') </script>";
                    }
                    else{
                        $nomeLivro = $_POST['nome-livro'];
                        $usuario = $_POST['usuario'];
                        $dat_aluguel = $_POST['dat_aluguel'];
                        $prev_devolucao = $_POST['prev_devolucao'];
                        $data_devolucao = $_POST['data_devolucao'];
                        
                        $sqlSelect = "SELECT * FROM alugueis WHERE livro = '$nomeLivro' AND usuario = '$usuario'";
                        $resultSelect = $conexao -> query($sqlSelect);
                        
                        if(mysqli_num_rows($resultSelect) == 1){
                            echo "<script>window.alert('O usuário já possui aluguel desse livro.')</script>";
                        }
                        else{
                            // Conexão tabela Livros
                            $sqllivro_conect = "SELECT * FROM livros WHERE nome = '$nomeLivro'";
                            $resultlivro_conect = $conexao -> query($sqllivro_conect);
                            
                            $livro_data = mysqli_fetch_assoc($resultlivro_conect);
                            $nomeLivro_BD = $livro_data['nome'];   
                            $quantidade_BD = $livro_data['quantidade'];
                            $quantidade_nova = $quantidade_BD - 1;

                            if($nomeLivro === $nomeLivro_BD && $quantidade_nova >= 0){
                                $sqlAlterar = "UPDATE livros SET quantidade = '$quantidade_nova' WHERE nome = '$nomeLivro'";
                                $sqlResultAlterar = $conexao -> query($sqlAlterar);

                                $result = mysqli_query($conexao, "INSERT INTO alugueis(livro, usuario, data_aluguel, prev_devolucao, data_devolucao) VALUES ('$nomeLivro', '$usuario', '$dat_aluguel', '$prev_devolucao', '$data_devolucao')");
                            }
                            else if($quantidade_nova < 0){
                                echo "<script> alert('Livro com estoque esgotado!!!') </script>";
                            }
                        }
                    }
                }
            }
            else{
                echo "<script> window.alert('A data de aluguel não pode ser posterior ao dia de hoje!') </script>";
            }
        }

        // Teste da seção
        if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: ../home.php');
        }
        $logado = $_SESSION['email'];

        if(!empty($_GET['search'])){
            $data = $_GET['search'];
    
            $sql = "SELECT * FROM alugueis WHERE CodAluguel LIKE '%$data%' OR livro LIKE '%$data%' or usuario LIKE '%$data%' OR data_aluguel LIKE '%$data%' OR prev_devolucao LIKE '%$data%' OR data_devolucao LIKE '%$data%' ORDER BY CodAluguel ASC";
        }
        else{
            $sql = "SELECT * FROM alugueis ORDER BY CodAluguel ASC LIMIT $inicio, $result_per_pg";
        }
        $result = $conexao -> query($sql);

        // Conexão tabela Livros
        $sqllivro_conect = "SELECT * FROM livros ORDER BY CodLivro ASC";
        $resultlivro_conect = $conexao -> query($sqllivro_conect);

        // Conexão tabela Usuários
        $sqluser_conect = "SELECT * FROM usuarios ORDER BY CodUsuario ASC";
        $resultuser_conect = $conexao -> query($sqluser_conect);

        // Montagem da grid (incrível)
        $dados = "<div class='grid-aluguel'>
        <div class='titulos'>ID</div>
        <div class='titulos'>LIVRO ALUGADO</div>
        <div class='titulos'>USUÁRIO QUE ALUGOU</div>
        <div class='titulos'>DATA DO ALUGUEL</div>
        <div class='titulos'>PREVISÃO DE DEVOLUÇÃO</div>
        <div class='titulos'>DATA DE DEVOLUÇÃO</div>
        <div class='titulos'>AÇÕES</div>";

        echo $dados;
        while($aluguel_data = mysqli_fetch_assoc($result)){
            $alug_dat = date("d/m/Y", strtotime($aluguel_data['data_aluguel']));
            $dev_dat = date("d/m/Y", strtotime($aluguel_data['prev_devolucao']));

            echo 
            "<div class='itens'>".$aluguel_data['CodAluguel']."</div>"
            ."<div class='itens'>".$aluguel_data['livro']."</div>"
            ."<div class='itens'>".$aluguel_data['usuario']."</div>"
            ."<div class='itens'>".$alug_dat."</div>"
            ."<div class='itens'>".$dev_dat."</div>";

            if($aluguel_data['data_devolucao'] == 0){   
                echo "<div class='itens'>Não Devolvido</div>";
                echo "<div class='itens'>
                <a href='edit/edit-aluguel.php?id=$aluguel_data[CodAluguel]'><img src='img/check.png' alt='Devolver' title='Devolver'></a>
                </div>";
            }
            else{
                $hoje = date("Y/m/d");
                $previsao = $aluguel_data['prev_devolucao'];

                if(strtotime($previsao) >= strtotime($hoje)){
                    echo "<div class='itens'>".$aluguel_data['data_devolucao']."(Entregue no prazo)</div>";
                    echo "<div class='itens'><a href='delete/delet-aluguel.php?id=$aluguel_data[CodAluguel]'><img src='img/bin.png' alt='Bin' title='Deletar'></a></div>";
                }
                else{
                    echo "<div class='itens'>".$aluguel_data['data_devolucao']."(Com atraso)</div>";
                    echo "<div class='itens'><a href='delete/delet-aluguel.php?id=$aluguel_data[CodAluguel]'><img src='img/bin.png' alt='Bin' title='Deletar'></a></div>";
                }
            }
        }
        echo "</div><br>"; 

        // Somar os alugueis
        $contagem = "SELECT COUNT(CodAluguel) AS num_result FROM alugueis";
        $result_contagem = $conexao -> query($contagem);
        $row_pg = $result_contagem -> fetch_assoc();
        
        //Quantidade de páginas
        $paginas_quant = ceil($row_pg['num_result'] / $result_per_pg);
        
        $maxlinks = 3; // Máximo de links

        // echo do topo da pagination
        echo "<nav aria-label='Page navigation example' class='pagination-gui'>
        <ul class='pagination'>
        <li class='page-item'>
            <a class='page-link' href='#' aria-label='Previous' onclick='listarUsuarios(1)'>
                <span aria-hidden='true'>&laquo;</span>
                <span class='sr-only'>Previous</span>
            </a>
        </li>";
        
        // For para exibir as páginas anteriores na guia
        for($pag_ant = $pagina - $maxlinks; $pag_ant <= $pagina - 1; $pag_ant++){
            if($pag_ant >= 1){
                echo  "<li class='page-item'><a class='page-link' onclick='listarUsuarios($pag_ant)' href='#'>$pag_ant</a></li>";
            }
        }

        // echo da página onde está localizado
        echo "<li class='page-item active'><a class='page-link' href='#'>$pagina</a></li>";

        // For para exibir as páginas posteriores na guia
        for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $maxlinks; $pag_dep++){
            if($pag_dep <= $paginas_quant){
            echo "<li class='page-item'><a class='page-link' onclick='listarUsuarios($pag_dep)' href='#'>$pag_dep</a></li>";
            }
        }

        // echo da base da pagination
        echo "<li class='page-item'>
                <a class='page-link' href='#' onclick='listarUsuarios($paginas_quant)' aria-label='Next'>
                    <span aria-hidden='true'>&raquo;</span>
                    <span class='sr-only'>Next</span>
                </a>
              </li>
            </ul>
        </nav>";
    }
    else{
        echo "<div class='alert alert-danger' role='alert'>
        Erro: Nenhum usuário foi encontrado!!!
      </div>";
    }
?>