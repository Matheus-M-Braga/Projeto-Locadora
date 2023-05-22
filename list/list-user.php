<?php
    session_start();
    $pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_NUMBER_INT);
    include_once('../config.php');

    if(!empty($pagina)){
        //Calcular o inicio da visualização
        $result_per_pg = 5; 
        $inicio = ($pagina * $result_per_pg) - $result_per_pg;

        // Insert
        if(isset($_POST['submit'])){
            include_once('../config.php');

            $nomeUsuario = $_POST['nome-user'];
            $cidade = $_POST['cidade'];
            $endereco = $_POST['endereco'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];
            
            $sqluser = "SELECT * FROM usuarios WHERE Nome = '$nomeUsuario'";
            $resultado = $conexao -> query($sqluser);

            if(mysqli_num_rows($resultado) == 1){
                echo "<script>window.alert('Usuário já cadastrado.')</script>";
            }
            else{
                $result = mysqli_query($conexao, "INSERT INTO usuarios(Nome, Cidade, Endereco, Email, CPF) VALUES ('$nomeUsuario', '$cidade', '$endereco', '$email', '$cpf')");
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

            $sql = "SELECT * FROM usuarios WHERE CodUsuario LIKE '%$data%' OR Nome LIKE '%$data%' OR Cidade LIKE '%$data%' OR Email LIKE '%$data%' OR Endereco LIKE '%$data%' ORDER BY CodUsuario ASC";
        }
        else{
            $sql = "SELECT * FROM usuarios ORDER BY CodUsuario ASC LIMIT $inicio, $result_per_pg";
        }
            
        $result = $conexao -> query($sql);
        
        // Montagem da grid (tenso)
        $dados = "<div class='container-grid'>
            <div class='titulos'>ID</div>
            <div class='titulos'>NOME</div>
            <div class='titulos'>CIDADE</div>
            <div class='titulos'>ENDEREÇO</div>
            <div class='titulos'>EMAIL</div>
            <div class='titulos'>AÇÕES</div>";

        echo $dados;
        while($user_data = mysqli_fetch_assoc($result)){
            echo "<div class='itens'>".$user_data['CodUsuario']."</div>"
            ."<div class='itens'>".$user_data['Nome']."</div>"
            ."<div class='itens'>".$user_data['Cidade']."</div>"
            ."<div class='itens'>".$user_data['Endereco']."</div>"
            ."<div class='itens'>".$user_data['Email']."</div>"
            ."<div class='itens'>
                <a href='edit/edit-user.php?id=$user_data[CodUsuario]'><img src='img/pencil.png' alt='PencilEdit' title='Editar'></a>
                &nbsp;&nbsp;
                <a href='delete/delet-user.php?id=$user_data[CodUsuario]'><img src='img/bin.png' alt='Bin' title='Deletar'></a>
            </div>";
        }
        echo "</div><br>";

        // Somar os usuários
        $contagem = "SELECT COUNT(CodUsuario) AS num_result FROM usuarios";
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