<?php
//Conecta no banco
include("conectar.php");

// Verificando se o usuário está logado
if (!isset($_SESSION['administrador'])) {
    //Redireciona para o home  
    header("Location: " . $base . "logar/");
}



//Verifica se enviou o fomrulário 
if (isset($_POST['titulo_post'])) {
    //Receba os dados
    $titulo_post = $_POST['titulo_post'];
    $descricao_post = $_POST['descricao_Post'];
    $foto_post = $_FILES['foto_post'];
    $id_administrador = $_SESSION['administrador']['id_administrador'];
      
    //Data atual 
    $data_post = date('Y-m-d H:i:s', time());   
    //Verifica se existe um post com o mesmo id
    $buscar_post = mysql_query("SELECT * FROM post WHERE titulo_post = '" . $titulo_post . "'");
    //Se encontrar um post com o mesmo titulo
    if (mysql_num_rows($buscar_post) >= 1) {
        //Mensagem de erro na tela
        $msgAlert = "Post já existe!";
    } else {
        //faz upload da foto
        $upload_fotoPost = copy($foto_post['tmp_name'], 'images/funcionarios/' . $foto_post['name']);
        //Verifica se fez upload
        if (!$upload_fotoPost) {
            $msgAlert = "Não foi poassível fazer upload da foto";
        } else {
            //Cadastra no banco  
            $inserir_post = mysql_query("INSERT post SET 
        titulo_post='" . $titulo_post . "',
        data_post = '".$data_post."',
        foto_post = '" . $foto_post['name'] . "',
        descricao_post = '" . $descricao_post . "',
        id_administrador = '" . $id_administrador . "'
        ");
            // verifica se cadastrou
            if ($inserir_post) {
                //Separa o ID do post
                $id_post = mysql_insert_id($conex);

                $msgAlert = "Post criado com sucesso code:" . $id_post;
            } else {
                $msgAlert = "Erro ao inserir post" . mysql_error();
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

    <title>Criar Post | PICS</title>
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
                            <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Criar post</div><br>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Crie seu post:</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <!-- Inicio do Formulário -->
                                    <form enctype="multipart/form-data" id="demo-form2" action="criarPost" method="post" data-parsley-validate class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-2 col-xs-12">
                                                <label class="control-label" for="titulo-post">Título Post:<span class="required">*</span>
                                                </label>
                                                <input type="text" value="" required="required" name="titulo_post" id="tituloPost" class="form-control">
                                            </div>
                                            <div class="col-md-12 col-sm-2 col-xs-12">
                                                <label class="control-label" for="titulo-curso">Descrição do Post <span class="required">*</span>
                                                </label>
                                                <textarea name="descricao_Post" id="descricaoPost" class="form-control" rows="7" editor="true">
                                                </textarea>
                                            </div>
                                            <!-- 
                                            <div class="col-md-12 col-sm-2 col-xs-12">
                                                <label class="control-label" for="titulo-curso">Data do Post:
                                                </label>
                                                <input type="date" name="data_post" id="dataPost" value="" class="form-control" required>
                                            </div> -->
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-2 col-xs-12">
                                                    <label class="control-label" for="titulo-curso">Imagem:
                                                    </label>
                                                    <input type="file" name="foto_post" id="" value="" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <!-- <button class="btn btn-primary" type="reset">Cancelar</button> -->
                                                <button type="submit" class="btn btn-success">Criar Post</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

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