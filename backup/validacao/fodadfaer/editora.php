<?php
include_once('../php/config.php');
if(isset($_POST['submit'])){

  /*testar para saber se a informações estão chegando
  print_r($_POST['nomeeditora']);
  print_r('<br>');
  print_r($_POST['email']);
  print_r('<br>');
  print_r($_POST['telefone']);
  */

  include_once('../php/config.php');

  $nome = $_POST['nomeeditora'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  
  
  $sqleditora="SELECT * FROM  editora WHERE nome ='$nome'";

  $resultado = $conexao->query($sqleditora);

  if(mysqli_num_rows($resultado)==1){

    echo "<script>window.alert('Editora já cadastrada')</script>";

}else{
  
  $result = mysqli_query($conexao, "INSERT INTO editora(nome,email,telefone) VALUES ('$nome','$email','$telefone')");

}

}
$sql = "SELECT * FROM editora ORDER BY id ASC";

//Barra de pesquisar 
if(!empty($_GET['pesquisar'])){
  $data = $_GET['pesquisar'];

  $sql = "SELECT * FROM  editora WHERE nome LIKE '%$data%'  OR email LIKE '%$data%' OR telefone LIKE '%$data%'  ORDER BY id ASC";
}
else{
   $sql = "SELECT * FROM editora ORDER BY id ASC";
}

// Define o número de registros por página
$item_por_pag = 5;
$pag_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($pag_atual - 1) * $item_por_pag;
$search = isset($_GET['pesquisar']) ? $_GET['pesquisar'] : '';

// Construa a consulta SQL com base nos parâmetros
$result = $conexao->query($sql);
$total_reg = $result->num_rows;
$totalPaginas = ceil($total_reg / $item_por_pag);
if (!empty($search)) {
    $sqlseach = "SELECT * FROM  editora WHERE nome LIKE '%$data%'  OR email LIKE '%$data%' OR telefone LIKE '%$data%'  ORDER BY id DESC";
    $result = $conexao->query($sqlseach);
} else {
    $sql = "SELECT * FROM editora
    ORDER BY id ASC 
    LIMIT $item_por_pag OFFSET $offset";
    $result = $conexao->query($sql);
}



//ordenar pelo nome crescente e decresente 
if (isset($_GET['name'])) {
  $sql="SELECT * FROM editora ORDER BY nome ASC";
  $result = $conexao -> query($sql);
  

  if($_GET['name']==1){
    $sql="SELECT * FROM editora ORDER BY nome DESC";
    $result = $conexao -> query($sql);
  }
}

//ordenar pelo id crescente e decresente 
if (isset($_GET['cod'])) {
  $sql="SELECT * FROM editora ORDER BY id ASC";
  $result = $conexao -> query($sql);

  if($_GET['cod']==1){
    $sql="SELECT * FROM editora ORDER BY id DESC";
    $result = $conexao -> query($sql);
  }

}

$result = $conexao -> query($sql);


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editora</title>
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
                <a class="nav-link" href="aluguel.php"> <img src="../img/aluguel.png" alt="Aluguel" width="20px" height="20px">    Aluguel</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#"> <img src="../img/editora.png" alt="Editora" width="20px" height="20px">   Editora</a>
              </li>
              
            </ul>
            <a class="btn btn-outline-danger" href="../php/sair.php" role="button">Sair</a>
          </div>
        </div>
         
      </nav>
    <!--Fim do Cabeçalho-->
    <!--Modal-->
    <!---Tela de Cadastro--->
    <div id="vis-modal" class="modal">
      <br><br><br><br><br><br>
      <div class="box" id="cadastro_editora">
          <img src="../img/botao-x.png" alt="Fechar" id="btn-fechar" onclick="fecharModal('vis-modal')">
          <br>
          <form action="editora.php" method='POST' id="form">
              <fieldset>
                  <legend><b>Cadastro  de Editora</b></legend>
                  <br><br>
                  <div class="label-float">
                      <input type="text" name="nomeeditora" id="nomeeditora" class="inputUser required"  placeholder=" "  oninput="nameValidate()">
                      <label for="nomeeditora" class="labelInput">Nome da Editora</label>

                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
                  <br><br>
                  <div class="label-float">
                      <input type="text" name="email" id="email" class="inputUser required" placeholder=" " oninput="emailValidate()">
                      <label for="email" class="labelInput">Email</label>

                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
                  <br><br>
                  <div class="label-float">
                      <input type="tel" name="telefone" id="telefone" class="inputUser required" placeholder=" " oninput="telValidate()">
                      <label for="telefone" class="labelInput">Telefone</label>

                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
              </fieldset>
              <br><br>
              <input type="submit" name="submit" id="submit" value="Cadastrar">
      
          </form>
      </div>
  </div>
  <!---Tela de Cadastro--->
    <!-- fim do Modal-->

    <!--Corpo--->
    <main>
      <div class="header-pag">
        <h1 class="titulo-pag">Editora</h1> <a href="#" class="btn-new" onclick="abrirModal('vis-modal')">Criar Novo</a>
      </div>
        <hr>
  
          <div class="container">
  
            <form action="">
              <!--Barra Pesquisar-->
              <div class="box-search">
                <input type="search" id="barra-search" placeholder="Pesquisar  editora" name="pesquisar">
                <button id="lupa">
                  <img src="../img/search.svg" alt="lupa">
                </button>
              </div>
              <!--Barra Pesquisar-->

            </form>
            <!--Tabela-->
              <table class="table text-white table-bg mt-4">
                <thead class="thead bg-primary">

                  <tr>
                  <th scope="col"># <a href="editora.php?cod=true" id="nome_table"><img src="../img/seta_pra_cima.svg" alt="Ordem crescente"></a> 
                <a href="editora.php?cod=1"><img src="../img/seta_pra_baixo.svg" alt="Ordem   Crescente"></a></th>

                <th scope="col">Nome <a href="editora.php?name=true"><img src="../img/seta_pra_cima.svg" alt="Ordem crescente"></a> 
                <a href="editora.php?name=1"><img src="../img/seta_pra_baixo.svg" alt="Ordem decrescente"></a>
                </th>
                    <th scope="col">Email</th>

                    <th scope="col">Telefone</th>

                    <th scope="col">...</th>
                    
                  </tr>
                </thead>
                <tbody>
                <?php
              while($user_data = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$user_data['id']."</td>";
                echo "<td>".$user_data['nome']."</td>";
                echo "<td>".$user_data['email']."</td>";
                echo "<td>".$user_data['telefone']."</td>";

                echo "<td>
                <a href='../editar/editar_editora.php?id=$user_data[id]' class='butao' id='bt1'>
                <img src='../img/pencil.svg' alt='editar' >
                </a>
                <a href='../delete/delete_editora.php?id=$user_data[id]' class='butao' id='bt2'>
                <img src='../img/trash3-fill.svg' alt='Apagar' >
                </a>
                </td>";
                echo "</tr>";
              }
            ?>
                </tbody>
              </table>

                               <!-- Div para os links de paginação -->
        <div class="pagination <?php if (!empty($search)) echo 'd-none'; ?>">
            <!-- links de paginação serão adicionados aqui -->
            <!-- Dentro da div de paginação -->
            <ul class="pagination">
                <li class="page-item <?php echo ($pag_atual == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="editora.php?pagina=<?php echo ($pag_atual - 1); ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Exibir link da página anterior, se existir
                if ($pag_atual > 3) {
                    echo "<li class='page-item'><a class='page-link' href='editora.php?pagina=1'>1</a></li>";
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }

                // Exibir páginas anteriores à página atual
                for ($i = max(1, $pag_atual - 1); $i < $pag_atual; $i++) {
                    echo "<li class='page-item'><a class='page-link' href='editora.php?pagina=$i'>$i</a></li>";
                }

                // Exibir página atual
                echo "<li class='page-item active'><span class='page-link'>$pag_atual</span></li>";

                // Exibir páginas posteriores à página atual
                for ($i = $pag_atual + 1; $i <= min($pag_atual + 1, $pag_atual); $i++) {
                    echo "<li class='page-item'><a class='page-link' href='editora.php?pagina=$i'>$i</a></li>";
                }

                // Exibir link da próxima página, se existir
                if ($pag_atual < $totalPaginas-1) {
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                    echo "<li class='page-item'><a class='page-link' href='editora.php?pagina=$totalPaginas'>$totalPaginas</a></li>";
                }
                ?>
                <li class="page-item <?php echo ($pag_atual == $totalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="editora.php?pagina=<?php echo ($pag_atual + 1); ?>" aria-label="Próxima">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
          </div>
               
          </div>
          <!-- Fim da Tabela-->
              
    </main>
    <!---fim do corpo-->

    <!---Script-->
  <script src="../js/modal.js"></script>

  <script>
      var search = document.getElementById('barra-search')
        search.addEventListener("keydown", function(event){
            if(event.key === "Enter"){
                searchData();
            }
        })
        function searchData(){
            window.location = "editora.php?search=" = search.value
        }

    </script>

<script>
      //pegando todos os dados do formulário 
      var form = document.getElementById('form');
      var campos = document.querySelectorAll('.required');
      var spans = document.querySelectorAll('.span-required');
      var emailRegex = /^[a-z0-9.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i;
      
      //criando a validação do formulário
      form.addEventListener('submit',(event)=>{
        if(campos[0].value.length!=0 && campos[1].value.length!=0 && campos[2].value.length!=0){
          
        }else{
        nameValidate();
        emailValidate();
        telValidate();
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
      //criando a função para validar o campo do nome
      function nameValidate(){
        if(campos[0].value.length < 3){
           setError(0);
        }else{
         removeError(0)
        }
      }
      //email
      function emailValidate(){
        if(emailRegex.test(campos[1].value)){
          removeError(1);
        }else{
          setError(1)
        }
      }
      //telefone
      function telValidate(){
        if(campos[2].value.length < 3){
           setError(2);
        }else{
         removeError(2)
        }
      }
   </script>
 <!---Fim do Script-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>    
</body>
</html>