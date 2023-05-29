<?php

include_once('../php/config.php');

if(isset($_POST['submit'])){

  /*testar para saber se a informações estão chegando
  print_r($_POST['nome']);
  print_r('<br>');
  print_r($_POST['email']);
  print_r('<br>');
  print_r($_POST['telefone']);
  */

  include_once('../php/config.php');

  $nome = $_POST['nome'];
  $autor = $_POST['autor'];
  $editora = $_POST['select_editora'];
  $data = $_POST['datal'];
  $estoque = $_POST['estoque'];
  
  $sqllivro="SELECT * FROM  livro WHERE nome ='$nome'";

  $resultado = $conexao->query($sqllivro);

  if(mysqli_num_rows($resultado)==1){

    echo "<script>window.alert('Livro já cadastrado')</script>";

}else{
  
  $result = mysqli_query($conexao, "INSERT INTO livro(nome,autor,editora,datal,estoque) VALUES ('$nome','$autor','$editora','$data','$estoque')"); 

}
  

}

$sql = "SELECT * FROM livro ORDER BY id ASC ";

if(!empty($_GET['pesquisar'])){
  $data = $_GET['pesquisar'];

  $sql = "SELECT * FROM livro WHERE nome LIKE '%$data%' OR autor LIKE '%$data%' OR editora LIKE '%$data%' OR datal LIKE '%$data%' OR estoque LIKE '%$data%' OR estoque LIKE '%$data%' OR id LIKE '%$data%'  ORDER BY id ASC";
}
else{
   $sql = "SELECT * FROM livro ORDER BY id ASC";
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
    $sqlseach = "SELECT * FROM livro WHERE nome LIKE '%$data%' OR autor LIKE '%$data%' OR editora LIKE '%$data%' OR datal LIKE '%$data%' OR estoque LIKE '%$data%' OR estoque LIKE '%$data%' OR id LIKE '%$data%'  ORDER BY id DESC";
    $result = $conexao->query($sqlseach);
} else {
    $sql = "SELECT * FROM livro
    ORDER BY id ASC 
    LIMIT $item_por_pag OFFSET $offset";
    $result = $conexao->query($sql);
}


//ordenar pelo nome crescente e decresente 
if (isset($_GET['name_livro'])) {
  $sql="SELECT * FROM livro ORDER BY nome ASC";
  $result = $conexao -> query($sql);

  if($_GET['name_livro']==1){
    $sql="SELECT * FROM livro ORDER BY nome DESC";
    $result = $conexao -> query($sql);
  }
}

