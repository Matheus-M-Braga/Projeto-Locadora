<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/style.css?<?php echo rand(1, 1000); ?>">
    <script src="javascript/javascript.js"></script>
    <title>WDA Livraria</title>
</head>
<body>
    <!-- Modal -->
    <div id="vis-modal" class="modal" style="display:block;">
        <?php
            if(!empty($_GET['id'])){
                include_once('../config.php');

                $codUsuario = $_GET['id'];

                $sqlSelect = "SELECT * FROM usuarios WHERE CodUsuario = $codUsuario";

                $result = $conexao -> query($sqlSelect);

                if($result -> num_rows > 0){
                    while($user_data = mysqli_fetch_assoc($result)){
                        $nomeUsuario = $user_data['Nome'];
                        $cidade = $user_data['Cidade'];
                        $endereco = $user_data['Endereco'];
                        $email = $user_data['Email'];
                        $cpf = $user_data['CPF'];
                    }
                }
                else{
                    header('Location: ../user.php');
                }  
            }
            else{
                header('Location: ../user.php');
            }
        ?>
        <div class="conteudo-modal">
            <a href="../user.php"><img src="../img/cross.png" alt="butão-fechar" class="fechar-modal"></a>
            <br>
            <h1 class="text-balck" style="font-size: 25px; margin-bottom: 5px;">Editar cadastro - Usuário</h1>
            <br>
            <div class="corpo-modal">
                <form action="../saves/save-user.php" method="POST" class="row g-3 needs-validation">
                <div class="col">
                        <input type="hidden" name="id" value="<?php echo $codUsuario; ?>">
                        <div class="row-md-3">
                            <label for="input1" class="form-label text-black bold">Nome</label>
                            <input name="nome-user" value="<?php echo $nomeUsuario; ?>" type="text" id="input1" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input2" class="form-label text-black">Cidade</label>
                            <input name="cidade" value="<?php echo $cidade; ?>" type="text" id="input2" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="input3" class="form-label text-black">Endereço</label>
                            <input name="endereco" value="<?php echo $endereco; ?>" type="text" id="input3" class="form-control" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="validationCustom02" class="form-label text-black">E-mail</label>
                            <input name="email" value="<?php echo $email; ?>" type="email" class="form-control date" required autocomplete="off">
                            <div class="invalid-feedback">
                            • Campo obrigatório •
                            </div>
                        </div>
                        <br>
                        <div class="row-md-3">
                            <label for="validationCustom02" class="form-label text-black">CPF</label>
                            <input name="cpf" placeholder="*Opcional*" value="<?php echo $cpf; ?>" type="text" class="form-control" autocomplete="off">
                            <div class="valid-feedback">
                            • Campo facultativo •
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