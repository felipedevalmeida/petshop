<?php
// views/principal.php
require_once("conexao/conexao.php");

// Carrega dados do BD
$queryAnimais = "SELECT a.*, t.nome AS nome_tutor, v.nome AS nome_vet
                 FROM animais a
                 LEFT JOIN tutores t ON a.tutor_id = t.id_tutor
                 LEFT JOIN veterinarios v ON a.vet_id = v.id_vet";
$resAnimais = mysqli_query($conn, $queryAnimais);

$queryTutores = "SELECT * FROM tutores";
$resTutores = mysqli_query($conn, $queryTutores);

$queryVets = "SELECT * FROM veterinarios";
$resVets = mysqli_query($conn, $queryVets);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Sistema PetShop &copy; (BETA)</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="/petshop/assets/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="/petshop/assets/css/main.css">
  <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet"> -->

  <!-- Libraries Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ContentBuilder -->
	<link href="/petshop/assets/minimalist-blocks/content.css" rel="stylesheet" type="text/css" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/petshop/assets/css/bootstrap.min.css?v=4" rel="stylesheet">

    <!-- Libraries Stylesheet -->
	<link href="/petshop/assets/css/essentials.css?v=4" rel="stylesheet" type="text/css" />
    <link href="/petshop/assets/lib/animate/animate.min.css" rel="stylesheet">
    <!-- <link href="/petshop/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"> -->
	<link href="/petshop/assets/plugins/owl-carousel/owl.pack.css" rel="stylesheet" type="text/css" />
    <link href="/petshop/assets/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
	<link href="/petshop/assets/css/flexslider.css" rel="stylesheet" type="text/css" />
	<link href="/petshop/assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">

    <!-- Template Stylesheet -->
    <link href="/petshop/assets/css/style.css?v=76" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

</head>
<body>
<div class="container mt-3">

  <div class="header-dashboard">  
    <h3 class="mb-3">Bem-vindo(a), <?php echo $_SESSION['vet_nome']; ?>!</h3>
    <a href="logout.php" class="btn btn-primary mb-3">Logout</a>
  </div>

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="animais-tab" data-bs-toggle="tab" data-bs-target="#animais" type="button" role="tab">Animais</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tutores-tab" data-bs-toggle="tab" data-bs-target="#tutores" type="button" role="tab">Tutores</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="vets-tab" data-bs-toggle="tab" data-bs-target="#vets" type="button" role="tab">Veterinários</button>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">

    <!-- ABA ANIMAIS -->
    <div class="tab-pane fade show active p-3" id="animais" role="tabpanel" aria-labelledby="animais-tab">
      <h4>Animais</h4>
      <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalAnimal" onclick="openModalAnimal()">+ Novo Animal</button>
      <form method="post" action="controllers/animais_controller.php">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" onclick="toggleCheckAll(this, 'animal_ids[]')"></th>
              <th>ID</th>
              <th>Nome</th>
              <th>Tutor</th>
              <th>Veterinário</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = mysqli_fetch_assoc($resAnimais)): ?>
            <tr>
              <td><input type="checkbox" name="animal_ids[]" value="<?php echo $row['id_animal']; ?>"></td>
              <td><?php echo $row['id_animal']; ?></td>
              <td><?php echo $row['nome']; ?></td>
              <td><?php echo $row['nome_tutor']; ?></td>
              <td><?php echo $row['nome_vet']; ?></td>
              <td><?php echo $row['status_vivo_morto']; ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAnimal" onclick="editarAnimal(<?php echo $row['id_animal']; ?>)">
                  Editar
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
        <!-- Ações em massa -->
        <button type="submit" name="acao" value="excluir" class="btn btn-primary">Excluir selecionados</button>
        <button type="submit" name="acao" value="trocar_status" class="btn btn-primary">Trocar status (vivo/morto)</button>
      </form>
    </div>

    <!-- ABA TUTORES -->
    <div class="tab-pane fade p-3" id="tutores" role="tabpanel" aria-labelledby="tutores-tab">
      <h4>Tutores</h4>
      <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalTutor" onclick="openModalTutor()">+ Novo Tutor</button>
      <form method="post" action="controllers/tutores_controller.php">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" onclick="toggleCheckAll(this, 'tutor_ids[]')"></th>
              <th>ID</th>
              <th>Nome</th>
              <th>CPF</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
          <?php while($rowT = mysqli_fetch_assoc($resTutores)): ?>
            <tr>
              <td><input type="checkbox" name="tutor_ids[]" value="<?php echo $rowT['id_tutor']; ?>"></td>
              <td><?php echo $rowT['id_tutor']; ?></td>
              <td><?php echo $rowT['nome']; ?></td>
              <td><?php echo $rowT['cpf']; ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTutor"
                        onclick="editarTutor(<?php echo $rowT['id_tutor']; ?>)">
                  Editar
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
        <button type="submit" name="acao" value="excluir" class="btn btn-primary">Excluir selecionados</button>
      </form>
    </div>

    <!-- ABA VETERINÁRIOS -->
    <div class="tab-pane fade p-3" id="vets" role="tabpanel" aria-labelledby="vets-tab">
      <h4>Veterinários</h4>
      <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalVet" onclick="openModalVet()">+ Novo Veterinário</button>
      <form method="post" action="controllers/veterinarios_controller.php">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" onclick="toggleCheckAll(this, 'vet_ids[]')"></th>
              <th>ID</th>
              <th>Nome</th>
              <th>CRMV</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
          <?php while($rowV = mysqli_fetch_assoc($resVets)): ?>
            <tr>
              <td><input type="checkbox" name="vet_ids[]" value="<?php echo $rowV['id_vet']; ?>"></td>
              <td><?php echo $rowV['id_vet']; ?></td>
              <td><?php echo $rowV['nome']; ?></td>
              <td><?php echo $rowV['crmv']; ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalVet"
                        onclick="editarVet(<?php echo $rowV['id_vet']; ?>)">
                  Editar
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
        <button type="submit" name="acao" value="excluir" class="btn btn-primary">Excluir selecionados</button>
      </form>
    </div>

  </div> <!-- fim .tab-content -->

