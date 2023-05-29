<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f4c3c17e91.js" crossorigin="anonymous"></script>
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

                $codEditora = $_GET['id'];

                $sqlSelect = "SELECT * FROM editoras WHERE CodEditora = $codEditora";

                $result = $conexao -> query($sqlSelect);

                if($result -> num_rows > 0){
                    while($editora_data = mysqli_fetch_assoc($result)){
                        $nomeEditora = $editora_data['nome'];
                        $email = $editora_data['email'];
                        $telefone = $editora_data['telefone'];
                        $website = $editora_data['website'];
                    }
                }
                else{
                    header('Location: ../editora.php');
                }  
            }
            else{
                header('Location: ../editora.php');
            }
        ?>
        <div class="conteudo-modal" style="display: block;">
            <a href="../editora.php"><img src="../img/cross.png" alt="butão-fechar" class="fechar-modal" onclick="fecharModal('vis-modal')"></a>
            <br>
            <h1 class="text-balck" style="font-size: 25px; margin-bottom: 5px;">Editar cadastro - Editora</h1>
            <br>
            <div class="corpo-modal">
                <form action="../saves/save-editora.php" method="POST" class="row g-3 needs-validation" novalidate>
                <div class="col">
                        <input type="hidden" name="id" value="<?php echo $codEditora; ?>">
                        <div class="row-md-3">
                            <label for="input1" class="form-label text-black bold">Nome</label>
                            <input name="nome-editora" value="<?php echo $nomeEditora; ?>" type="text" id="input1" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input2" class="form-label text-black">E-mail</label>
                            <input name="email-editora" value="<?php echo $email; ?>" type="email" id="input2" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input3" class="form-label text-black">Telefone</label>
                            <input name="telefone-editora" value="<?php echo $telefone; ?>" type="tel" id="input3" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input4" class="form-label text-black">Site</label>
                            <input name="site-editora" value="<?php echo $website; ?>" placeholder="*Facultativo*" type="text" id="input4" class="form-control date" autocomplete="off">
                            <div class="valid-feedback">
                            • Campo Facultativo •
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
</body>
</html>