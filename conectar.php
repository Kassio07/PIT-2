<?php
//Conexao
$host = "mysql745.umbler.com"; // Servidor
$user = "kassio-php"; // Usuário
$pass = "?,2Qi/n9o6Cs9"; // Senha
$banco = "kassio"; // Nome do Banco
$conex = mysql_connect($host, $user, $pass);
$bd = mysql_select_db($banco);

//Base do Site
$base = "https://www.uniplenagraduacao.com.br/php-kassio/";

//Inicio de sessão
session_start();
?>