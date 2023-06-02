<?php
    session_start();

    include_once('config.php');

    // inserção dos dados na tebela
    if(isset($_POST['submit'])){
        include_once('config.php');

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
        header('Location: index.php');
    }
    $logado = $_SESSION['email'];

    // Pesquisa
    if(!empty($_GET['search'])){
        $data = $_GET['search'];
 
        $sql = "SELECT * FROM alugueis WHERE CodAluguel LIKE '%$data%' OR livro LIKE '%$data%' or usuario LIKE '%$data%' OR data_aluguel LIKE '%$data%' OR prev_devolucao LIKE '%$data%' OR data_devolucao LIKE '%$data%' ORDER BY CodAluguel ASC";
    }
    else{
        $sql = "SELECT * FROM alugueis ORDER BY CodAluguel ASC";
    }
    $result = $conexao -> query($sql);

    // Número de registros por página
    $registrosPorPagina = 5;
    $paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $offset = ($paginaAtual - 1) * $registrosPorPagina;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Select com os parâmetros
    $result = $conexao -> query($sql);
    $totalRegistros = $result -> num_rows;
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
    if(!empty($search)){
        $sqlsearch = "SELECT * FROM alugueis WHERE CodAluguel LIKE '%$data%' OR livro LIKE '%$data%' or usuario LIKE '%$data%' OR data_aluguel LIKE '%$data%' OR prev_devolucao LIKE '%$data%' OR data_devolucao LIKE '%$data%' ORDER BY CodAluguel ASC";
        $result = $conexao -> query($sqlsearch);
    } 
    else{
        $sql = "SELECT * FROM alugueis ORDER BY CodAluguel ASC LIMIT $registrosPorPagina OFFSET $offset";
        $result = $conexao -> query($sql);
    }

    // Conexão tabela Livros
    $sqllivro_conect = "SELECT * FROM livros ORDER BY CodLivro ASC";
    $resultlivro_conect = $conexao -> query($sqllivro_conect);

    // Conexão tabela Usuários
    $sqluser_conect = "SELECT * FROM usuarios ORDER BY CodUsuario ASC";
    $resultuser_conect = $conexao -> query($sqluser_conect);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f4c3c17e91.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="estilos/style.css?<?php echo rand(1, 1000); ?>">
    <link rel="stylesheet" href="estilos/mediaquery.css?<?php echo rand(1, 1000); ?>">
    <script src="javascript/javascript.js"></script>
    <script>
        var search = document.getElementById('pesquisadora')
        search.addEventListener("keydown", function(event){
            if(event.key === "Enter"){
                searchData();
            }
        })
        function searchData(){
            window.location = "user.php?search=" = search.value
        }
    </script>
    <title>WDA Livraria</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg" id="navbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="inicio.php"><img src="img/books.png" style="height: 30px; width: 30px;" alt=""> WDA Livraria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" aria-current="page" href="inicio.php">Início</a>
                    <a class="nav-link" href="user.php">Usuário</a>
                    <a class="nav-link" href="livro.php">Livro</a>
                    <a class="nav-link" href="editora.php">Editora</a>
                    <a class="nav-link" href="aluguel.php" style="text-decoration: underline;">Aluguel</a>
                    <a href="sair.php"><button class="btn btn-outline-danger" id="botao-sair" type="submit">SAIR</button></a>
                </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
    <div id="vis-modal" class="modal" style="font-family: 'Source Sans Pro',sans-serif;">
            <div class="conteudo-modal">
                <img src="img/cross.png" alt="butão-fechar" class="fechar-modal" onclick="fecharModal('vis-modal')"><br>
                <h1 class="text-balck" style="font-size: 30px; margin-bottom: 5px;">Cadastro da Editora</h1>
                <form action="aluguel.php" method="POST" class="row g-3 needs-validation" novalidate>
                    <div class="col">
                        <div class="row-md-3">
                            <label for="input1" class="form-label text-black">Livro Alugado</label>
                            <select name="nome-livro" class="form-select needs-validation is-invalid" id="input1" required>
                                <option value="" selected disabled>Selecione:</option>
                                <?php
                                while($livro_data = mysqli_fetch_assoc($resultlivro_conect)){
                                    echo "<option>".$livro_data['nome']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input2" class="form-label text-black">Usuário</label>
                            <select name="usuario" class="form-select needs-validation is-invalid" id="input2" required>
                                <option value="" selected disabled>Selecione:</option>
                                <?php
                                while($user_data = mysqli_fetch_assoc($resultuser_conect)){
                                    echo "<option>".$user_data['Nome']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input3" class="form-label text-black">Data do Aluguel</label>
                            <input name="dat_aluguel" type="date" id="input3" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input4" class="form-label text-black">Previsão de Devolução</label>
                            <input name="prev_devolucao" type="date" id="input4" class="form-control date" autocomplete="off" required>
                            <div class="invalid-feedback">
                            • Campo Facultativo •
                            </div>
                        </div>
                        <br>
                        <input type="hidden" name="data_devolucao" value="0">
                        <div class="col-12" style="text-align: center;">
                            <button class="btn btn-success" type="submit" name="submit">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Script da validação -->
        <script>
            (function () {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
                })
            })()
        </script>
        <!-- GRID -->
        <div class="grid-header">
            <span class="titulo-pg">Alugueis</span>
            <div class="novo-btn" onclick="abrirModal('vis-modal')">NOVO +</div>
            <form class="searchbox sbx-custom" id="search-alug">
            <div role="search" class="sbx-custom__wrapper">
                <input type="search" name="search" placeholder="Pesquisar..." autocomplete="off" class="sbx-custom__input" id="pesquisadora">
                <button type="submit" class="sbx-custom__submit" onclick="searchData()">
                    <img src="img/search.png" alt="">
                </button>
            </div>
            </form>
        </div>                          
       <?php
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
                    
                    echo "<div class='itens'>".$aluguel_data['data_devolucao']."</div>";
                    echo "<div class='itens'><a href='delete/delet-aluguel.php?id=$aluguel_data[CodAluguel]'><img src='img/bin.png' alt='Bin' title='Deletar'></a></div>";
                }
            }
            echo "</div><br>";
       ?>
       <!-- Área da paginação -->
       <div class="pagination <?php if (!empty($search)){ echo 'd-none'; }?>">
            <!-- Guia da paginação-->
            <ul class="pagination">
                <li class="page-item <?php echo ($paginaAtual == 1) ? '' : ''; ?>">
                    <a class="page-link" href="aluguel.php?pagina=1" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Exibir link da página anterior, se existir
                if($paginaAtual > 4) {
                    echo "<li class='page-item'><a class='page-link' href='aluguel.php?pagina=1'>1</a></li>";
                }

                // Exibir páginas anteriores à página atual
                if($paginaAtual == $totalPaginas){
                    for ($i = max(1, $paginaAtual - 2); $i < $paginaAtual; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='aluguel.php?pagina=$i'>$i</a></li>";
                    }
                }
                else{
                    for ($i = max(1, $paginaAtual - 1); $i < $paginaAtual; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='aluguel.php?pagina=$i'>$i</a></li>";
                    }
                }
                // Exibir página atual
                echo "<li class='page-item active'><span class='page-link'>$paginaAtual</span></li>";

                // Exibir páginas posteriores à página atual
                if($paginaAtual == 1){
                    for ($i = $paginaAtual + 1; $i <= min($paginaAtual + 2, $totalPaginas); $i++) {
                        echo "<li class='page-item'><a class='page-link' href='aluguel.php?pagina=$i'>$i</a></li>";
                    }
                }
                else{
                    for ($i = $paginaAtual + 1; $i <= min($paginaAtual + 1, $totalPaginas); $i++) {
                        echo "<li class='page-item'><a class='page-link' href='aluguel.php?pagina=$i'>$i</a></li>";
                    }
                }
                ?>
                <li class="page-item <?php echo ($paginaAtual == $totalPaginas) ? '' : ''; ?>">
                    <a class="page-link" href="aluguel.php?pagina=<?php echo $totalPaginas; ?>" aria-label="Próxima">
                        <span aria-hidden="true">&raquo;</span> 
                    </a>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>