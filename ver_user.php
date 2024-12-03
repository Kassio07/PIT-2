<?php
include("conectar.php");

// Verificando se o usuário está logado
if(!isset($_SESSION['administrador'])){
    //Redireciona para o home
    header("Location: ".$base."logar/");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Tags do PICS -->
    <?php include("tags.php"); ?>
    <!-- Final das tags PICS -->

    <title>VER | PICS</title>
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
                                <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Usuário</div><br>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>

                <div class="col-md-3">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Usuário</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <ul class="list-unstyled">
                                <li>
                                    <a>
                                        <span class="img-fluid">
                                            <img src="images/img.jpg" alt="img" />
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <p class="name_user">Nome:</p>
                            <p class="login_user">Login:</p>
                            <p class="cpf_user">CPF:</p>
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