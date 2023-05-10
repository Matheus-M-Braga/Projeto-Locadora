<?php
    session_start();

    include_once('config.php');

    // Insert
    if(isset($_POST['submit'])){
        include_once('config.php');

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

    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: home.php');
    }
    $logado = $_SESSION['email'];

    if(!empty($_GET['search'])){
        $data = $_GET['search'];
 
        $sql = "SELECT * FROM livros WHERE CodLivro LIKE '%$data%'or nome LIKE '%$data%' or autor LIKE '%$data%' or editora LIKE '%$data%' or lancamento LIKE '%$data%' or quantidade LIKE '%$data%' ORDER BY CodLivro ASC";
     }
     else{
        $sql = "SELECT * FROM livros ORDER BY CodLivro ASC";
     }
 
    $result = $conexao -> query($sql); 

    // Conexão tabela editoras
    $sqlEditoras_conect = "SELECT * FROM editoras ORDER BY CodEditora ASC";
    $resultEditora_conect = $conexao -> query($sqlEditoras_conect);
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
    <link rel="shortcut icon" href="/img/logo.svg" type="image/x-icon">
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
                    <a class="nav-link" href="livro.php" style="text-decoration: underline;">Livro</a>
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
                  <form action="livro.php" method="POST">
                    <br>
                    <p class="titulo-modal">Cadastro do Livro</p>
                    <div class="input-modal" id="area-nome">
                      <input type="text" placeholder=" " name="nome-livro" required autocomplete="off">
                      <label for="nome">Nome:</label>
                    </div>
                    <div class="input-modal" id="area-autor">
                      <input type="text" placeholder=" " name="autor" required autocomplete="off">
                      <label for="autor">Autor:</label>
                    </div>
                    <div class="input-modal" id="area-editora">
                        <div class="select">
                            <select name="editora" id="editoras_opcoes">
                                <option>Editora:</option>
                                <?php
                                while($editora_data = mysqli_fetch_assoc($resultEditora_conect)){
                                    echo "<option>".$editora_data['nome']."</option>";
                                }
                                ?>
                            </select>
                            <div class="select_arrow"></div>
                        </div>
                    </div>
                    <div class="input-modal" id="area-lancamento">
                        <input type="date" placeholder=" " name="lancamento" required autocomplete="off">
                        <label for="lancamento">Lançamento:</label>
                    </div>
                    <div class="input-modal" id="area-quantidade">
                        <input type="number" placeholder=" " name="quantidade" required autocomplete="off">
                        <label for="quantidade">Quantidade:</label>
                    </div>
                    <input name="submit" type="reset" value="Limpar" class="cancelar-btn">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" value="Registrar" class="entrar-btn">
                  </form>
                </div>
            </div>
        </div>
        <!-- GRID -->
        <div class="grid-header">
            <span class="titulo-pg">Livros</span>
            <div class="novo-btn" onclick="abrirModal('vis-modal')">NOVO +</div>
            <form class="searchbox sbx-custom" style="margin-left: 520px;">
            <div role="search" class="sbx-custom__wrapper">
                <input type="search" name="search" placeholder="Pesquisar..." autocomplete="off" class="sbx-custom__input" id="pesquisadora">
                <button type="submit" class="sbx-custom__submit" onclick="searchData()">
                    <img src="img/search.png" alt="">
                </button>
            </div>
            </form>
        </div>                          
        <div class="grid-users">
            <div class="titulos">ID</div>
            <div class="titulos">NOME</div>
            <div class="titulos">AUTOR</div>
            <div class="titulos">EDITORA</div>
            <div class="titulos">LANÇAMENTO</div>
            <div class="titulos">QUANTIDADE</div>
            <div class="titulos">AÇÕES</div>
            <?php
                while($livro_data = mysqli_fetch_assoc($result)){
                    $lanca = date("d/m/Y", strtotime($livro_data['lancamento']));
                    echo "<div class='itens'>".$livro_data['CodLivro']."</div>"
                    ."<div class='itens'>".$livro_data['nome']."</div>"
                    ."<div class='itens'>".$livro_data['autor']."</div>"
                    ."<div class='itens'>".$livro_data['editora']."</div>"
                    ."<div class='itens'>".$lanca."</div>"
                    ."<div class='itens'>".$livro_data['quantidade']."</div>"
                    ."<div class='itens'>
                    <a href='edit/edit-livro.php?id=$livro_data[CodLivro]'><img src='img/pencil.png' alt='PencilEdit' title='Editar'></a>
                    &nbsp;&nbsp;
                    <a href='delete/delet-livro.php?id=$livro_data[CodLivro]'><img src='img/bin.png' alt='Bin' title='Deletar'></a>
                    </div>";
                }
            ?>
       </div>
    </main>
</body>
</html>