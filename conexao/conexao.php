<?php
// conexao/conexao.php

$servidor = "localhost";
$usuario_bd = "root";
$senha_bd   = "";
$banco      = "petshop";

$conn = mysqli_connect($servidor, $usuario_bd, $senha_bd, $banco);

if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
?>
