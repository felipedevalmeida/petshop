<?php
// login.php
session_start();
require_once("conexao/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha   = $_POST['senha'] ?? '';

    // Consulta no BD (sem hash, conforme solicitado)
    $sql = "SELECT * FROM veterinarios WHERE usuario='$usuario' AND senha='$senha' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $vet = mysqli_fetch_assoc($result);
        $_SESSION['vet_id']   = $vet['id_vet'];
        $_SESSION['vet_nome'] = $vet['nome'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - PetShop &copy; (BETA)</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="/petshop/assets/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="/petshop/assets/css/main.css">
  <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet"> -->
</head>
<body class="bg-light">

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-4 offset-sm-4">
            <h3 class="text-center mb-3">Login do Veterinário</h3>
            <?php if(isset($erro)): ?>
            <div class="alert alert-danger">
                <?php echo $erro; ?>
            </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" name="senha" id="senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (deve vir antes do Bootstrap JS) -->
<script src="/petshop/assets/js/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="/petshop/assets/js/bootstrap.min.js"></script>
</body>
</html>
