<?php
    session_start();

    include_once('config.php');

    $pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_NUMBER_INT);

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
        header('Location: home.php');
    }
    $logado = $_SESSION['email'];

    if(!empty($_GET['search'])){
        $data = $_GET['search'];
 
        $sql = "SELECT * FROM alugueis WHERE CodAluguel LIKE '%$data%' OR livro LIKE '%$data%' or usuario LIKE '%$data%' OR data_aluguel LIKE '%$data%' OR prev_devolucao LIKE '%$data%' OR data_devolucao LIKE '%$data%' ORDER BY CodAluguel ASC";
    }
    else{
        $sql = "SELECT * FROM alugueis ORDER BY CodAluguel ASC";
    }
    $result = $conexao -> query($sql);

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
        <!-- Modal -->
        <div id="vis-modal" class="modal">
            <div class="conteudo-modal">
                    <img src="img/cross.png" alt="butão-fechar" class="fechar-modal" onclick="fecharModal('vis-modal')">
                <div class="corpo-modal">
                  <form action="aluguel.php" method="POST" target="_self">
                    <br>
                    <p class="titulo-modal">Cadastro do Alguel</p>
                    <div class="input-modal" id="area-nome">
                      <div class="select">
                          <select name="nome-livro" id="" value="Livro alugado:" style="width:180px; border: 0.5px solid black;">
                            <option>Livro Alugado:</option>
                            <?php
                                while($livro_data = mysqli_fetch_assoc($resultlivro_conect)){
                                    echo "<option>".$livro_data['nome']."</option>";
                                }
                            ?>
                          </select>
                          <div class="select_arrow"></div>
                      </div>
                    </div>
                    <div class="input-modal" id="area-cidade">
                      <div class="select">
                          <select name="usuario" id="" style="width:180px; border: 0.5px solid black;">
                            <option>Usuário que alugou:</option>
                            <?php
                                while($user_data = mysqli_fetch_assoc($resultuser_conect)){
                                    echo "<option>".$user_data['Nome']."</option>";
                                }
                            ?>
                          </select>
                          <div class="select_arrow"></div>
                      </div>
                    </div>
                    <div class="input-modal" id="area-endereco">
                        <input type="date" placeholder=" " value="" name="dat_aluguel" required autocomplete="off">
                        <label for="senha">Data do Aluguel:</label>
                    </div>
                    <div class="input-modal" id="area-email">
                        <input type="date" placeholder=" " name="prev_devolucao" required autocomplete="off">
                        <label for="prev_devolucao">Previsão de Devolução:</label>
                    </div>
                    <input type="hidden" name="data_devolucao" value="0">
                    <input name="reset" type="reset" value="Limpar" class="cancelar-btn">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" value="Registrar" class="entrar-btn">
                  </form>
                </div>
            </div>
        </div>
        <!-- GRID -->
        <div class="grid-header">
            <span class="titulo-pg">Alugueis</span>
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
        <!-- Tag responsável por exibir a listagem da página list -->
        <span class="listar-alugueis"></span>
        <!-- Script para listagem de alugueis -->
        <script>
            const body = document.querySelector(".listar-alugueis");

            const listarUsuarios = async(pagina) => {
                const dados = await fetch("list/list-aluguel.php?pagina=" + pagina);
                const resposta = await dados.text();
                body.innerHTML = resposta;
            }

            listarUsuarios(1);
        </script>
    </main>
</body>
</html>