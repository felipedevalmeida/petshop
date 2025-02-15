<?php
// controllers/animais_controller.php
session_start();
require_once("../conexao/conexao.php");

$acao = $_REQUEST['acao'] ?? '';

if ($acao === 'inserir') {
    $nome            = $_POST['nome'];
    $data_nasc       = $_POST['data_nasc'];
    $sexo            = $_POST['sexo'];
    $especie         = $_POST['especie'];
    $tutor_id        = $_POST['tutor_id'];
    $vet_id          = $_POST['vet_id'];
    $doencas_prex    = $_POST['doencas_prex'];
    $exames_solic    = $_POST['exames_solicitados'];
    $laudo           = $_POST['laudo_medico'];
    $status          = $_POST['status_vivo_morto'];
    $data_vacina     = $_POST['data_vacina'];

    // Upload da foto (exemplo simples, sem validações)
    $fotoName = '';
    if(!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$fotoName);
    }

    $sql = "INSERT INTO animais 
    (nome, data_nasc, sexo, especie, tutor_id, vet_id, doencas_prex, exames_solicitados, laudo_medico, status_vivo_morto, data_vacina, foto)
    VALUES 
    ('$nome', '$data_nasc', '$sexo', '$especie', '$tutor_id', '$vet_id', '$doencas_prex', '$exames_solic', '$laudo', '$status', '$data_vacina', '$fotoName')";
    mysqli_query($conn, $sql);

    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'editar') {
    $id_animal       = $_POST['id_animal'];
    $nome            = $_POST['nome'];
    $data_nasc       = $_POST['data_nasc'];
    $sexo            = $_POST['sexo'];
    $especie         = $_POST['especie'];
    $tutor_id        = $_POST['tutor_id'];
    $vet_id          = $_POST['vet_id'];
    $doencas_prex    = $_POST['doencas_prex'];
    $exames_solic    = $_POST['exames_solicitados'];
    $laudo           = $_POST['laudo_medico'];
    $status          = $_POST['status_vivo_morto'];
    $data_vacina     = $_POST['data_vacina'];

    $sqlFoto = "";
    if(!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$fotoName);
        $sqlFoto = ", foto='$fotoName'";
    }

    $sql = "UPDATE animais SET
              nome='$nome',
              data_nasc='$data_nasc',
              sexo='$sexo',
              especie='$especie',
              tutor_id='$tutor_id',
              vet_id='$vet_id',
              doencas_prex='$doencas_prex',
              exames_solicitados='$exames_solic',
              laudo_medico='$laudo',
              status_vivo_morto='$status',
              data_vacina='$data_vacina'
              $sqlFoto
            WHERE id_animal='$id_animal'";
    mysqli_query($conn, $sql);

    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'excluir_unico') {
    $id_animal = $_POST['id_animal'];
    $sql = "DELETE FROM animais WHERE id_animal='$id_animal'";
    mysqli_query($conn, $sql);
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'excluir') {
    // Exclusão em massa
    if (!empty($_POST['animal_ids'])) {
        $ids = implode(',', $_POST['animal_ids']);
        $sql = "DELETE FROM animais WHERE id_animal IN ($ids)";
        mysqli_query($conn, $sql);
    }
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'trocar_status') {
    // Trocar status (vivo <-> morto) em massa
    if (!empty($_POST['animal_ids'])) {
        foreach($_POST['animal_ids'] as $id_animal) {
            $sqlStatus = "SELECT status_vivo_morto FROM animais WHERE id_animal='$id_animal'";
            $resSt = mysqli_query($conn, $sqlStatus);
            $rowSt = mysqli_fetch_assoc($resSt);
            $novoStatus = ($rowSt['status_vivo_morto'] === 'vivo') ? 'morto' : 'vivo';

            $sqlUpd = "UPDATE animais SET status_vivo_morto='$novoStatus' WHERE id_animal='$id_animal'";
            mysqli_query($conn, $sqlUpd);
        }
    }
    header("Location: ../index.php");
    exit;
}
elseif ($acao === 'carregar') {
    // Carrega dados de um animal (para edição via AJAX)
    $id_animal = $_GET['id_animal'];
    $sql = "SELECT * FROM animais WHERE id_animal='$id_animal'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
    exit;
}
