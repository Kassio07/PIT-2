<?php 
/* define o limitador de cache para 'private' 
session_cache_limiter('private');
$cache_limiter = session_cache_limiter();

/* define o prazo do cache em 30 minutos
session_cache_expire(30);
$cache_expire = session_cache_expire();
*/
session_start();
include("conectar.php");

//Verifica se o usuario esta logado
if(!isset($_SESSION['aluno'])){header("Location: ".$base."logar/");}

//Separa o ID da matrícula
$id_matricula = $_SESSION['aluno']['id_matricula'];
$id_aluno = $_SESSION['aluno']['id_aluno'];

//Verifica se está em lote
$busca_lote_edicao = mysql_query("SELECT * FROM solicitacoes_certificacao WHERE id_matricula='$id_matricula' AND status_solicitacao_certificacao != '0' AND id_lote !='0'");
if(mysql_num_rows($busca_lote_edicao) >= 1){header("Location: ".$base."home/");}

//Formulário enviado
if(isset($_POST['nome'])){
  //Verifica se existe a solicitação
  $busca_solicitacao = mysql_query("SELECT * FROM matriculas WHERE id_matricula='$id_matricula' AND id_aluno='$id_aluno'");
  if(mysql_num_rows($busca_solicitacao) == 0){header("Location: ".$base."escolher-matricula/");}
  else{
    //Atualizar os dados do aluno
    $upAluno = mysql_query("UPDATE alunos SET 
      nome_aluno='".$_POST['nome']."', 
      email_aluno='".$_POST['email']."', 
      celular_aluno='".$_POST['celular']."', 
      sexo_aluno='".$_POST['sexo']."',
      nascimento_aluno='".$pics->dataInserir($_POST['data_nascimento'])."', 
      estado_nascimento_aluno='".$_POST['estado_nascimento']."', 
      cidade_nascimento_aluno='".addslashes($_POST['cidade_nascimento'])."', 
      rg_aluno='".$_POST['rg']."',
      data_rg_aluno='".$pics->dataInserir($_POST['data_rg'])."',
      orgao_rg_aluno='".$_POST['orgao_rg']."',
      estado_rg_aluno='".$_POST['estado_rg']."',
      cep_aluno='".$_POST['cep']."',
      endereco_aluno='".$_POST['endereco']."',
      numero_aluno='".$_POST['numero_endereco']."',
      complemento_aluno='".$_POST['complemento']."',
      bairro_aluno='".$_POST['bairro']."',
      cidade_aluno='".$_POST['cidade']."',
      estado_aluno='".$_POST['estado']."'
      WHERE id_aluno='$id_aluno'");
    
    //Atualiza os dados da matrícula
    $upMatricula = mysql_query("UPDATE matriculas SET 
      faculdade_matricula='".$_POST['faculdade']."',
      tipo_graduacao_matricula='".$_POST['tipo_graduacao']."',
      curso_formacao_matricula='".$_POST['curso_formacao']."',
      colacao_grau_matricula='".$pics->dataInserir($_POST['data_colacao_matricula'])."',
      inscricao_online='1'
      WHERE id_matricula='$id_matricula'");

    //Verifica se inseriu
    if($upAluno && $upMatricula){$msgAlert = $pics->alertMSG(1, "Dados pessoais atualizados com sucesso, <a href='".$base."home/'>clique aqui</a> para acessar a sua matrícula.");}
    else{$msgAlert = $pics->alertMSG(2, "Erro ao atualizar os seus dados pessoais, entre em contato com a coordenação!".mysql_error());}
  }
}

//Busca os dados do aluno / matrícula
$busca_aluno = mysql_query("SELECT * FROM matriculas INNER JOIN alunos ON matriculas.id_aluno=alunos.id_aluno WHERE matriculas.id_matricula='$id_matricula' AND alunos.id_aluno='$id_aluno'");
//Separa os dados
while($resAluno = mysql_fetch_array($busca_aluno)){$aluno = $resAluno;}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Tags do PICS -->
    <?php include("tags.php"); ?>
    <!-- Final das tags PICS -->

    <title>Atualizar dados pessoais | PICS</title>
    <!-- Inserindo CSS -->
    <?php include("css-include.php");?>
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
        <div class="x_content bs-example-popovers"><?php if(isset($msgAlert)){echo $msgAlert;}  ?></div>

        <!-- final das Notifiações -->        
          <div class="">
            
            <div class="row">
              
              <div class="col-md-12 col-sm-12">
                <div class="bs-example" data-example-id="simple-jumbotron">
                  <div class="jumbotron">
                    <h3>Dados Pessoais ( <i class="fa fa-user"></i> )</h3>
                    <p style="font-size: 16px; color: #364b5f;">Mantenha sempre os seus dados pessoais atualizados para que não haja erros em seu processo de certificação.</p>
                  </div>
                </div>
              </div>

              </div>
            </div>

            

            <form enctype="multipart/form-data" id="demo-form2" action="" method="post"  data-parsley-validate class="form-horizontal form-label-left">
              <div id="htmlCep"></div>
              <!-- Dados Pessoais -->
              <div class="row">              
                <div class="col-md-12 col-sm-12">
                  <div class="x_panel">
                    <div class="x_content">                      
                      <div class="x_title"><h2>Dados Pessoais</h2><div class="clearfix"></div></div>
                      <div class="form-group">
                        <div class="col-md-12 col-sm-2 col-xs-12">
                          <label class="control-label" for="titulo-curso">Nome Completo: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['nome_aluno']; ?>" required="required" name="nome"  class="form-control">
                        </div>                              
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">E-mail: <span class="required">*</span>
                          </label>
                          <input type="email" value="<?php echo $aluno['email_aluno']; ?>" required="required" name="email"  class="form-control">
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <label class="control-label" for="valor_mensal">WhatsApp <span class="required">*</span>
                          </label>
                          <input type="text" data-inputmask="'mask': '(99) 9999.9999[9]'" value="<?php echo $aluno['celular_aluno']; ?>" required="required" name="celular" class="form-control col-md-6 col-xs-6">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <label class="control-label" for="valor_matricula">Sexo <span class="required">*</span>
                          </label>
                          <select name="sexo" required="required" class="form-control">
                            <option value=""></option>
                            <option <?php if($aluno['sexo_aluno'] == '1'){echo "selected='selected'";}; ?> value="1">Masculino</option>
                            <option <?php if($aluno['sexo_aluno'] == '2'){echo "selected='selected'";}; ?> value="2">Feminino</option>                                  
                          </select>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Data de Nascimento: <span class="required">*</span>
                          </label>
                          <input type="text" data-inputmask="'mask': '99/99/9999'" value="<?php echo $pics->dataMostrar($aluno['nascimento_aluno']); ?>" required="required" name="data_nascimento"  class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-2 col-xs-12">
                          <label class="control-label" for="qtde_mensais">Estado de Nascimento: <span class="required">*</span>
                          </label>
                            <input type="text" value="<?php echo $aluno['estado_nascimento_aluno']; ?>" name="estado_nascimento" data-inputmask="'mask': 'AA'" required="required" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-2 col-xs-12">
                          <label class="control-label" for="valor_mensal">Cidade de Nascimento: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['cidade_nascimento_aluno']; ?>" required="required" name="cidade_nascimento" class="form-control col-md-6 col-xs-6">
                        </div>                                            
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Documento -->
              <div class="row">              
                <div class="col-md-12 col-sm-12">
                  <div class="x_panel">
                    <div class="x_content">                      
                      <div class="x_title"><h2>Documento</h2><div class="clearfix"></div></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-2 col-xs-12">
                          <label class="control-label" for="titulo-curso">CPF: <span class="required">*</span>
                          </label>
                          <input disabled="disabled" type="text" value="<?php echo $aluno['cpf_aluno']; ?>" name="cpf" data-inputmask="'mask': '999.999.999-99'" class="form-control">
                        </div>                              
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">RG: <span class="required">*</span>
                          </label>
                          <input type="text" required="required" value="<?php echo $aluno['rg_aluno']; ?>" name="rg"  class="form-control">
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-12">
                          <label class="control-label" for="qtde_mensais">Data de Expedição (RG): <span class="required">*</span>
                          </label>
                            <input type="text" data-inputmask="'mask': '99/99/9999'" value="<?php echo $pics->dataMostrar($aluno['data_rg_aluno']); ?>" required="required" name="data_rg" class="form-control">
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-12">
                          <label class="control-label" for="valor_mensal">Órgão Emissor (RG): <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['orgao_rg_aluno']; ?>" required="required" name="orgao_rg" class="form-control col-md-6 col-xs-6">
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Estado Emissor (RG): <span class="required">*</span> 
                          </label>
                          <input type="text" data-inputmask="'mask': 'AA'" required="required" value="<?php echo $aluno['estado_rg_aluno']; ?>" name="estado_rg"  class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Endereço -->
              <div class="row">              
                <div class="col-md-12 col-sm-12">
                  <div class="x_panel">
                    <div class="x_content">                      
                      <div class="x_title"><h2>Endereço:</h2><div class="clearfix"></div></div>
                      <div class="form-group">
                        <div class="col-md-12 col-sm-2 col-xs-12">
                          <label class="control-label" for="titulo-curso">CEP: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['cep_aluno']; ?>" required="required" name="cep" data-inputmask="'mask': '99.999-999'" id="cep" buscaCep="buscaCep" class="form-control">
                        </div>                              
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Endereço: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['endereco_aluno']; ?>" required="required" name="endereco"  class="form-control" id="endereco">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <label class="control-label" for="qtde_mensais">Número: <span class="required">*</span>
                          </label>
                            <input type="text" value="<?php echo $aluno['numero_aluno']; ?>" required="required" name="numero_endereco" class="form-control">
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-12">
                          <label class="control-label" for="valor_mensal">Complemento: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['complemento_aluno']; ?>" required="required" name="complemento" class="form-control col-md-6 col-xs-6">
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Bairro: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['bairro_aluno']; ?>" required="required" name="bairro"  class="form-control" id="bairro">
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-12">
                          <label class="control-label" for="qtde_mensais">Cidade: <span class="required">*</span>
                          </label>
                            <input type="text" value="<?php echo $aluno['cidade_aluno']; ?>" required="required" name="cidade" class="form-control" id="cidade">
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-12">
                          <label class="control-label" for="valor_mensal">Estado: <span class="required">*</span></label>
                          <input type="text" data-inputmask="'mask': 'AA'" required="required" value="<?php echo $aluno['estado_aluno']; ?>" name="estado" class="form-control col-md-6 col-xs-6" id="estado">
                        </div>                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Graduação -->
              <div class="row">              
                <div class="col-md-12 col-sm-12">
                  <div class="x_panel">
                    <div class="x_content">                      
                      <div class="x_title"><h2>Graduação:</h2><div class="clearfix"></div></div>
                      <div class="form-group">
                        <div class="col-md-12 col-sm-2 col-xs-12">
                          <label class="control-label" for="titulo-curso">Faculdade/Universidade: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['faculdade_matricula']; ?>" required="required" name="faculdade" class="form-control">
                        </div>  
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="valor_matricula">Tipo de Graduação: <span class="required">*</span>
                          </label>
                          <select name="tipo_graduacao" required="required" class="form-control">
                            <option value=""></option>
                            <option <?php if($aluno['tipo_graduacao_matricula'] == '1'){echo "selected='selected'";}; ?> value="1">Bacharel</option>
                            <option <?php if($aluno['tipo_graduacao_matricula'] == '2'){echo "selected='selected'";}; ?> value="2">Tecnólogo</option> 
                            <option <?php if($aluno['tipo_graduacao_matricula'] == '3'){echo "selected='selected'";}; ?> value="3">Licenciatura</option>                                  
                          </select>
                        </div>                            
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="titulo-curso">Nome do Curso: <span class="required">*</span>
                          </label>
                          <input type="text" value="<?php echo $aluno['curso_formacao_matricula']; ?>" required="required" name="curso_formacao"  class="form-control">
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label class="control-label" for="qtde_mensais">Data da Colação de Grau: <span class="required">*</span>
                          </label>
                            <input type="text" data-inputmask="'mask': '99/99/9999'" required="required" value="<?php echo $pics->dataMostrar($aluno['colacao_grau_matricula']); ?>" name="data_colacao_matricula" class="form-control">
                        </div>                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Enviar formulário -->
              <div class="row">              
                <div class="col-md-12 col-sm-12">
                  <div class="x_panel">
                    <div class="x_content"> 
                      <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <button type="submit" class="btn btn-warning pull-right">Atualizar dados pessoais</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <!-- Promocional -->
            <?php include('promocional.php'); ?>
            <!-- FIM Promocional -->

            </form>


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