//ordenar pelo id crescente e decresente 
if (isset($_GET['cod_livro'])) {
  $sql="SELECT * FROM livro ORDER BY id ASC";
  $result = $conexao -> query($sql);

  if($_GET['cod_livro']==1){
    $sql="SELECT * FROM livro ORDER BY id DESC";
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
    <title>Livro</title>
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
                <a class="nav-link active" href="#"><img src="../img/livro.png" alt="Livro" width="20px" height="20px">    Livro</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="aluguel.php"> <img src="../img/aluguel.png" alt="Aluguel" width="20px" height="20px">    Aluguel</a>
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
    <!---Tela de Cadastro--->
    <div id="vis-modal" class="modal">
      <br><br><br><br><br><br>
      <div class="box" id="cadastro_livro">
          <img src="../img/botao-x.png" alt="Fechar" id="btn-fechar" onclick="fecharModal('vis-modal')">
          <br>
          <form action="livro.php" method="POST" id="form">
              <fieldset>
                  <legend><b>Cadastro  de Livro</b></legend>
                  <br><br>
                  <div class="label-float">
                      <input type="text" name="nome" id="nome" class="inputUser required"  placeholder=" " oninput="nameValidate()" >
                      <label for="nome" class="labelInput">Nome do Livro</label>

                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
                  <br><br>
                  <div class="label-float">
                      <input type="text" name="autor" id="autor" class="inputUser required" placeholder=" " oninput=" autorValidate()">
                      <label for="autor" class="labelInput">Autor</label>

                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
                  <br><br>
                  <label for="select_editora">Editora:</label>
                  <br>
                  <select name="select_editora" id="select_editora" class="required" oninput="editoraValidate()">
                    <option value="0">Selecione</option>
                    <?php 
                    $result_select_editora = "SELECT * FROM editora";
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
                      <input type="date" name="datal" id="datal" class="inputUser required" placeholder=" " oninput="dataValidate()">
                      <label for="datal" class="labelInput">Data de Lançamento</label>
                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
                  <br><br>
                  <div class="label-float">
                      <input type="number" name="estoque" id="estoque" class="inputUser required" placeholder=" " oninput="estoqueValidate()">
                      <label for="estoque" class="labelInput">Estoque</label>

                      <span class="span-required">*Preencha esse campo corretamente !</span>
                  </div>
                  <br>
              </fieldset>
              <br><br>
              <input type="submit" name="submit" id="submit" value="Cadastrar">
      
          </form>
      </div>
  </div>
  <!--Fim da Tela de Cadastro-->
      <!--fim do modal-->

    <!--Corpo--->
    <main>
     <!--Header--->               
      <div class="header-pag">
        <h1 class="titulo-pag">Livro</h1> <a href="#" class="btn-new" id="btn-livro" onclick="abrirModal('vis-modal')">Criar Novo</a>
      </div>
      <hr>
        <div class="container">

          <form action="">
                    <!-----Barra de pesquisar---->
            <div class="box-search">
              <input type="search" id="barra-search" placeholder="Pesquisar livro" name="pesquisar">
              <button id="lupa">
                <img src="../img/search.svg" alt="lupa">
              </button>

            </div>

          </form>
          <!---Fim do Cabeçalho---->

                  <!---Tabela--->
          <table class="table text-white table-bg mt-4">
            <thead class="thead bg-primary">
              <tr>
              <th scope="col"># <a href="livro.php?cod_livro=true" id="nome_table"><img src="../img/seta_pra_cima.svg" alt="Ordem crescente"></a> 
                <a href="livro.php?cod_livro=1"><img src="../img/seta_pra_baixo.svg" alt="Ordem decrescente"></a></th>

                <th scope="col">Nome <a href="livro.php?name_livro=true" id="nome_table"><img src="../img/seta_pra_cima.svg" alt="Ordem crescente"></a> 
                <a href="livro.php?name_livro=1"><img src="../img/seta_pra_baixo.svg" alt="Ordem decrescente"></a>
                </th>
                <th scope="col">Autor</th>
                <th scope="col">Editora</th>
                <th scope="col">Data de Lançamento</th>
                <th scope="col">Estoque</th>
                <th scope="col">...</th>
              </tr>
            </thead>
            <tbody>
            <?php
              while($user_data = mysqli_fetch_assoc($result)){
                $data=date("d/m/Y",strtotime($user_data['datal']));
                echo "<tr>";
                echo "<td>".$user_data['id']."</td>";
                echo "<td>".$user_data['nome']."</td>";
                echo "<td>".$user_data['autor']."</td>";
                echo "<td>".$user_data['editora']."</td>";
                echo "<td>".$data."</td>";
                echo "<td>".$user_data['estoque']."</td>";
                echo "<td>
                <a href='../editar/editar_livro.php?id=$user_data[id]' class='butao' id='bt1'>
                <img src='../img/pencil.svg' alt='editar' >
                </a>
                <a href='../delete/delete_livro.php?id=$user_data[id]' class='butao' id='bt2'>
                <img src='../img/trash3-fill.svg' alt='Apagar' >
                </a>
                </td>";
                echo "</tr>";
              }
            ?>
            </tbody>
          </table>
          <!--- Fim da Tabela--->
            <!-- Div para os links de paginação -->
        <div class="pagination <?php if (!empty($search)) echo 'd-none'; ?>">
            <!-- links de paginação serão adicionados aqui -->
            <!-- Dentro da div de paginação -->
            <ul class="pagination">
                <li class="page-item <?php echo ($pag_atual == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="livro.php?pagina=<?php echo ($pag_atual - 1); ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Exibir link da página anterior, se existir
                if ($pag_atual > 3) {
                    echo "<li class='page-item'><a class='page-link' href='livro.php?pagina=1'>1</a></li>";
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }

                // Exibir páginas anteriores à página atual
                for ($i = max(1, $pag_atual - 1); $i < $pag_atual; $i++) {
                    echo "<li class='page-item'><a class='page-link' href='livro.php?pagina=$i'>$i</a></li>";
                }

                // Exibir página atual
                echo "<li class='page-item active'><span class='page-link'>$pag_atual</span></li>";

                // Exibir páginas posteriores à página atual
                for ($i = $pag_atual + 1; $i <= min($pag_atual + 1, $pag_atual); $i++) {
                    echo "<li class='page-item'><a class='page-link' href='livro.php?pagina=$i'>$i</a></li>";
                }

                // Exibir link da próxima página, se existir
                if ($pag_atual < $totalPaginas-1) {
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                    echo "<li class='page-item'><a class='page-link' href='livro.php?pagina=$totalPaginas'>$totalPaginas</a></li>";
                }
                ?>
                <li class="page-item <?php echo ($pag_atual == $totalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="livro.php?pagina=<?php echo ($pag_atual + 1); ?>" aria-label="Próxima">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
          </div>
        </div>
          
        </div>
    </main>
    <!---fim do corpo-->

    <!--Script-->
    <script src="../js/modal.js"></script>

    <script>
      var search = document.getElementById('barra-search')
        search.addEventListener("keydown", function(event){
            if(event.key === "Enter"){
                searchData();
            }
        })
        function searchData(){
            window.location = "livro.php?search=" = search.value
        }

    </script>

<script>
      //pegando todos os dados do formulário 
      var form = document.getElementById('form');
      var campos = document.querySelectorAll('.required');
      var spans = document.querySelectorAll('.span-required');
      var data = document.getElementById('datal');
      var select = document.getElementById('select_editora');
      
      //criando a validação do formulário
      form.addEventListener('submit',(event)=>{
        if(campos[0].value.length!=0 && campos[1].value.length!=0 && campos[2].value.length!=0 && campos[3].value.length!=0 && campos[4].value.length!=0){
      
        }else{
        nameValidate();
        autorValidate();
        editoraValidate();
        dataValidate();
        estoqueValidate();
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
     
      //autor
      function autorValidate(){
        if(campos[1].value.length < 3){
           setError(1);
        }else{
         removeError(1)
        }
      }
       //editora
      function editoraValidate(){
        if(select.value==0){
           setError(2);
           return;
        }else{
         removeError(2);
        }
      }
       //data
       function dataValidate(){
        if(data.value == ''){
    		setError(3);
    		return;
    	}else{
        removeError(3);
      }
       
      }
       //estoque
      function estoqueValidate(){
        if(campos[4].value.length< 1){
           setError(4);
        }else{
         removeError(4)
        }
      }

    </script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>    
</body>
</html>