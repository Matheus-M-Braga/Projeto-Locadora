<?php
//Envio de dados!!
include_once('../php/config.php');

if(isset($_POST['submit'])){

  $nome = $_POST['select_nome'];
  $livro = $_POST['select_livro'];
  $dataal = $_POST['dataal'];
  $dataprev = $_POST['dataprev'];
  $datadevo = $_POST['datadevo'];

  $sqlaluguel="SELECT * FROM  aluguel WHERE nome ='$nome' and livro='$livro'";

  $resultado = $conexao->query($sqlaluguel);

  $entrada = new DateTime(date("Y/m/d", strtotime($_POST['dataal'])));
  $saida = new DateTime(date("Y/m/d", strtotime($_POST['dataprev'])));


  $intervalo=$entrada->diff($saida);
  $dias=$intervalo->days;

  
  $hoje = date("Y/m/d");
  $aluguel = $_POST['dataal'];

//Condição para limite de aluguel 
 if(strtotime($aluguel)<=strtotime($hoje)){
  
          if($dias>30){

          echo "<script> alert('O limite do aluguel é de 30 dias.') </script>";
          }else{

          $dataprev = $_POST['dataprev'];
          $dataal = $_POST['dataal'];

          if(strtotime($dataprev) < strtotime($dataal)){
          echo "<script> alert('Data de previsão não pode ser anterior a de aluguel') </script>";
          }
          else if(mysqli_num_rows($resultado)==1){

          echo "<script>window.alert('Usúario não pode Alugar o mesmo livro')</script>";

          }else{ 
          
          $sql_livro_connect = "SELECT * FROM livro WHERE nome = '$livro'";
          $resultado_livro_connect = $conexao -> query($sql_livro_connect);

          $livro_data = mysqli_fetch_assoc($resultado_livro_connect);
          $nome_livro_bd = $livro_data['nome'];   
          $estoque_bd = $livro_data['estoque'];
          $estoque_novo = $estoque_bd - 1;

          if($nome_livro_bd === $nome_livro_bd && $estoque_novo >=0 ){
              $sqlalterar = "UPDATE livro SET estoque = '$estoque_novo' WHERE nome = '$livro'";
              $sqlResultAlterar = $conexao -> query($sqlalterar);
              $result = mysqli_query($conexao, "INSERT INTO aluguel(nome,livro,dataal,dataprev,datadevo) VALUES ('$nome','$livro','$dataal','$dataprev','$datadevo')");
           } else if($estoque_novo < 0){
            echo "<script> alert('Livro esgotado') </script>";
          }
        }
       
    }
         
      }else{
    echo "<script> window.alert('A data de aluguel não pode ser posterior ao dia de hoje!') </script>";
  }
}

  $sql_aluguel= "SELECT * FROM aluguel ORDER BY id ASC";

  if (!empty($_GET['pesquisar'])) {
    $data = $_GET['pesquisar'];

    $sql_aluguel = "SELECT * FROM aluguel WHERE nome LIKE '%$data%' OR livro LIKE '%$data%' OR dataal LIKE '%$data%' OR dataprev LIKE '%$data%' OR datadevo LIKE '%$data%' OR id LIKE '%$data%' ORDER BY id ASC";

    $result = $conexao->query($sql_aluguel);
  } else {
    $sql_aluguel = "SELECT * FROM aluguel ORDER BY id ASC";
    $result = $conexao->query($sql_aluguel);
}

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aluguel</title>
    <!--links-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/estilo.css?<?php echo rand(1, 1000); ?>">
    <link rel="stylesheet" href="../css/tabela.css?<?php echo rand(1, 1000); ?>">
    <link rel="stylesheet" href="../css/mediaquerry.css?<?php echo rand(1, 1000); ?>">
