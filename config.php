<link rel="stylesheet" href="estilos/style.css?" <?php rand(1, 1000); ?>>
<?php
    $dbHost = 'localhost';
    $dbUsername= 'root';
    $dbPassword = 'admin';
    $dbName = 'locadorabd';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
?>