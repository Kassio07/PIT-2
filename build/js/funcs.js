
//Busca de endereço por CEP
$("[buscaCep=buscaCep]").focusout(function(){
	//Recebe o CEP
	cep = $("#cep").val();
	if(cep != ""){
		$.post("retornaEndereco.php",{
		cep_buscar: cep
		},function(data){
			$("#htmlCep").html(data);
			});
	}
});
//Atualizar Endereço
function atualizaInput(endereco, estado, cidade, bairro){
	$("input#endereco").val(endereco);
	$("input#estado").val(estado);
	$("input#cidade").val(cidade);
	$("input#bairro").val(bairro);
}
//Mascaras
$("[decimalInput=decimalInput]").maskMoney({decimal:",",thousands:"."});

//Botões Históricos
$("#texto-historico").hide();
$("a#btn-coin").click(function(){
	$("#texto-historico").show();
	$("#texto-prepostagem").hide();
	icone = $(this).attr("icon");
	texto = $(this).attr("text");
	value = $(this).attr("tipo");
	pretexto = $(this).attr("preTexto");
	$("#icone-historico").attr("class", icone);
	$("#text-historico").text(texto);
	$("#value-historico").val(value);
	$("#textoHistorico").val(pretexto);
	$("#tipoMensagem").val(value);
	if(value == '11'){$("#textoHistorico").inputmask("99/99/9999");}
	else{$("#textoHistorico").inputmask("remove");}
	});

//Botão Pré-Postagem
$("#btn-prepostagem").click(function(){
	$("#texto-historico").hide();
	$("#texto-prepostagem").show();
});

//Busca Aluno
$("#idAluno").focusout(function(){
	//Recebe o Id
	idaluno = $(this).val();
	if(idaluno != ""){
		$.post("retornaPrematricula.php",{
		id_aluno: idaluno
		},function(data){
			$("#htmlPrematricula").html(data);
			});
	}
});

//Busca Matricula
$("#idMatricula").focusout(function(){
	//Recebe o Id
	idaluno = $(this).val();
	if(idaluno != ""){
		$.post("retornaPrematricula.php",{
		id_matricula: idaluno
		},function(data){
			$("#htmlPrematricula").html(data);
			});
	}
});

//Atualizar InputAluno
function atualizaInputAluno(nome){
	if(nome == ""){
		alert("Nenhum aluno encontrado com esse ID!")
		$("#idAluno").val("");
		$("#nomeAluno").val("");
		$("#idAluno").focus();
	}
	else{$("#nomeAluno").val(nome);}
}

//Atualizar InputLote
function atualizaInputLote(nome, certificadora){
	if(nome == ""){
		alert("Nenhum lote encontrado com esse ID!")
		$("#idLote").val("");
		$("#tituloLote").val("");
		$("#idCertificadora").val("");
		$("#certifi").val('0');
	}
	else{
		$("#tituloLote").val(nome);
		$("#certifi").val(certificadora);
	}
}

//Busca Turma
$("input#idTurma").focusout(function(){
	//Recebe o Id
	idturma = $(this).val();
	if(idturma != ""){
		$.post("retornaPrematricula.php",{
		id_turma: idturma
		},function(data){
			$("#htmlPrematricula").html(data);
			});
	}
});

//Busca Turma
$("input#idLote").focusout(function(){
	//Recebe o Id
	idlote= $(this).val();
	if(idlote != ""){
		$.post("retornaLote.php",{
		id_lote: idlote
		},function(data){
			$("#htmlPrematricula").html(data);
			});
	}
});

//Atualizar InputTurma
function atualizaInputTurma(curso, unidade, inicio, disciplinas){
	if(curso == ""){
		alert("Nenhuma turma encontrada com esse ID!")
		$('select#disciplinas').find("option").remove();
		$("#curso").val("");
		$("#unidade").val("");
		$("#idTurma").val("");
		$('#dataTurma').val("");
		$("#idTurma").focus();
	}
	else{
		$('select#disciplinas').find("option").remove();
		$("#curso").val(curso);
		$("#unidade").val(unidade);
		$('#disciplinas').append(disciplinas);
		$('#dataTurma').val(inicio);
	}
}

//Formulário de PreMatrículas
$("select#buscarMes").change(function(){
	$("form#formBuscarPrematricula").submit();
});
//Formulário de PreMatrículas
$("select#buscarMes").change(function(){
	$("form#formBuscarMatricula").submit();
});

