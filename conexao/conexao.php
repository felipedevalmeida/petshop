<?php
// conexao/conexao.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servidor = "localhost";
$usuario_bd = "root";
$senha_bd   = "";
$banco      = "petshop";

$conn = mysqli_connect($servidor, $usuario_bd, $senha_bd, $banco);

if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
?>
