<?php
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
    //Se não estiver redireciona para o home
    header("Location: " . $base . "logar/");
}


//Exclusão de e-mail
if (isset($_POST['id_email_dell'])) {
    //Receba os dados
    $id_email_dell = $_POST['id_email_dell'];
    //Exclui do banco
    $email_dell = mysql_query("DELETE FROM newsletter WHERE id_letter = '" . $id_email_dell . "'");
    //Verifica se excluiu
    if ($email_dell) {
        //Mensagem de sucesso na tela
        $msgAlert = "E-mail excluído com sucesso";
    } else {
        //Mensagem de erro na tela
        $msgAlert = "Não foi possível excluir!" . mysql_error();
    }
}


//Verifica se enviou o formulário
if (isset($_POST['parametro_busca_email'])) {
    //Receba os dados
    $parametro_busca = $_POST['parametro_busca_email'];
    $valor_busca = $_POST['valor_busca_email'];
    //Inicia a string que será usada na Query
    $strQuery = "SELECT * FROM newsletter WHERE ";
    //Verifica se enviou um parâmetro
    if ($parametro_busca != "") {
        if ($parametro_busca == "email_letter") {
            //Acrescenta na query
            $strQuery .= "email_letter LIKE('%" . $valor_busca . "%')";
        } else {
            $strQuery .= $parametro_busca . " = '" . $valor_busca . "'";
        }
        //Caso não use parâmetro
    } else {
        //Acrescenta na Query
        $strQuery .= "1=1";
    }
    //Finaliza o Array
    $strQuery .= "ORDER BY id_letter DESC";
    //Executa a query
    $buscar_email = mysql_query($strQuery);
} else {
    //Mostre os e-mails
    $buscar_email = mysql_query("SELECT * FROM newsletter ORDER BY id_letter DESC LIMIT 5");
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
                            <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Buscar</div><br>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>E-mails Cadastrados:</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-12 col-sm-12 ">
                                    <div class="container mt-5">
                                        <div style="margin: 20px 0;">
                                            <div class="x-content">
                                                <form enctype="multipart/form-data" id="formBusca" action="" method="post" data-parsley-validate class="form-horizontal form-label-left">
                                                    <div class="form-group">
                                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                                            <label class="control-label" for="titulo-curso">Parametro:
                                                            </label>
                                                            <select id="parametroAlunos" name="parametro_busca_email" class="form-control">
                                                                <option></option>
                                                                <option value="email_letter">E-mail</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                                            <label class="control-label" for="titulo-curso">Valor:
                                                            </label>
                                                            <input value="" name="valor_busca_email" type="text" class="form-control" />
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
                                        <!-- Resultado da busca -->
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="x-panel">
                                                    <div class="x-content">
                                                        <table id="datatable-fixed-header" role="grid" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>E-mail</th>
                                                                    <th>Ações</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Imprime o resultado -->
                                                                <?php
                                                                while ($resEmail = mysql_fetch_array($buscar_email)) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="email" class="form-control" style="width: 100%;" readonly value="<?php echo $resEmail['email_letter']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <div style="display:flex;">
                                                                                <a href="edit-email/<?php echo $resEmail['id_letter'] ?>" title="Editar" class="btn btn-sm btn-success">Editar</a>
                                                                                <form action="" method="post">
                                                                                    <input type="hidden" name="id_email_dell" value="<?php echo $resEmail['id_letter']; ?>" class="btn btn-sm btn-danger">
                                                                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
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