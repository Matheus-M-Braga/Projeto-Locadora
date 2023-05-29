<?php
// Inicia a sessão
session_start();

// Inclui o arquivo de configuração da conexão com o banco de dados
include_once("../Config.php");

/* 
// Debug: imprime o array $_SESSION
print_r($_SESSION); 
*/

// Verifica se não existem informações de username e senha na sessão
if ((!isset($_SESSION['username']) == true) and (!isset($_SESSION['senha']) == true)) {
    // Destrói as informações existentes na sessão
    unset($_SESSION['username']);
    unset($_SESSION['senha']);

    // Redireciona o usuário para a página de login
    header('Location: ../ADMIN/login.php');
}

// Verifica se há uma busca na URL
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    // Monta a consulta SQL com o critério de busca
    $sqleditora = "SELECT * FROM editora
    WHERE Cod_editora LIKE '%$data%' or Nome_editora 
    LIKE '%$data%' or Cidade LIKE '%$data%' or Contato 
    LIKE '%$data%'
    ORDER BY Cod_editora DESC";
} else {
    // Monta a consulta SQL sem o critério de busca
    $sqleditora = "SELECT * FROM editora ORDER BY Cod_editora DESC";
}

// Define o número de registros por página
$registrosPorPagina = 5;
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $registrosPorPagina;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Construa a consulta SQL com base nos parâmetros
$result = $conexao->query($sqleditora);
$totalRegistros = $result->num_rows;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
if (!empty($search)) {
    $sqlseach = "SELECT * FROM editora 
    WHERE Cod_editora LIKE '%$data%' or Nome_editora 
    LIKE '%$data%' or Cidade LIKE '%$data%' or Contato 
    LIKE '%$data%'
    ORDER BY Cod_editora DESC";
    $result = $conexao->query($sqlseach);
} else {
    $sql = "SELECT * FROM editora 
    ORDER BY Cod_editora ASC 
    LIMIT $registrosPorPagina OFFSET $offset";
    $result = $conexao->query($sql);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/Table.css">
    <title>EDITORAS</title>
</head>

<body>
    <!-- Código HTML para exibição da barra de navegação e da tabela de clientes -->
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg" style="background-color: rgb(255,255,255);">
        <div class="container-fluid">
            <a class="navbar-brand" href="Sistema.php">WDA LIVRARIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Links para as diferentes páginas -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="Sistema.php">INICIO
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Usuarios.php">CLIENTES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Livros.php">LIVROS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Editoras.php" style="text-decoration:underline">EDITORAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Aluguel.php">ALUGUEL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Historico.php">HISTÓRICO</a>
                    </li>
                </ul>
                <!-- Botão de sair -->
                <span class="navbar-text">
                    <div class="d-flex">
                        <a href="Sair.php" id="out" class="btn btn-outline-danger me-5 ">Sair</a>
                    </div>
                </span>
            </div>
        </div>
    </nav>
    <br><br>

    <!-- Tabela de clientes -->
    <div class="table-responsive m-5">
        <div class="header-table d-flex justify-content-between align-items-center">
            <h2>Editoras</h2>
            <!-- Botão para adicionar novo cliente -->
            <a href="../CREATE/C_editora.php" class="btn btn-success" style="margin: 3px 3px 3px 3px;">ADICIONAR +</a><br>
            <!-- Campo de busca -->
            <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
            <!-- Botão para executar a busca -->
            <button class="btn btn-primary" onclick="searchData()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
            </button>
        </div>
        <!-- Tabela propriamente dita -->
        <table class="table text-white table-bg">
            <thead class="table-header">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cidade</th>
                    <th scape="col">Contato</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Laço while para percorrer todos os dados de editoras obtidos do banco de dados
                while ($editora_data = mysqli_fetch_assoc($result)) {
                    // Imprime uma linha da tabela HTML, com as informações de um editoras
                    echo "<tr>";
                    echo "<td>" . $editora_data['Cod_editora'] . "</td>";
                    echo "<td>" . $editora_data['Nome_editora'] . "</td>";
                    echo "<td>" . $editora_data['Cidade'] . "</td>";
                    echo "<td>" . $editora_data['Contato'] . "</td>";
                    // Imprime os botões de edição e deleção para cada editoras na linha da tabela HTML
                    echo "<td>
                        <a class='btn btn-sm btn-primary' href='../UPDATE/U_editora.php?id=$editora_data[Cod_editora]' title='Editar'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                            </svg>
                        </a> 
                        <a class='btn btn-sm btn-danger' href../DELETE/D_editora.php' title='Deletar' onclick='confirmDelete($editora_data[Cod_editora])'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                        <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                        </svg>
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
                <li class="page-item <?php echo ($paginaAtual == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="Editoras.php?pagina=<?php echo ($paginaAtual - 1); ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Exibir link da página anterior, se existir
                if ($paginaAtual > 3) {
                    echo "<li class='page-item'><a class='page-link' href='Editoras.php?pagina=1'>1</a></li>";
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }

                // Exibir páginas anteriores à página atual
                for ($i = max(1, $paginaAtual - 1); $i < $paginaAtual; $i++) {
                    echo "<li class='page-item'><a class='page-link' href='Editoras.php?pagina=$i'>$i</a></li>";
                }

                // Exibir página atual
                echo "<li class='page-item active'><span class='page-link'>$paginaAtual</span></li>";

                // Exibir páginas posteriores à página atual
                for ($i = $paginaAtual + 1; $i <= min($paginaAtual + 1, $totalPaginas); $i++) {
                    echo "<li class='page-item'><a class='page-link' href='Editoras.php?pagina=$i'>$i</a></li>";
                }

                // Exibir link da próxima página, se existir
                if ($paginaAtual < $totalPaginas - 1) {
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                    echo "<li class='page-item'><a class='page-link' href='Editoras.php?pagina=$totalPaginas'>$totalPaginas</a></li>";
                }
                ?>
                <li class="page-item <?php echo ($paginaAtual == $totalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="Editoras.php?pagina=<?php echo ($paginaAtual + 1); ?>" aria-label="Próxima">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</body>
<script>
    var search = document.getElementById('pesquisar')

    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            searchData()
        }
    })

    function searchData() {
        window.location = 'Editoras.php?search=' + search.value
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../DELETE/D_editora.php?id=${id}`;
            }
        })
    }
</script>

</html>