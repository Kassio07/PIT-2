<?php
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
    //Se não estiver redireciona para o home
    header("Location: " . $base . "logar/");
}

//Verifica se enviou o formulário
if (isset($_POST['email_letter'])) {
    //Receba os dados
    $id_letter = $_POST['id_letter'];
    $email_letter = $_POST['email_letter'];
    //Faz a busca no banco
    $buscar_email = mysql_query("SELECT * FROM newsletter WHERE email_letter = '" . $email_letter . "'");
    //verifica se esse e-mail já existe no banco
    if (mysql_num_rows($buscar_email) > 0) {
        //Mensagem na tela que já existe
        $msgAlert = "E-mail já cadastrado!";
    } else {
        //cadastra no banco
        $inserir_email = mysql_query("INSERT newsletter SET 
        email_letter = '" . $email_letter . "'
        ");

        //Verifica se cadastrou
        if ($inserir_email) {
            //Mensagem de sucesso
            $msgAlert = "E-mail cadastrado com sucesso!";
        } else {
            //mensagem de erro na tela
            $msgAlert = "Não foi possível cadastrar o e-mail" . mysql_error();
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

    <title>E-mails | PICS</title>
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
                            <h3>E-mails Cadastrados</h3>
                            <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Cadastro E-mail</div><br>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Cadastre um e-mail:</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-6 col-sm-6 ">
                                    <div class="container mt-5">
                                        <div style="margin: 20px 0;">

                                            <form action="cadastro-email" method="post">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <input type="email" name="email_letter" placeholder="Cadastre um e-mail" style="width: 250px; padding: 5px;" required>
                                                        </td>
                                                        <td style="padding-left: 10px;">
                                                            <button class="btn btn-sm btn-success">Cadastrar</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                    </div>

                                </div>
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