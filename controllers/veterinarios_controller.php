<?php
// controllers/veterinarios_controller.php
session_start();
require_once("../conexao/conexao.php");

$acao = $_REQUEST['acao'] ?? '';

if ($acao === 'inserir') {
    $nome       = $_POST['nome'];
    $data_nasc  = $_POST['data_nasc'];
    $sexo       = $_POST['sexo'];
    $telefone   = $_POST['telefone'];
    $email      = $_POST['email'];
    $cpf        = $_POST['cpf'];
    $crmv       = $_POST['crmv'];
    $endereco   = $_POST['endereco'];
    $usuario    = $_POST['usuario'];
    $senha      = $_POST['senha'];

    // Upload da foto
    $fotoName = '';
    if(!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$fotoName);
    }

    $sql = "INSERT INTO veterinarios 
            (nome, data_nasc, sexo, telefone, email, cpf, crmv, endereco, foto, usuario, senha)
            VALUES 
            ('$nome','$data_nasc','$sexo','$telefone','$email','$cpf','$crmv','$endereco','$fotoName','$usuario','$senha')";
    mysqli_query($conn, $sql);

    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'editar') {
    $id_vet   = $_POST['id_vet'];
    $nome     = $_POST['nome'];
    $data_nasc= $_POST['data_nasc'];
    $sexo     = $_POST['sexo'];
    $telefone = $_POST['telefone'];
    $email    = $_POST['email'];
    $cpf      = $_POST['cpf'];
    $crmv     = $_POST['crmv'];
    $endereco = $_POST['endereco'];
    $usuario  = $_POST['usuario'];
    $senha    = $_POST['senha'];

    $sqlFoto = "";
    if(!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$fotoName);
        $sqlFoto = ", foto='$fotoName'";
    }

    // Se o campo de senha estiver vazio, não atualiza a senha
    $sqlSenha = "";
    if(!empty($senha)) {
        $sqlSenha = ", senha='$senha'";
    }

    $sql = "UPDATE veterinarios SET
              nome='$nome',
              data_nasc='$data_nasc',
              sexo='$sexo',
              telefone='$telefone',
              email='$email',
              cpf='$cpf',
              crmv='$crmv',
              endereco='$endereco',
              usuario='$usuario'
              $sqlFoto
              $sqlSenha
            WHERE id_vet='$id_vet'";
    mysqli_query($conn, $sql);

    header("Location: ../index.php");
    exit;
}
// 
elseif ($acao === 'excluir_unico') {
    $id_vet = $_POST['id_vet'];
    
    // 1) Verifica se existem animais vinculados a este veterinário
    $sqlCheck = "SELECT COUNT(*) as total FROM animais WHERE vet_id = '$id_vet'";
    $resCheck = mysqli_query($conn, $sqlCheck);
    $rowCheck = mysqli_fetch_assoc($resCheck);
    
    if($rowCheck['total'] > 0){
        // Se existir, você pode impedir a exclusão e avisar o usuário
        // Exemplo simples:
        echo "<script>alert('Não é possível excluir. Existem animais vinculados a este veterinário.'); window.location='../index.php';</script>";
        exit;
    }
    
    // 2) Se não existem animais, pode excluir
    $sql = "DELETE FROM veterinarios WHERE id_vet='$id_vet'";
    mysqli_query($conn, $sql);
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'excluir') {
    if (!empty($_POST['vet_ids'])) {
        $ids = implode(',', $_POST['vet_ids']);
        $sql = "DELETE FROM veterinarios WHERE id_vet IN ($ids)";
        mysqli_query($conn, $sql);
    }
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'carregar') {
    $id_vet = $_GET['id_vet'];
    $sql = "SELECT * FROM veterinarios WHERE id_vet='$id_vet'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
    exit;
}
