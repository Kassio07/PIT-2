<?php
//conexão com o banco
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
  //Redireciona para o home
  header("Location: " . $base . "logar/");
}


//Consulta para contar o total de post e e-mails
$res = mysql_query("SELECT 
  (SELECT COUNT(*) FROM post) AS total_post,
  (SELECT COUNT(*) FROM newsletter) AS total_email
 ");

//Obtendo o total
$totais = mysql_fetch_array($res);
$tot_email = $totais['total_email'];
$tot_post = $totais['total_post'];

//Buscando o último post
$ultimo_post = mysql_query("SELECT 
    post.id_post,
    post.titulo_post,
    post.data_post,
    post.descricao_post,
    post.foto_post,
    administrador.nome_administrador
FROM 
    post
INNER JOIN 
    administrador 
ON 
    post.id_administrador = administrador.id_administrador
ORDER BY 
    id_post DESC
LIMIT 1;");

//Verifica se encontrou o post
if (mysql_num_rows($ultimo_post) > 0) {
  //Recebe os dados 
  while ($resultado = mysql_fetch_assoc($ultimo_post)) {
    $titulo_post = $resultado['titulo_post'];
    $data_post = $resultado['data_post'];
    $descricao_post = $resultado['descricao_post'];
    $foto_post = $resultado['foto_post'];
    $nome_administrador = $resultado['nome_administrador'];
  }
} else {
  $msgAlert = "Post não encontrado!";
}
//Seleciona todos os post
$todos_post = mysql_query("SELECT * FROM post ORDER BY id_post DESC");


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Tags do PICS -->
  <?php include("tags.php"); ?>
  <!-- Final das tags PICS -->

  <title>Home | PICS</title>
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
        <div class="x_content bs-example-popovers"><?php if (isset($msgAlert)) {
                                                      echo $msgAlert;
                                                    }  ?></div>

        <!-- final das Notifiações -->
        <div class="">


          <div class="clearfix"></div> 

          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="x_panel" style="height: 800px; overflow:auto;">
                <div class="x_title">
                  <h2>Conteúdo</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="animated flipInY col-lg-12 col-md-12 col-sm-12">
                    <div class="tile-stats col-lg-4 col-md-4 col-sm-6">
                      <div class="icon"><i class="fa fa-check-square-o"></i></div>
                      <div class="count"><?php echo "$tot_post" ?></div>
                      <h3>Total de Post</h3>
                      <!-- <p>Lorem ipsum psdea itgum rixt.</p> -->
                    </div>
                    <div class="tile-stats col-lg-4 col-md-4 col-sm-6">
                      <div class="icon"><i class="fa fa-check-square-o"></i></div>
                      <div class="count">10</div>
                      <h3>Total de Tags</h3>
                      <!-- <p>Lorem ipsum psdea itgum rixt.</p> -->
                    </div>
                    <div class="tile-stats col-lg-4 col-md-4 col-sm-6">
                      <div class="icon"><i class="fa fa-check-square-o"></i></div>
                      <div class="count"><?php echo $tot_email ?></div>
                      <h3>E-mails cadastrados</h3>
                    </div>
                  </div>
                  <br>
                  <div class="col-md-12 col-sm-12  profile_details">
                    <div class="well profile_view">
                      <div class="col-sm-12"> 
                        <h4 class="brief"><i>Último post</i></h4>
                        <div class="left col-md-7 col-sm-7">
                          <h2 id="titlePost">Título: <?php echo $titulo_post ?></h2>
                          <p id="resumoPost"><strong>Descrição: </strong><?php echo $descricao_post ?></p>
                          <p id="posAutor" class=""><strong>Autor:</strong> <?php echo $nome_administrador ?></p>
                          <p id="posAutor" class=""><strong>Data:</strong> <?php echo $data_post ?></p>
                        </div>
                        <div class="right col-md-5 col-sm-5 text-center">
                          <img src="images/funcionarios/<?php echo $foto_post ?>" alt="imagem do post" class="img-fluid" style="width: 250px;">
                        </div>
                      </div>
                      <div class=" profile-bottom text-start ">
                        <div class="col-sm-6 emphasis text-start">
                          <a href='#' title='Ver' class='btn btn-xs btn-primary'>Enviar para e-mails</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- começo post -->
                <div class="col-md-9">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Posts <small>-</small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        </li>
                      </ul>
                      <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                      <?php while ($resPosts = mysql_fetch_array($todos_post)) { ?>
                        <ul class="list-unstyled msg_list">
                          <li>
                            <a>
                              <span class="image">
                                <img src="images/funcionarios/<?php echo $resPosts['foto_post'] ?>" style="width: 100px;" class="img-fluid" alt="foto do post" />
                              </span>
                              <span>
                                <span>Título: <?php echo $resPosts['titulo_post'] ?> </span>
                                <span class="time"><?php  echo $resPosts['data_post'] ?></span>
                              </span>
                              <span class="message">
                                <?php echo $resPosts['descricao_post']; ?>
                              </span>
                              <p id="posAutor" class=""><strong>Autor:</strong> <?php echo $nome_administrador ?></p>
                            </a>
                          </li>
                        </ul>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- fim post -->
                </div>
                <div class="clearfix"></div>
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