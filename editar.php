<?php
include("conectar.php");
// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
  //Redireciona para o home
  header("Location: " . $base . "logar/");
}

//verifica se enviou o formulário
if (isset($_POST['nome_administrador'])) {
  //Recebe os dados
  $id_administrador = $_POST["id_administrador"];
  $nome_administrador = $_POST["nome_administrador"];
  $login_administrador = $_POST["login_administrador"];
  $senha_administrador = $_POST["senha_administrador"];
  //Atualiza no banco
  $edit_administrador = mysql_query("UPDATE administrador SET 
    id_administrador ='" . $id_administrador . "',
    nome_administrador ='" . $nome_administrador . "',
    login_administrador = '" . $login_administrador . "',
    senha_administrador = '" . $senha_administrador . "' 

    WHERE id_administrador = '" . $id_administrador . "'
  ");

  //Verifica se atualizou
  if ($edit_administrador) {
  //Mensagem de cadastro com sucesso
    $msgAlert = "Cadastrado com sucesso";
  } else {
    $msgAlert = "Erro ao atualizar! " . mysql_error();
  }
}

//Alteração de foto
if (isset($_FILES['foto_nova_administrador'])) {
  //Recebe os dados
  $id_administrador = $_POST['id_administrador'];
  $foto_antiga_administrador = $_POST['foto_antiga_administrador'];
  $foto_nova_administrador = $_FILES['foto_nova_administrador'];
  //Faz o Upload da foto
  $upload_foto = copy($foto_nova_administrador['tmp_name'], 'images/funcionarios/' . $foto_nova_administrador['name']);
  //Verifica se fez Upload
  if (!$upload_foto) {
    //Mensagem de alerta
    $msgAlert = "Não foi possível fazer upload da foto!";
  } else {
    //Atualiza no banco
    $edit_foto_administrador = mysql_query("UPDATE administrador SET foto_administrador ='" . $foto_nova_administrador['name'] . "'
     WHERE id_administrador='" . $id_administrador . "'");
    //Verifica se atualizou
    if (!$edit_foto_administrador) {
      //Mensagem de erro na tela
      $msgAlert = "Erro ao alterar a foto no banco de dados !";
    } else {
      //Excluir a foto anterior
      unlink('images/funcionarios/' . $foto_antiga_administrador);
      //Mensagem de acerto na tela
      $msgAlert = "Foto alterada com sucesso !";
    }
  }
}


//Buscando dados do ADM
$busca_administrador =  mysql_query("SELECT * FROM administrador WHERE id_administrador= '" . $primeiro_parametro . "'");
//verifica se acho um usuário
if (mysql_num_rows($busca_administrador) == 0) {
  //redireciona
  header("Location: " . $base . "editar/");
} else {
  //Percorre os resultados
  while ($resAdministrador = mysql_fetch_array($busca_administrador)) {
  //Separa os dados
    $nome_administrador = $resAdministrador['nome_administrador'];
    $login_administrador = $resAdministrador['login_administrador'];
    $senha_administrador = $resAdministrador['senha_administrador'];
    $foto_administrador = $resAdministrador['foto_administrador'];
  }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Tags do PICS -->
  <?php include("tags.php"); ?>
  <!-- Final das tags PICS -->

  <title>Editar | PICS</title>
  <!-- Inserindo CSS -->
  <?php include("css-include.php"); ?>
  <!-- Final CSS -->
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <!-- Inserindo Esquerda -->
        <?php include("esquerda.php"); ?>
        <!-- Final da Esquerda -->
      </div>

      <!-- Inserindo Topo -->
      <?php include("topo.php"); ?>
      <!-- Final do Topo -->

      <!-- Conteudo da Página -->
      <div class="right_col" role="main">
        <!-- Notificações -->
        <!-- Notificações -->
        <div class="x_content bs-example-popovers">
          <?php if (isset($msgAlert)) { ?>
            <div class='alert alert-info alert-dismissible fade in' role='alert'>
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>×</span>
              </button>
              <i class="fa fa-info-circle"></i> | <?php echo $msgAlert; ?>
            </div>
          <?php } ?>
        </div>
        <!-- final das Notifiações -->
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Editar </h3>
              <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Editar</div><br>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Preencha com os dados:</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <!-- Inicio do Formulário -->
                  <form enctype="multipart/form-data" id="demo-form2" action="" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    <input type="hidden" name="id_administrador" value="<?php echo $primeiro_parametro; ?>">
                    <div class="form-group">
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="nome-administrador">Nome:</span>
                        </label>
                        <input type="text" name="nome_administrador" value="<?php echo $nome_administrador; ?>" class="form-control">
                      </div>
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="login-curso">Login:
                        </label>
                        <input type="text" value="<?php echo $login_administrador; ?>" required="required" name="login_administrador" class="form-control">
                      </div>
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="senha-curso">Senha:
                        </label>
                        <input type="password" name="senha_administrador" value="<?php echo $senha_administrador ?>" class="form-control">
                      </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <!-- <button class="btn btn-primary" type="reset">Cancelar</button> -->
                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                  </div>
                </div>

                </form>
                <!-- Final do Formulário -->
              </div>
              <div class="x_panel">
                <div class="x_title">
                  <h2>Alterar foto</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <img src="images/funcionarios/<?php echo $foto_administrador ?>" width="200" alt="foto do administrador"> <br>
                  <!-- Inicio do Formulário -->
                  <form enctype="multipart/form-data" id="demo-form2" action="" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    <input type="hidden" name="id_administrador" value="<?php echo $primeiro_parametro; ?>">
                    <input type="hidden" name="foto_antiga_administrador" value="<?php echo $foto_administrador; ?>">

                    <div class="form-group">
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="foto-administrador">Foto:
                        </label>
                        <input type="file" name="foto_nova_administrador" value="" class="form-control">
                      </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <!-- <button class="btn btn-primary" type="reset">Cancelar</button> -->
                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                  </div>
                </div>

                </form>
                <!-- Final do Formulário -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Final Conteudo Página -->

    <!-- Inserindo Rodapé -->
    <?php include("rodape.php"); ?>
    <!-- Final Rodapé -->


    <!-- Inserindo JS -->
    <?php include("js-include.php") ?>
    <!-- Final JS -->
  </div>
  </div>
</body>

</html>