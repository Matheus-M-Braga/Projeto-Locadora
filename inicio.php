<?php
    session_start();

    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: home.php');
    }
    $logado = $_SESSION['email'];

    include_once('config.php');

    $sqlAluguel = "SELECT livro FROM alugueis";

    $resultadolivro= $conexao -> query($sqlAluguel);

  
    $sql_total_alugueis = "SELECT COUNT(*) AS total_alugueis FROM alugueis";
    $resultado_total_alugueis = $conexao -> query($sql_total_alugueis);

    $linha_total_alugueis = $resultado_total_alugueis -> fetch_assoc();
    
    if(isset($linha_total_alugueis['total_alugueis'])) {
        $quantidade_alugueis = $linha_total_alugueis['total_alugueis'];
    }

    $sql_ultimo_aluguel = "SELECT * FROM alugueis ORDER BY CodAluguel DESC LIMIT 1";
    $resultado_ultimo_aluguel = $conexao->query($sql_ultimo_aluguel);

    $ultimo_alugado = $resultado_ultimo_aluguel -> fetch_assoc();

    if(isset($ultimo_alugado['livro'])){
        $ultimo_livro = $ultimo_alugado['livro'];
    }

    $sql_mais_alugado = "SELECT livro FROM alugueis WHERE livro = livro GROUP BY livro ORDER BY COUNT(livro) DESC LIMIT 1";
    $resultado_mais_alugado = $conexao -> query($sql_mais_alugado);
    $mais_alugado = $resultado_mais_alugado -> fetch_assoc();
    if(isset( $mais_alugado['livro'])){
        $mais_alug =  $mais_alugado['livro'];
    }

    $sql_total_livros = "SELECT sum(quantidade) AS total_livros FROM livros";
    $resultado_total_livros = $conexao->query($sql_total_livros);
    $total_livros=$resultado_total_livros->fetch_assoc();

    if(isset($total_livros['total_livros'])){
        $totais_livros = $total_livros['total_livros'];
    }

    $sql_nao_devolvidos = "SELECT count(data_devolucao) as nao_devolvidos FROM alugueis where data_devolucao = 0";
    $resultado_nao_devolvidos = $conexao -> query($sql_nao_devolvidos);
    $total_nao_devo = $resultado_nao_devolvidos -> fetch_assoc();
    if(isset($total_nao_devo['nao_devolvidos'])){
        $total_nao_devo = $total_nao_devo['nao_devolvidos'];
    }

    $sql_devo = "SELECT count(data_devolucao) as devolvidos FROM alugueis where data_devolucao!=0";
    $resultado_devo = $conexao -> query($sql_devo);
    $total_devo = $resultado_devo -> fetch_assoc();
    if(isset($total_devo['devolvidos'])){
        $total_devol= $total_devo['devolvidos'];
    }

    $sql_grafico = "SELECT livro, count(livro) as quantidade_aluguel FROM alugueis WHERE livro = livro GROUP BY livro ORDER BY COUNT(livro) DESC limit 3";
    $resultado_grafico= $conexao -> query($sql_grafico);

    while($barra=$resultado_grafico -> fetch_assoc()){
        $nomes[]=$barra['livro'];
        $info[]=$barra['quantidade_aluguel'];
    }

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
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
                    <a class="nav-link" aria-current="page" href="inicio.php" style="text-decoration: underline;">Início</a>
                    <a class="nav-link" href="user.php">Usuário</a>
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
        <div class="" id="container-ultimoalugado">
            <h3>Livro mais alugado</h3>
            <div id="top">  
                <?php if(isset($mais_alug)){
                echo "<h4>".$mais_alug."</h4>";
                }
                else{
                echo " <h4>Aguardando dados...</h4> ";
                }
                ?> 
            </div>
        </div>
        <div class="" id="container-maisalugados">
            <h3>Status dos livros</h3>
            <h4>Total:</h4> 
            <?php echo "<span style='font-size: 28px;'>".$totais_livros."</span style='font-size: 28px;'>" ?>

            <h4>Empréstmos:</h4> 
            <?php echo "<span style='font-size: 28px; display: inline;'>".$quantidade_alugueis."</span>" ?>
        </div>

        <div class="" id="container-emprestados">
            <h3>Status dos Alugueis:</h3>

            <h4>Entregues:</h4>
            <?php echo  "<span style='font-size: 28px;'>".$total_devol."</span>"; ?>
            
            <h4>Não entregues:</h4>
            <?php echo"<span style='font-size: 28px;'>".$total_nao_devo."</span>"; ?>
            
            <h4>Últmo livro alugado:</h4> 
            <?php 
                if(isset($ultimo_alugado)){
                    echo "<span style='font-size: 28px;'>".$ultimo_livro."</span>"; 
                }
                else{
                    echo "<span style='font-size: 28px;'>Aguardando dados...</span>";
                }
            ?>
            <br>
        </div>
        <br>
        <div id="grafico" class="container bg-light">
            <div style="text-align:center;">
              <h2>Livros mais alugados: </h2>
            </div>
            <canvas id="grafico01" width="300px" style="margin-top:-6px;"></canvas>
            <div>
            </div>
        </div>    
    </main>
    <script>
        const ctx = document.getElementById('grafico01');

        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: [ "<?php  echo $nomes[0]; ?>","<?php  echo $nomes[1]; ?>","<?php  echo $nomes[2]; ?>"],
            datasets: [{
                label: 'Mais Alugados',
                data: ["<?php echo $info[0]; ?>","<?php echo $info[1]; ?>","<?php echo $info[2]; ?>"],
                backgroundColor: ['rgba(128, 0, 0)','rgb(65, 69, 94)','rgb(182, 143, 43)'],
                borderWidth: 0
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
</script>
</body>
</html>