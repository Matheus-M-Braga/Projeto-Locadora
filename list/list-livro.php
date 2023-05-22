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

            $nomeLivro = $_POST['nome-livro'];
            $autor = $_POST['autor'];
            $editora = $_POST['editora'];
            $lancamento = $_POST['lancamento'];
            $quantidade = $_POST['quantidade'];

            $sqllivro = "SELECT * FROM livros WHERE nome = '$nomeLivro' AND autor = '$autor'";
            $resultado = $conexao -> query($sqllivro);
            
            if(mysqli_num_rows($resultado) == 1){
                echo "<script> window.alert ('Livro já cadastrado.')</script>";
            }
            else{
                $result = mysqli_query($conexao, "INSERT INTO livros(nome, autor, editora, lancamento, quantidade) VALUES ('$nomeLivro', '$autor', '$editora', '$lancamento', '$quantidade')");
            }
        }

        // Teste de seção
        if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: ../home.php');
        }
        $logado = $_SESSION['email'];

        if(!empty($_GET['search'])){
            $data = $_GET['search'];
    
            $sql = "SELECT * FROM livros WHERE CodLivro LIKE '%$data%'or nome LIKE '%$data%' or autor LIKE '%$data%' or editora LIKE '%$data%' or lancamento LIKE '%$data%' or quantidade LIKE '%$data%' ORDER BY CodLivro ASC";
        }
        else{
            $sql = "SELECT * FROM livros ORDER BY CodLivro ASC LIMIT $inicio, $result_per_pg";
        }
    
        $result = $conexao -> query($sql); 

        // Conexão tabela editoras
        $sqlEditoras_conect = "SELECT * FROM editoras ORDER BY CodEditora ASC";
        $resultEditora_conect = $conexao -> query($sqlEditoras_conect);

        // Montagem da grid (foda)
        $dados = "<div class='grid-users'>
        <div class='titulos'>ID</div>
        <div class='titulos'>NOME</div>
        <div class='titulos'>AUTOR</div>
        <div class='titulos'>EDITORA</div>
        <div class='titulos'>LANÇAMENTO</div>
        <div class='titulos'>QUANTIDADE</div>
        <div class='titulos'>ALUGADOS</div>
        <div class='titulos'>AÇÕES</div>";

        echo $dados;
        while($livro_data = mysqli_fetch_assoc($result)){
            $lanca = date("d/m/Y", strtotime($livro_data['lancamento']));

            $nome_livro = $livro_data['nome'];
            $id = $livro_data['CodLivro'];
            // Conexão tabela alugueis
            $sqlAluguelConect = "SELECT * FROM alugueis WHERE livro = '$nome_livro' AND data_devolucao = 0";
            $sqlAluguelResult = $conexao -> query($sqlAluguelConect);
            $livro_data['alugados'] = $sqlAluguelResult -> num_rows;
            $aluguel_quant = $livro_data['alugados'];

            mysqli_query($conexao, "UPDATE livros SET alugados = '$aluguel_quant' WHERE CodLivro = '$id' ");

            echo "<div class='itens'>".$livro_data['CodLivro']."</div>"
            ."<div class='itens'>".$livro_data['nome']."</div>"
            ."<div class='itens'>".$livro_data['autor']."</div>"
            ."<div class='itens'>".$livro_data['editora']."</div>"
            ."<div class='itens'>".$lanca."</div>"
            ."<div class='itens'>".$livro_data['quantidade']."</div>"
            ."<div class='itens'>".$livro_data['alugados']."</div>"
            ."<div class='itens'>
            <a href='edit/edit-livro.php?id=$livro_data[CodLivro]'><img src='img/pencil.png' alt='PencilEdit' title='Editar'></a>
            &nbsp;&nbsp;
            <a href='delete/delet-livro.php?id=$livro_data[CodLivro]'><img src='img/bin.png' alt='Bin' title='Deletar'></a>
            </div>";
        }
        echo "</div><br>";

        // Somar os livros
        $contagem = "SELECT COUNT(CodLivro) AS num_result FROM livros";
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
        Erro: Nenhum livro foi encontrado!!!
        </div>"; 
    }
?>