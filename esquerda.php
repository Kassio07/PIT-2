<div class="left_col scroll-view">
  <div class="navbar nav_title" style="border: 0;">
    <a href="home/" class="site_title">
      <img src="images/logo1.png" class="logo-completo" width="54%" class="img-responsive">
      <img src="images/icone4.png" class="logo-incompleto" width="75%" class="img-responsive">
    </a>
  </div>

  <div class="clearfix"></div>

  <!-- menu profile quick info -->
  <div class="profile clearfix">
    <div class="profile_pic">
      <img src="images/funcionarios/<?php echo $_SESSION['administrador']['foto_administrador']; ?>" alt="foto do usuário" class="img-circle profile_img">
    </div>
    <div class="profile_info">
      <span>Bem Vindo(a)</span>
      <h2><?php echo $_SESSION['administrador']['nome_administrador'] ?></h2>
    </div>
    <div class="clearfix"></div>   
  </div>
  <!-- /menu profile quick info -->

  <br />

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <ul class="nav side-menu">
        <li><a href="home/"><i class="fa fa-home"></i> Home</span></a></li>        
        <li><a><i class="fa fa-pencil-square"></i> Post <span class="fa fa-chevron-down"></span></a>
          <ul id="subMenuEsquerda" class="nav child_menu">
            <li><a href="criarPost/">Criar um novo Post</a></li>
            <li><a href="buscarPost/">Buscar um Post</a></li> 
            <li><a href="tag-post/">Tags</a></li> 
          </ul>
        </li>   
        <li><a><i class="fa fa-envelope"></i> E-mail <span class="fa fa-chevron-down"></span></a>
          <ul id="subMenuEsquerda" class="nav child_menu  ">
            <li><a href="cadastro-email/">Cadastrar E-mail</a></li>
            <li><a href="buscar-email/">Buscar E-mail</a></li>
          </ul>
        </li>   
        <li><a><i class="fa fa-user"></i> Usuário <span class="fa fa-chevron-down"></span></a>
          <ul id="subMenuEsquerda" class="nav child_menu">
            <li><a href="adicionar/">Adicionar</a></li>
            <li><a href="buscar/">Buscar</a></li> 
          </ul>
        </li>   
        <li><a href="logar/"><i class="fa fa-sign-in"></i> Login</span></a></li>              
      </ul>
    </div>
  </div>
  <!-- /sidebar menu -->

  
  <div class="sidebar-footer hidden-small">
    <div class="tema"></div>
  </div>
   
</div>