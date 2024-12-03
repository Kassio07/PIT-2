<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="images/funcionarios/<?php echo $_SESSION['administrador'] ['foto_administrador']; ?>" alt=""><?php echo $_SESSION['administrador']['nome_administrador'] ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="logoff/"><i class="fa fa-sign-out pull-right"></i> Sair</a></li>
          </ul>
        </li>
        <li role="presentation" class="dropdown">
          <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-envelope-o"></i> <span class="badge bg-green">1</span>
          </a>
          <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">            
              <li>
                <a href="#">
                  <span class="image"><img src="images/user.png" alt="UniPlena Educacional" /></span>
                  <span>
                    <span>Usuário</span>
                    <span class="time">01/01/2024 às 23:59:59</span>
                  </span>
                  <span class="message">
                    Mensagem de teste
                  </span>
                </a>
              </li>
          </ul>
        </li>
        <li role="presentation" class="dropdown">
          <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell-o"></i>            
            <span class="badge bg-green">1</span>
          </a>
          <ul id="menu1" style="overflow-y: auto; overflow-x: auto; max-height: 500px;" class="dropdown-menu list-unstyled msg_list" role="menu">
              <li>
                <a href="#">
                  <span class="image"><i class="fa fa-bell-o" style="font-size: 18px; padding: 5px"></i></span>
                  <span>
                    <span><b>Título</b></span>
                    <span class="time">
                      Há 10 minutos;
                    </span>
                  </span>
                  <span class="message">
                    Notificação de teste
                    <i class='fa fa-circle' style='color: #26B99A; float: right;'></i>
                  </span>
                </a>
              </li>
            
            <li class="nav-item">
              <div class="text-center">
                <a href="#" class="dropdown-item">
                  <strong>Ver todas as notificações</strong>
                </a>
              </div>
            </li>
            
            
          </ul>
        </li>

        

        <li style="display: none;" id="pararnevar-tema" role="presentation" class="dropdown">
          <a title="Parar de nevar" href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-asterisk"></i>
          </a>
        </li>



      </ul>
    </nav>
  </div>
</div>