<?php
//Verifica se chegou alguma página por GET se não a página é a Home
$pagina = (isset($_GET['pag'])) ? $_GET['pag'] : "home";
//Numero de Barras
$barras = substr_count($pagina,"/");
//Verifica se tem alguma barra
if($barras > 0){
	//Explode pela barra a página
	$pagina = explode("/",$pagina);
	//Página a ser inclusa
	$incluir = $pagina[0];
	//Primeiro parametro
	$primeiro_parametro = $pagina[1];
	//Segundo parametro
	$segundo_parametro = ($barras > 1) ? $pagina[2] : "";
	//Terceiro parametro
	$terceiro_parametro = ($barras > 2) ? $pagina[3] : "";
	//Quarto parametro
	$quarto_parametro = ($barras > 3) ? $pagina[4] : "";
	}
//Se não existir barra
else{
	//Página padrão é o Home 
	$incluir = $pagina;
	}
//Inclui esta página
@$include = include($incluir.".php");
if(!$include){
	//Inclui a página de Erro
	include("pagina-nao-encontrada.php");
	}
?>

