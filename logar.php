<?php
//Conexão Banco
include("conectar.php");

//Verificando se o usuaário está logado / Existe uma variavél $_SESSION administrador ? Se SIM, direciona para o home.
if(isset($_SESSION["administrador"])){
  //Redireciona para o home
  header("Location:" .$base."home/");
}

//Verifica se recebeu o login
if(isset($_POST['login_administrador'])){
  //Separa os dados
  $login_administrador = $_POST['login_administrador']; 
  $senha_administrador = $_POST['senha_administrador'];
  //Busca usuario
  $busca_administrador = mysql_query("SELECT * FROM administrador WHERE login_administrador='$login_administrador' AND senha_administrador='$senha_administrador'");
  //Caso não tenha encontrado
  if(mysql_num_rows($busca_administrador) == 0){
    $msgAlert = "Login ou senha invalidos!";
  }else{
    //Separa dados usuarios
    while($resAdministrador = mysql_fetch_array($busca_administrador)){
      //Cria as variáveis com a sessão
      $_SESSION['administrador']['id_administrador'] = $resAdministrador['id_administrador'];
      $_SESSION['administrador']['nome_administrador'] = $resAdministrador['nome_administrador'];
      $_SESSION['administrador']['foto_administrador'] = $resAdministrador['foto_administrador'];
    }
    
    header("Location: ".$base."home/");
  }
}
?>
<!DOCTYPE html>
<html lang="pt">
  <head>
    <?php include("tags.php"); ?>
    <title>Logar | PICS</title>

    <?php include("css-include.php") ?>
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="logar/" method="post">
              <h1>PICS</h1>
              <div>
                <input type="text" class="form-control" name="login_administrador" placeholder="Digite seu Login" />
              </div>
              <div>
                <input type="password" class="form-control" name="senha_administrador" placeholder="Digite seu Senha"/>
              </div>
              <div>
                <input type="submit" class="btn btn-dark submit pull-right" value="Entrar no PICS">
                <p style="color: red;"><b><?php if(isset($msgAlert)){echo $msgAlert;}?></b></p>
              </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div>
                  <img class="img-responsive" src="images/logo1.png" /><br>
                  <p>Não é funcionário da Uniplena Educacional?
                  <a href="http://www.uniplena.com.br" class="to_register"> Volte para o Site!</p>
                </div>
              </div>
            </form>
          </section>
        </div>

       
      </div>
    </div>
  </body>
</html>
