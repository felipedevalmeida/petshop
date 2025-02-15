<?php
// lembrete_vacinas.php
require_once("conexao/conexao.php");

// Seleciona animais cuja data_vacina = data atual + 2 dias
$sql = "SELECT a.*, t.email, t.telefone
        FROM animais a
        JOIN tutores t ON a.tutor_id = t.id_tutor
        WHERE data_vacina = DATE_ADD(CURDATE(), INTERVAL 2 DAY)";
$res = mysqli_query($conn, $sql);

$mensagem = "Faltam dois dias para o seu/sua amiguinho(a) visitar o(a) seu/sua Vet, para eventuais providências. Não esqueça! A saudade do seu Pet agradece!";

while($row = mysqli_fetch_assoc($res)) {
    // Disparo de e-mail (função mail simples)
    $to      = $row['email'];
    $subject = "Lembrete de Vacina - PetShop";
    $headers = "From: lembretes@petshop.com\r\n";
    // Se quiser HTML:
    // $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    mail($to, $subject, $mensagem, $headers);

    // Exemplo de disparo WhatsApp via API (fictícia):
    // $telefone = $row['telefone'];
    // enviarWhatsApp($telefone, $mensagem);
    // ...
}

echo "Lembretes enviados (se houver destinatários).";
