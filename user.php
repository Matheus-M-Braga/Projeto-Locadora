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

    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: home.php');
    }
    $logado = $_SESSION['email'];

    if(!empty($_GET['search'])){
       $data = $_GET['search'];

       $sql = "SELECT * FROM usuarios WHERE CodUsuario LIKE '%$data%' OR Nome LIKE '%$data%' OR Cidade LIKE '%$data%' OR Email LIKE '%$data%' OR Endereco LIKE '%$data%' ORDER BY CodUsuario ASC";
    }
    else{
        $sql = "SELECT * FROM usuarios ORDER BY CodUsuario ASC";
    }

    $result = $conexao -> query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f4c3c17e91.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="estilos/style.css?<?php echo rand(1, 1000); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
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
        <div id="vis-modal" class="modal">
            <div class="conteudo-modal">
                    <img src="img/cross.png" alt="butão-fechar" class="fechar-modal" onclick="fecharModal('vis-modal')">
                <div class="corpo-modal">
                  <form action="user.php" method="POST">
                    <br>
                    <p class="titulo-modal">Cadastro do Usuário</p>
                    <div class="input-modal" id="area-nome">
                      <input type="text" placeholder=" " name="nome-user" required autocomplete="off">
                      <label for="email">Nome:</label>
                    </div>
                    <div class="input-modal" id="area-cidade">
                      <input type="text" placeholder=" " name="cidade" required autocomplete="off">
                      <label for="senha">Cidade:</label>
                    </div>
                    <div class="input-modal" id="area-endereco">
                        <input type="text" placeholder=" " name="endereco" required autocomplete="off">
                        <label for="senha">Endereço:</label>
                    </div>
                    <div class="input-modal" id="area-email">
                        <input type="text" placeholder=" " name="email" required autocomplete="off">
                        <label for="senha">E-mail:</label>
                    </div>
                    <div class="input-modal" id="area-email">
                        <input type="text" placeholder="*Facultativo*" name="cpf" autocomplete="off">
                        <label for="senha">CPF: <i style="font-size: 12.5px;">*Opcional*</i></label>
                    </div>
                    <input type="reset" value="Limpar" class="cancelar-btn">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" value="Registrar" class="entrar-btn">
                  </form>
                </div>
            </div>
        </div>
        <!-- GRID -->
        <div class="grid-header">
            <span class="titulo-pg">Usuários</span>
            <div class="novo-btn" onclick="abrirModal('vis-modal')">NOVO +</div>
            <form class="searchbox sbx-custom">
            <div role="search" class="sbx-custom__wrapper">
                <input type="search" name="search" placeholder="Pesquisar..." autocomplete="off" class="sbx-custom__input" id="pesquisadora">
                <button type="submit" class="sbx-custom__submit" onclick="searchData()">
                    <img src="img/search.png" alt="">
                </button>
            </div>
            </form>
        </div>                          
       <div class="container-grid">
                <div class="titulos">ID</div>
                <div class="titulos">NOME</div>
                <div class="titulos">CIDADE</div>
                <div class="titulos">ENDEREÇO</div>
                <div class="titulos">EMAIL</div>
                <div class="titulos">AÇÕES</div>
                <?php
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
                ?>
       </div>
        
    </main>
</body>
</html>