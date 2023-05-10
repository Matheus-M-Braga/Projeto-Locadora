<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/style.css?<?php echo rand(1, 1000); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
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
            <div class="corpo-modal">
                <form action="../saves/save-livro.php" method="POST">
                <br>
                <p class="titulo-modal">Cadastro do Livro</p>
                <div class="input-modal" id="area-nome">
                    <input type="text" placeholder=" " name="nome-livro" required autocomplete="off" value="<?php echo $nomeLivro; ?>">
                    <label for="nome">Nome:</label>
                </div>
                <div class="input-modal" id="area-autor">
                    <input type="text" placeholder=" " name="autor" required autocomplete="off" value="<?php echo $autor; ?>">
                    <label for="autor">Autor:</label>
                </div>
                <div class="input-modal" id="area-editora">
                    <div class="select">
                        <select name="editora" id="">
                            <option value="Cuzinho">Selecione</option>
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
                    <input type="date" placeholder=" " name="lancamento" required autocomplete="off" value="<?php echo $lancamento; ?>">
                    <label for="lancamento">Lançamento:</label>
                </div>
                <div class="input-modal" id="area-quantidade">
                    <input type="number" placeholder=" " name="quantidade" required autocomplete="off" value="<?php echo $quantidade; ?>">
                    <label for="quantidade">Quantidade:</label>
                </div>
                <input type="hidden" name="id" value="<?php echo $codLivro ?>">
                <input name="submit" type="reset" value="Limpar" class="cancelar-btn">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="update" id="update" type="submit" value="Registrar" class="entrar-btn">
                </form>
            </div>
        </div>
    </div>
</body>
</html>