<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/style.css?<?php echo rand(1, 1000); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/style.css?<?php echo rand(1, 1000); ?>">
    <script src="javascript/javascript.js"></script>
    <title>WDA Livraria</title>
</head>
<body>
    <!-- Modal -->
    <div id="vis-modal" class="modal" style="display: block;">
        <?php
            if(!empty($_GET['id'])){
                include_once('../config.php');

                $codLivro = $_GET['id'];

                $sqlSelect = "SELECT * FROM livros WHERE CodLivro = $codLivro";

                $result = $conexao -> query($sqlSelect);

                if($result -> num_rows > 0){
                    while($livro_data = mysqli_fetch_assoc($result)){
                        $nomeLivro = $livro_data['nome'];
                        $autor = $livro_data['autor'];
                        $editora = $livro_data['editora'];
                        $lancamento = $livro_data['lancamento'];
                        $quantidade = $livro_data['quantidade'];
                    }
                }
                else{
                    header('Location: ../livro.php');
                }  
            }
            else{
                header('Location: ../livro.php');
            }
            
            // Conexão tabela editoras
            $sqlEditoras_conect = "SELECT * FROM editoras ORDER BY CodEditora ASC";
            $resultEditora_conect = $conexao -> query($sqlEditoras_conect);
        ?>
        <div class="conteudo-modal">
                <a href="../livro.php"><img src="../img/cross.png" alt="butão-fechar" class="fechar-modal" onclick="fecharModal('vis-modal')"></a>
                <br>
                <h1 class="text-balck" style="font-size: 25px; margin-bottom: 5px;">Editar cadastro - Livro</h1>
                <br>
            <div class="corpo-modal">
                <form action="../saves/save-livro.php" method="POST" class="row g-3 needs-validation" novalidate>
                <div class="col">
                        <input type="hidden" name="id" value="<?php echo $codLivro; ?>">
                        <div class="row-md-3">
                            <label for="input1" class="form-label text-black bold">Nome</label>
                            <input name="nome-livro" value="<?php echo $nomeLivro; ?>" type="text" class="form-control" id="input1" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input2" class="form-label text-black">Autor</label>
                            <input name="autor" value="<?php echo $autor; ?>" type="text" class="form-control" id="input2" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input3" class="form-label text-black">Editora</label>
                            <select name="editora" class="form-select needs-validation is-invalid" id="input3" required>
                                <option selected><?php echo $editora; ?></option>
                                <?php
                                while($editora_data = mysqli_fetch_assoc($resultEditora_conect)){
                                    if($editora_data['nome'] === $editora){

                                    }
                                    else{
                                        echo "<option>".$editora_data['nome']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input4" class="form-label text-black">Lançamento</label>
                            <input name="lancamento" value="<?php echo $lancamento; ?>" type="date" class="form-control date" id="input4" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input5" class="form-label text-black">Quantidade</label>
                            <input name="quantidade" value="<?php echo $quantidade; ?>" type="number" class="form-control" id="input5" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="col-12" style="text-align: center;">
                            <button class="btn btn-success" type="submit" name="update">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>