//Excluir Pré-Matrícula
$("button#excluriPrematricula").click(function(){
	prematricula = $(this).val();
	aluno = $(this).attr("idAluno");
	$("input#prematriculaDell").val(prematricula);
	$("input#alunoDell").val(aluno);
});

//Opção Voltar no Sistema
$("[type=reset]").text("Voltar");
$("[type=reset]").click(function(){
	history.back();
});

//Parametro Buscar Matrículas
//Esconde Selects
$("#valorCurso").hide();
$("#valorUnidade").hide();
$("#valorInscricao").hide();
$("#valorFuncionario").hide();
//Opção selecionada
$("#parametroMatriculas").change(function(){
	//Recebe os dados
	parametro = $(this).val();
	if(parametro == "turmas.id_unidade"){
		//Input Texto
		$("#valorInput").hide();
		$("#invalorInput").removeAttr("name");
		//Select Curso
		$("#valorCurso").hide();
		$("#invalorCurso").removeAttr("name");
		//Select Inscrição
		$("#valorInscricao").hide();
		$("#invalorInscricao").removeAttr("name");
		//Select Unidade
		$("#valorUnidade").show();
		$("#invalorUnidade").attr("name","valor");
		//Select Funcionario
		$("#valorVendedor").hide();
		$("#invalorVendedor").removeAttr("name");
	}
	else if(parametro == "turmas.id_curso"){
		//Input Texto
		$("#valorInput").hide();
		$("#invalorInput").removeAttr("name");
		//Select Curso
		$("#valorCurso").show();
		$("#invalorCurso").attr("name","valor");
		//Select Unidade
		$("#valorUnidade").hide();
		$("#invalorUnidade").removeAttr("name");
		//Select Inscrição
		$("#valorInscricao").hide();
		$("#invalorInscricao").removeAttr("name");
		//Select Funcionario
		$("#valorVendedor").hide();
		$("#invalorVendedor").removeAttr("name");
	}
	else if(parametro == "alunos.inscricao_aluno"){
		//Input Texto
		$("#valorInput").hide();
		$("#invalorInput").removeAttr("name");
		//Select Curso
		$("#valorCurso").hide();
		$("#invalorCurso").removeAttr("name","valor");
		//Select Unidade
		$("#valorUnidade").hide();
		$("#invalorUnidade").removeAttr("name");
		//Select Funcionario
		$("#valorVendedor").hide();
		$("#invalorVendedor").removeAttr("name");
		//Select Inscrição
		$("#valorInscricao").show();
		$("#invalorInscricao").attr("name","valor");
	}
	else if(parametro == "matriculas.id_funcionario"){
		//Input Texto
		$("#valorInput").hide();
		$("#invalorInput").removeAttr("name");
		//Select Curso
		$("#valorCurso").hide();
		$("#invalorCurso").removeAttr("name","valor");
		//Select Unidade
		$("#valorUnidade").hide();
		$("#invalorUnidade").removeAttr("name");
		//Select Inscrição
		$("#valorInscricao").hide();
		$("#invalorInscricao").removeAttr("name");
		//Select Funcionario
		$("#valorVendedor").show();
		$("#invalorVendedor").attr("name","valor");
	}
	else if(parametro == "prematriculas.id_funcionario"){
		//Input Texto
		$("#valorInput").hide();
		$("#invalorInput").removeAttr("name");
		//Select Curso
		$("#valorCurso").hide();
		$("#invalorCurso").removeAttr("name","valor");
		//Select Unidade
		$("#valorUnidade").hide();
		$("#invalorUnidade").removeAttr("name");
		//Select Inscrição
		$("#valorInscricao").hide();
		$("#invalorInscricao").removeAttr("name");
		//Select Funcionario
		$("#valorVendedor").show();
		$("#invalorVendedor").attr("name","valor");
	}
	else{
		//Input Texto
		$("#valorInput").show();
		$("#invalorInput").attr("name","valor");
		//Select Curso
		$("#valorCurso").hide();
		$("#invalorCurso").removeAttr("name");
		//Select Unidade
		$("#valorUnidade").hide();
		$("#invalorCurso").removeAttr("name");
		//Select Inscrição
		$("#valorInscricao").hide();
		$("#invalorInscricao").removeAttr("name");
		//Select Funcionario
		$("#valorVendedor").hide();
		$("#invalorVendedor").removeAttr("name");
		//Verifica se é CPF
		if(parametro == "alunos.cpf_aluno"){
			$("#valorInput").show();
			$("#invalorInput").inputmask("999.999.999-99");
		}
		else{
			$("#invalorInput").inputmask("remove");
		}
	}
});
//Buscar Aluno
$("#parametroAlunos").change(function(){
	//Recebe os dados
	parametro = $(this).val();
	//Caso seja CPF
	if(parametro == 'cpf_aluno' | parametro == 'alunos.cpf_aluno'){
		//Valor Texto
		$("#valorParametroAluno").show(); 
		$("#valorParametroAluno").inputmask("999.999.999-99");
		$("#valorParametroAluno").attr("name","valor");
		//Select Inscrição
		$("#valorParametroSelecAluno").hide();
		$("#valorParametroSelecAluno").removeAttr("name");
	}
	//Caso seja a inscrição
	else if(parametro == 'inscricao_aluno'){
		//Select Inscrição
		$("#valorParametroSelecAluno").show();
		$("#valorParametroSelecAluno").attr("name","valor");
		//Valor Texto
		$("#valorParametroAluno").hide(); 
		$("#valorParametroAluno").removeAttr("name");
	}
	//Caso não seja nenhum
	else{
		//Valor Texto
		$("#valorParametroAluno").show(); 
		$("#valorParametroAluno").inputmask("remove");
		$("#valorParametroAluno").attr("name","valor");
		//Select Inscrição
		$("#valorParametroSelecAluno").hide();
		$("#valorParametroSelecAluno").removeAttr("name");
	}
});

