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
                                <div class="pics-map-navigation"><b><a href="home/">Home</a></b> / Tags</div><br>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="container mt-5">
                        <h3>Gerenciar Tags</h3>
                        <!-- Campo para adicionar nova tag -->
                        <form id="novaTagForm" class="mb-3" action="" method="post">
                            <div class="input-group">
                                <input type="text" class="form-control" id="novaTag" placeholder="Digite uma nova tag" required>
                                <button type="button" class="btn btn-primary" onclick="adicionarTag()">Adicionar Tag</button>
                            </div>
                        </form>

                        <!-- Tabela de tags -->
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Tag</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tagsLista">
                                <!-- Exemplo de uma tag cadastrada -->
                                <tr>
                                    <td>
                                        <!-- Campo de tag editável -->
                                        <input type="text" class="form-control" value="Tecnologia" readonly>
                                    </td>
                                    <td>
                                        <!-- Botões de ação para Editar e Excluir -->
                                        <button class="btn btn-sm btn-warning" onclick="habilitarEdicaoTag(this)">Editar</button>
                                        <button class="btn btn-sm btn-success d-none" onclick="salvarTag(this)">Salvar</button>
                                        <button class="btn btn-sm btn-danger" onclick="excluirTag(this)">Excluir</button>
                                    </td>
                                </tr>
                                <!-- Mais tags podem ser listadas da mesma forma -->
                            </tbody>
                        </table> 
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