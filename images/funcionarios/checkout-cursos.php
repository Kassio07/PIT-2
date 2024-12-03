<?php 
//Conecta no Banco
include("conectar.php");
//Este mês
$data_inicial = date("Y-m-01");
$data_final = date("Y-m-t");
$hoje = date('Y-m-d');

//Link de vendedor
if($primeiro_parametro == ""){$id_funcionario = '1';}
else{$id_funcionario = $primeiro_parametro;}
//Busca o funcionario ativo
$busca_funcionario = mysql_query("SELECT * FROM funcionarios WHERE id_funcionario='$id_funcionario' AND ativo_funcionario='1' AND (nivel_funcionario='3' OR nivel_funcionario='4' OR nivel_funcionario='5' OR nivel_funcionario='6')");
if(mysql_num_rows($busca_funcionario) > 0){
  while ($resF = mysql_fetch_array($busca_funcionario)) {
    $nome_funcionario = $resF['nome_funcionario'];
    $id_funcionario = $resF['id_funcionario'];
    $telefone_funcionario = $resF['telefone_funcionario'];
    $foto_funcionario = $resF['foto_funcionario'];
  }    
}
else{
  $id_funcionario = '1';
  $nome_funcionario = 'Uniplena Educacional';
  $telefone_funcionario = "(11) 94510-9006";
  $foto_funcionario = 'uniplena-educacional.png';
}
?>
<!doctype html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><html lang="en" class="no-js"> <![endif]-->
<html lang="en">
<head>
  <!-- Basic -->
  <title>Matrícula Online - UniPlena Educacional</title>
	<!-- Tags do Site-->
   <?php include("tags.php"); ?>

   <!-- Bibliotecas do Template-->
   <?php include("bibliotecas.php"); ?>
</head>
<body>
  <!-- Container -->
  <div id="container">
    <!-- Start Content -->
    <div id="content">
      <div class="container" style="background-color: : #9ea4ac">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-md-offset-2" style="border: solid 2px #f7f9fa; border-radius: 2%; background-color: #FFF;"><br>
            <!-- LOGO -->
            <div class="col-md-12" style="text-align: center;">  
              <img src="images/logo-uniplena-educacional.png" style="max-width: 200px;"><br>
              <br><br>  
            </div>

            <!-- Notificações -->
            <div class="col-md-12 col-sm-12">
              <div class="x_content bs-example-popovers"><?php if(isset($msgAlert)){echo $msgAlert.'<br>';}  ?></div>
            </div>

            <!-- Dados Pessoais -->
            <div class="col-md-12 col-sm-12">
              
              <div class="form-group">
                <h2>Matrícula Online</h2>
                <p>Escolha abaixo em qual curso você deseja realizar a sua matrícula:</p>
              </div>

              <div class="pricing-tables">
                <div class="pricing-table" style="text-align: left;">
                    <!-- Categoria -->
                    <?php
                    //Busca as categorias 
                    $busca_categoria = mysql_query("SELECT * FROM categorias_cursos WHERE ativo_categoria='1'");
                    while ($resCat = mysql_fetch_array($busca_categoria)) { ?>                      
                    <div class="plan-name" id="gradeTitulo" style="padding: 15px 15px; cursor: pointer;"><h3><?php echo $resCat['titulo_categoria']; ?> <small>(ver cursos)</small></h3></div>
                    <div class="plan-list" id="gradeConteudo" style="display: none">
                      <ul style="padding: 15px 15px;">
                        <?php
                        //Busca as disciplians dessa categoria
                        $busca_disciplinas = mysql_query("SELECT * FROM disciplinas_cursos INNER JOIN cursos ON disciplinas_cursos.id_curso=cursos.id_curso AND disciplinas_cursos.ativo_disciplina='1' AND cursos.ativo_curso='1' WHERE cursos.id_categoria='".$resCat['id_categoria']."' ORDER BY cursos.id_categoria DESC");
                        while ($resDis = mysql_fetch_array($busca_disciplinas)) {?>
                        <li style="font-size: 16px; padding: 12px;"><a style="color: #666" href='checkout/<?php echo $resDis['id_curso']; ?>/<?php echo $id_funcionario; ?>/<?php echo $resDis['id_disciplina']; ?>'>
                          <?php echo $resDis['titulo_disciplina'].' - '.$resDis['titulo_curso'] ?> - <?php echo $resDis['duracao_curso'] ?> meses</a></li>
                        <?php } ?>                          
                      </ul>
                    </div>
                    <?php } ?>
                    <!-- Fim Categoria-->
                </div>
              </div>

              <div class="form-group"><br>
                <img src='../PICS/images/funcionarios/<?php echo $foto_funcionario ?>' class='img-responsive' />
                <!-- End Contact Form -->
                <p style="text-align: center;">Precisa de ajuda? Entre em contato conosco! <br> <a href="https://wa.me/55<?php echo $pics->soNumero($telefone_funcionario); ?>" target="_blank" style="color: #666; text-decoration: underline;"><?php echo $telefone_funcionario; ?></a> / <a href="mailto: atendimento@uniplena.com.br" target="_blank" style="color: #666; text-decoration: underline;">atendimento@uniplena.com.br</a><br> Uniplena Educacional © 2024 - Todos os direitos reservados</p>
                <br>
              </div>
            
          </div>
        </div>

      </div>
    </div>
      </div>
    </div>
    <!-- End content -->

  </div>
  <!-- End Container -->
    <!-- Go To Top Link -->
  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
  <script type="text/javascript" src="js/script.js"></script>
</body>

</html>