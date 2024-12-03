<?php
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
  //Redireciona para o home
  header("Location: " . $base . "logar/");
}

//Verifica se enviou o fomulário
if (isset($_POST['nome_administrador'])) {
  //Recebe os dados
  $nome_administrador = $_POST['nome_administrador'];
  $login_administrador = $_POST['login_administrador'];
  $senha_administrador = $_POST['senha_administrador'];
  $foto_administrador = $_FILES['foto_administrador'];

  //Verifica se existe um usuário com esse mesmo login
  $buscar_administrador = mysql_query("SELECT * FROM administrador WHERE login_administrador='" . $login_administrador . "'");
  //Caso encontre um usuário com esse login
  if (mysql_num_rows($buscar_administrador) >= 1) {
    //Mensagem de alerta
    $msgAlert = "Já existe um usuário com esse login!";
  } else {
    //Faz upload da foto
    $upload_foto = copy($foto_administrador['tmp_name'], 'images/funcionarios/' . $foto_administrador['name']);
    //Verifica se fez o upload da imagem
    if (!$upload_foto) {
      //Mensagem de erro
      $msgAlert = "Não foi possível fazer o upload da foto";
    } else {
      //Cadastra no banco
      $inserir_administrador = mysql_query("INSERT administrador SET 
      nome_administrador='" . $nome_administrador . "',
      login_administrador='" . $login_administrador . "',
      senha_administrador='" . $senha_administrador . "',
      foto_administrador='" . $foto_administrador['name'] . "'
      ");
      //Verifica se cadastrou
      if ($inserir_administrador) {
        //Separa o id que cadastrou
        $id_administrador = mysql_insert_id($conex);
        //Mostra a mensagem de acerto na tela
        $msgAlert = "Cadastrado com sucesso, cod:" . $id_administrador;
      } else {
        //Mostra a mensagem de de erro na tela
        $msgAlert = "Erro ao cadastrar usuário!" . mysql_error();
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Tags do PICS -->
  <?php include("tags.php"); ?>
  <!-- Final das tags PICS -->

  <title>Adicionar usuário | PICS</title>
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
              <h3>Adicionar </h3>
              <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Adicionar</div><br>
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
                  <form enctype="multipart/form-data" id="demo-form2" action="adicionar" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    <div class="form-group">
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="titulo-curso">Nome: <span class="required">*</span>
                        </label>
                        <input type="text" value="" required="required" name="nome_administrador" class="form-control">
                      </div>
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="titulo-curso">Login: <span class="required">*</span>
                        </label>
                        <input type="text" value="" required="required" name="login_administrador" class="form-control">
                      </div>
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="titulo-curso">Senha: <span class="riquired">*</span>
                        </label>
                        <input type="text" value="" required="required" name="senha_administrador" class="form-control">
                      </div>
                      <div class="col-md-12 col-sm-2 col-xs-12">
                        <label class="control-label" for="foto-usuario">Foto:
                        </label>
                        <input type="file" name="foto_administrador" value="" class="form-control">
                      </div>

                    </div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <button class="btn btn-primary" type="reset">Limpar</button>
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>

                <div class="ln_solid"></div>


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