//Links Travados
$("[lock=true]").click(function(){
	event.preventDefault();
	alert('Esse item será liberado em breve!');
});

//Mostrar Nova Mensagem Fórum
$("#btnNovaMsgForum").click(function(){
	$(this).hide();
	$("#novaMsgForum").show();
});

//Confirmar Envio de Form
function confirmForm(msg, idform){ 
   if (confirm(msg)){ 
      $(idform).submit(); 
   }
   else {return false;} 
}

//Editor Textarea
$("[editor=true]").richText();

//Esconder notificações
$("[hideNotificacao]").click(function(){
	var tipo = $(this).attr("hideNotificacao"); 
	$("[tipoTr]").hide();
	$("[tipoTr="+tipo+"]").show();

});

//Editar Questõs do Quizz
$("[edit=questao]").click(function(){
	questao = $(this).attr("editV");
	$("[editP="+questao+"]").hide();
	$("[editF="+questao+"]").show();
	
});

//Adicionar alternativa
$("[showForm=true]").click(function(){
	alternativa = $(this).attr("form");
	$("[formShow="+alternativa+"]").show();
	$(this).hide();
});

//Buscar AVA
$("#id_curso").change(function(){
	id_curso = $(this).val();
	if(id_curso == ""){
		$("[opt=item]").find("optgroup").hide();
		$("[opt=item]").find("option").hide();
	}
	else{
		$("[opt=item]").hide();
		$("[opt=item]").removeAttr('name');
		$("[id="+id_curso+"]").attr('name', 'item');
		$("[id="+id_curso+"]").show();
		$("[id="+id_curso+"]").find("optgroup").show();
		$("[id="+id_curso+"]").find("option").show();
	}
	
});

//Grade Curricular
  $("button#mostrar-recursos").click(function(){
    display = $(this).parent().next("#recursos-nota").attr("style");
    if(display == "display: none;"){
      $("div#recursos-nota").slideUp();
      $(this).parent().next("#recursos-nota").slideDown();
      $(this).find("i").attr("class","fa fa-chevron-up");
    }
    else{
      $("div#recursos-nota").slideUp();
      $(this).find("i").attr("class","fa fa-chevron-down");
    }
  });

  //Grade Curricular (Aluno)
$("button#mostrar-recursos-aluno").click(function(){
	idItem = $(this).attr("idItem");
	displayA = $("[trItem="+idItem+"]").attr("style");
	if(displayA == "display: none;"){
		$("[trRecurso='sim']").hide();
		$("button#mostrar-recursos-aluno").find("i").attr("class","fa fa-chevron-down");
		$("[trItem="+idItem+"]").show();
		$(this).find("i").attr("class","fa fa-chevron-up");
	}
	else{
		$("[trRecurso='sim']").hide();
		$("button#mostrar-recursos-aluno").find("i").attr("class","fa fa-chevron-down");
	}		
});

