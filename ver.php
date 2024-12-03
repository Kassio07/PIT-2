<?php
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
  //Redireciona para o home
  header("Location: " . $base . "logar/");
}


//Buscar posts
$buscar_post = mysql_query("
SELECT
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
WHERE
    post.id_post = " . $primeiro_parametro);

// Verifica se encontrou
if (mysql_num_rows($buscar_post) == 0) {
  //Redireciona
  header("Location: " . $base . "buscarPost/");
} else {
  //Separa os dados
  while ($resPost = mysql_fetch_array($buscar_post)) {
    //Dados do post
    $id_post = $resPost['id_post'];
    $titulo_post = $resPost['titulo_post'];
    $data_post = $resPost['data_post'];
    $foto_post = $resPost['foto_post'];
    $descricao_post = $resPost['descricao_post'];
    $id_administrador = $resPost['id_administrador'];
    $nome_administrador = $resPost['nome_administrador'];
    
  }
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Tags do PICS -->
  <?php include("tags.php"); ?>
  <!-- Final das tags PICS -->

  <title>Ver post | PICS</title>
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
          <div class="page-title">
            <div class="title_left">
              <div class="title_left">
                <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Ver</div><br>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>

        <div class="col-md-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Post</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <ul class="list-unstyled msg_list">
                <li>
                  <a>
                    <span class="image">
                      <img src="images/funcionarios/<?php echo $foto_post ?>" class="img-fluid" style="width:250px;" alt="Imagem do post" />
                    </span>
                    <span>
                      <span style="font-size: 2rem;"> <?php echo $titulo_post; ?></span>
                    </span>
                    <span class="message" style="margin: 10px 0; font-size: 1.4rem;">
                      <?php echo $descricao_post ?>;
                    </span>
                    <span>Autor: <?php echo $nome_administrador ?></span>
                  </a>
                </li>
              </ul>
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