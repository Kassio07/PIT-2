<?php 
//Conecta no banco
include("conectar.php"); 
//Vendedor de Acompanhamento
$id_funcionario = ($primeiro_parametro == "" || $primeiro_parametro == '0') ? "1" : $primeiro_parametro;
$busca_funcionario = mysql_query("SELECT * FROM funcionarios WHERE id_funcionario='$id_funcionario'");
while ($resFunc = mysql_fetch_array($busca_funcionario)) {
  //Separa os dados
  $nome_funcionario = $resFunc['nome_funcionario'];
  $telefone_funcionario = $resFunc['telefone_funcionario'];
  $foto_funcionario = $resFunc['foto_funcionario'];
}
?>
<!doctype html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><html lang="en" class="no-js"> <![endif]-->
<html lang="en">
<head>
  <!-- Define Charset -->
  <meta charset="utf-8">
  <!-- Basic -->
  <title>Mensagem Enviada - UniPlena Educacional</title>
	<!-- Tags do Site-->
   <?php include("tags.php"); ?>


   <meta name="googlebot" content="noindex">
   <!-- Bibliotecas do Template-->
   <?php include("bibliotecas.php"); ?>
   
   <!-- Evento de Conversão -->
    <script>
      gtag('event', 'conversion', {'send_to': 'AW-979861334/uLHgCNzD-ZIBENb-ndMD'});
    </script>
   <!-- Fim do Evento de Conversão -->
   
</head>
<body>
  <!-- Container -->
  <div id="container">
    <!-- Start Header -->
    <!-- Topo do Site -->
	<?php include("topo.php"); ?>
    <!-- Final do Topo do Site -->
    <!-- Start Content -->
    <div id="content">
      <div class="container">
        <div class="page-content">
          <div class="page-content">
          <div class="error-page">
            <img class="img-responsive" src='../PICS/images/funcionarios/<?php echo $foto_funcionario ?>' /><br>
            <h3>Mensagem Recebida! <i class="fa fa-thumbs-o-up "></i></h3><br>
            <p>Olá, eu me chamo <b><?php echo $nome_funcionario ?></b> e faço parte do time de consultores educacionais da Uniplena Educacional! Já recebi a sua mensagem e entrarei em contato o mais rápido possível, caso queira, você pode me chamar no WhatsApp clicando no botão abaixo:</p>
            <div class="text-center"><a href="https://wa.me/55<?php echo $pics->soNumero($telefone_funcionario); ?>" target="_blank" class="btn-system btn-small">Enviar mensagem no WhatsApp <?php echo $telefone_funcionario ?> </a></div>
          </div>
        </div>
        </div>
      </div>
    </div>
    <!-- End Content -->


    <!-- Start Footer -->
    <?php include("rodape.php") ?>
    <!-- End Footer -->
  </div>
  <!-- End Container -->
    <!-- Go To Top Link -->
  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
  <script type="text/javascript" src="js/script.js"></script>
</body>

</html>