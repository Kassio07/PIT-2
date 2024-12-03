<?php
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
    //Redireciona para o home
    header("Location: " . $base . "logar/");
}

//Verifica se enviou o formulário
if (isset($_POST['email_letter'])) {
    //Recebe os dados
    $id_letter = $_POST['id_letter'];
    $email_letter = $_POST['email_letter'];
    //Atualiza no banco
    $edit_email = mysql_query("UPDATE newsletter SET 
    
    id_letter = '" . $id_letter . "',
    email_letter = '" . $email_letter . "'
    WHERE id_letter = '" . $id_letter . "'
    ");

    //Verifica se atualizou no banco
    if ($edit_email) {
        $msgAlert = "E-mail atualizado com sucesso";
    } else {
        $msgAlert = "Não foi possível atualizar o e-mail";
    }
}

// Busca dados do e-mail
$buscar_email = mysql_query("SELECT * FROM newsletter WHERE id_letter = '" . $primeiro_parametro . "'");
//verifica se achou um e-mail
if (mysql_num_rows($buscar_email) == 0) {
    header("Location:" . $base . "cadastro-email/");
} else {
    //percorre os resultados
    while ($resEmail = mysql_fetch_array($buscar_email)) {
        $email_letter = $resEmail['email_letter'];
        $id_letter = $resEmail['id_letter'];
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
                            <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Editar e-mail</div><br>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Edita e-mail:</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-6 col-sm-6 ">
                                    <div class="container mt-5">
                                        <table id="datatable-fixed-header" role="grid" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>E-mail</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <form action="" method="post">
                                                        <input type="hidden" name="id_letter" value="<?php echo $primeiro_parametro ?>">
                                                        <td>
                                                            <input type="email" name="email_letter" class="form-control" value="<?php echo $email_letter ?>">
                                                        </td>
                                                        <td>
                                                            <button type="submit" class="btn btn-sm btn-success">Salvar</button>
                                                        </td>
                                                    </form>
                                                </tr>


                                            </tbody>
                                        </table>
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