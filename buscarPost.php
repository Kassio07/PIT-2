<?php

include("conectar.php");
// Verificando se o usuário está logado
if(!isset($_SESSION['administrador'])){
  //Redireciona para o home
  header("Location: ".$base."logar/");
}


//Exlusão de post
if(isset($_POST['id_post_dell'])){
  //Receba os dados
  $id_post_dell = $_POST['id_post_dell'];
  $foto_post_dell = $_POST['foto_post_dell'];
  //Faz a exclusão no banco
  $dell_post = mysql_query("DELETE FROM post WHERE id_post = '".$id_post_dell."'");
  //Verifica se excluiu
  if($dell_post){
    unlink("images/funcionarios/".$foto_post_dell);
    $msgAlert = "Post excluído com sucesso";
  }else{
    $msgAlert = "Erro ao excluir post".mysql_error();
  }
}



//Verifica se chegou o fomrulário
if(isset($_POST['parametro_busca_post'])){
  //Recebe os dados
  $parametro_busca = $_POST['parametro_busca_post'];
  $valor_busca = $_POST['valor_busca_post'];
  //Inicia a string que será usada na query
  $stringQuery = "SELECT * FROM post WHERE ";
  //Verifica se enviou um parâmetro
  if($parametro_busca != ""){
    //Parâmetro nome do adm
    if($parametro_busca == "titulo_post"){
      //Acrescenta na Query
      $stringQuery .= "titulo_post LIKE('%".$valor_busca."%')";
    }else{
      $stringQuery .= $parametro_busca . " = '" .$valor_busca. "'";
    }
    //Caso não usa parâmetro
  }else{
    //acrescenta na Query
    $stringQuery .= "1=1";
  }
  //Finaliza o array
  $stringQuery .= "ORDER BY id_post DESC";
  //Executa a Query
  $busca_post = mysql_query($stringQuery);
}else{
  //Caso não tenha nenhuma busca
  $busca_post = mysql_query("SELECT * FROM post ORDER BY id_post DESC LIMIT 5");

}

?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Tags do PICS -->
    <?php include("tags.php"); ?>
    <!-- Final das tags PICS -->

    <title>Buscar Post | PICS</title>
    <!-- Inserindo CSS -->
    <?php include("css-include.php");?>
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
        <div class="x_content bs-example-popovers"><?php if(isset($msgAlert)){echo $msgAlert;}  ?></div>
        <!-- final das Notifiações -->
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Buscar</h3><br>
                <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Buscar Post</div><br>
              </div>
            </div>

            <div class="clearfix"></div>  
            
            <!-- Buscar Aluno -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <form enctype="multipart/form-data" id="formBusca" action="" method="post"  data-parsley-validate class="form-horizontal form-label-left">
                      <div class="form-group">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Parametro:
                          </label>
                          <select id="parametroAlunos" name="parametro_busca_post" class="form-control">
                            <option></option>                            
                            <option value="id_post">COD</option>                            
                            <option value="titulo_post">Título</option>                            
                            <option value="id_administrador">Autor</option>                            
                            <option value="data_post">Data</option>                      
                          </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Valor:
                          </label>
                          <input value="" name="valor_busca_post" type="text" class="form-control" />                    
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
                  <div class="x_title"><h2>Resultado da busca (<?php echo mysql_num_rows($busca_post) ?>):</h2><div class="clearfix"></div></div>                  
                  <!-- Inicio do Conteudo -->
                  <div class="x_content">

                    <!-- Unidade Adicionada -->
                    <table id="datatable-fixed-header" role="grid" class="table table-bordered">
                      <thead>
                        <tr>
                          <th>COD</th>
                          <th>Título</th>
                          <th>Autor</th>
                          <th>Data</th> 
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        while($resPost = mysql_fetch_array($busca_post)){ ?>
                        <tr>
                          <td> <?php echo $resPost['id_post']; ?></td>
                          <td><?php echo $resPost['titulo_post']; ?></td>
                          <td><?php echo $resPost['id_administrador']; ?></td>
                          
                           <td><?php echo date('d/m/Y H:i:s', strtotime($resPost['data_post'])) ?></td>
                          <td>
                            <div style="display: flex;">
                              <a href='ver/<?php echo $resPost['id_post'];?>' title='Ver' class='btn btn-xs btn-primary'>Ver</a>
                              <a href='editPost/<?php echo $resPost['id_post'];  ?>' title='editar' class='btn btn-xs btn-primary'>Editar</a>
                              <form action="buscarPost" method="post">
                                <input type="hidden" name="id_post_dell" value="<?php echo $resPost['id_post']?>">
                                <input type="hidden" name="foto_post_dell" value="<?php echo $resPost['foto_post']?>">
                                <button type="submit" title='Excluir' class='btn btn-xs btn-danger pull-left'>Excluir</a>
                              </form>
                            </div>
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