</head>
<body>
      <!--Cabeçalho-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="" href="inicio.php"><img src="../img/WDA LIVRARIA4.png" alt=" wda né " height="70px" width="267px" id="inicio"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="lista">
              <li class="nav-item">
                <a class="nav-link"  href="inicio.php"> <img src="../img/home.png" alt="Início" width="20px" height="20px">   Início</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="user.php"> <img src="../img/user.png" alt="user.png" width="20px" height="20px">    Usuários</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="livro.php"><img src="../img/livro.png" alt="Livro" width="20px" height="20px">    Livro</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#"> <img src="../img/aluguel.png" alt="Aluguel" width="20px" height="20px">    Aluguel</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="editora.php"> <img src="../img/editora.png" alt="Editora" width="20px" height="20px">   Editora</a>
              </li>
              
            </ul>
            <a class="btn btn-outline-danger" href="../php/sair.php" role="button">Sair</a>
          </div>
        </div>
         
      </nav>
    <!--Fim do Cabeçalho-->

    <!--Modal-->
    <div id="vis-modal" class="modal">
            <br><br><br><br><br><br>
            <div class="box" id="cadastro_aluguel">
                <img src="../img/botao-x.png" alt="Fechar" id="btn-fechar" onclick="fecharModal('vis-modal')">
                <br>
                <form action="aluguel.php" method='POST' id="form">
                    <fieldset>
                        <legend><b>Aluguel de Livro</b></legend>
                        <br>
                        <label for="select_nome">Nome:</label>
                  <br>
                  <select name="select_nome" id="select_nome" class="required" oninput="nameValidate()">
                    <option value="0">Selecione</option>
                    <?php 
                    $result_select_editora = "SELECT * FROM usuario";
                    $resultado_select_editora = mysqli_query($conexao ,  $result_select_editora ) ;
                    while($row_editora = mysqli_fetch_assoc($resultado_select_editora )){ ?>
                      <option value="<?php echo $row_editora['nome'];?>">
                      <?php echo $row_editora['nome'];?>
                      </option><?php
                    }
                    ?>
                  </select>

                  <span class="span-required">*Preencha esse campo corretamente !</span>
                        <br><br>
                        <label for="select_livro">Livro:</label>
                        <br>
                        <select name="select_livro" id="select_livro" class="required" oninput="livroValidate()">
                    <option value="0">Selecione</option>
                    <?php 
                    $result_select_editora = "SELECT * FROM livro";
                    $resultado_select_editora = mysqli_query($conexao ,  $result_select_editora ) ;
                    while($row_editora = mysqli_fetch_assoc($resultado_select_editora )){ ?>
                      <option value="<?php echo $row_editora['nome'];?>">
                      <?php echo $row_editora['nome'];?>
                      </option><?php
                    }
                    ?>
                  </select>

                  <span class="span-required">*Preencha esse campo corretamente !</span>
                        <br><br>
                        <div class="label-float">
                            <input type="date" name="dataal" id="dataal" class="inputUser required" placeholder=" " oninput="data1Validate()" >
                            <label for="dataal" class="labelInput">Data do aluguel</label>

                            <span class="span-required">*Preencha esse campo corretamente !</span>
                        </div>
                        <br><br>
                        <div class="label-float">
                            <input type="date" name="dataprev" id="dataprev" class="inputUser required" placeholder=" " oninput="data2Validate()">
                            <label for="dataprev" class="labelInput">Previsão de Devolução</label>

                            <span class="span-required">*Preencha esse campo corretamente !</span>
                        </div>
                        <br>
                    </fieldset>
                      <input  type="hidden" name="datadevo"  id="datadevo" value="0">
                    <br>
                    <input type="submit" name="submit" id="submit" value="Cadastrar">
            
                </form>
            </div>
        </div>
    <!-- fim do Modal-->

    <!--Corpo--->
    <main>
      <div class="header-pag">
        <h1 class="titulo-pag">Aluguel</h1> <a href="#" class="btn-new" onclick="abrirModal('vis-modal')">Criar Novo</a>
      </div>
      <hr>
        <div class="container">
                     <!--Barra de Pesquisar--->
          <form action="">
            <div class="box-search">
              <input type="search" id="barra-search" placeholder="Pesquisar aluguel" name="pesquisar" autocomplete="off">
              <button id="lupa" type="submit" onclick="searchData()">
                <img src="../img/search.svg" alt="lupa">
              </button>
            </div>
          </form>
            <!--Barra de Pesquisar--->

                    <!--Tabela-->
          <table class="table text-white table-bg mt-4">
            <thead class="thead bg-primary">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Livro</th>
                <th scope="col">Data de Aluguel</th>
                <th scope="col">Previsão de Devolução</th>
                <th scope="col">Data de Devolução</th>
                <th scope="col">Ações</th>
              </tr>
            </thead>
            <tbody>
               <?php
               
              while($user_data = mysqli_fetch_assoc($result)){
                $dataal=date("d/m/Y",strtotime($user_data['dataal']));
                $dataprev=date("d/m/Y",strtotime($user_data['dataprev']));

                echo "<tr>";
                echo "<td>".$user_data['id']."</td>";
                echo "<td>".$user_data['nome']."</td>";
                echo "<td>".$user_data['livro']."</td>";
                echo "<td>".$dataal."</td>";
                echo "<td>".$dataprev."</td>"; 
                if($user_data['datadevo']==0){
                  echo "<td>Não devolvido</td>";
                  echo "<td>
                  <a href='../editar/editar_aluguel.php?id=$user_data[id]'>
                  <img src='../img/devolver.svg' class='butao' id='bt1' title='Devolver' alt='editar' >
                  </a> </td>";
                  echo "</tr>";
                }else{
                  $hoje = date("Y/m/d");
                  $previsao = $user_data['dataprev'];

                  if(strtotime($previsao) >= strtotime($hoje)){
                      echo "<td>".$user_data['datadevo']." (No prazo)</td>";
                      echo "<td><a href='../delete/delete_aluguel.php?id=$user_data[id]'><img src='../img/trash3-fill.svg' class='butao' id='bt2' alt='Bin' title='Deletar'></a></td>";
                  }
                  else{
                      echo "<td>".$user_data['datadevo']."  (Atrasado)</td>";
                      echo "<td><a href='../delete/delete_aluguel.php?id=$user_data[id]'><img src='../img/trash3-fill.svg'  class='butao' id='bt2' alt='Bin' title='Deletar'></a></td>";
                  }
              }

        }
               
              
            ?>
            </tbody>
          </table>
        </div>
                         <!--Tabela-->
    </main>
    <!---fim do corpo-->

    <!--Script-->
    <script src="../js/modal.js"></script>

    <script>
      //pegando todos os dados do formulário 
      var form = document.getElementById('form');
      var campos = document.querySelectorAll('.required');
      var spans = document.querySelectorAll('.span-required');
      var data1 = document.getElementById('dataal');
      var data2 = document.getElementById('dataprev');
      var select_n = document.getElementById('select_nome');
      var select_l = document.getElementById('select_livro');
      
      //criando a validação do formulário
      form.addEventListener('submit',(event)=>{
        if(campos[0].value.length!=0 && campos[1].value.length!=0 && campos[2].value.length!=0 && campos[3].value.length!=0){
      
        }else{
        nameValidate();
        livroValidate();
        data1Validate();
        data2Validate();
        event.preventDefault();
        }
        
      })
        
      //criando uma função para alertar que ta errado
      function setError(index){
        campos[index].style.color = '#e63636'
        spans[index].style.display ='block'
      }
      //criando uma função para remover o alerta 
      function removeError(index){
        campos[index].style.color = ''
        spans[index].style.display ='none'
      }
        //nome
        function nameValidate(){
        if(select_n.value==0){
           setError(0);
           return;
        }else{
         removeError(0);
        }
      }
     
      //livro
      function livroValidate(){
        if(select_l.value==0){
           setError(1);
           return;
        }else{
         removeError(1);
        }
      }

      //data
      function data1Validate(){
        if(data1.value == ''){
    		setError(2);
    		return;
    	}else{
        removeError(2);
      }
       
      }
       //data
       function data2Validate(){
        if(data2.value == ''){
    		setError(3);
    		return;
    	}else{
        removeError(3);
      }
       
      }
      

    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>  
  
  <!--Script-->
</body>
</html>


