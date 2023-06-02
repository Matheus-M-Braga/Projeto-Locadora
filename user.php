<?php
    session_start();

    include_once('config.php');

    // Insert
    if(isset($_POST['submit'])){
        include_once('config.php');

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
        header('Location: index.php');
    }
    $logado = $_SESSION['email'];

    // Pesquisa
    if(!empty($_GET['search'])){
       $data = $_GET['search'];

       $sql = "SELECT * FROM usuarios WHERE CodUsuario LIKE '%$data%' OR Nome LIKE '%$data%' OR Cidade LIKE '%$data%' OR Email LIKE '%$data%' OR Endereco LIKE '%$data%' ORDER BY CodUsuario ASC";
    }
    else{
        $sql = "SELECT * FROM usuarios ORDER BY CodUsuario ASC";
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
        $sqlsearch = "SELECT * FROM usuarios WHERE CodUsuario LIKE '%$data%' OR Nome LIKE '%$data%' OR Cidade LIKE '%$data%' OR Email LIKE '%$data%' OR Endereco LIKE '%$data%' ORDER BY CodUsuario ASC";
        $result = $conexao -> query($sqlsearch);
    } 
    else{
        $sql = "SELECT * FROM usuarios ORDER BY CodUsuario ASC LIMIT $registrosPorPagina OFFSET $offset";
        $result = $conexao -> query($sql);
    }
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
    <link rel="stylesheet" href="estilos/style.css?<?php echo rand(1, 1000); ?>" media="all">
    <link rel="stylesheet" href="estilos/mediaquery.css?<?php echo rand(1, 1000); ?>">
    <link rel="shortcut icon" href="/img/logo.svg?<?php echo rand(1, 1000); ?>" type="image/x-icon">
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
                    <a class="nav-link" href="user.php" style="text-decoration: underline;">Usuário</a>
                    <a class="nav-link" href="livro.php">Livro</a>
                    <a class="nav-link" href="editora.php">Editora</a>
                    <a class="nav-link" href="aluguel.php">Aluguel</a>
                    <a href="sair.php"><button class="btn btn-outline-danger" id="botao-sair" type="submit">SAIR</button></a>
                </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <!-- Modal -->
        <div id="vis-modal" class="modal" style="font-family: 'Source Sans Pro',sans-serif;">
            <div class="conteudo-modal">
                <img src="img/cross.png" alt="butão-fechar" class="fechar-modal" onclick="fecharModal('vis-modal')"><br>
                <h1 class="text-balck" style="font-size: 30px; margin-bottom: 5px;">Cadastro do Usuário</h1>
                <form action="user.php" method="POST" class="row g-3 needs-validation" novalidate>
                    <div class="col">
                        <div class="row-md-3">
                            <label for="input1" class="form-label text-black bold">Nome</label>
                            <input name="nome-user" type="text" id="input1" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input2" class="form-label text-black">Cidade</label>
                            <input name="cidade" type="text" id="input2" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input3" class="form-label text-black">Endereço</label>
                            <input name="endereco" type="text" id="input3" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="validationCustom02" class="form-label text-black">E-mail</label>
                            <input name="email" type="email" class="form-control date" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="validationCustom02" class="form-label text-black">CPF</label>
                            <input name="cpf" placeholder="*Opcional*" type="text" class="form-control" autocomplete="off">
                            <div class="valid-feedback">
                            • Campo facultativo •
                            </div>
                        </div>
                        <br>
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
            <span class="titulo-pg">Usuários</span>
            <div class="novo-btn" onclick="abrirModal('vis-modal')">NOVO +</div>
            <form class="searchbox sbx-custom" id="search-user">
            <div role="search" class="sbx-custom__wrapper">
                <input type="search" name="search" placeholder="Pesquisar..." autocomplete="off" class="sbx-custom__input" id="pesquisadora">
                <button type="submit" class="sbx-custom__submit" onclick="searchData()">
                    <img src="img/search.png" alt="">
                </button>
            </div>
            </form>
        </div>                      
        <!-- Tag responsável por exibir a listagem da página list -->
        <?php
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
        ?>
        <!-- Área da paginação -->
        <div class="pagination <?php if (!empty($search)){ echo 'd-none'; }?>">
            <!-- Guia da paginação-->
            <ul class="pagination">
                <li class="page-item <?php echo ($paginaAtual == 1) ? '' : ''; ?>">
                    <a class="page-link" href="user.php?pagina=1" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Exibir link da página anterior, se existir
                if($paginaAtual > 4) {
                    echo "<li class='page-item'><a class='page-link' href='user.php?pagina=1'>1</a></li>";
                }

                // Exibir páginas anteriores à página atual
                if($paginaAtual == $totalPaginas){
                    for ($i = max(1, $paginaAtual - 2); $i < $paginaAtual; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='user.php?pagina=$i'>$i</a></li>";
                    }
                }
                else{
                    for ($i = max(1, $paginaAtual - 1); $i < $paginaAtual; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='user.php?pagina=$i'>$i</a></li>";
                    }
                }
                // Exibir página atual
                echo "<li class='page-item active'><span class='page-link'>$paginaAtual</span></li>";

                // Exibir páginas posteriores à página atual
                if($paginaAtual == 1){
                    for ($i = $paginaAtual + 1; $i <= min($paginaAtual + 2, $totalPaginas); $i++) {
                        echo "<li class='page-item'><a class='page-link' href='user.php?pagina=$i'>$i</a></li>";
                    }
                }
                else{
                    for ($i = $paginaAtual + 1; $i <= min($paginaAtual + 1, $totalPaginas); $i++) {
                        echo "<li class='page-item'><a class='page-link' href='user.php?pagina=$i'>$i</a></li>";
                    }
                }
                ?>
                <li class="page-item <?php echo ($paginaAtual == $totalPaginas) ? '' : ''; ?>">
                    <a class="page-link" href="user.php?pagina=<?php echo $totalPaginas; ?>" aria-label="Próxima">
                        <span aria-hidden="true">&raquo;</span> 
                    </a>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>