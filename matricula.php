<?php 
session_start();
include("conectar.php");
//Integração com correios
include ("../Correios/picsCorreios.php");
$picsCorreios = new picsCorreios();
//Verifica se o usuario esta logado
if(!isset($_SESSION['funcionario'])){header("Location: ".$base."logar/");}
//Verifica se chegou o id do curso
if($primeiro_parametro == ""){header("Location: ".$base."matriculas/");}
//Cadastra Histórico de Negociação
if(isset($_POST['tipoMensagem'])){
  //Recebe os dados
  $tipoMensagem = $_POST['tipoMensagem'];
  $texto = $_POST['textoHistorico'];
  $id_funcionario = $_SESSION['funcionario']['id_funcionario'];
  //Cancelamento de Matrícula
  if($tipoMensagem == 0){
    //Verifica se tem faturas vencidas
    $busca_faturas_vencidas = mysql_query("SELECT * FROM faturas_matriculas WHERE status_fatura='2' AND id_matricula='".$primeiro_parametro."'");
    if(mysql_num_rows($busca_faturas_vencidas) > 0){$msgAlert = $pics->alertMSG(2, "Não é possível cancelar matrículas com faturas vencidas!");}
    else{
      //Busca as faturas aguardando pagamento
      $busca_faturas_ag = mysql_query("SELECT * FROM faturas_matriculas WHERE status_fatura='1' AND id_matricula='".$primeiro_parametro."'");
      if(mysql_num_rows($busca_faturas_ag) > 1){
        while ($resFaturasAg = mysql_fetch_array($busca_faturas_ag)) {
          //Atualiza o status
          $upFaturaAg = mysql_query("UPDATE faturas_matriculas SET status_fatura='0' WHERE id_fatura='".$resFaturasAg['id_fatura']."'");
          //Cancelamento no Asaas
          if($resFaturasAg['forma_pagamento'] == 4){          
            $cancelaAsaas = $pics->asaasCancelarFatura($resFaturasAg['link_fatura']);
          }
        }
      }
      //Atualiza o status da matrícula
      $upMatricula = mysql_query("UPDATE matriculas SET status_matricula='0' WHERE id_matricula='$primeiro_parametro'");
      //Cadastra Mensagem da UniPlena (Matrícula)
      $texto_mensagem_uniplena = "Matrícula cancelada por ".$_SESSION['funcionario']['nome_funcionario']." com motivo: ".$texto;
      $inserirMensagem = mysql_query("INSERT INTO mensagens_matriculas SET id_matricula='$primeiro_parametro', id_funcionario='1', texto_mensagem='$texto_mensagem_uniplena', tipo_mensagem='1', ativo_mensagem_matricula='1'");
      //Veririca se Inseriu
      if($inserirMensagem){$msgAlert = $pics->alertMSG(1, "Mensagem cadastrada com sucesso!");}
      else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar mensagem!");}
    }
  }
  //Trancamento de Matrícula
  elseif($tipoMensagem == 3){
    //Verifica se tem faturas vencidas
    $busca_faturas_vencidas = mysql_query("SELECT * FROM faturas_matriculas WHERE status_fatura='2' AND id_matricula='".$primeiro_parametro."'");
    if(mysql_num_rows($busca_faturas_vencidas) > 0){$msgAlert = $pics->alertMSG(2, "Não é possível trancar matrículas com faturas vencidas!");}
    else{
      //Busca as faturas aguardando pagamento
      $busca_faturas_ag = mysql_query("SELECT * FROM faturas_matriculas WHERE status_fatura='1' AND id_matricula='".$primeiro_parametro."'");
      if(mysql_num_rows($busca_faturas_ag) > 1){
        while ($resFaturasAg = mysql_fetch_array($busca_faturas_ag)) {
          //Atualiza o status
          $upFaturaAg = mysql_query("UPDATE faturas_matriculas SET status_fatura='0' WHERE id_fatura='".$resFaturasAg['id_fatura']."'");
          //Cancelamento no Asaas
          if($resFaturasAg['forma_pagamento'] == 4){          
            $cancelaAsaas = $pics->asaasCancelarFatura($resFaturasAg['link_fatura']);
          }
        }
      }
      //Atualiza o status da matrícula
      $upMatricula = mysql_query("UPDATE matriculas SET status_matricula='3' WHERE id_matricula='$primeiro_parametro'");
      //Cadastra Mensagem da UniPlena (Matrícula)
      $texto_mensagem_uniplena = "Matrícula trancada por ".$_SESSION['funcionario']['nome_funcionario']." com motivo: ".$texto;
      $inserirMensagem = mysql_query("INSERT INTO mensagens_matriculas SET id_matricula='$primeiro_parametro', id_funcionario='1', texto_mensagem='$texto_mensagem_uniplena', tipo_mensagem='1', ativo_mensagem_matricula='1'");
      //Veririca se Inseriu
      if($inserirMensagem){$msgAlert = $pics->alertMSG(1, "Mensagem cadastrada com sucesso!");}
      else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar mensagem!");}
    }
  }
  else{
    //Cadastra no Banco
    $inserirMensagem = mysql_query("INSERT INTO mensagens_matriculas SET id_matricula='$primeiro_parametro', id_funcionario='$id_funcionario', texto_mensagem='$texto', tipo_mensagem='$tipoMensagem', ativo_mensagem_matricula='1'");
    //Veririca se Inseriu
    if($inserirMensagem){$msgAlert = $pics->alertMSG(1, "Mensagem cadastrada com sucesso!");}
    else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar mensagem!");}
  }
}
//Formar Matrícula
if($segundo_parametro == "formar"){
  //Cadastra no Banco
  $id_funcionario = $_SESSION['funcionario']['id_funcionario'];
  //Busca essa matricula
  $busca_mtEsta = mysql_query("SELECT * FROM matriculas WHERE id_matricula='$primeiro_parametro'");
  while($resEsta = mysql_fetch_array($busca_mtEsta)){
    //Separa os dados
    $id_aluno_esta = $resEsta['id_aluno'];
    $id_turma_esta = $resEsta['id_turma'];
    $id_disciplina_esta = $resEsta['id_disciplina'];
    //Busca se tem matrícula ativa
    $busca_mtAtiva = mysql_query("SELECT * FROM matriculas WHERE id_aluno='$id_aluno_esta' AND id_turma='$id_turma_esta'  AND id_disciplina='$id_disciplina_esta' AND status_matricula != '0' AND id_matricula !='$primeiro_parametro'");
    if(mysql_num_rows($busca_mtAtiva) >= 1){
      $msgAlert = $pics->alertMSG(2, "Já existe uma matrícula ativa para esse aluno nessa turma!");
    }
    else{
      //Cadastra Mensagem da UniPlena (Matrícula)
      $texto_mensagem_uniplena = "Matrícula formada por ".$_SESSION['funcionario']['nome_funcionario'].".";
      $inserirMensagemUniPlena = mysql_query("INSERT INTO mensagens_matriculas SET id_matricula='$primeiro_parametro', id_funcionario='1', texto_mensagem='$texto_mensagem_uniplena', tipo_mensagem='1', ativo_mensagem_matricula='1'");
      //Altera status da Matrícula
      $upMatricula = mysql_query("UPDATE matriculas SET status_matricula='2' WHERE id_matricula='$primeiro_parametro'");
      //Mensagem de Alerta
      if($inserirMensagemUniPlena && $upMatricula){$msgAlert = $pics->alertMSG(1, "Matrícula formada com sucesso!");}
      else{$msgAlert = $pics->alertMSG(2, "Erro ao concluir formação de matrícula!");}
    }
  }
      
}
//Reativa Matrícula
elseif($segundo_parametro == "reativar"){
  //Cadastra no Banco
  $id_funcionario = $_SESSION['funcionario']['id_funcionario'];
  //Busca essa matricula
  $busca_mtEsta = mysql_query("SELECT * FROM matriculas WHERE id_matricula='$primeiro_parametro'");
  while($resEsta = mysql_fetch_array($busca_mtEsta)){
    //Separa os dados
    $id_aluno_esta = $resEsta['id_aluno'];
    $id_turma_esta = $resEsta['id_turma'];
    $id_disciplina_esta = $resEsta['id_disciplina'];
    //Busca se tem matrícula ativa
    $busca_mtAtiva = mysql_query("SELECT * FROM matriculas WHERE id_aluno='$id_aluno_esta' AND id_turma='$id_turma_esta'  AND id_disciplina='$id_disciplina_esta' AND status_matricula != '0' AND id_matricula !='$primeiro_parametro'");
    if(mysql_num_rows($busca_mtAtiva) >= 1){
      $msgAlert = $pics->alertMSG(2, "Já existe uma matrícula ativa para esse aluno nessa turma!");
    }
    else{
      //Cadastra Mensagem da UniPlena (Matrícula)
      $texto_mensagem_uniplena = "Matrícula reativada por ".$_SESSION['funcionario']['nome_funcionario'].".";
      $inserirMensagemUniPlena = mysql_query("INSERT INTO mensagens_matriculas SET id_matricula='$primeiro_parametro', id_funcionario='1', texto_mensagem='$texto_mensagem_uniplena', tipo_mensagem='1', ativo_mensagem_matricula='1'");
      //Altera status da Matrícula
      $upMatricula = mysql_query("UPDATE matriculas SET status_matricula='1' WHERE id_matricula='$primeiro_parametro'");
      //Mensagem de Alerta
      if($inserirMensagemUniPlena && $upMatricula){$msgAlert = $pics->alertMSG(1, "Matrícula reativada com sucesso!");}
      else{$msgAlert = $pics->alertMSG(2, "Erro ao reativar matrícula!");}
    }
  }
}
//Apaga Histórico de Negociação
elseif($segundo_parametro != ""){
  //Busca a Mensagem com o Aluno
  $busca_mensagem = mysql_query("SELECT * FROM mensagens_matriculas WHERE id_matricula='$primeiro_parametro' AND id_mensagem='$segundo_parametro'");
  if(mysql_num_rows($busca_mensagem) == 0){header("Location: ".$base."matriculas/");}
  else{
    //Separa os dados da mensagem
    while($resMensagem = mysql_fetch_array($busca_mensagem)){$mensagemDell = $resMensagem;}
    //Exclui a mensagem
    $dellMsg = mysql_query("UPDATE mensagens_matriculas SET ativo_mensagem_matricula='0' WHERE id_mensagem='$segundo_parametro'");
    //Mensagem
    if($dellMsg){$msgAlert = $pics->alertMSG(1, "Mensagem apagada com sucesso!");}
    else{$msgAlert = $pics->alertMSG(2, "Erro ao apagar Mensagem!");}
  }
}
//Envio de Documento
if(isset($_FILES['resposta'])){
  //Recebe os Dados
  $id_matricula = $primeiro_parametro;
  $nome_aluno_upload = $_POST['nome_aluno_upload'];
  $turma_aluno_upload = $_POST['turma_aluno_upload'];
  $id_documento_curso = $_POST['id_documentocurso'];
  $resposta = $_FILES['resposta'];
  $descricao = addslashes($_POST['descricao']);
  $tipoArquivo = $_POST['tipo'];
  $status_documento = $_POST['status_documento'];
  $comentario_documento = $_POST['comentario_documento'];
  $id_funcionario = ($status_documento != 3) ? $_SESSION['funcionario']['id_funcionario'] : " ";
  //Link Externo
  if($tipoArquivo == "2"){
    //Recebe os dados
    $link_externo = $_POST['link_externo'];
    //Cadastra no Banco
    $inserir_atividade = mysql_query("INSERT INTO upload_documentos SET id_documento_curso='$id_documento_curso', id_matricula='$id_matricula', titulo_upload='Link Externo', descricao_upload='$descricao', link_externo_upload='$link_externo', ativo_upload_documento='1', status_upload='$status_documento', comentario_upload='$comentario_documento', id_funcionario='$id_funcionario', data_comentario = NOW()");
    if($inserir_atividade){
      $id_material = mysql_insert_id($conex);
      //NOTIFICAÇÕES PARA O ALUNO
        //Cadastra a notificação
        $tipoDest = "3";
        //Mensagem
        if($status_documento == '3'){$msgNot = "Recebemos o documento ".$titulo_disciplina." e estamos analisando.";}
        else{$msgNot = "Seu documento ".$titulo_disciplina." acaba de ser analisado.";}
        //Link
        $linkNot = "upload-documento/".$id_material;
        //Cadastra a Notificação
        $pics->inserirNotificacao("1", $tipoDest, $id_matricula, $msgNot, $linkNot);
      //FIM DAS NOTIFICAÇÕES 
      $msgAlert = $pics->alertMSG(1, "Documento enviado com sucesso, <a style='color: #FFF;' target='_blank' href='".$base."upload-documento/".$id_material."'><u>clique aqui para acessar</u></a>");
    }
    else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar documento!".mysql_error());}
  }
  //Upload
  elseif($tipoArquivo == "1"){
    //Mensagens na tela
    $msgSucesso = "";
    $msgErro = "";
    //Inicia o envio
    for($controle = 0; $controle < count($resposta['name']); $controle++){
      //Recebe os Dados
      $resposta_nome = $resposta['name'][$controle];
      $extensao = explode(".", $resposta_nome);
      $ext = $extensao[1];
      $caminho = "../AVA/Documentos/".$id_matricula."/";
      //Verifica a extensao
      $extup = $pics->extUpload($ext);
      if(!$extup){$msgAlert = $pics->alertMSG(2, "Este tipo de arquivo não é aceito para upload!");}
      else{
        //Verifica se existe uma pasta para esse item
        if(!is_dir("../AVA/Documentos/".$id_matricula)){
          //Cria a pasta para o item
          mkdir("../AVA/Documentos/".$id_matricula);
        }
        //Cadastra no Banco
        $inserir_atividade = mysql_query("INSERT INTO upload_documentos SET id_documento_curso='$id_documento_curso', id_matricula='$id_matricula', titulo_upload='$resposta_nome', descricao_upload='$descricao', link_upload='$resposta_nome', ativo_upload_documento='1', status_upload='$status_documento', comentario_upload='$comentario_documento', id_funcionario='$id_funcionario', data_comentario = NOW()");
        //Verifica se inseriu
        if($inserir_atividade){
          //UPLOAD
          $id_material = mysql_insert_id($conex);
          $nomeBD = $id_material.".".$extensao[1];
          //Faz o upload
          $upMat = copy($resposta['tmp_name'][$controle], $caminho.$nomeBD);
          //Verifica se subiu          
          if($upMat){
            //Altera o link no banco
            $up = mysql_query("UPDATE upload_documentos SET link_upload='$nomeBD' WHERE id_upload_documento='$id_material'");
            //Mensagem na tela
            $msgSucesso = $msgSucesso."Documento enviado com sucesso, <a style='color: #FFF;' target='_blank' href='".$base."upload-documento/".$id_material."'><u>clique aqui para acessar</u></a><br>"; 
          }
          else{
            //Desativa o material
            $up = mysql_query("UPDATE upload_documentos SET ativo_upload_documento='0' WHERE id_upload_documento='$id_material'");
            //Mensagem na tela
            $msgErro = $msgErro."Erro ao enviar o documento ".$id_material."<br>";
          }
          //NOTIFICAÇÕES PARA O ALUNO
            //Cadastra a notificação
            $tipoDest = "3";
            //Mensagem
            if($status_documento == '3'){$msgNot = "Recebemos o documento ".$titulo_disciplina." e estamos analisando.";}
            else{$msgNot = "Seu documento ".$titulo_disciplina." acaba de ser analisado.";}
            //Link
            $linkNot = "upload-documento/".$id_material;
            //Cadastra a Notificação
            $pics->inserirNotificacao("1", $tipoDest, $id_matricula, $msgNot, $linkNot);
                                
        }         
      }
    }
    //Mensagem na tela
    if($msgSucesso != ""){$msgAlert = $pics->alertMSG(1, $msgSucesso);}
    elseif($msgErro != ""){$msgAlert = $pics->alertMSG(2, $msgErro);}
  }
  //Contrato (HTML)
  elseif($tipoArquivo == "3"){
    //Cadastra o contrato do curso
    $busca_contratos = mysql_query("SELECT * FROM documentos_curso WHERE contrato_documento='1' AND ativo_documento='1' AND id_documento_curso='$id_documento_curso'");
    while ($resContrato = mysql_fetch_array($busca_contratos)) {
      //Separa os dados
      $titulo_upload = $resContrato['titulo_documento'].' - '.$nome_aluno_upload.' - M'.$id_matricula.' - T'.$turma_aluno_upload;
      $html_contrato = $resContrato['html_contrato_documento'];
      $descricao_upload = "Gerado automaticamente via PICS";
      //Sobe um upload com o contrato
      $upDocumento = mysql_query("INSERT INTO upload_documentos SET
        id_documento_curso='$id_documento_curso',
        id_matricula='$id_matricula',
        titulo_upload='$titulo_upload',
        html_documento='$html_contrato',
        descricao_upload='$descricao_upload',
        status_upload='4',
        ativo_upload_documento='1'
      ");
      //Verifica se cadastrou
      if($upDocumento){$msgAlert = $pics->alertMSG(1, "Contrato cadastrado com sucesso!");}
      else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar contrato!".mysql_error());}
    }
  }

}

//Liberar o documento
if(isset($_POST['id_documento_curso'])){
  //Recebe os Dados
  $id_funcionario = $_SESSION['funcionario']['id_funcionario'];
  $id_documento_curso = $_POST['id_documento_curso'];
  $id_matricula = $primeiro_parametro;

  if($id_funcionario == '' || $id_funcionario == '0'){
    $msgAlert = $pics->alertMSG(2, "Erro ao liberar documento, faça login novamente no PICS!");
  }
  else{
    //Verifica se ja foi liberado
    $buscaNota = mysql_query("SELECT * FROM documentos_matriculas WHERE id_documento_curso='$id_documento_curso' AND id_matricula='$id_matricula' AND id_funcionario != 0");
    if(mysql_num_rows($buscaNota) > 0){$msgAlert = $pics->alertMSG(2, "Esse documento já foi liberado!");}
    else{
      //Inseri no Banco de Dados
      $inserir = mysql_query("INSERT INTO documentos_matriculas SET
        id_matricula='$id_matricula',
        id_documento_curso='$id_documento_curso',
        id_funcionario='$id_funcionario',
        ativo_documento_matricula='1'
        ");

      //Confirma se Inseriu
      if($inserir){
        //Redireciona
        $msgAlert = $pics->alertMSG(1, "Documento liberado com sucesso!");
      }
      else{$msgAlert = $pics->alertMSG(2, "Erro ao liberar documento!".mysql_error());}
    }
  }
}

//Cadastra Mensagem - Certificacao
if(isset($_POST['tipoMensagemSo'])){
  //Recebe os dados
  $tipoMensagem = $_POST['tipoMensagemSo'];
  $texto = $_POST['textoHistoricoSo'];
  $idSo = $_POST['idSo'];
  $id_funcionario = $_SESSION['funcionario']['id_funcionario'];
  $emailNotificacao = $_POST['emailNotificacao'];
  $nomeNotificacao = $_POST['nomeNotificacao'];
  //atualizando status
  switch ($tipoMensagem) {
    case '1':
      $a = 1;      
      break;
    case '2':
      //Atualiza o status
      $upSo = mysql_query("UPDATE solicitacoes_certificacao SET status_solicitacao_certificacao='1' WHERE id_solicitacao_certificacao='$idSo'");     
      break;
    case '3':
      //Atualiza o status
      $upSo = mysql_query("UPDATE solicitacoes_certificacao SET status_solicitacao_certificacao='2' WHERE id_solicitacao_certificacao='$idSo'");      
      break;
    case '4':
      //Atualiza o status
      $upSo = mysql_query("UPDATE solicitacoes_certificacao SET status_solicitacao_certificacao='3' WHERE id_solicitacao_certificacao='$idSo'");      
      break;
    case '5':
      //Atualiza o status
      $upSo = mysql_query("UPDATE solicitacoes_certificacao SET status_solicitacao_certificacao='4' WHERE id_solicitacao_certificacao='$idSo'");  
      //Cadastra Mensagem da UniPlena (Matrícula)
      $texto_mensagem_uniplena = "Matrícula formada por ".$_SESSION['funcionario']['nome_funcionario'].".";
      $inserirMensagemUniPlena = mysql_query("INSERT INTO mensagens_matriculas SET id_matricula='$primeiro_parametro', id_funcionario='1', texto_mensagem='$texto_mensagem_uniplena', tipo_mensagem='1', ativo_mensagem_matricula='1'");
      //Altera status da Matrícula
      $upMatricula = mysql_query("UPDATE matriculas SET status_matricula='2' WHERE id_matricula='$primeiro_parametro'");    
      break;
    case '6':
      //Atualiza o status
      $upSo = mysql_query("UPDATE solicitacoes_certificacao SET status_solicitacao_certificacao='0' WHERE id_solicitacao_certificacao='$idSo'");     
      break;
    case '7':
      //Atualiza o status
      $upSo = mysql_query("UPDATE solicitacoes_certificacao SET status_solicitacao_certificacao='5' WHERE id_solicitacao_certificacao='$idSo'");       
      break;
    default:
      $a = 1;
      break;
  }
  //Cadastra no Banco
  $inserirMensagem = mysql_query("INSERT INTO mensagens_solicitacoes_certificacao SET id_solicitacao='$idSo', tipo_remetente='1', id_remetente='$id_funcionario', texto_mensagem='$texto', tipo_mensagem='$tipoMensagem', ativo_mensagem='1'");
  //Veririca se Inseriu
  if($inserirMensagem){
    //Cadastra a notificação
    $tipoDest = "3";
    //Mensagem
    $msgNot = "Nova mensagem recebida em sua solicitação de certificação: ".$texto;
    //Link
    $linkNot = "certificacao-iniciada/";
    //Cadastra a Notificação
    $pics->inserirNotificacao("1", $tipoDest, $primeiro_parametro, $msgNot, $linkNot);
    //Id cadastrado
    $id_notificacao = mysql_insert_id($conex);
    //Envia o e-mail da notificação
    $mail = $pics->enviarEmail("naoresponda@uniplena.com.br", "Uniplena Educacional", $emailNotificacao, $nomeNotificacao, "certificacao@uniplena.com.br", "Certificação - Uniplena", "Você tem uma nova notificação em seu AVA - #".$id_notificacao, "Você tem uma nova notificação em seu AVA!", file_get_contents("https://www.uniplena.com.br/Mail/notificacao-geral/".$id_notificacao), "");
    //Mensagem na tela
    $msgAlert = $pics->alertMSG(1, "Mensagem cadastrada com sucesso!");
  }
  else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar mensagem!");}
}

//Desativa Mensagem - Certificacao
if(isset($_POST['dellMsgSo'])){
  //Separa o ID
  $idDell = $_POST['dellMsgSo'];
  //Desativa
  $delMsgSo = mysql_query("UPDATE mensagens_solicitacoes_certificacao SET ativo_mensagem='0' WHERE id_mensagem='$idDell'");
  //Veririca se Atualizou
  if($delMsgSo){$msgAlert = $pics->alertMSG(1, "Mensagem desativada com sucesso!");}
  else{$msgAlert = $pics->alertMSG(2, "Erro ao desativar mensagem!");}
}

//Excluir solicitação
if(isset($_POST['id_solicitacao_del'])){
  //Recebe os dados
  $id_solicitacao = $_POST['id_solicitacao_del'];
  //Exclui a solicitação
  $dellSoli = mysql_query("DELETE FROM solicitacoes_certificacao WHERE id_solicitacao_certificacao='$id_solicitacao'");
  //Mensagem de erro
  if($dellSoli){$msgAlert = $pics->alertMSG(1, 'Solicitação excluida com sucesso!');}
  else{$msgAlert = $pics->alertMSG(2, 'Erro ao excluir solicitação!'.mysql_error());}
}

//Solicitação de Frete
if(isset($_POST['freteSolicitacao'])){
  //Recebe e prepara os dados
  $freteSolicitacao = explode(";", $_POST['freteSolicitacao']);
  $servico_frete = $freteSolicitacao[0];
  $valor_frete = $freteSolicitacao[1];
  $prazo_frete = $freteSolicitacao[2];
  $cep_frete = $freteSolicitacao[3];
  $id_solicitacao_frete = $freteSolicitacao[4];
  $id_aluno_frete = $freteSolicitacao[5];
  $descricao_fatura = "Envio da solicitação #".$id_solicitacao_frete.": ".$servico_frete." | R$ ".$valor_frete." | ".$prazo_frete." dias úteis | CEP ".$cep_frete;
  $hoje_frete = date("Y-m-d");

  //Gera a fatura
  $add_fatura = mysql_query("INSERT INTO faturas_matriculas SET
    id_matricula='".$primeiro_parametro."',
    id_funcionario_cadastro='1',
    tipo_fatura='5',
    descricao_pagamento='".$descricao_fatura."',
    valor_fatura='".$pics->mascaras($valor_frete,'inserirMoeda')."',
    data_vencimento_fatura='".$hoje_frete."',
    forma_pagamento='4',
    status_fatura='1',
    ativo_fatura=1");
  //Verifica se cadastrou a fatura
  if(!$add_fatura){$msgAlert = $pics->alertMSG(2, 'Erro ao cadastrar fatura, consulte a coordenação!');}
  else{
    //Resgata o ID da Fatura
    $id_fatura = mysql_insert_id($conex);
    //Busca o CPF do aluno
    $busca_cpf = mysql_query("SELECT cpf_aluno, nome_aluno, email_aluno, id_aluno, celular_aluno FROM alunos WHERE id_aluno='".$id_aluno_frete."'");
    //Separa os dados do aluno
    while($resCPF = mysql_fetch_array($busca_cpf)){
      //Dados do aluno
      $cpf_aluno = $pics->soNumero($resCPF['cpf_aluno']);
      $nome_aluno = $resCPF['nome_aluno'];
      $email_aluno = $resCPF['email_aluno'];
      $id_aluno = $resCPF['id_aluno'];
      $celular_aluno = $pics->soNumero($resCPF['celular_aluno']);
    }
    //Verifica se o cliente tem cadastro no Asaas
    $clienteAsaas = $pics->asaasBuscarCliente($cpf_aluno);
    //Aluno sem cadastro no Asaas
    if($clienteAsaas == 0){
      //Cria o cadastro do cliente
      $idAsaas = $pics->asaasAddCliente($cpf_aluno, $nome_aluno, $email_aluno, $id_aluno, $celular_aluno);
      //Verifica se criou o cliente
      if($idAsaas == 0){$msgAlert = $pics->alertMSG(2, "Erro com o cadastro no Asaas, consulte à coordenação!");$link_pagamento = '';}
      else{
        //Adiciona a fatura no Asaas
        $addCobranca = $pics->asaasAddCobranca($idAsaas, $pics->mascaras($valor_frete, "inserirMoeda"), $hoje_frete, $descricao_fatura);
        if($addCobranca == 0){$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar a fatura no Asaas, consulte à coordenação!");$link_pagamento = '';}
        else{$link_pagamento = $addCobranca;}   
      }
    }
    //Aluno com cadastro no Asaas
    else{          
      //Adiciona a fatura no Asaas
      $addCobranca = $pics->asaasAddCobranca($clienteAsaas, $pics->mascaras($valor_frete, "inserirMoeda"), $hoje_frete, $descricao_fatura);
      //Verifica se cadastrou
      if($addCobranca == 0){$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar a fatura no Asaas, consulte à coordenação!");$link_pagamento = '';}
      else{$link_pagamento = $addCobranca;}
    }
  }
  //Atualiza a fatura
  $upFatura = mysql_query("UPDATE faturas_matriculas SET link_fatura='$link_pagamento' WHERE id_fatura='$id_fatura'");
  //Atualiza a solicitação
  $upSolic = mysql_query("UPDATE solicitacoes_certificacao SET id_fatura_postagem='".$id_fatura."', servico_postagem='".$servico_frete."' WHERE id_solicitacao_certificacao='$id_solicitacao_frete'");
  if($upSolic){
    //Prepara a mensagem
    $mensagem_solic = "Frete solicitado: Fatura #".$id_fatura." | ".$servico_frete." | R$ ".$valor_frete." | ".$prazo_frete." dias úteis | CEP ".$cep_frete;
    //Cadastra essa informação na solicitação
    $addMsgSolic = mysql_query("INSERT INTO mensagens_solicitacoes_certificacao SET
      id_solicitacao='$id_solicitacao_frete',
      tipo_remetente='1',
      id_remetente='1',
      texto_mensagem='$mensagem_solic',
      tipo_mensagem='1',
      ativo_mensagem='1'
     ");
    //Mensagem na tela
    if($addMsgSolic){$msgAlert = $pics->alertMSG(1, "Frete solicitado com sucesso!");}
    else{$msgAlert = $pics->alertMSG(2, "Erro ao solicitar frete!".mysql_error());}
  }
}

//Cadastro de Pré-Postagem
if(isset($_POST['idSoCorreios'])){
  //Separa os dados
  $id_solicitacao_prepostagem = $_POST['idSoCorreios'];
  $servico_prepostagem = $_POST['servico_prepostagem'];
  $servico_prepostagem_texto = ($servico_prepostagem == '03298') ? "PAC" : "SEDEX";
  //Busca os dados necessários
  $busca_solicitacao_prepostagem = mysql_query("SELECT * FROM solicitacoes_certificacao INNER JOIN matriculas ON solicitacoes_certificacao.id_matricula=matriculas.id_matricula INNER JOIN alunos ON matriculas.id_aluno=alunos.id_aluno WHERE solicitacoes_certificacao.id_solicitacao_certificacao='$id_solicitacao_prepostagem'");
  //Verifica se achou
  if(mysql_num_rows($busca_solicitacao_prepostagem) > 0){
    while ($resPrePostagem = mysql_fetch_array($busca_solicitacao_prepostagem)) { 
      //Verifica se a solicitação já possui uma pré-postagem
      if($resPrePostagem['idPrePostagem'] != ""){
        //Mensagem de Erro
        $msgAlert = $pics->alertMSG(2, "Essa solicitação já possui uma pré-postagem, cod: ".$resPrePostagem['idPrePostagem']);
      }
      else{
        //Prepara os dados
        $ddDestinatario = $pics->soNumero($resPrePostagem['celular_aluno']);    
        //Cria a prépostagem
        $criarPrePostagemCorreios = $picsCorreios->criarPrePostagemCorreios($servico_prepostagem, $resPrePostagem['nome_aluno'], substr($pics->soNumero($resPrePostagem['celular_aluno']), -11, 2), substr($pics->soNumero($resPrePostagem['celular_aluno']), -9), $resPrePostagem['email_aluno'], $pics->soNumero($resPrePostagem['cpf_aluno']), $pics->soNumero($resPrePostagem['cep_aluno']), $resPrePostagem['endereco_aluno'], $resPrePostagem['numero_aluno'], $resPrePostagem['complemento_aluno'], $resPrePostagem['bairro_aluno'], $resPrePostagem['cidade_aluno'], $resPrePostagem['estado_aluno']);
        //Verifica se criou a pré-postagem
        if (is_array($criarPrePostagemCorreios)) {
            //Gerando Rótulo de Rastramento
            $gerarRotuloCorreios = $picsCorreios->gerarRotuloCorreios(array($criarPrePostagemCorreios['id']));
            //Gera a etiqueta de postagem
            $nome_arquivo_etiqueta = "../AVA/Documentos/".$resPrePostagem['id_matricula']."/".$id_solicitacao_prepostagem."etiqueta.pdf";
            //Verifica se existe a pasta do aluno
            if(!is_dir("../AVA/Documentos/".$resPrePostagem['id_matricula'])){
              //Cria a pasta para o item
              mkdir("../AVA/Documentos/".$resPrePostagem['id_matricula']);
            }
            //Imprime a etiqueta
            $imprimirEtiquetaCorreios = $picsCorreios->imprimirEtiquetaCorreios($gerarRotuloCorreios, $nome_arquivo_etiqueta);
            //Atualiza a solicitação
            $upSolicitacaoCorreios = mysql_query("UPDATE solicitacoes_certificacao SET rastreio_postagem='".$criarPrePostagemCorreios['codigoObjeto']."', idPrePostagem='".$criarPrePostagemCorreios['id']."', servico_postagem='".$servico_prepostagem_texto."', idRotuloPrepostagem='".$gerarRotuloCorreios."' WHERE id_solicitacao_certificacao='$id_solicitacao_prepostagem'");
            //Verifica se atualizou
            if($upSolicitacaoCorreios){
              //Cadastra a mensagem na solicitação
              $mensgem_solicitacao = "Pré-Postagem criada por ".$_SESSION['funcionario']['nome_funcionario'].": <br> <b>Cod de Rastreio: </b>".$criarPrePostagemCorreios['codigoObjeto'];
              $add_mensagem_solicitacao = mysql_query("INSERT INTO mensagens_solicitacoes_certificacao SET id_solicitacao='$id_solicitacao_prepostagem', tipo_remetente='1', id_remetente='1', texto_mensagem='$mensgem_solicitacao', tipo_mensagem='1', ativo_mensagem='1'");
              //Verifica se cadastrou
              if($add_mensagem_solicitacao){$msgAlert = $pics->alertMSG(1, "Pré-Postagem cadastrada com sucesso!");}
              else{$msgAlert = $pics->alertMSG(2, "Erro ao cadastrar pré-postagem!".mysql_error());}
            }
        } 
        else {
            //Mensagem de Erro
            $msgAlert = $pics->alertMSG(2, "Erro ao cadastrar pré-postagem array!".mysql_error());
        }
      }
    }
  }
}

//Busca o Aluno
$busca_matricula = mysql_query("SELECT matriculas.id_matricula, matriculas.id_turma, matriculas.status_matricula, matriculas.data_cadastro_matricula, matriculas.id_prematricula, alunos.nome_aluno, alunos.id_aluno, alunos.status_aluno, alunos.cep_aluno, alunos.telefone_aluno, alunos.celular_aluno, alunos.email_aluno, alunos.cpf_aluno, alunos.email2_aluno, alunos.rg_aluno, disciplinas_cursos.id_disciplina, disciplinas_cursos.titulo_disciplina, cursos.titulo_curso, cursos.subtitulo_curso, cursos.duracao_curso, unidades.titulo_unidade, turmas.data_inicio_turma, turmas.inicio_imediato, turmas.id_curso, funcionarios.nome_funcionario FROM matriculas INNER JOIN alunos ON matriculas.id_aluno=alunos.id_aluno INNER JOIN disciplinas_cursos ON matriculas.id_disciplina=disciplinas_cursos.id_disciplina INNER JOIN turmas ON matriculas.id_turma = turmas.id_turma  INNER JOIN cursos ON turmas.id_curso = cursos.id_curso INNER JOIN unidades ON turmas.id_unidade = unidades.id_unidade LEFT JOIN funcionarios ON matriculas.id_funcionario = funcionarios.id_funcionario WHERE matriculas.id_matricula='$primeiro_parametro'");
//Busca Matrículas;
if(mysql_num_rows($busca_matricula) == 0){header("Location: ".$base."matriculas/");}
else{while($resMatricula = mysql_fetch_array($busca_matricula)){$matricula = $resMatricula;$id_matricula=$resMatricula['id_matricula'];$id_turma=$resMatricula['id_turma'];$id_disciplina=$resMatricula['id_disciplina']; $id_curso=$resMatricula['id_curso'];$inicio_imediato = $resMatricula['inicio_imediato'];}}
//Data de Início e Término
$data_inicio = ($inicio_imediato == 1) ? $pics->dataTimeStampInserir($matricula['data_cadastro_matricula']) : $matricula['data_inicio_turma'];
$data_conclusao = date('d/m/Y', strtotime($data_inicio.' + '.$matricula['duracao_curso'].' months')); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Tags do PICS -->
    <?php include("tags.php"); ?>
    <!-- Final das tags PICS -->

    <title><?php echo $matricula['id_matricula'] ?> Matrícula - <?php echo $matricula['nome_aluno'] ?> | PICS</title>
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
            <div class="page-title">
              <div class="title_left">
                <div class="title_left">
                  <div class="pics-map-navigation"><b><a href="matriculas/">Nossas Matrículas</a></b> / Matrícula <?php echo $matricula['id_matricula'] ?> - <?php echo $matricula['nome_aluno'] ?></div><br>
                </div> 
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel" style="min-height: 204px;">
                  <div class="x_title">
                    <h2>Dados da Matrícula <?php echo $matricula['id_matricula']; ?></h2>
                    <div class="clearfix"></div>
                  </div>                  
                  <div class="x_content col-md-10 col-sm-12 col-xs-12">
                    <table style="line-height: 28px; font-size: 14px;" class="col-md-10 col-sm-12 col-xs-12" >
                    <tr>
                      <td><i class="fa fa-calendar" aria-hidden="true"></i> <b>Turma:</b> <?php echo $matricula['id_turma']; ?> - <?php if($inicio_imediato){echo "Início Imediato";} else { echo "Início em ".$data_inicio;} ?> / Conclusão em <?php echo $data_conclusao ?></td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-book"></i> <b>Curso:</b> <?php echo $matricula['titulo_curso']; ?> (<?php echo $matricula['subtitulo_curso']; ?>) - <?php echo $matricula['titulo_disciplina']; ?> </td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-map-marker"></i> <b>Unidade:</b> <?php echo $matricula['titulo_unidade']; ?> / <i class="fa fa fa-compress"></i> <b>Pré-matrícula:</b> <a href="prematricula/<?php echo $matricula['id_prematricula']; ?>" target="_blank"><?php echo $matricula['id_prematricula']; ?></a> </td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-calendar-o" aria-hidden="true"></i> <b>Data de Cadastro:</b> <?php echo $pics->timeStampMostrar($matricula['data_cadastro_matricula']); ?> - <?php echo $matricula['nome_funcionario']; ?></td>
                    </tr>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel" style="min-height: 204px;">
                  <div class="x_title">
                    <h2>Dados do Aluno</h2>
                    <div class="clearfix"></div>
                  </div>                  
                  <div class="x_content col-md-10 col-sm-12 col-xs-12">
                    <?php
                    //Formatação de Dados
                    $telefone = ($matricula['telefone_aluno'] == "") ? "" : " / ".$matricula['telefone_aluno'];
                    $rg_aluno = ($matricula['rg_aluno'] == "") ? "" : " / ".$matricula['rg_aluno'];
                    ?>
                    <p style="line-height: 28px; font-size: 14px;">
                    <?php 
                    echo "<a href='".$base."aluno/".$matricula['id_aluno']."' target='_blank' title='Acessar Aluno'>".$matricula['id_aluno']." - ".$matricula['nome_aluno']."</a><br>";
                    echo $matricula['celular_aluno']." ".$telefone."<br>"; 
                    echo $matricula['email_aluno']."<br>";
                    echo $matricula['cpf_aluno'].$rg_aluno;
                    
                    ?>
                    </p>
                  </div>
                </div>
              </div>

              <div class="col-md-2 col-sm-12 col-xs-12">
                <div class="x_panel" style="min-height: 204px;">
                  <div class="x_title">
                    <h2>Status</h2>
                    <div class="clearfix"></div>
                  </div>                  
                  <div class="x_content col-md-10">
                    <?php $statusAluno = $pics->statusMatricula($matricula["status_matricula"]); ?>
                    <button type="button" class="btn btn-<?php echo $statusAluno['tipo'] ?> btn-xs"><?php echo $statusAluno['texto'] ?></button>
                  </div>
                </div>
              </div>
            </div>

            <div class="pull-right">
              <a href="edit-matricula/<?php echo $primeiro_parametro; ?>"><button type="button" class="btn btn-default btn-xs">Editar Matrícula</button></a>
              <a target="_blank" href="add-dp/<?php echo $primeiro_parametro; ?>"><button type="button" class="btn btn-dark btn-xs">Add DP</button></a>
              <a target="_blank" href="add-fatura/<?php echo $primeiro_parametro; ?>"><button type="button" class="btn btn-dark btn-xs">Add Fatura</button></a>
              <a target="_blank" href="add-certificacao/<?php echo $primeiro_parametro; ?>"><button type="button" class="btn btn-dark btn-xs">Add Certificação</button></a>
              <a href="matricula/<?php echo $primeiro_parametro; ?>/formar"><button type="button" class="btn btn-success btn-xs">Formar Matrícula</button></a>
              <?php if($matricula['status_matricula'] == 0){ ?>
                <a href="matricula/<?php echo $primeiro_parametro; ?>/reativar" class="btn btn-primary btn-xs">Reativar Matrícula</a>
              <?php } else { ?>
                 <button data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-danger btn-xs">Cancelar / Trancar</button>
              <?php } ?>
            </div>

            <div class="row">
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">                                    
                  <!-- Mensagem Descartar -->
                  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <form method="post" action="matricula/<?php echo $primeiro_parametro; ?>"  class="form-horizontal form-label-left">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel">Deseja mesmo cancelar / trancar essa matricula?</h4>
                            </div>
                            <div class="modal-body">                                                            
                              <p><i class="fa fa-trash-o"></i> - Justificativa:</p>
                              <input type="text" name="textoHistorico" required="required" class="form-control"><br>
                              <select name="tipoMensagem" class="form-control"> 
                                <option value="0">Cancelar</option>
                                <option value="3">Trancar</option>
                              </select>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                              <button type="subtmit" class="btn btn-primary">Enviar</button><br><br>
                              <p style="text-align: center;"><b>Atenção: </b> ao cancelar / trancar a matrícula, todas as fatuas no status "Aguardando Pagamento" serão canceladas!</p>
                            </div>
                            
                          </form>
                        </div>
                      </div>
                  </div>
                  <div class="x_content">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Mensagens</a>
                        </li>                        
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Documentos</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Notas</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Financeiro</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Certificação</a>
                        </li>
                        <?php $te = ($inicio_imediato) ? "Matrícula" : "Turma"; ?>                                
                        <li role="presentation" class=""><a href="#tab_content6" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Prazos</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">

                        <!-- Histórico de Mensagens -->
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                          <form method="post" action="matricula/<?php echo $primeiro_parametro; ?>"  class="form-horizontal form-label-left">
                            <div class="input-group">
                              <input type="hidden" value="1" name="tipoMensagem" />
                              <input type="text" name="textoHistorico" required="required" class="form-control">
                              <span class="input-group-btn">
                              <button type="submit" class="btn btn-primary">Cadastrar Mensagem</button>
                              </span>
                            </div>
                          </form>
                          <div id="myTabContent" class="tab-content">  
                              <!-- start recent activity -->
                              <ul class="messages">
                                <?php
                                $busca_histórico = mysql_query("SELECT * FROM mensagens_matriculas INNER JOIN funcionarios ON mensagens_matriculas.id_funcionario = funcionarios.id_funcionario WHERE mensagens_matriculas.id_matricula='$primeiro_parametro' AND mensagens_matriculas.ativo_mensagem_matricula='1' ORDER BY mensagens_matriculas.data_mensagem DESC");
                                while ($resHistorico = mysql_fetch_array($busca_histórico)){ ?>
                                  <li class="row"><br>
                                      <img src="images/funcionarios/<?php echo $resHistorico['foto_funcionario']; ?>" class="avatar" alt="Avatar">
                                      <div class="message_wrapper">
                                        <div class="col-md-12"><h4 class="heading"><?php echo $resHistorico['nome_funcionario']; ?></h4></div>
                                        <div class="col-md-10">
                                          <blockquote class="message"><?php echo $resHistorico['texto_mensagem']; ?></blockquote>
                                        </div>
                                        <div class="col-md-2">
                                        <?php $tipoMensagem = $pics->tipoMensagem($resHistorico['tipo_mensagem']); ?>
                                          <p style="text-align: right;">Mensagem em Geral <br> 
                                          <?php echo $pics->timeStampMostrar($resHistorico['data_mensagem']); ?></p><br>
                                          <?php 
                                          //Excluir Mensagem
                                          if($resHistorico['id_funcionario'] == $_SESSION['funcionario']['id_funcionario']){
                                            echo "<a href='matricula/".$primeiro_parametro."/".$resHistorico['id_mensagem']."' class='btn btn-default btn-xs pull-right'><i class='fa fa-trash-o'></i></a>";
                                          } 
                                          ?>
                                       </div>
                                      </div>
                                    </li>
                                <?php } ?>
                              </ul>
                              <!-- end recent activity -->
                          </div>
                        </div>
                        <!-- FIM Histórico de Mensagens -->

                        <!-- Pedagógico -->
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="home-tab">
                          <!-- Histórico de Documentos -->
                          <div class="row">
                            <!-- Tudo entregue -->
                            <div class="col-md-12 col-sm-12 col-xs-12"><br>
                              <div class="x_panel">
                                <div class="x_content">  
                                  <div class="col-md-12 col-sm-12">
                                    <a href="<?php echo $base ?>documentos-zip/<?php echo $primeiro_parametro ?>" class="btn btn-app"><i class="fa fa-file-archive-o"></i> Baixar ZIP </a>
                                    <a href="<?php echo $base ?>documentos-zip/<?php echo $primeiro_parametro ?>/unificado" class="btn btn-app"><i class="fa fa-file-pdf-o"></i> Baixar PDF </a>
                                  </div>
                                </div>
                              </div>
                                
                              <!-- Tudo entregue -->
                              <div class="x_panel"><br>
                                
                                <div class="x_content">
                                  <table style="text-align: center;" class="table">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Responsável</th>
                                        <th style="text-align: center;"></th>
                                      </tr>
                                    </thead>
                                    <tbody>            
                                      <?php 
                                      //Busca os Itens já cadastrados
                                      $busca_itens = mysql_query("SELECT * FROM documentos_curso INNER JOIN turmas ON turmas.id_turma='$id_turma' WHERE documentos_curso.id_curso = turmas.id_curso AND documentos_curso.ativo_documento='1'");

                                      //Imprime
                                      while($item = mysql_fetch_array($busca_itens)){

                                        //Organiza
                                        $id_item = $item['id_documento_curso'];
                                        //Busca a nota
                                        $busca_nota = mysql_query("SELECT * FROM documentos_matriculas INNER JOIN funcionarios ON documentos_matriculas.id_funcionario = funcionarios.id_funcionario WHERE documentos_matriculas.id_documento_curso = '$id_item' AND documentos_matriculas.id_matricula='$id_matricula'");

                                        if(mysql_num_rows($busca_nota) == 0){
                                          $nota = "Pendente";
                                          $professor = "-";
                                          $liberar = "
                                          <form action='matricula/".$id_matricula."' method='post'>
                                            <input type='hidden' name='id_documento_curso' value='".$id_item."' />
                                            <button type='submit' class='btn btn-dark btn-xs'>Liberar</button>
                                          </form>

                                          ";
                                        }
                                        else{
                                          while($resNota = mysql_fetch_array($busca_nota)){
                                          $nota = "<i class='fa fa-check-square-o' aria-hidden='true'></i>";
                                          $professor = $resNota['nome_funcionario'];
                                          $liberar = '';                            
                                          }
                                        }

                                                                 
                                        echo "<tr style='text-align:center;'><td style='text-align:left'>".$item['titulo_documento']."</td><td>".$nota."</td><td>".$professor."</td><td>".$liberar."</td><td> <button type='button' class='btn btn-dark btn-xs' id='mostrar-recursos-aluno' idItem='".$id_item."'><i class='fa fa-chevron-down'></i></button></td></tr>";

                                        //Busca os Uploads                          
                                          $trs = "";
                                          //UPLOAD
                                          $busca_correcao = mysql_query("SELECT upload_documentos.id_upload_documento, upload_documentos.titulo_upload, funcionarios.nome_funcionario, upload_documentos.status_upload FROM upload_documentos LEFT JOIN funcionarios ON upload_documentos.id_funcionario = funcionarios.id_funcionario WHERE upload_documentos.ativo_upload_documento='1' AND upload_documentos.id_documento_curso='$id_item' AND upload_documentos.id_matricula='$id_matricula'");
                                          while($resCo = mysql_fetch_array($busca_correcao)){
                                            $titulo = $resCo['titulo_upload'];
                                            $id_upload = $resCo['id_upload_documento'];
                                            $professor = ($resCo['nome_funcionario'] == "") ? "-" : $resCo['nome_funcionario'];
                                            $data = ($resCo['data_comentario'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_comentario']);
                                            if($resCo['status_upload'] == 3){
                                              $nota = "<button type='button' class='btn btn-warning btn-xs'>Em Análise</button>";
                                            }
                                            elseif($resCo['status_upload'] == 2){
                                              $nota = "<button type='button' class='btn btn-danger btn-xs'>Reprovado</button>";
                                            }
                                            elseif($resCo['status_upload'] == 4){
                                              $nota = "<button type='button' class='btn btn-info btn-xs'>Ag. Assinatura</button>";
                                            }
                                            else{
                                              $nota = "<button type='button' class='btn btn-success btn-xs'>Aprovado</button>";
                                            }
                                            $iconTitulo = explode(".", $titulo);
                                            $icone = $pics->iconExtensao($iconTitulo[1]);
                                            $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='upload-documento/".$id_upload."' targer='_blank'><i class='".$icone."'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                            }        

                                        //Imprime os Recursos
                                        echo $trs;
                                        
                                      }

                                      ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Fim Histórico de Documentos -->

                          <!-- Upload -->
                          <div class="row">
                            <div class="col-md-12">
                              <div class="x_panel" style="overflow:auto;">
                                <div class="x_content">

                                  <div class="col-md-12 col-sm-12">
                                    <div class="x_title"><h2>Enviar Documento</h2><div class="clearfix"></div></div>
                                    
                                    <form action="matricula/<?php echo $primeiro_parametro; ?>" enctype="multipart/form-data" id="formAtividade" method="post" data-parsley-validate class="form-horizontal ">
                                      <input type="hidden" name="nome_aluno_upload" value="<?php echo $matricula['nome_aluno'] ?>" />
                                      <input type="hidden" name="turma_aluno_upload" value="<?php echo $matricula['id_turma'] ?>" />
                                      
                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label" for="titulo-curso">Tipo de Documento: <span class="required">*</span>
                                        </label>
                                        <select name="id_documentocurso" required="required" class="form-control">
                                          <option value=""></option>
                                          <?php
                                          $busca_documentos = mysql_query("SELECT * FROM documentos_curso INNER JOIN turmas ON turmas.id_turma='$id_turma' WHERE documentos_curso.id_curso=turmas.id_curso AND documentos_curso.ativo_documento='1'");
                                          while($resDoc = mysql_fetch_array($busca_documentos)){
                                            echo "<option value='".$resDoc['id_documento_curso']."'>".$resDoc['titulo_documento']."</option>";
                                          }
                                          ?>
                                        </select>
                                      </div>

                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label" for="titulo-curso">Tipo de Arquivo: <span class="required">*</span>
                                        </label><br>
                                        <div class="radio">
                                          <label class="" id="radioType" div="uploadType">
                                            <div class="iradio_flat-green"><input value="1" checked="checked" type="radio" class="flat" name="tipo"></ins></div> Arquivo para Upload
                                          </label>
                                        </div>
                                        <div class="radio">
                                          <label class="" id="radioType" div="linkType">
                                            <div class="iradio_flat-green"><input value="2" type="radio" class="flat" name="tipo"></ins></div> Link Externo
                                          </label>
                                        </div>
                                        <div class="radio">
                                          <label class="" id="radioType" div="htmlType">
                                            <div class="iradio_flat-green"><input value="3" type="radio" class="flat" name="tipo"></ins></div> Contrato (HTML)
                                          </label>
                                        </div>
                                      </div>

                                      <div class="col-md-12 col-sm-12 col-xs-12" id="uploadType">
                                        <label class="control-label" for="titulo-curso">Selecione o arquivo: <span class="required">*</span>
                                        </label>                              

                                        <input <?php echo $disableTextarea ?> type="file" id="uploadType" name="resposta[]" multiple="multiple" class="form-control">
                                      </div>

                                      <div class="col-md-12 col-sm-12 col-xs-12" style="display: none;" id="linkType">
                                        <label class="control-label" for="titulo-curso">Link do Arquivo: <span class="required">*</span>
                                        </label>
                                        <input type='text' name="link_externo" id="linkType" class="form-control"></input>
                                      </div>

                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label" for="titulo-curso">Status: <span class="required">*</span>
                                        </label>
                                        <select class="form-control" name="status_documento">                                          
                                          <option value="3">Em Análise</option>
                                          <option value="4">Ag. Assinatura</option>
                                          <option value="2">Reprovado</option>
                                          <option value="1">Aprovado</option>
                                        </select>
                                      </div>
                                  
                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label" for="titulo-curso">Observações do Documento:
                                        </label>
                                        <input type='text' name="descricao" class="form-control"></input>
                                      </div>

                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label" for="titulo-curso">Comentário da Análise:
                                        </label>
                                        <input type='text' name="comentario_documento" class="form-control"></input>
                                      </div>
                                      
                                      <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                                        <br><br>
                                        <input type="submit" class="btn btn-success" value="Enviar Documento" />
                                      </div>
                                    </form>

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Fim Upload -->
                        </div>
                        <!-- Fim Pedagógico -->

                        <!-- Coordenação -->
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="home-tab">
                          <div class="row"><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <!-- Tudo entregue -->
                              <div class="x_panel">
                                
                                <div class="x_content">
                                  <table style="text-align: center;" class="table">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th style="text-align: center;">CH</th>
                                        <th style="text-align: center;">Nota</th>
                                        <th style="text-align: center;">Professor</th>
                                        <th style="text-align: center;"></th>
                                      </tr>
                                    </thead>
                                    <tbody>            
                                      <?php 
                                      $inicio_imediato = $_SESSION['aluno']['inicio_imediato'];
                                      if($inicio_imediato == 0){
                                        //Busca os Itens já cadastrados
                                        $busca_itens = mysql_query("SELECT * FROM prazos_turmas INNER JOIN itens_curso ON itens_curso.id_item_curso=prazos_turmas.id_item WHERE prazos_turmas.id_turma='$id_turma' AND (itens_curso.id_disciplina = 0 OR itens_curso.id_disciplina='$id_disciplina') AND (itens_curso.ativo_item='1' OR (itens_curso.ativo_item='0' AND EXISTS (SELECT * FROM notas_matriculas WHERE id_matricula='$id_matricula' AND id_item = prazos_turmas.id_item))) AND itens_curso.tipo_item != '3' AND NOT EXISTS(SELECT * FROM prazos_matriculas WHERE id_matricula='$id_matricula' AND id_item=prazos_turmas.id_item) ORDER BY prazos_turmas.data_liberar ASC");

                                        //Imprime
                                        while($item = mysql_fetch_array($busca_itens)){

                                          //Organiza
                                          $id_item = $item['id_item_curso'];
                                          $ch_item = $item['ch_item'].'h';
                                          //Busca a nota
                                          $busca_nota = mysql_query("SELECT * FROM notas_matriculas LEFT JOIN professores ON notas_matriculas.id_professor = professores.id_professor WHERE notas_matriculas.id_item = '$id_item' AND notas_matriculas.id_matricula='$id_matricula'");
                                          if(mysql_num_rows($busca_nota) == 0){
                                            $nota = "-";
                                            $professor = "-";
                                          }
                                          else{
                                            while($resNota = mysql_fetch_array($busca_nota)){
                                            $nota = ($resNota['nota_matricula'] < 7) ? "<span style='color: #d43f3a;'>".$resNota['nota_matricula']." (DP)</span>" : "<span style='color: #4cae4c;'>".$resNota['nota_matricula']."</span>";
                                            $professor = $resNota['nome_professor'];                            
                                            }
                                          }
                                          

                                          //Imprime                         
                                          echo "<tr style='text-align:center;'><td style='text-align:left'>".$item['titulo_item']."</td><td>".$ch_item."</td><td>".$nota."</td><td>".$professor."</td><td><button type='button' class='btn btn-dark btn-xs' id='mostrar-recursos-aluno' idItem='".$id_item."'><i class='fa fa-chevron-down'></i></button></td></tr>";



                                          //Busca a escala
                                          $busca_escala = mysql_query("SELECT * FROM escalas_professores_turmas WHERE id_turma = '$id_turma' AND id_item = '$id_item'");
                                          //COM PROFESSOR ESCALADO
                                          if(mysql_num_rows($busca_escala) > 0){
                                            while($resEscala = mysql_fetch_array($busca_escala)){
                                              //Id da Escala
                                              $id_escala = $resEscala['id_escala'];
                                              //Busca os recursos da escala 
                                              $busca_recursos = mysql_query("SELECT * FROM recursos_escala WHERE id_escala='$id_escala' AND ativo_recurso='1' AND (tipo_recurso='2' OR tipo_recurso='4' OR tipo_recurso='5')");
                                              $trs = "";
                                              while($resRec = mysql_fetch_array($busca_recursos)){
                                                //Dados do Recurso
                                                $id_recurso = $resRec['id_recurso'];
                                                $tipo_recurso = $resRec['tipo_recurso'];
                                                switch ($tipo_recurso) {
                                                  case '2':
                                                    $busca_correcao = mysql_query("SELECT atividade_item.id_atividade, atividade_item.titulo_atividade, respostas_atividades.id_resposta, respostas_atividades.id_professor, respostas_atividades.nota_atividade, respostas_atividades.data_correcao, professores.nome_professor FROM atividade_item LEFT JOIN respostas_atividades ON atividade_item.id_atividade=respostas_atividades.id_atividade AND respostas_atividades.id_matricula='$id_matricula' AND respostas_atividades.ativo_resposta='1' LEFT JOIN professores ON respostas_atividades.id_professor = professores.id_professor WHERE atividade_item.ativo_atividade='1' AND atividade_item.id_item='$id_item' AND atividade_item.id_atividade='$id_recurso'");
                                                      while($resCo = mysql_fetch_array($busca_correcao)){
                                                        $titulo = $resCo['titulo_atividade'];
                                                        $id_atividade = $resCo['id_atividade'];
                                                        $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                        $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                        if($resCo['nota_atividade'] == 0 && $resCo['id_resposta'] != ""){
                                                          $nota = "<a href='atividade-item/".$id_atividade."' target='_blank'>Aguardando Correção</a>";
                                                        }
                                                        elseif($resCo['nota_atividade'] > 0){
                                                          $nota = $resCo['nota_atividade'];
                                                        }
                                                        else{
                                                          $nota = "-";
                                                        }
                                                        $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='atividade-item/".$id_atividade."' targer='_blank'><i class='fa fa-paper-plane'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                        }                                         
                                                    break;
                                                  case '4':
                                                    $busca_correcao = mysql_query("SELECT quizz_item.id_quizz, quizz_item.titulo_quizz, respostas_quizz.id_resposta, respostas_quizz.id_professor, respostas_quizz.nota_quizz, respostas_quizz.data_envio_quizz, professores.nome_professor FROM quizz_item LEFT JOIN respostas_quizz ON quizz_item.id_quizz=respostas_quizz.id_quizz AND respostas_quizz.id_matricula='$id_matricula' AND respostas_quizz.ativo_resposta='1' LEFT JOIN professores ON respostas_quizz.id_professor = professores.id_professor WHERE  quizz_item.ativo_quizz='1' AND quizz_item.id_item='$id_item' AND quizz_item.id_quizz='$id_recurso'");
                                                      while($resCo = mysql_fetch_array($busca_correcao)){
                                                        $titulo = $resCo['titulo_quizz'];
                                                        $id_quizz = $resCo['id_quizz'];
                                                        $id_resposta = $resCo['id_resposta'];
                                                        $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                        $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                        $nota = ($resCo['nota_quizz'] == "") ? "-" : $resCo['nota_quizz'];
                                                        $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='quizz/".$id_resposta."' targer='_blank'><i class='fa fa-comments'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                        } 
                                                    break;
                                                  case '5':
                                                    $busca_correcao = mysql_query("SELECT upload_item.id_upload, upload_item.titulo_upload, respostas_upload.id_resposta, respostas_upload.id_professor, respostas_upload.nota_upload, respostas_upload.data_correcao, professores.nome_professor FROM upload_item LEFT JOIN respostas_upload ON upload_item.id_upload=respostas_upload.id_upload AND respostas_upload.id_matricula='$id_matricula' AND respostas_upload.ativo_resposta='1' LEFT JOIN professores ON respostas_upload.id_professor = professores.id_professor WHERE upload_item.ativo_upload='1' AND upload_item.id_item='$id_item' AND upload_item.id_upload='$id_recurso'");
                                                      while($resCo = mysql_fetch_array($busca_correcao)){
                                                        $titulo = $resCo['titulo_upload'];
                                                        $id_upload = $resCo['id_upload'];
                                                        $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                        $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                        if($resCo['nota_upload'] == 0 && $resCo['id_resposta'] != ""){
                                                          $nota = "<a href='upload-item/".$id_upload."' target='_blank'>Aguardando Correção</a>";
                                                        }
                                                        elseif($resCo['nota_upload'] > 0){
                                                          $nota = $resCo['nota_upload'];
                                                        }
                                                        else{
                                                          $nota = "-";
                                                        }
                                                        $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='upload-item/".$id_upload."' targer='_blank'><i class='fa fa-upload'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                        }
                                                    break;
                                                }
                                              }
                                            }                                                           
                                          }                                                     
                                          
                                          //SEM PROFESSOR ESCALADO
                                          else{                                                          
                                            //Busca os recursos da item 
                                            $busca_recursos = mysql_query("SELECT * FROM recursos_item WHERE id_item='$id_item' AND ativo_recurso='1' AND (tipo_recurso='2' OR tipo_recurso='4' OR tipo_recurso='5')");
                                            $trs = "";
                                            while($resRec = mysql_fetch_array($busca_recursos)){
                                              //Dados do Recurso
                                              $id_recurso = $resRec['id_recurso'];
                                              $tipo_recurso = $resRec['tipo_recurso'];
                                              switch ($tipo_recurso) {
                                                case '2':
                                                  $busca_correcao = mysql_query("SELECT atividade_item.id_atividade, atividade_item.titulo_atividade, respostas_atividades.id_resposta, respostas_atividades.id_professor, respostas_atividades.nota_atividade, respostas_atividades.data_correcao, professores.nome_professor FROM atividade_item LEFT JOIN respostas_atividades ON atividade_item.id_atividade=respostas_atividades.id_atividade AND respostas_atividades.id_matricula='$id_matricula' AND respostas_atividades.ativo_resposta='1' LEFT JOIN professores ON respostas_atividades.id_professor = professores.id_professor WHERE atividade_item.ativo_atividade='1' AND atividade_item.id_item='$id_item' AND atividade_item.id_atividade='$id_recurso'");
                                                    while($resCo = mysql_fetch_array($busca_correcao)){
                                                      $titulo = $resCo['titulo_atividade'];
                                                      $id_atividade = $resCo['id_atividade'];
                                                      $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                      $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                      if($resCo['nota_atividade'] == 0 && $resCo['id_resposta'] != ""){
                                                        $nota = "<a href='atividade-item/".$id_atividade."' target='_blank'>Aguardando Correção</a>";
                                                      }
                                                      elseif($resCo['nota_atividade'] > 0){
                                                        $nota = $resCo['nota_atividade'];
                                                      }
                                                      else{
                                                        $nota = "-";
                                                      }
                                                      $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='atividade-item/".$id_atividade."' targer='_blank'><i class='fa fa-paper-plane'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                      }                                         
                                                  break;
                                                case '4':
                                                  $busca_correcao = mysql_query("SELECT quizz_item.id_quizz, quizz_item.titulo_quizz, respostas_quizz.id_resposta, respostas_quizz.id_professor, respostas_quizz.nota_quizz, respostas_quizz.data_envio_quizz, professores.nome_professor FROM quizz_item LEFT JOIN respostas_quizz ON quizz_item.id_quizz=respostas_quizz.id_quizz AND respostas_quizz.id_matricula='$id_matricula' AND respostas_quizz.ativo_resposta='1' LEFT JOIN professores ON respostas_quizz.id_professor = professores.id_professor WHERE  quizz_item.ativo_quizz='1' AND quizz_item.id_item='$id_item' AND quizz_item.id_quizz='$id_recurso'");
                                                    while($resCo = mysql_fetch_array($busca_correcao)){
                                                      $titulo = $resCo['titulo_quizz'];
                                                      $id_quizz = $resCo['id_quizz'];
                                                      $id_resposta = $resCo['id_resposta'];
                                                      $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                      $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                      $nota = ($resCo['nota_quizz'] == "") ? "-" : $resCo['nota_quizz'];
                                                      $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='quizz/".$id_resposta."' targer='_blank'><i class='fa fa-comments'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                      } 
                                                  break;
                                                case '5':
                                                  $busca_correcao = mysql_query("SELECT upload_item.id_upload, upload_item.titulo_upload, respostas_upload.id_resposta, respostas_upload.id_professor, respostas_upload.nota_upload, respostas_upload.data_correcao, professores.nome_professor FROM upload_item LEFT JOIN respostas_upload ON upload_item.id_upload=respostas_upload.id_upload AND respostas_upload.id_matricula='$id_matricula' AND respostas_upload.ativo_resposta='1' LEFT JOIN professores ON respostas_upload.id_professor = professores.id_professor WHERE upload_item.ativo_upload='1' AND upload_item.id_item='$id_item' AND upload_item.id_upload='$id_recurso'");
                                                    while($resCo = mysql_fetch_array($busca_correcao)){
                                                      $titulo = $resCo['titulo_upload'];
                                                      $id_upload = $resCo['id_upload'];
                                                      $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                      $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                      if($resCo['nota_upload'] == 0 && $resCo['id_resposta'] != ""){
                                                        $nota = "<a href='upload-item/".$id_upload."' target='_blank'>Aguardando Correção</a>";
                                                      }
                                                      elseif($resCo['nota_upload'] > 0){
                                                        $nota = $resCo['nota_upload'];
                                                      }
                                                      else{
                                                        $nota = "-";
                                                      }
                                                      $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='upload-item/".$id_upload."' targer='_blank'><i class='fa fa-upload'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                      }
                                                  break;
                                              }
                                            }
                                          }
                                          //Imprime os Recursos
                                          echo $trs;
                                        }
                                      }
                                      //Busca os Itens já cadastrados
                                      $busca_itens = mysql_query("SELECT * FROM prazos_matriculas INNER JOIN itens_curso ON itens_curso.id_item_curso=prazos_matriculas.id_item WHERE prazos_matriculas.id_matricula='$id_matricula' AND (itens_curso.ativo_item='1' OR (itens_curso.ativo_item='0' AND EXISTS (SELECT * FROM notas_matriculas WHERE id_matricula='$id_matricula' AND id_item = prazos_matriculas.id_item))) AND itens_curso.tipo_item != '3' ORDER BY prazos_matriculas.data_liberar ASC");

                                      //Imprime
                                      while($item = mysql_fetch_array($busca_itens)){

                                        //Organiza
                                        $id_item = $item['id_item_curso'];
                                        $ch_item = $item['ch_item'].'h';
                                        //Busca a nota
                                        $busca_nota = mysql_query("SELECT * FROM notas_matriculas LEFT JOIN professores ON notas_matriculas.id_professor = professores.id_professor WHERE notas_matriculas.id_item = '$id_item' AND notas_matriculas.id_matricula='$id_matricula'");
                                        if(mysql_num_rows($busca_nota) == 0){
                                          $nota = "-";
                                          $professor = "-";
                                        }
                                        else{
                                          while($resNota = mysql_fetch_array($busca_nota)){
                                          $nota = ($resNota['nota_matricula'] < 7) ? "<span style='color: #d43f3a;'>".$resNota['nota_matricula']." (DP)</span>" : "<span style='color: #4cae4c;'>".$resNota['nota_matricula']."</span>";
                                          $professor = $resNota['nome_professor'];                            
                                          }
                                        }
                                        

                                        //Imprime                         
                                        echo "<tr style='text-align:center;'><td style='text-align:left'>".$item['titulo_item']."</td><td>".$ch_item."</td><td>".$nota."</td><td>".$professor."</td><td><button type='button' class='btn btn-dark btn-xs' id='mostrar-recursos-aluno' idItem='".$id_item."'><i class='fa fa-chevron-down'></i></button></td></tr>";



                                        //Busca a escala
                                        $busca_escala = mysql_query("SELECT * FROM escalas_professores_turmas WHERE id_turma = '$id_turma' AND id_item = '$id_item'");
                                        //COM PROFESSOR ESCALADO
                                        if(mysql_num_rows($busca_escala) > 0){
                                          while($resEscala = mysql_fetch_array($busca_escala)){
                                            //Id da Escala
                                            $id_escala = $resEscala['id_escala'];
                                            //Busca os recursos da escala 
                                            $busca_recursos = mysql_query("SELECT * FROM recursos_escala WHERE id_escala='$id_escala' AND ativo_recurso='1' AND (tipo_recurso='2' OR tipo_recurso='4' OR tipo_recurso='5')");
                                            $trs = "";
                                            while($resRec = mysql_fetch_array($busca_recursos)){
                                              //Dados do Recurso
                                              $id_recurso = $resRec['id_recurso'];
                                              $tipo_recurso = $resRec['tipo_recurso'];
                                              switch ($tipo_recurso) {
                                                case '2':
                                                  $busca_correcao = mysql_query("SELECT atividade_item.id_atividade, atividade_item.titulo_atividade, respostas_atividades.id_resposta, respostas_atividades.id_professor, respostas_atividades.nota_atividade, respostas_atividades.data_correcao, professores.nome_professor FROM atividade_item LEFT JOIN respostas_atividades ON atividade_item.id_atividade=respostas_atividades.id_atividade AND respostas_atividades.id_matricula='$id_matricula' AND respostas_atividades.ativo_resposta='1' LEFT JOIN professores ON respostas_atividades.id_professor = professores.id_professor WHERE atividade_item.ativo_atividade='1' AND atividade_item.id_item='$id_item' AND atividade_item.id_atividade='$id_recurso'");
                                                    while($resCo = mysql_fetch_array($busca_correcao)){
                                                      $titulo = $resCo['titulo_atividade'];
                                                      $id_atividade = $resCo['id_atividade'];
                                                      $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                      $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                      if($resCo['nota_atividade'] == 0 && $resCo['id_resposta'] != ""){
                                                        $nota = "<a href='atividade-item/".$id_atividade."' target='_blank'>Aguardando Correção</a>";
                                                      }
                                                      elseif($resCo['nota_atividade'] > 0){
                                                        $nota = $resCo['nota_atividade'];
                                                      }
                                                      else{
                                                        $nota = "-";
                                                      }
                                                      $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='atividade-item/".$id_atividade."' targer='_blank'><i class='fa fa-paper-plane'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                      }                                         
                                                  break;
                                                case '4':
                                                  $busca_correcao = mysql_query("SELECT quizz_item.id_quizz, quizz_item.titulo_quizz, respostas_quizz.id_resposta, respostas_quizz.id_professor, respostas_quizz.nota_quizz, respostas_quizz.data_envio_quizz, professores.nome_professor FROM quizz_item LEFT JOIN respostas_quizz ON quizz_item.id_quizz=respostas_quizz.id_quizz AND respostas_quizz.id_matricula='$id_matricula' AND respostas_quizz.ativo_resposta='1' LEFT JOIN professores ON respostas_quizz.id_professor = professores.id_professor WHERE  quizz_item.ativo_quizz='1' AND quizz_item.id_item='$id_item' AND quizz_item.id_quizz='$id_recurso'");
                                                    while($resCo = mysql_fetch_array($busca_correcao)){
                                                      $titulo = $resCo['titulo_quizz'];
                                                      $id_quizz = $resCo['id_quizz'];
                                                      $id_resposta = $resCo['id_resposta'];
                                                      $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                      $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                      $nota = ($resCo['nota_quizz'] == "") ? "-" : $resCo['nota_quizz'];
                                                      $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='quizz/".$id_resposta."' targer='_blank'><i class='fa fa-comments'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                      } 
                                                  break;
                                                case '5':
                                                  $busca_correcao = mysql_query("SELECT upload_item.id_upload, upload_item.titulo_upload, respostas_upload.id_resposta, respostas_upload.id_professor, respostas_upload.nota_upload, respostas_upload.data_correcao, professores.nome_professor FROM upload_item LEFT JOIN respostas_upload ON upload_item.id_upload=respostas_upload.id_upload AND respostas_upload.id_matricula='$id_matricula' AND respostas_upload.ativo_resposta='1' LEFT JOIN professores ON respostas_upload.id_professor = professores.id_professor WHERE upload_item.ativo_upload='1' AND upload_item.id_item='$id_item' AND upload_item.id_upload='$id_recurso'");
                                                    while($resCo = mysql_fetch_array($busca_correcao)){
                                                      $titulo = $resCo['titulo_upload'];
                                                      $id_upload = $resCo['id_upload'];
                                                      $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                      $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                      if($resCo['nota_upload'] == 0 && $resCo['id_resposta'] != ""){
                                                        $nota = "<a href='upload-item/".$id_upload."' target='_blank'>Aguardando Correção</a>";
                                                      }
                                                      elseif($resCo['nota_upload'] > 0){
                                                        $nota = $resCo['nota_upload'];
                                                      }
                                                      else{
                                                        $nota = "-";
                                                      }
                                                      $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='upload-item/".$id_upload."' targer='_blank'><i class='fa fa-upload'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                      }
                                                  break;
                                              }
                                            }
                                          }                                                        
                                        }                                                     
                                        
                                        //SEM PROFESSOR ESCALADO
                                        else{                          
                                          //Busca os recursos da item 
                                          $busca_recursos = mysql_query("SELECT * FROM recursos_item WHERE id_item='$id_item' AND ativo_recurso='1' AND (tipo_recurso='2' OR tipo_recurso='4' OR tipo_recurso='5')");
                                          $trs = "";
                                          while($resRec = mysql_fetch_array($busca_recursos)){
                                            //Dados do Recurso
                                            $id_recurso = $resRec['id_recurso'];
                                            $tipo_recurso = $resRec['tipo_recurso'];
                                            switch ($tipo_recurso) {
                                              case '2':
                                                $busca_correcao = mysql_query("SELECT atividade_item.id_atividade, atividade_item.titulo_atividade, respostas_atividades.id_resposta, respostas_atividades.id_professor, respostas_atividades.nota_atividade, respostas_atividades.data_correcao, professores.nome_professor FROM atividade_item LEFT JOIN respostas_atividades ON atividade_item.id_atividade=respostas_atividades.id_atividade AND respostas_atividades.id_matricula='$id_matricula' AND respostas_atividades.ativo_resposta='1' LEFT JOIN professores ON respostas_atividades.id_professor = professores.id_professor WHERE atividade_item.ativo_atividade='1' AND atividade_item.id_item='$id_item' AND atividade_item.id_atividade='$id_recurso'");
                                                  while($resCo = mysql_fetch_array($busca_correcao)){
                                                    $titulo = $resCo['titulo_atividade'];
                                                    $id_atividade = $resCo['id_atividade'];
                                                    $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                    $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                    if($resCo['nota_atividade'] == 0 && $resCo['id_resposta'] != ""){
                                                      $nota = "<a href='atividade-item/".$id_atividade."' target='_blank'>Aguardando Correção</a>";
                                                    }
                                                    elseif($resCo['nota_atividade'] > 0){
                                                      $nota = $resCo['nota_atividade'];
                                                    }
                                                    else{
                                                      $nota = "-";
                                                    }
                                                    $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='atividade-item/".$id_atividade."' targer='_blank'><i class='fa fa-paper-plane'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                    }                                         
                                                break;
                                              case '4':
                                                $busca_correcao = mysql_query("SELECT quizz_item.id_quizz, quizz_item.titulo_quizz, respostas_quizz.id_resposta, respostas_quizz.id_professor, respostas_quizz.nota_quizz, respostas_quizz.data_envio_quizz, professores.nome_professor FROM quizz_item LEFT JOIN respostas_quizz ON quizz_item.id_quizz=respostas_quizz.id_quizz AND respostas_quizz.id_matricula='$id_matricula' AND respostas_quizz.ativo_resposta='1' LEFT JOIN professores ON respostas_quizz.id_professor = professores.id_professor WHERE  quizz_item.ativo_quizz='1' AND quizz_item.id_item='$id_item' AND quizz_item.id_quizz='$id_recurso'");
                                                  while($resCo = mysql_fetch_array($busca_correcao)){
                                                    $titulo = $resCo['titulo_quizz'];
                                                    $id_quizz = $resCo['id_quizz'];
                                                    $id_resposta = $resCo['id_resposta'];
                                                    $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                    $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                    $nota = ($resCo['nota_quizz'] == "") ? "-" : $resCo['nota_quizz'];
                                                    $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='quizz/".$id_resposta."' targer='_blank'><i class='fa fa-comments'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                    } 
                                                break;
                                              case '5':
                                                $busca_correcao = mysql_query("SELECT upload_item.id_upload, upload_item.titulo_upload, respostas_upload.id_resposta, respostas_upload.id_professor, respostas_upload.nota_upload, respostas_upload.data_correcao, professores.nome_professor FROM upload_item LEFT JOIN respostas_upload ON upload_item.id_upload=respostas_upload.id_upload AND respostas_upload.id_matricula='$id_matricula' AND respostas_upload.ativo_resposta='1' LEFT JOIN professores ON respostas_upload.id_professor = professores.id_professor WHERE upload_item.ativo_upload='1' AND upload_item.id_item='$id_item' AND upload_item.id_upload='$id_recurso'");
                                                  while($resCo = mysql_fetch_array($busca_correcao)){
                                                    $titulo = $resCo['titulo_upload'];
                                                    $id_upload = $resCo['id_upload'];
                                                    $professor = ($resCo['nome_professor'] == "") ? "-" : $resCo['nome_professor'];
                                                    $data = ($resCo['data_correcao'] == "") ? "-" : $pics->dataTimeStampMostrar($resCo['data_correcao']);
                                                    if($resCo['nota_upload'] == 0 && $resCo['id_resposta'] != ""){
                                                      $nota = "<a href='upload-item/".$id_upload."' target='_blank'>Aguardando Correção</a>";
                                                    }
                                                    elseif($resCo['nota_upload'] > 0){
                                                      $nota = $resCo['nota_upload'];
                                                    }
                                                    else{
                                                      $nota = "-";
                                                    }
                                                    $trs.="<tr trItem='".$id_item."' trRecurso='sim' style='display: none;'><td style='text-align:left; padding-left:30px;'><a href='upload-item/".$id_upload."' targer='_blank'><i class='fa fa-upload'></i> | ".$titulo."</a></td><td>".$nota."</td><td>".$professor."<td/><td></td></tr>"; 
                                                    }
                                                break;
                                            }
                                          }
                                          
                                        }

                                        //Imprime os Recursos
                                        echo $trs;
                                      }

                                      ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                        <!-- Fim Coordenação -->

                        <!-- Financeiro -->
                        <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="home-tab">
                          <div class="row"><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <!-- Tudo entregue -->
                              <div class="x_panel">                                
                                <div class="x_content">
                                  <table style="text-align: center;" class="table">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th style="text-align: center;">Valor</th>
                                        <th style="text-align: center;">Vencimento</th>
                                        <th style="text-align: center;">Tipo</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Pagamento</th>
                                        <th style="text-align: center;"></th>
                                      </tr>
                                    </thead>
                                    <tbody>            
                                      <?php 
                                      //Busca os Itens já cadastrados
                                      $busca_itens = mysql_query("SELECT * FROM faturas_matriculas LEFT JOIN funcionarios ON faturas_matriculas.id_funcionario_cadastro = funcionarios.id_funcionario WHERE faturas_matriculas.id_matricula='$primeiro_parametro' ORDER BY faturas_matriculas.data_vencimento_fatura");

                                      //Imprime
                                      while($item = mysql_fetch_array($busca_itens)){

                                        //Organiza
                                        $id_item = $item['id_fatura'];
                                        $data_vencimento_fatura = $pics->dataMostrar($item['data_vencimento_fatura']);
                                        $data_pagamento_fatura = $pics->dataMostrar($item['data_pagamento_fatura']);
                                        $data_cadastro_fatura = $pics->timeStampMostrar($item['data_cadastro_fatura']);
                                        $status_fatura = $pics->statusFatura($item['status_fatura']);
                                        $tipo_fatura = $pics->tipoFatura($item['tipo_fatura']);
                                        if($item['forma_pagamento'] == 1){$link = "../AVA/Boletos/".$id_matricula."/".$item['link_fatura'];}
                                        elseif($item['forma_pagamento'] == 2){$link = "fatura/".$id_item;}
                                        elseif($item['forma_pagamento'] == 3){$link = $item['link_fatura'];}
                                        elseif($item['forma_pagamento'] == 4){$link = $pics->asaasLinkFatura($item['link_fatura']);}

                                        //Data de Pagamento
                                        $pagamento = ($item['status_fatura'] == 3)  ? $data_pagamento_fatura : "<a title='Acessar Pagamento' href='".$link."' target='_blank'><u>Acessar Pagamento</u></a>";

                                                                 
                                        echo "
                                          <tr style='text-align:center;'>
                                            <td style='text-align:left'>#".$item['id_fatura']."</td>
                                            <td>R$ ".$pics->mascaras($item['valor_fatura'], 'moeda')."</td>
                                            <td>".$data_vencimento_fatura."</td>
                                            <td>".$tipo_fatura."</td>
                                            <td><button type='button' class='btn btn-".$status_fatura['tipo']." btn-xs'>".$status_fatura['texto']."</button></td>
                                            <td>".$pagamento."</td>
                                            <td> <a title='Acessar Fatura' href='fatura/".$id_item."' target='_blank' class='btn btn-dark btn-xs'><i class='fa fa-chevron-right'></i></a></td>
                                          </tr>";

                                          $id_funcionario_baixa = $item['id_funcionario_baixa'];
                                          $busca_funcionario_baixa = mysql_query("SELECT id_funcionario, nome_funcionario FROM funcionarios WHERE id_funcionario='$id_funcionario_baixa'");

                                          
                                                                  
                                        }

                                      ?>
                                    </tbody>
                                  </table>
                                  <p><a href="financeiro-mensais-asaas/<?php echo $primeiro_parametro ?>" target="_blank" style="text-decoration: underline;"><u>Atualizar faturas com o Asaas</u></a></p>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                        <!-- Fim Financeiro -->

                        <!-- Certificação -->
                        <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="home-tab">
                          <?php
                            //Busca solciitação
                            $busca_solicitacao = mysql_query("SELECT *, lotes_postagens.data_envio_lote AS data_postagem_lote, lotes_certificacao.titulo_lote AS titulo_lote_certificacao, solicitacoes_certificacao.id_lote AS id_lote_certificacao FROM solicitacoes_certificacao LEFT JOIN lotes_certificacao ON solicitacoes_certificacao.id_lote=lotes_certificacao.id_lote LEFT JOIN certificadoras ON solicitacoes_certificacao.id_certificadora=certificadoras.id_certificadora LEFT JOIN faturas_matriculas ON solicitacoes_certificacao.id_fatura_postagem=faturas_matriculas.id_fatura LEFT JOIN lotes_postagens ON solicitacoes_certificacao.id_lote_postagens = lotes_postagens.id_lote WHERE solicitacoes_certificacao.id_matricula='$id_matricula'");
                            if(mysql_num_rows($busca_solicitacao) > 0){?>
                              <!-- Com solicitação -->
                              <div class="row">
                                <div class="x_panel">
                                  <div class="x_content">
                                    <table class="table">
                                      <thead>
                                        <th>#</th>
                                        <th>Lote</th>
                                        <th>Data</th>                                        
                                        <th>Prazo</th>
                                        <th>Declaração</th>
                                        <th>Certificado</th>
                                        <th>Histórico</th>
                                        <th>Diploma</th>
                                        <th>Status</th>
                                        <th><th>
                                      </thead>
                                      <tbody>
                                        <?php while($resSolic = mysql_fetch_array($busca_solicitacao)){
                                          $statusCertificacao = $pics->statusCertificacao($resSolic["status_solicitacao_certificacao"]);
                                          $id_solicitacao = $resSolic['id_solicitacao_certificacao'];
                                          $rastreio_postagem = $resSolic['rastreio_postagem'];
                                          //Documentos
                                          $declaracao_recebido = ($resSolic['declaracao_recebido']) ? "<i class='fa fa-check'></i>" : "";
                                          $certificado_recebido = ($resSolic['certificado_recebido']) ? "<i class='fa fa-check'></i>" : "";
                                          $historico_recebido = ($resSolic['historico_recebido']) ? "<i class='fa fa-check'></i>" : "";
                                          $diploma_recebido = ($resSolic['diploma_recebido']) ? "<i class='fa fa-check'></i>" : "";
                                          //Fatura de Frete
                                          $id_lote_postagens = $resSolic['id_lote_postagens'];
                                          $id_fatura_postagem = $resSolic['id_fatura_postagem'];
                                          $status_fatura_postagem = $resSolic['status_fatura'];
                                          $id_rotulo_prepostagem = $resSolic['idRotuloPrepostagem'];
                                          $data_postagem_lote = $pics->dataMostrar($resSolic['data_postagem_lote']);
                                        ?>                                                             
                                          <tr>
                                            <td><?php echo $resSolic['id_solicitacao_certificacao']; ?></td>
                                            <td><?php echo $resSolic['id_lote_certificacao'].' - '.$resSolic['titulo_lote_certificacao']; ?></td>
                                            <td><?php echo $pics->timeStampMostrar($resSolic['data_cadastro_solicitacao_certificacao']); ?></td>
                                            <td><?php echo $pics->dataMostrar($resSolic['prazo_recebimento']); ?></td>
                                            <td><?php echo $declaracao_recebido ?></td> 
                                            <td><?php echo $certificado_recebido ?></td> 
                                            <td><?php echo$historico_recebido ?></td> 
                                            <td><?php echo$diploma_recebido ?></td> 
                                            <td><button type="button" class="btn btn-<?php echo $statusCertificacao['tipo'] ?> btn-xs"><?php echo $statusCertificacao['texto'] ?></button></td>                                            
                                            <td>                                              
                                              <a target='_blank' href="edit-certificacao/<?php echo $resSolic['id_solicitacao_certificacao'] ?>" class='btn btn-xs btn-warning'><i class="fa fa-pencil"></i></a> 
                                              <form method="post" action="matricula/<?php echo $primeiro_parametro ?>">
                                                <input type="hidden" name="id_solicitacao_del" value="<?php echo $resSolic['id_solicitacao_certificacao']; ?>" />
                                                <button type="submit" class='btn btn-xs btn-danger'><i class="fa fa-trash-o"></i></button>
                                              </form>
                                            </td>
                                          </tr>
                                        <?php } ?>
                                      </tbody>
                                    </table> 
                                  </div>
                                </div>

                                <div class="x_panel">
                                  <div class="x_content">
                                    <div class="row">
                                        <div  class="col-md-12 col-sm-12 col-xs-12">
                                          <a icon="fa fa-info-circle" text="Informação" preTexto="" tipo="1" id="btn-coin" class="btn btn-app"><i class="fa fa-info-circle"></i> Informação</a> 
                                          <?php if($id_rotulo_prepostagem != ""){?>
                                            <a target='_blank' href="etiqueta-correios/etiqueta/<?php echo $id_solicitacao; ?>" class='btn btn-app'><i class="fa fa-print"></i> Etiqueta </a> 
                                          <?php } else{ ?>
                                            <a id="btn-prepostagem" class="btn btn-app"><i class="fa fa fa-envelope-o"></i> Pré-Postagem</a> 
                                          <?php } ?>                                          
                                          <a icon="fa fa-circle" preTexto="Solicitação recebida e passando por análise da secretaria da Uniplena Educacional." text="Em Análise - Uniplena" tipo="2" id="btn-coin" class="btn btn-app"><i class="fa fa-circle" style="color: #ffc107;"></i> Em Análise - Uniplena</a>
                                          <a icon="fa fa-circle" preTexto="Solicitação passando por análise da secretaria da Faculdade Parceira." text="Em Análise - Faculdade" tipo="7" id="btn-coin" class="btn btn-app"><i class="fa fa-circle" style="color: #17a2b8;"></i> Em Análise - Faculdade</a>
                                          <a icon="fa fa-circle" text="Em Confecção" preTexto="Documentação em processo de confecção pela faculdade." tipo="3" id="btn-coin" class="btn btn-app"><i class="fa fa-circle" style="color: #007bff;"></i> Em Confecção</a>
                                          <a icon="fa fa-circle" text="Aguardando Retirada" preTexto="Documento pronto, aguardando a retirada por parte do aluno." tipo="4" id="btn-coin" class="btn btn-app"><i class="fa fa-circle" style="color: #6c757d;"></i> Ag. Retirada</a>
                                          <a icon="fa fa-circle" text="Entregue" preTexto="Documento entregue para o aluno, solicitação concluída." tipo="5" id="btn-coin" class="btn btn-app"><i class="fa fa-circle" style="color: #28a745;"></i> Entregue</a>
                                          <a icon="fa fa-trash-o" preTexto="A solicitação de certificação foi cancelada." text="Cancelada" preTexto="" tipo="6" id="btn-coin" class="btn btn-app"><i class="fa fa-trash-o"></i> Cancelada</a>
                                        </div>
                                    </div>

                                    <div class="row" id="texto-historico"><br><br>
                                      <label><i id="icone-historico" class="fa fa-info-circle"></i> - <span id="text-historico">Informação</span>:</label>
                                      <form method="post" action="matricula/<?php echo $primeiro_parametro; ?>"  class="form-horizontal form-label-left">
                                        <div class="input-group">
                                          <input type="hidden" name="idSo" value="<?php echo $id_solicitacao; ?>" />
                                          <input type="hidden" id="tipoMensagem" name="tipoMensagemSo" />
                                          <input type="text" id="textoHistorico" name="textoHistoricoSo" required="required" class="form-control">
                                          <input type="hidden" name="emailNotificacao" value="<?php echo $matricula['email_aluno'] ?>">
                                          <input type="hidden" name="nomeNotificacao" value="<?php echo $matricula['nome_aluno'] ?>">
                                          <span class="input-group-btn">
                                          <button type="submit" class="btn btn-primary">Cadastrar</button>
                                          </span>
                                        </div>
                                      </form>
                                    </div>

                                    <div class="row" style="display: none" id="texto-prepostagem"><br><br>
                                      <label><i class="fa fa-envelope-o"></i> - <span>Serviço da Pré-Postagem</span>:</label>
                                      <form method="post" action="matricula/<?php echo $primeiro_parametro; ?>"  class="form-horizontal form-label-left">
                                        <div class="input-group">
                                          <input type="hidden" name="idSoCorreios" value="<?php echo $id_solicitacao; ?>" />
                                          <?php
                                          //Calcula o frete nos correios
                                          $precoCorreios = $picsCorreios->calcularPrecoCorreios($pics->soNumero($matricula['cep_aluno']));
                                          $prazoCorreios = $picsCorreios->calcularPrazoCorreios($pics->soNumero($matricula['cep_aluno']));
                                          ?>
                                          <select name="servico_prepostagem" class="form-control">
                                            <option value="03298">PAC - R$ <?php echo $precoCorreios['PAC'] ?> - <?php echo $prazoCorreios['PAC'] ?> dias úteis</option>
                                            <option value="03220">SEDEX - R$ <?php echo $precoCorreios['SEDEX'] ?> - <?php echo $prazoCorreios['SEDEX'] ?> dias úteis</option>
                                          </select>
                                          <span class="input-group-btn">
                                          <button type="submit" class="btn btn-primary">Cadastrar</button>
                                          </span>
                                        </div>
                                      </form>
                                    </div>
                                  </div><br>
                                </div>

                                <!-- RASTREIO DE OBJETOS -->
                                <?php if($rastreio_postagem != ""){?>
                                <div class="x_panel"> 
                                  <div class="x_title">
                                    <h2><i class='fa fa-truck' aria-hidden='true'></i> Rastrear o objeto <b><?php echo $rastreio_postagem ?></b></h2> 
                                    
                                    <div class="clearfix"></div>
                                  </div> 
                                  <div class="x_content">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                      <?php
                                      //Rastrando Objetos
                                      $rastrearObjetoCorreios = $picsCorreios->rastrearObjetoCorreios($rastreio_postagem);
                                      if (is_array($rastrearObjetoCorreios)) {
                                          //Verifica se já foi postado
                                          if(isset($rastrearObjetoCorreios['objetos'][0]['eventos'])){
                                            echo "<ul>";
                                            foreach ($rastrearObjetoCorreios['objetos'][0]['eventos'] as $evento) {
                                                  $dataEvento = str_replace("T", " ", $evento['dtHrCriado']);
                                                  echo "<li>".$evento['descricao']." | <b>".$evento['unidade']['endereco']['cidade']." - ".$evento['unidade']['endereco']['uf']."</b> | ".$pics->timeStampMostrar($dataEvento)."</li>";
                                              }  
                                            echo "</ul><br>";
                                            echo "<img src='../Correios/logo-correios.png' width='100' />";
                                          }
                                          else{
                                              //Texto da mensagem
                                              $textoInf = (is_null($resSolic['data_postagem_lote'])) ? "Documento ainda não postado e sem nenhum lote de postagem." : "Documento está previsto para ser postado no dia: ".$data_postagem_lote." - Lote: <a href='lote-postagem/".$id_lote_postagens."' target='_blank'>".$id_lote_postagens."</a>";
                                              //Verifica se tem data de postagem
                                              echo $textoInf;
                                          }        
                                      } else {
                                          $textoInf = (is_null($resSolic['data_postagem_lote'])) ? "Documento ainda não postado e sem nenhum lote de postagem." : "Documento está previsto para ser postado no dia: ".$data_postagem_lote." - Lote: <a href='lote-postagem/".$id_lote_postagens."' target='_blank'>".$id_lote_postagens."</a>";
                                              //Verifica se tem data de postagem
                                              echo $textoInf;
                                      }
                                      echo "<br><br>";
                                      ?>
                                    </div>
                                  </div>
                                </div>
                                <?php } ?>

                                <div class="x_panel"> 
                                  <div class="x_title">
                                    <h2>Histórico de Atualizações</h2> 
                                    
                                    <div class="clearfix"></div>
                                  </div> 
                                  <div class="x_content">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                      <!-- start recent activity -->
                                      <ul class="messages">
                                      <?php
                                      $busca_historicoSo = mysql_query("SELECT * FROM mensagens_solicitacoes_certificacao WHERE id_solicitacao='$id_solicitacao' AND ativo_mensagem='1' ORDER BY data_mensagem DESC");
                                      
                                      while ($resHistoricoSo = mysql_fetch_array($busca_historicoSo)){ 
                                        //FUNCIONARIO
                                        if($resHistoricoSo['tipo_remetente'] == '1'){
                                          $func = $resHistoricoSo['id_remetente'];
                                          $busca_remetente = mysql_query("SELECT * FROM funcionarios WHERE id_funcionario='$func'");
                                          while ($resRemet = mysql_fetch_array($busca_remetente)) {
                                            //Separa os dados
                                            $foto_re = "images/funcionarios/".$resRemet['foto_funcionario'];
                                            $nome_re = $resRemet['nome_funcionario'];
                                          }
                                        }
                                        //ALUNO
                                        else{
                                          //Separa os dados
                                          $foto_re = '../AVA/build/images/user.png';
                                          $nome_re = $matricula['nome_aluno'];
                                        }
                                        ?>
                                        <li class="row"><br>
                                            <img src="<?php echo $foto_re; ?>" class="avatar" alt="Avatar">
                                            <div class="message_wrapper">
                                              <div class="col-md-12"><h4 class="heading"><?php echo $nome_re; ?></h4></div>
                                              <div class="col-md-10">
                                                <blockquote class="message"><?php echo $resHistoricoSo['texto_mensagem']; ?></blockquote><br>

                                                <?php
                                              //Aguardando Retirada
                                              if($resHistoricoSo['tipo_mensagem'] == 4){
                                                                                                
                                                //Solicitação com fatura de postagem
                                                if($id_fatura_postagem == 0 || $status_fatura_postagem == 0 || is_null($status_fatura_postagem)){
                                                  //Botão de Correios
                                                  echo "<button type='button' id='mostrarFrete' class='btn btn-info'><i class='fa fa-truck' aria-hidden='true'></i> | Enviar por correios</button></a>";
                                                  //Busca o CEP do aluno
                                                  $busca_aluno_cert = mysql_query("SELECT cep_aluno FROM alunos WHERE id_aluno='".$matricula['id_aluno']."'");
                                                  //Separa o CEP do aluno
                                                  $cep_aluno = mysql_fetch_assoc($busca_aluno_cert);
                                                  //Calcula o frete nos correios
                                                  $calcularPrecoCorreios = $picsCorreios->calcularPrecoCorreios($pics->soNumero($cep_aluno['cep_aluno']));
                                                  $calcularPrazoCorreios = $picsCorreios->calcularPrazoCorreios($pics->soNumero($cep_aluno['cep_aluno']));
                                                  //Imprime o formulário
                                                  ?>
                                                  <div id="dadosFrete" style="display: none;">
                                                    <hr>
                                                    <p>Frete calculado para o CEP <u><?php echo $cep_aluno['cep_aluno'] ?></u>, caso queira alterar, <a target="_blank
                                                      " href='edit-aluno/<?php echo $matricula['id_aluno'] ?>'>clique aqui</a> para editar o aluno.</p>
                                                    <form action="matricula/<?php echo $primeiro_parametro ?>"  method="post" id="formQuizz" data-parsley-validate class="form-horizontal form-label-left">
                                                      
                                                      <div class="form-group">
                                                      <!-- PAC -->
                                                      <?php
                                                      if($calcularPrecoCorreios['PAC'] != ""){
                                                        //Prepara o campo value
                                                        $value_option_pac = "PAC;".$calcularPrecoCorreios['PAC'].";".$calcularPrazoCorreios['PAC'].";".$cep_aluno['cep_aluno'].";".$id_solicitacao.";".$matricula['id_aluno'];
                                                      ?>
                                                      <div class="radio">
                                                        <label class="">
                                                          <div class="iradio_flat-green">
                                                            <input type="radio" required="required" value="<?php echo $value_option_pac ?>" class="flat" name="freteSolicitacao">
                                                          </div> <?php echo "<b>PAC: </b> R$ ".$calcularPrecoCorreios['PAC']." (".$calcularPrazoCorreios['PAC']." dia(s) úteis)"; ?>
                                                        </label>
                                                      </div>
                                                      <?php } ?>

                                                      <!-- SEDEX -->
                                                      <?php
                                                      if($calcularPrecoCorreios['SEDEX'] != ""){
                                                        //Prepara o campo value
                                                        $value_option_sedex = "SEDEX;".$calcularPrecoCorreios['SEDEX'].";".$calcularPrazoCorreios['SEDEX'].";".$cep_aluno['cep_aluno'].";".$id_solicitacao.";".$matricula['id_aluno'];
                                                      ?>
                                                      <div class="radio">
                                                        <label class="">
                                                          <div class="iradio_flat-green">
                                                            <input type="radio" required="required" value="<?php echo $value_option_sedex ?>" class="flat" name="freteSolicitacao">
                                                          </div> <?php echo "<b>SEDEX: </b> R$ ".$calcularPrecoCorreios['SEDEX']." (".$calcularPrazoCorreios['SEDEX']." dia(s) úteis)"; ?>
                                                        </label>
                                                      </div>
                                                      <?php } ?>                                            
                                                    </div>
                                                      <div class="form-group"><br>
                                                        <input type="button" class="btn btn-success" onclick="confirmForm('Atenção: o envio só será confirmado após o pagamento da fatura!','#formQuizz')" value="Gerar Fatura do Frete" />
                                                      </div>
                                                    </form>
                                                  </div>
                                                  <?php
                                                }
                                              }
                                              ?>
                                              </div>

                                              
                                              <div class="col-md-2">
                                              <?php $tipoMensagem = $pics->tipoMensagemSo($resHistoricoSo['tipo_mensagem']); ?>
                                                <p style="text-align: right;"><i class="fa <?php echo $tipoMensagem['icon'] ?>"></i> - <?php echo $tipoMensagem['texto'] ?> <br> 
                                                <?php echo $pics->timeStampMostrar($resHistoricoSo['data_mensagem']); ?></p><br>
                                                <?php 
                                                //Excluir Mensagem
                                                if($resHistoricoSo['tipo_mensagem'] == '1' && $resHistoricoSo['tipo_remetente'] == '1' && $resHistoricoSo['id_remetente'] == $_SESSION['funcionario']['id_funcionario']){
                                                  echo "<form method='post' action='matricula/".$primeiro_parametro."'><input type='hidden' value='".$resHistoricoSo['id_mensagem']."' name='dellMsgSo' /><button type='submit' class='btn btn-default btn-xs pull-right'><i class='fa fa-trash-o'></i></button></form>";
                                                }  
                                                ?>
                                             </div>
                                            </div>
                                        </li>
                                      <?php } ?>
                                      </ul>
                                      <!-- end recent activity -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php } else{ ?>
                              <!-- Sem solicitação -->
                              <div class="row">
                                <div class="x_panel">
                                  <div class="x_content">
                                    <h4>Ainda não houve solicitação de certificação (conclusão prevista para <?php echo $data_conclusao; ?>).</h4>
                                    
                                  </div>
                                </div>
                              </div>
                            <?php } ?>
                        </div>
                        <!-- Fim Certificação -->

                        <!-- Prazos -->
                        <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="home-tab">
                          <div class="row">
                            <div class="x_panel">

                              <div class="x_content">  
                                <?php
                                  //Busca os prazos da turma
                                  if($inicio_imediato == 0){
                                    $busca_prazos_turma = mysql_query("SELECT * FROM prazos_turmas INNER JOIN itens_curso ON itens_curso.id_item_curso=prazos_turmas.id_item WHERE prazos_turmas.id_turma='$id_turma' AND (itens_curso.id_disciplina='0' OR itens_curso.id_disciplina='$id_disciplina') ORDER BY prazos_turmas.data_liberar ASC");
                                ?>       
                                <!-- Prazos da Turma -->
                                <table class="table table-striped">
                                  <thead>
                                    <tr>
                                      <th>PRAZOS DA TURMA</th>
                                      <th>Liberação</th>
                                      <th>Prazo Regular</th>
                                      <th>Prazo Adicional</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  <?php 
                                    //Imprimi o resultado
                                    while($item = mysql_fetch_array($busca_prazos_turma)){
                                    ?>
                                    <tr>
                                      <th scope="row"><?php echo $item['titulo_item'] ?></th>
                                      <td><?php echo $pics->dataMostrar($item['data_liberar']) ?></td>
                                      <td><?php echo $pics->dataMostrar($item['data_regular']) ?></td>
                                      <td><?php echo $pics->dataMostrar($item['data_adicional']) ?></td>
                                    </tr>
                                    
                                  <?php } ?>
                                  </tbody>
                                </table>
                                <!-- Fim Prazos da Turma-->
                                <?php } 
                                $busca_prazos_matricula = mysql_query("SELECT * FROM prazos_matriculas INNER JOIN itens_curso ON itens_curso.id_item_curso=prazos_matriculas.id_item WHERE prazos_matriculas.id_matricula='$id_matricula' ORDER BY prazos_matriculas.data_liberar ASC");
                                if(mysql_num_rows($busca_prazos_matricula) > 0){
                                ?>
                                <!-- Prazos da Matrícula -->
                                <table class="table table-striped">
                                  <thead>
                                    <tr>
                                      <th>PRAZOS DA MATRÍCULA</th>
                                      <th>Liberação</th>
                                      <th>Prazo Regular</th>
                                      <th>Prazo Adicional</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  <?php 
                                    //Imprimi o resultado
                                    while($item = mysql_fetch_array($busca_prazos_matricula)){
                                    ?>
                                    <tr>
                                      <th scope="row"><?php echo $item['titulo_item'] ?></th>
                                      <td><?php echo $pics->dataMostrar($item['data_liberar']) ?></td>
                                      <td><?php echo $pics->dataMostrar($item['data_regular']) ?></td>
                                      <td><?php echo $pics->dataMostrar($item['data_adicional']) ?></td>
                                    </tr>
                                    
                                  <?php } ?>
                                  </tbody>
                                </table>
                                <?php } ?>
                                <!-- Fim Prazos da Matrícula -->


                                <p><a href="prazos-matricula/<?php echo $primeiro_parametro ?>" target="_blank" style="text-decoration: underline;"><u>Editar prazos da matrícula</u></a></p>



                                

                              </div>
                              
                            </div>
                          </div>
                        </div>
                        <!-- Fim Prazos-->
                      </div>
                    </div>
                  </div>                
                </div>                    
              </div>
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