</div> <!-- fim .container -->

<!-- MODAL ANIMAL -->
<div class="modal fade" id="modalAnimal" tabindex="-1" aria-labelledby="modalAnimalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAnimal" method="post" action="controllers/animais_controller.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title" id="modalAnimalLabel">Cadastro de Animal</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_animal" id="id_animal">

          <div class="mb-3">
            <label for="nomeAnimal" class="form-label">Nome do Animal</label>
            <input type="text" class="form-control" name="nome" id="nomeAnimal" required>
          </div>
          <div class="mb-3">
            <label for="dataNascAnimal" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" name="data_nasc" id="dataNascAnimal" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Sexo</label><br>
            <input type="radio" name="sexo" value="M" checked> Macho
            <input type="radio" name="sexo" value="F"> Fêmea
          </div>
          <div class="mb-3">
            <label for="especie" class="form-label">Espécie</label>
            <input type="text" class="form-control" name="especie" id="especie">
          </div>
          <div class="mb-3">
            <label for="tutor_id" class="form-label">Tutor</label>
            <select class="form-select" name="tutor_id" id="tutor_id">
              <option value="">Selecione o tutor</option>
              <?php
              // Carrega lista de tutores
              $sqlT = "SELECT id_tutor, nome FROM tutores";
              $rT = mysqli_query($conn, $sqlT);
              while($t = mysqli_fetch_assoc($rT)){
                  echo "<option value='{$t['id_tutor']}'>{$t['nome']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="vet_id" class="form-label">Veterinário Responsável</label>
            <select class="form-select" name="vet_id" id="vet_id">
              <option value="">Selecione o veterinário</option>
              <?php
              // Carrega lista de veterinários
              $sqlV = "SELECT id_vet, nome FROM veterinarios";
              $rV = mysqli_query($conn, $sqlV);
              while($v = mysqli_fetch_assoc($rV)){
                  echo "<option value='{$v['id_vet']}'>{$v['nome']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="doencas_prex" class="form-label">Doenças Pré-existentes</label>
            <textarea class="form-control" name="doencas_prex" id="doencas_prex"></textarea>
          </div>
          <div class="mb-3">
            <label for="exames_solicitados" class="form-label">Exames Solicitados</label>
            <textarea class="form-control" name="exames_solicitados" id="exames_solicitados"></textarea>
          </div>
          <div class="mb-3">
            <label for="laudo_medico" class="form-label">Laudo / Parecer</label>
            <textarea class="form-control" name="laudo_medico" id="laudo_medico"></textarea>
          </div>
          <div class="mb-3">
            <label for="status_vivo_morto" class="form-label">Status</label>
            <select class="form-select" name="status_vivo_morto" id="status_vivo_morto">
              <option value="vivo">Vivo</option>
              <option value="morto">Morto</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="data_vacina" class="form-label">Data da Vacina</label>
            <input type="date" class="form-control" name="data_vacina" id="data_vacina">
          </div>
          <div class="mb-3">
            <label for="fotoAnimal" class="form-label">Foto</label>
            <input type="file" class="form-control" name="foto" id="fotoAnimal">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="acao" value="inserir" id="btnSalvarAnimal" class="btn btn-primary">Incluir</button>
          <button type="submit" name="acao" value="editar" id="btnEditarAnimal" class="btn btn-success d-none">Salvar Modificações</button>
          <button type="submit" name="acao" value="excluir_unico" id="btnExcluirAnimal" class="btn btn-danger d-none">Excluir</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL TUTOR -->
<div class="modal fade" id="modalTutor" tabindex="-1" aria-labelledby="modalTutorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formTutor" method="post" action="controllers/tutores_controller.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title" id="modalTutorLabel">Cadastro de Tutor</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_tutor" id="id_tutor">

          <div class="mb-3">
            <label for="nomeTutor" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" id="nomeTutor" required>
          </div>
          <div class="mb-3">
            <label for="dataNascTutor" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" name="data_nasc" id="dataNascTutor" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Sexo</label><br>
            <input type="radio" name="sexo" value="M" checked> Masculino
            <input type="radio" name="sexo" value="F"> Feminino
          </div>
          <div class="mb-3">
            <label for="cpfTutor" class="form-label">CPF</label>
            <input type="text" class="form-control" name="cpf" id="cpfTutor">
          </div>
          <div class="mb-3">
            <label for="telefoneTutor" class="form-label">Telefone</label>
            <input type="text" class="form-control" name="telefone" id="telefoneTutor">
          </div>
          <div class="mb-3">
            <label for="emailTutor" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="emailTutor">
          </div>
          <div class="mb-3">
            <label for="enderecoTutor" class="form-label">Endereço</label>
            <textarea class="form-control" name="endereco" id="enderecoTutor"></textarea>
          </div>
          <div class="mb-3">
            <label for="fotoTutor" class="form-label">Foto</label>
            <input type="file" class="form-control" name="foto" id="fotoTutor">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="acao" value="inserir" id="btnSalvarTutor" class="btn btn-primary">Incluir</button>
          <button type="submit" name="acao" value="editar" id="btnEditarTutor" class="btn btn-success d-none">Salvar Modificações</button>
          <button type="submit" name="acao" value="excluir_unico" id="btnExcluirTutor" class="btn btn-danger d-none">Excluir</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL VETERINARIO -->
<div class="modal fade" id="modalVet" tabindex="-1" aria-labelledby="modalVetLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formVet" method="post" action="controllers/veterinarios_controller.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title" id="modalVetLabel">Cadastro de Veterinário</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_vet" id="id_vet">

          <div class="mb-3">
            <label for="nomeVet" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" id="nomeVet" required>
          </div>
          <div class="mb-3">
            <label for="dataNascVet" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" name="data_nasc" id="dataNascVet" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Sexo</label><br>
            <input type="radio" name="sexo" value="M" checked> Masculino
            <input type="radio" name="sexo" value="F"> Feminino
          </div>
          <div class="mb-3">
            <label for="telefoneVet" class="form-label">Telefone</label>
            <input type="text" class="form-control" name="telefone" id="telefoneVet">
          </div>
          <div class="mb-3">
            <label for="emailVet" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="emailVet">
          </div>
          <div class="mb-3">
            <label for="cpfVet" class="form-label">CPF</label>
            <input type="text" class="form-control" name="cpf" id="cpfVet">
          </div>
          <div class="mb-3">
            <label for="crmv" class="form-label">CRMV</label>
            <input type="text" class="form-control" name="crmv" id="crmv">
          </div>
          <div class="mb-3">
            <label for="enderecoVet" class="form-label">Endereço</label>
            <textarea class="form-control" name="endereco" id="enderecoVet"></textarea>
          </div>
          <div class="mb-3">
            <label for="fotoVet" class="form-label">Foto</label>
            <input type="file" class="form-control" name="foto" id="fotoVet">
          </div>
          <div class="mb-3">
            <label for="usuarioVet" class="form-label">Usuário</label>
            <input type="text" class="form-control" name="usuario" id="usuarioVet">
          </div>
          <div class="mb-3">
            <label for="senhaVet" class="form-label">Senha</label>
            <input type="password" class="form-control" name="senha" id="senhaVet">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="acao" value="inserir" id="btnSalvarVet" class="btn btn-primary">Incluir</button>
          <button type="submit" name="acao" value="editar" id="btnEditarVet" class="btn btn-success d-none">Salvar Modificações</button>
          <button type="submit" name="acao" value="excluir_unico" id="btnExcluirVet" class="btn btn-danger d-none">Excluir</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- jQuery (deve vir antes do Bootstrap JS) -->
  <script src="/petshop/assets/js/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="/petshop/assets/js/bootstrap.min.js"></script>

<script>
// Selecionar/deselecionar todos
function toggleCheckAll(source, checkboxName) {
  var checkboxes = document.getElementsByName(checkboxName);
  for(var i=0; i<checkboxes.length; i++){
    checkboxes[i].checked = source.checked;
  }
}

// ABRIR MODAL ANIMAL PARA INSERÇÃO
function openModalAnimal(){
  document.getElementById('formAnimal').reset();
  document.getElementById('id_animal').value = '';
  document.getElementById('btnSalvarAnimal').classList.remove('d-none');
  document.getElementById('btnEditarAnimal').classList.add('d-none');
  document.getElementById('btnExcluirAnimal').classList.add('d-none');
  document.getElementById('modalAnimalLabel').textContent = 'Cadastro de Animal';
}

// EDITAR ANIMAL
function editarAnimal(id){
  $.ajax({
    url: 'controllers/animais_controller.php',
    type: 'GET',
    data: { acao: 'carregar', id_animal: id },
    dataType: 'json',
    success: function(data){
      $('#id_animal').val(data.id_animal);
      $('#nomeAnimal').val(data.nome);
      $('#dataNascAnimal').val(data.data_nasc);
      if(data.sexo === 'F'){
        $('input[name="sexo"][value="F"]').prop('checked',true);
      } else {
        $('input[name="sexo"][value="M"]').prop('checked',true);
      }
      $('#especie').val(data.especie);
      $('#tutor_id').val(data.tutor_id);
      $('#vet_id').val(data.vet_id);
      $('#doencas_prex').val(data.doencas_prex);
      $('#exames_solicitados').val(data.exames_solicitados);
      $('#laudo_medico').val(data.laudo_medico);
      $('#status_vivo_morto').val(data.status_vivo_morto);
      $('#data_vacina').val(data.data_vacina);

      $('#btnSalvarAnimal').addClass('d-none');
      $('#btnEditarAnimal').removeClass('d-none');
      $('#btnExcluirAnimal').removeClass('d-none');
      $('#modalAnimalLabel').text('Editar Animal');
    }
  });
}

// FUNÇÕES SIMILARES PARA TUTOR
function openModalTutor(){
  document.getElementById('formTutor').reset();
  document.getElementById('id_tutor').value = '';
  document.getElementById('btnSalvarTutor').classList.remove('d-none');
  document.getElementById('btnEditarTutor').classList.add('d-none');
  document.getElementById('btnExcluirTutor').classList.add('d-none');
  document.getElementById('modalTutorLabel').textContent = 'Cadastro de Tutor';
}

function editarTutor(id){
  $.ajax({
    url: 'controllers/tutores_controller.php',
    type: 'GET',
    data: { acao: 'carregar', id_tutor: id },
    dataType: 'json',
    success: function(data){
      $('#id_tutor').val(data.id_tutor);
      $('#nomeTutor').val(data.nome);
      $('#dataNascTutor').val(data.data_nasc);
      if(data.sexo === 'F'){
        $('input[name="sexo"][value="F"]').prop('checked',true);
      } else {
        $('input[name="sexo"][value="M"]').prop('checked',true);
      }
      $('#cpfTutor').val(data.cpf);
      $('#telefoneTutor').val(data.telefone);
      $('#emailTutor').val(data.email);
      $('#enderecoTutor').val(data.endereco);

      $('#btnSalvarTutor').addClass('d-none');
      $('#btnEditarTutor').removeClass('d-none');
      $('#btnExcluirTutor').removeClass('d-none');
      $('#modalTutorLabel').text('Editar Tutor');
    }
  });
}

// FUNÇÕES SIMILARES PARA VETERINÁRIO
function openModalVet(){
  document.getElementById('formVet').reset();
  document.getElementById('id_vet').value = '';
  document.getElementById('btnSalvarVet').classList.remove('d-none');
  document.getElementById('btnEditarVet').classList.add('d-none');
  document.getElementById('btnExcluirVet').classList.add('d-none');
  document.getElementById('modalVetLabel').textContent = 'Cadastro de Veterinário';
}

function editarVet(id){
  $.ajax({
    url: 'controllers/veterinarios_controller.php',
    type: 'GET',
    data: { acao: 'carregar', id_vet: id },
    dataType: 'json',
    success: function(data){
      $('#id_vet').val(data.id_vet);
      $('#nomeVet').val(data.nome);
      $('#dataNascVet').val(data.data_nasc);
      if(data.sexo === 'F'){
        $('input[name="sexo"][value="F"]').prop('checked',true);
      } else {
        $('input[name="sexo"][value="M"]').prop('checked',true);
      }
      $('#telefoneVet').val(data.telefone);
      $('#emailVet').val(data.email);
      $('#cpfVet').val(data.cpf);
      $('#crmv').val(data.crmv);
      $('#enderecoVet').val(data.endereco);
      // ...
      $('#usuarioVet').val(data.usuario);
      // senha é opcional popular (por segurança, pode deixar em branco e trocar se quiser)...

      $('#btnSalvarVet').addClass('d-none');
      $('#btnEditarVet').removeClass('d-none');
      $('#btnExcluirVet').removeClass('d-none');
      $('#modalVetLabel').text('Editar Veterinário');
    }
  });
}
</script>

</body>
</html>
