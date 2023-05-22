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
        
            $nomeEditora = $_POST['nome-editora'];
            $email = $_POST['email-editora'];
            $telefone = $_POST['telefone-editora'];
            $website = $_POST['site-editora'];
        
            $sqleditora = "SELECT * FROM editoras WHERE nome = '$nomeEditora'";
            $resultado = $conexao -> query($sqleditora);
            
            if(mysqli_num_rows($resultado) == 1){
                echo "<script>window.alert('Editora já cadastrada.')</script>";
            }
            else{
                $resultI = mysqli_query($conexao, "INSERT INTO editoras(nome, email, telefone, website) VALUES ('$nomeEditora', '$email', '$telefone', '$website')");
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
    
            $sql = "SELECT * FROM editoras WHERE CodEditora LIKE '%$data%' OR nome LIKE '%$data%' OR email LIKE '%$data%' OR telefone LIKE '%$data%' ORDER BY CodEditora ASC";
        }
        else{
            $sql = "SELECT * FROM editoras ORDER BY CodEditora ASC LIMIT $inicio, $result_per_pg";
        }
    
        $result = $conexao -> query($sql);

        // Montagem da grid (complicado)
        $dados = "<div class='grid-editora'>
        <div class='titulos'>ID</div>
        <div class='titulos'>NOME</div>
        <div class='titulos'>EMAIL</div>
        <div class='titulos'>TELEFONE</div>
        <div class='titulos'>AÇÕES</div>";

        echo $dados;
        while($editora_data = mysqli_fetch_assoc($result)){
            echo "<div class='itens'>".$editora_data['CodEditora']."</div>"
            ."<div class='itens'>".$editora_data['nome']."</div>"
            ."<div class='itens'>".$editora_data['email']."</div>"
            ."<div class='itens'>".$editora_data['telefone']."</div>"
            ."<div class='itens'>
                <a href='edit/edit-editora.php?id=$editora_data[CodEditora]'><img src='img/pencil.png' alt='PencilEdit' title='Editar'></a>
                &nbsp;&nbsp;
                <a href='delete/delet-editora.php?id=$editora_data[CodEditora]'><img src='img/bin.png' alt='Bin' title='Deletar'></a>
            </div>";
        }
        echo "</div><br>";

        // Somar as editoras
        $contagem = "SELECT COUNT(CodEditora) AS num_result FROM editoras";
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
        Erro: Nenhuma editora foi encontrada!!!
      </div>";
    }
?>