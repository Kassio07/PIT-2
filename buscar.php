<?php
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
  //Redireciona para o home
  header("Location: " . $base . "logar/");
}


//Exclusão de usuário
if(isset($_POST['id_administrador_dell'])){
  //Receba os dados
  $id_administrador_dell = $_POST['id_administrador_dell'];
  $foto_administrador_dell = $_POST['foto_administrador_dell'];
  //Faz a exclusão no banco
  $del_administrador = mysql_query("DELETE FROM administrador WHERE id_administrador ='".$id_administrador_dell."'");
  //Verifica se excluiu
  if($del_administrador){
    //Deleta a foto do usuário
    unlink("images/funcionarios/".$foto_administrador_dell);
    $msgAlert = "Administrador exluído com sucesso";
  }else{
    // Se não excluíu, mensagem de erro na tela
    $msgAlert = "Erro ao excluir usuário".mysql_error();
  }
}



//Verifica se chegou formulário
if (isset($_POST['parametro_busca'])) {
  //Recebe os dados
  $parametro_busca = $_POST['parametro_busca'];
  $valor_busca = $_POST['valor_busca'];
  //Iniciar a string que será usado na query
  $strQuery = "SELECT * FROM administrador WHERE ";
  //Verifica se enviou um parâmetro
  if ($parametro_busca != "") {
    //Parâmetro nome de adm
    if ($parametro_busca == "nome_administrador") {
      //Acrescenta na Query
      $strQuery .= " nome_administrador LIKE('%" . $valor_busca . "%') ";
    } else {
      //Acrescenta na query
      $strQuery .= $parametro_busca . " = '" . $valor_busca . "'";
    }
    //Caso n utilize parâmetro
  } else {
    //Acrescenta no query
    $strQuery .= "1=1";
  }
  //Finaliza o Array  
  $strQuery .= "ORDER BY id_administrador DESC";
  //Executa a query
  $busca_administrador = mysql_query($strQuery);
} else {
  //Caso não tenha nenhuma busca
  $busca_administrador = mysql_query("SELECT * FROM administrador ORDER BY id_administrador DESC LIMIT 5");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Tags do PICS -->
  <?php include("tags.php"); ?>
  <!-- Final das tags PICS -->

  <title>Buscar usuário | PICS</title>
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
              <h3>Buscar</h3><br>
            </div>
          </div>

          <div class="clearfix"></div>

          <!-- Buscar Aluno -->
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <form enctype="multipart/form-data" id="formBusca" action="" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    <div class="form-group">
                      <div class="col-md-4 col-sm-12 col-xs-12">
                        <label class="control-label" for="titulo-curso">Parametro:
                        </label>
                        <select id="parametroAluno" name="parametro_busca" class="form-control">
                          <option></option>
                          <option value="id_administrador">COD</option>
                          <option value="nome_administrador">Nome</option>
                          <option value="nome_administrador">Login</option>
                        </select>
                      </div>
                      <div class="col-md-4 col-sm-12 col-xs-12">
                        <label class="control-label" for="titulo-curso">Valor:
                        </label>
                        <input value="" name="valor_busca" type="text" class="form-control" />
                      </div>

                    </div>
                    <div class="form-group">
                      <div class="col-md-12 col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success"><i class="fa fa-search-plus"></i> | Buscar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Resultado da Busca -->
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Resultado da busca (<?php echo mysql_num_rows($busca_administrador) ?>):</h2>
                  <div class="clearfix"></div>
                </div>
                <!-- Inicio do Conteudo -->
                <div class="x_content">

                  <!-- Unidade Adicionada -->
                  <table id="datatable-fixed-header" role="grid" class="table table-bordered">
                    <thead>
                      <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Login</th>
                        <th>Nome</th>
                        <th></th>
                      </tr>
                    </thead>
                    <!-- Imprime resultado da busca -->
                    <tbody>
                      <?php
                      while ($resAdministrador = mysql_fetch_array($busca_administrador)) { ?>


                        <tr>
                          <td><img class="avatar" src="images/funcionarios/<?php echo $resAdministrador['foto_administrador']; ?>"/></td>
                          <td><?php echo $resAdministrador['id_administrador']; ?></td>
                          <td><?php echo $resAdministrador['login_administrador']; ?></td>
                          <td><?php echo $resAdministrador['nome_administrador']; ?></td>
                          <td>
                            <a href='editar/<?php echo $resAdministrador['id_administrador'] ?>' title='Editar' class='btn btn-xs btn-warning pull-left'>Editar</a>

                            <form action="buscar" method="post">
                              <input type="hidden" name="id_administrador_dell" value="<?php echo $resAdministrador['id_administrador']; ?>">
                              <input type="hidden" name="foto_administrador_dell" value="<?php echo $resAdministrador['foto_administrador']; ?>">
                              <button type="submit" title='Excluir' class='btn btn-xs btn-danger pull-left'>Excluir</a>
                            </form>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <!-- Unidade Adicionada -->

                </div>
                <!-- Fim do Conteudo-->

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