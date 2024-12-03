<?php
//Conectar no banco
include('conectar.php');
//destroi a sessão
session_destroy();
//Redireciona
header("Location: ".$base."logar/");
?>