//Enviar Documento
$("label#radioType").click(function(){
	div = $(this).attr("div");
	$("div#uploadType").hide();
	$("div#linkType").hide();
	$("input#uploadType").removeAttr("required");
	$("input#linkType").removeAttr("required");
	$("div#"+div).show();
	$("input#"+div).attr("required", "required");
});

//Forma de Pagamento - Fatura
$("#formaPagamento").change(function(){
	tipo = $(this).val();
	$("div#divPagamento").hide();
	$("[tipoPagamento="+tipo+"]").show();
});

//Mostrar TR - Correção Financeiro
$("button#showFaturas").click(function(){
	//TRs das Faturas
	var matricula = $(this).attr("matricula");
	$("tr#faturasMatricula").hide();
	$("tr[matricula="+matricula+"]").show();
	//Botão
	$(this).hide();
	$("button#hideFaturas[matricula="+matricula+"]").show();
});
//Esconder TR - Correção Financeiro
$("button#hideFaturas").click(function(){
	//TRs das Faturas
	var matricula = $(this).attr("matricula");
	$("tr#faturasMatricula").hide();
	$("tr[matricula="+matricula+"]").hide();
	//Botão
	$(this).hide();
	$("button#showFaturas[matricula="+matricula+"]").show();
});
//Excluir fatura - Correção Financeiro
$("button#dellFatura").click(function(){
	var fatura = $(this).attr("fatura");
	$("tr[fatura="+fatura+"]").remove();
});

//Validador de Senha
function verificaForcaSenha() 
      {
        var numeros = /([0-9])/;
        var alfabeto = /([a-zA-Z])/;
        var chEspeciais = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;

        if($('#password').val().length<6) 
        {
          $('#password-status').html("<div style='color:red; text-align:left;'>Fraco, insira no mínimo 6 caracteres</div>");
          $('#submitSenha').attr('disabled', 'disabled');
        } else {    
          if($('#password').val().match(numeros) && $('#password').val().match(alfabeto) && $('#password').val().match(chEspeciais))
          {            
            $('#password-status').html("<div style='color:green; text-align:left; '><b>Forte</b></div>");
            $('#submitSenha').removeAttr('disabled');
          } else {
            $('#password-status').html("<div style='color:orange; text-align:left;'>Médio, insira um caracter especial</div>");
            $('#submitSenha').attr('disabled', 'disabled');
          }
        }
      }

 //Formulário de Livro
 $("select#tipoLivro").change(function(){
 	escolha = $(this).val();
 	if(escolha == 1){$('div#livroDigital').hide();}
 	else{$('div#livroDigital').show();}
 });

 //Formulário de certificação
$('form[cert=iniCertificacao]').submit(function(){
	$('button#submitCert').hide();
	$('div#carreg').show();
});

 //Paginacao
 $('button#btnPaginacao').click(function(){
 	pagina = $(this).attr('pg');
 	actionAtual = $("form#formBusca").attr('action');
 	$("form#formBusca").attr('action', actionAtual+pagina);
 	$("form#formBusca").submit();
 });

 //Editar Título - Unidade de Aprendizagem
 $("button#uniEdit").click(function(){
 	idUni = $(this).attr("uniEdit");

 	$("font[uniId="+idUni+"]").hide();
 	$("form[uniForm="+idUni+"]").show();
 });

  //Dados do Frete - Solicitação de Certificacao
 $("button#mostrarFrete").click(function(){
 	$("div#dadosFrete").show();
 });

  //Logo Esquerda
 bodyAtual = ("body").attr("class"); 
 if(bodyAtual == "nav-sm"){
 		$("img.logo-incompleto").hide();
 		$("img.logo-completo").show();
 	}
 	else{
 		$("img.logo-incompleto").show();
 		$("img.logo-completo").hide();
 	}
 $("#menu_toggle").click(function(){
 	bodyAtual = ("body").attr("class"); 	
 	if(bodyAtual == "nav-sm"){
 		$("img.logo-incompleto").hide();
 		$("img.logo-completo").show();
 	}
 	else{
 		$("img.logo-incompleto").show();
 		$("img.logo-completo").hide();
 	}
 });
