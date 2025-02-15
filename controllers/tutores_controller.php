<?php
// controllers/tutores_controller.php
session_start();
require_once("../conexao/conexao.php");

$acao = $_REQUEST['acao'] ?? '';

if ($acao === 'inserir') {
    $nome       = $_POST['nome'];
    $data_nasc  = $_POST['data_nasc'];
    $sexo       = $_POST['sexo'];
    $cpf        = $_POST['cpf'];
    $telefone   = $_POST['telefone'];
    $email      = $_POST['email'];
    $endereco   = $_POST['endereco'];

    // Upload da foto
    $fotoName = '';
    if(!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$fotoName);
    }

    $sql = "INSERT INTO tutores (nome, data_nasc, sexo, cpf, telefone, email, endereco, foto)
            VALUES ('$nome','$data_nasc','$sexo','$cpf','$telefone','$email','$endereco','$fotoName')";
    mysqli_query($conn, $sql);

    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'editar') {
    $id_tutor  = $_POST['id_tutor'];
    $nome      = $_POST['nome'];
    $data_nasc = $_POST['data_nasc'];
    $sexo      = $_POST['sexo'];
    $cpf       = $_POST['cpf'];
    $telefone  = $_POST['telefone'];
    $email     = $_POST['email'];
    $endereco  = $_POST['endereco'];

    $sqlFoto = "";
    if(!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$fotoName);
        $sqlFoto = ", foto='$fotoName'";
    }

    $sql = "UPDATE tutores SET 
              nome='$nome',
              data_nasc='$data_nasc',
              sexo='$sexo',
              cpf='$cpf',
              telefone='$telefone',
              email='$email',
              endereco='$endereco'
              $sqlFoto
            WHERE id_tutor='$id_tutor'";
    mysqli_query($conn, $sql);

    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'excluir_unico') {
    $id_tutor = $_POST['id_tutor'];
    $sql = "DELETE FROM tutores WHERE id_tutor='$id_tutor'";
    mysqli_query($conn, $sql);
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'excluir') {
    if (!empty($_POST['tutor_ids'])) {
        $ids = implode(',', $_POST['tutor_ids']);
        $sql = "DELETE FROM tutores WHERE id_tutor IN ($ids)";
        mysqli_query($conn, $sql);
    }
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'carregar') {
    $id_tutor = $_GET['id_tutor'];
    $sql = "SELECT * FROM tutores WHERE id_tutor='$id_tutor'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
    exit;
}
