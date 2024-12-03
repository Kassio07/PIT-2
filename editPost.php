<?php
//Conecta ao banco
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
    //Redireciona para o home
    header("Location: " . $base . "logar/");
}

//Verifica se enviou p formulário
if (isset($_POST['titulo_post'])) {
    //Recebe os dados
    $id_post = $_POST["id_post"];
    $titulo_post = $_POST["titulo_post"];
    $descricao_post = $_POST["descricao_post"];

    //Atualiza no banco

    $edit_post = mysql_query("UPDATE post SET 
    
    id_post = '" . $id_post . "',
    titulo_post = '" . $titulo_post . "',
    descricao_post = '" . $descricao_post . "'
    WHERE id_post = '" . $id_post . "'
    ");

    //Verifica se atualizou

    if ($edit_post) {
        //Se atualizou, mostra uma mensagem na tela de sucesso
        $msgAlert = "Post atualizado com sucesso";
    } else {
        //Se não atualizou, mostra uma mensagem de erro na tela
        $msgAlert = "Não foi possivél atualizar o post!" . mysql_error();
    }
}

//alteração da foto
if (isset($_FILES['foto_nova_post'])) {
    //Recebe os dados
    $id_post = $_POST["id_post"];
    $foto_antiga_post = $_POST["foto_antiga_post"];
    $foto_nova_post = $_FILES['foto_nova_post'];

    //faz o UPLOAD da foto
    $upload_fotoPost = copy($foto_nova_post['tmp_name'], 'images/funcionarios/' . $foto_nova_post['name']);
    //Verifica se fez o upload
    if (!$upload_fotoPost) {
        //mensagem de alerta na tela
        $msgAlert = "Não foi possível alter a foto!";
    } else {
        //Altera no banco
        $edit_foto_post = mysql_query("UPDATE post SET foto_post = '" . $foto_nova_post['name'] . "' WHERE id_post = '" . $id_post . "'");
        //verifica se atualizou
        if (!$edit_foto_post) {
            //Msg de alerta na tela
            $msgAlert = "Erro ao alterar a foto no banco de dados";
        } else {
            //Msg de sucesso na tela
            unlink('images/funcionarios/' . $foto_antiga_post);
            $msgAlert = "Foto alterada com sucesso!";
        }
    }
}


//Buscando dados do post
$buscar_post = mysql_query("SELECT * FROM post WHERE id_post = '" . $primeiro_parametro . "'");
//verifica se achou um post
if (mysql_num_rows($buscar_post) == 0) {
    header("Location:" . $base . "buscarPost/");
} else {
    //Percorre os resultados
    while ($resPost = mysql_fetch_array($buscar_post)) {
        //Separa os dados
        $titulo_post = $resPost['titulo_post'];
        $descricao_post = $resPost['descricao_post'];
        $foto_post = $resPost['foto_post'];
    }
}



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Tags do PICS -->
    <?php include("tags.php"); ?>
    <!-- Final das tags PICS -->

    <title>Editar Post | PICS</title>
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
                            <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Editar</div><br>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Edita seu Post:</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <!-- Inicio do Formulário -->
                                    <form enctype="multipart/form-data" id="demo-form2" action="" method="post" data-parsley-validate class="form-horizontal form-label-left">
                                        <input type="hidden" name="id_post" value="<?php echo $primeiro_parametro ?>">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-2 col-xs-12">
                                                <label class="control-label" for="titulo-post">Título Post:
                                                </label>
                                                <input type="text" value="<?php echo $titulo_post ?>" name="titulo_post" class="form-control">
                                            </div>
                                            <div class="col-md-12 col-sm-2 col-xs-12">
                                                <label class="control-label" for="titulo-curso">Descrição do Post
                                                </label>
                                                <textarea name="descricao_post" class="form-control" rows="7" editor="true"><?php echo $descricao_post ?></textarea>
                                            </div>

                                        </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <!-- <button class="btn btn-primary" type="reset">Cancelar</button> -->
                                        <button type="submit" class="btn btn-warning">Confirmar</button>
                                    </div>
                                </div>

                                </form>
                                <!-- Final do Formulário -->


                            </div>
                            <div class="x_panel">
                                <img src="images/funcionarios/<?php echo $foto_post ?>" width="200" alt="foto do post">
                                <!-- Inicio do formúlario -->
                                <form enctype="multipart/form-data" id="demo-form2" action="" method="post" data-parsley-validate class="form-horizontal form-label-left">
                                    <input type="hidden" name="id_post" value="<?php echo $primeiro_parametro; ?>">
                                    <input type="hidden" name="foto_antiga_post" value="<?php echo $foto_post; ?>">
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-2 col-xs-12">
                                            <label class="control-label" for="titulo-curso">Imagem do post:
                                            </label>
                                            <input type="file" name="foto_nova_post" value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- <button class="btn btn-primary" type="reset">Cancelar</button> -->
                                            <button type="submit" class="btn btn-warning">Salvar</button>
                                        </div>
                                    </div>
                                </form>
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