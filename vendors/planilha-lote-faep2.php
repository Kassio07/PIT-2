<?php
//Conecta no banco
include('conectar.php');
session_start();
//Verifica se o usuario esta logado
if(!isset($_SESSION['funcionario'])){header("Location: ".$base."logar/");}
?>
<table border="1">
	<tr style="font-weight: bold">
		<td>Nome</td>
		<td>Parceria	</td>
		<td>Genero	</td>
		<td>Diplomado/Diplomada	</td>
		<td>Licenciado/Licenciada	</td>
		<td>Nascido/Nascida	 </td>
		<td>Data Nascimento	</td>
		<td>RG	</td>
		<td>Org Exp RG	</td>
		<td>CPF	</td>
		<td>Cidade Naturalidade	</td>
		<td>Estado Naturalidade	</td>
		<td>Nacionalidade	</td>
		<td>Inicio Curso	</td>
		<td>Conclusão Curso	</td>
		<td>Data Colação	</td>
		<td>Data Expedição Diploma 	</td>
		<td>Data Expedição Histórico</td>	
		<td>Folha	</td>
		<td>Livro	</td>
		<td>Registro </td>
		<td>1a Graduação - Ensino Médio - Escola	</td>
		<td>Cidade	</td>
		<td>Estado	</td>
		<td>Ano de Conclusão</td>	
		<td>Curso 1a Graduação	</td>
		<td>Faculdade Colação - 1a Graduação	</td>
		<td>Data Conclusão - 1a Graduação	</td>
		<td>Faculdade Registro - 1a Graduação	</td>
		<td>Sob Número - 1a Graduação	</td>
		<td>Data Colação  1a Graduação	</td>
		<td>Enade Ano Ingresso	</td>
		<td>Enade Ano Conclusão</td>	
		<td>Curso Identificação	</td>
		<td>Curso Diploma	</td>
		<td>Total Horas	</td>
		<td>Disciplina1	</td>
		<td>Professor1	</td>
		<td>Carga Horaria1	</td>
		<td>Período1	</td>
		<td>Situação1	</td>
		<td>Nota1</td>	
		<td>Disciplina2	</td>
		<td>Professor2	</td>
		<td>Carga Horaria2	</td>
		<td>Período2	</td>
		<td>Situação2	</td>
		<td>Nota2	</td>
		<td>Disciplina3	</td>
		<td>Professor3	</td>
		<td>Carga Horaria3	</td>
		<td>Período3	</td>
		<td>Situação3	</td>
		<td>Nota3	</td>
		<td>Disciplina4	</td>
		<td>Professor4	</td>
		<td>Carga Horaria4	</td>
		<td>Período4	</td>
		<td>Situação4	</td>
		<td>Nota4	</td>
		<td>Disciplina5	</td>
		<td>Professor5	</td>
		<td>Carga Horaria5	</td>
		<td>Período5	</td>
		<td>Situação5	</td>
		<td>Nota5	</td>
		<td>Disciplina6	</td>
		<td>Professor6	</td>
		<td>Carga Horaria6	</td>
		<td>Período6	</td>
		<td>Situação6	</td>
		<td>Nota6	</td>
		<td>Disciplina7	</td>
		<td>Professor7	</td>
		<td>Carga Horaria7	</td>
		<td>Período7</td>	
		<td>Situação7	</td>
		<td>Nota7	</td>
		<td>Disciplina8	</td>
		<td>Professor8	</td>
		<td>Carga Horaria8	</td>
		<td>Período8	</td>
		<td>Situação8	</td>
		<td>Nota8</td>	
		<td>Disciplina9	</td>
		<td>Professor9	</td>
		<td>Carga Horaria9	</td>
		<td>Período9	</td>
		<td>Situação9	</td>
		<td>Nota9	</td>
		<td>Disciplina10	</td>
		<td>Professor10	</td>
		<td>Carga Horaria10	</td>
		<td>Período10	</td>
		<td>Situação10	</td>
		<td>Nota10	</td>
		<td>Disciplina11</td>	
		<td>Professor11	</td>
		<td>Carga Horaria11	</td>
		<td>Período11</td>	
		<td>Situação11	</td>
		<td>Nota11	</td>
		<td>Disciplina12	</td>
		<td>Professor12	</td>
		<td>Carga Horaria12	</td>
		<td>Período12	</td>
		<td>Situação12	</td>
		<td>Nota12	</td>
		<td>Disciplina13	</td>
		<td>Professor13	</td>
		<td>Carga Horaria13	</td>
		<td>Período13	</td>
		<td>Situação13	</td>
		<td>Nota13	</td>
		<td>Disciplina14</td>	
		<td>Professor14	</td>
		<td>Carga Horaria14	</td>
		<td>Período14	</td>
		<td>Situação14	</td>
		<td>Nota14	</td>
		<td>Disciplina15	</td>
		<td>Professor15	</td>
		<td>Carga Horaria15	</td>
		<td>Período15	</td>
		<td>Situação15	</td>
		<td>Nota15	</td>
		<td>Disciplina16	</td>
		<td>Professor16	</td>
		<td>Carga Horaria16	</td>
		<td>Período16	</td>
		<td>Situação16	</td>
		<td>Nota16	</td>
		<td>Disciplina17	</td>
		<td>Professor17	</td>
		<td>Carga Horaria17	</td>
		<td>Período17	</td>
		<td>Situação17	</td>
		<td>Nota17	</td>
		<td>Disciplina18</td>
		<td>Professor18	</td>
		<td>Carga Horaria18	</td>
		<td>Período18</td>	
		<td>Situação18	</td>
		<td>Nota18	</td>
		<td>Disciplina19	</td>
		<td>Professor19	</td>
		<td>Carga Horaria19	</td>
		<td>Período19	</td>
		<td>Situação19	</td>
		<td>Nota19	</td>
		<td>Disciplina20	</td>
		<td>Professor20	</td>
		<td>Carga Horaria20	</td>
		<td>Período20	</td>
		<td>Situação20	</td>
		<td>Nota20	</td>
		<td>Disciplina21	</td>
		<td>Professor21	</td>
		<td>Carga Horaria21	</td>
		<td>Período21	</td>
		<td>Situação21	</td>
		<td>Nota21	</td>
		<td>Disciplina22</td>	
		<td>Professor22	</td>
		<td>Carga Horaria22	</td>
		<td>Período22	</td>
		<td>Situação22	</td>
		<td>Nota22	</td>
		<td>Disciplina23	</td>
		<td>Professor23</td>	
		<td>Carga Horaria23	</td>
		<td>Período23	</td>
		<td>Situação23	</td>
		<td>Nota23	</td>
		<td>Disciplina24	</td>
		<td>Professor24	</td>
		<td>Carga Horaria24	</td>
		<td>Período24	</td>
		<td>Situação24	</td>
		<td>Nota24	</td>
		<td>Disciplina25</td>	
		<td>Professor25	</td>
		<td>Carga Horaria25	</td>
		<td>Período25	</td>
		<td>Situação25	</td>
		<td>Nota25	</td>
		<td>Disciplina26</td>	
		<td>Professor26	</td>
		<td>Carga Horaria26	</td>
		<td>Período26	</td>
		<td>Situação26	</td>
		<td>Nota26	</td>
		<td>Disciplina27	</td>
		<td>Professor27	</td>
		<td>Carga Horaria27	</td>
		<td>Período27	</td>
		<td>Situação27	</td>
		<td>Nota27	</td>
		<td>Disciplina28	</td>
		<td>Professor28	</td>
		<td>Carga Horaria28	</td>
		<td>Período28	</td>
		<td>Situação28	</td>
		<td>Nota28	</td>
		<td>Disciplina29	</td>
		<td>Professor29	</td>
		<td>Carga Horaria29	</td>
		<td>Período29</td>	
		<td>Situação29	</td>
		<td>Nota29	</td>
		<td>Disciplina30</td>	
		<td>Professor30	</td>
		<td>Carga Horaria30	</td>
		<td>Período30</td>	
		<td>Situação30	</td>
		<td>Nota30</td>	
		<td>Disciplina31	</td>
		<td>Professor31</td>	
		<td>Carga Horaria31	</td>
		<td>Período31	</td>
		<td>Situação31	</td>
		<td>Nota31	</td>
		<td>Disciplina32	</td>
		<td>Professor32	</td>
		<td>Carga Horaria32	</td>
		<td>Período32	</td>
		<td>Situação32	</td>
		<td>Nota32	</td>
		<td>Disciplina33	</td>
		<td>Professor33	</td>
		<td>Carga Horaria33	</td>
		<td>Período33	</td>
		<td>Situação33	</td>
		<td>Nota33	</td>
		<td>Disciplina34	</td>
		<td>Professor34	</td>
		<td>Carga Horaria34	</td>
		<td>Período34	</td>
		<td>Situação34	</td>
		<td>Nota34	</td>
		<td>Disciplina35	</td>
		<td>Professor35	</td>
		<td>Carga Horaria35	</td>
		<td>Período35	</td>
		<td>Situação35	</td>
		<td>Nota35	</td>
		<td>Disciplina36</td>	
		<td>Professor36</td>	
		<td>Carga Horaria36</td>	
		<td>Período36</td>	
		<td>Situação36	</td>
		<td>Nota36</td>	
		<td>Observações</td>
		<td>Portaria</td>		
	</tr>

	<?php
	//Busca as solicitações
	$busca_solicitacoes = mysql_query("SELECT * FROM solicitacoes_certificacao INNER JOIN matriculas ON solicitacoes_certificacao.id_matricula = matriculas.id_matricula INNER JOIN alunos ON matriculas.id_aluno=alunos.id_aluno INNER JOIN turmas ON matriculas.id_turma = turmas.id_turma INNER JOIN cursos ON turmas.id_curso=cursos.id_curso LEFT JOIN disciplinas_cursos ON matriculas.id_disciplina=disciplinas_cursos.id_disciplina WHERE solicitacoes_certificacao.id_lote='$primeiro_parametro' ORDER BY cursos.id_curso DESC, disciplinas_cursos.titulo_disciplina ASC");
	while ($resSoli = mysql_fetch_array($busca_solicitacoes)) {
		//Manipulações
		$sexo = $pics->retornaSexo($resSoli['sexo_aluno']);
		$inicio = ($resSoli['inicio_imediato']) ? $pics->DataTimeStampInserir($resSoli['data_cadastro_matricula']) : $resSoli['data_inicio_turma'];
		$conclusao = $pics->DataTimeStampInserir($resSoli['data_cadastro_solicitacao_certificacao']);
		$dataTermino = strtotime($inicio." 00:00:00 +".$resSoli['duracao_curso']." month");
		switch ($resSoli['tipo_graduacao_matricula']) {
			case '1':
				$tipo_graduacao = 'Bacharelado em ';
				break;
			case '2':
				$tipo_graduacao = 'Curso Superior de Tecnologia em ';
				break;
			case '3':
				$tipo_graduacao = 'Licenciatura em ';
				break;			
			default:
				$tipo_graduacao = 'Graduado em ';
				break;
		}
		?>
		
	<tr>
		<td><?php echo $resSoli['nome_aluno']; ?></td><!--Nome-->
		<td>Uniplena</td>
		<td><?php echo $sexo['sexo'] ?></td><!--Genero-->
		<td>Diplomad<?php echo $sexo['letra'] ?>	</td>
		<td>Licenciad<?php echo $sexo['letra'] ?>	</td>
		<td>Nascid<?php echo $sexo['letra'] ?>	 </td>
		<td><?php echo $pics->retornaDataCompleta($resSoli['nascimento_aluno']) ?></td><!--Data Nascimento-->
		<td><?php echo $resSoli['rg_aluno']; ?></td><!-- RG -->
		<td><?php echo $resSoli['orgao_rg_aluno']; ?> - <?php echo $resSoli['estado_rg_aluno']; ?></td><!-- Org Exp RG -->
		<td><?php echo $resSoli['cpf_aluno']; ?>	</td><!-- CPF -->
		<td><?php echo $resSoli['cidade_nascimento_aluno']; ?></td><!--Cidade Naturalidade -->		
		<td><?php echo $resSoli['estado_nascimento_aluno']; ?></td><!--Estado Naturalidade -->
		<td>Brasileir<?php echo $sexo['letra'] ?></td><!-- Nacionalidade -->
		<td><?php echo $pics->retornaDataCompleta($inicio)?></td><!--Inicio Curso-->
		<td><?php echo $pics->retornaDataCompleta(date('Y-m-d', $dataTermino))?>	</td><!-- Conclusão Curso -->
		<td><?php echo $pics->retornaDataCompleta(date('Y-m-d', $dataTermino))?></td><!-- Data Colação -->
		<td><?php echo $pics->retornaDataCompleta(date('Y-m-d', $dataTermino))?></td><!-- Data Colação -->
		<td><?php echo $pics->retornaDataCompleta(date('Y-m-d', $dataTermino))?></td><!-- Data Colação -->
		<td>	</td><!-- Folha -->
		<td>	</td><!-- Livro -->
		<td> </td><!-- Registro -->
		<td>	</td><!-- 1a Graduação - Ensino Médio - Escola -->
		<td>	</td><!-- Cidade -->
		<td>	</td><!-- Estado -->
		<td></td>	<!-- Ano de Conclusão -->
		<td><?php echo $tipo_graduacao.$resSoli['curso_formacao_matricula']; ?></td><!-- Curso 1a Graduação -->
		<td><?php echo $resSoli['faculdade_matricula']; ?></td><!--Faculdade Colação - 1a Graduação-->
		<td><?php echo $pics->retornaDataCompleta($resSoli['colacao_grau_matricula']); ?></td><!--Data Conclusão - 1a Graduação-->
		<td><?php echo $resSoli['universidade_registro']; ?></td><!--Faculdade Registro - 1a Graduação-->
		<td><?php echo $resSoli['registro_diploma']; ?></td><!--Sob Número - 1a Graduação-->
		<td><?php echo $pics->retornaDataCompleta($resSoli['colacao_grau_matricula']); ?></td><!--Data Colação  1a Graduação-->
		<td>Não aplicável</td><!--Enade Ano Ingresso-->
		<td>Dispensado em razão do triênio do curso.</td><!-- Enade Ano Conclusão -->	
		<td> <?php echo $resSoli['titulo_curso']; ?></td><!-- Curso Identificação -->
		<td><?php echo $resSoli['titulo_disciplina']; ?></td><!-- Curso Diploma -->

		<?php 
		//Busca os itens
		$busca_itens = mysql_query("SELECT notas_matriculas.nota_matricula, itens_curso.titulo_item, itens_curso.ch_item, dp_matriculas.id_item_dp, notas_matriculas.data_nota, itens_curso.id_disciplina, itens_curso.id_item_curso as IDITEM FROM notas_matriculas INNER JOIN itens_curso ON itens_curso.id_item_curso = notas_matriculas.id_item LEFT JOIN dp_matriculas ON dp_matriculas.id_item_regular = notas_matriculas.id_item AND dp_matriculas.id_matricula='".$resSoli['id_matricula']."' WHERE notas_matriculas.id_matricula='".$resSoli['id_matricula']."' AND itens_curso.tipo_item='1' GROUP BY notas_matriculas.id_item ORDER BY  itens_curso.id_disciplina, itens_curso.titulo_item ASC");
		$x = 0;
		$ch = 0;
		$id_matricula = $resSoli['id_matricula'];
		while($resItem = mysql_fetch_array($busca_itens)){
			$item[$id_matricula][$x] = $resItem; 
			$x = $x+1; 
			$ch = $ch + $resItem['ch_item'];
		}		
		echo "<td>".$ch." horas</td>";
		//TRs
		$i = 0;
		while ($i < 36) { 
			$nota = 0;
			if($item[$id_matricula][$i]['titulo_item'] != ""){
				//DP
				if(is_null($item[$id_matricula][$i]['id_item_dp'])){					
					//Período
					$dt = explode("-", $item[$id_matricula][$i]['data_nota']);
				}
				else{
					//Busca a última DP
					$busca_ultima_dp = mysql("SELECT * FROM dp_matriculas WHERE id_item_regular='".$item[$id_matricula][$i]['IDITEM']."' AND id_matricula='".$id_matricula."' ORDER BY id_dp DESC LIMIT 1");
					if(mysql_num_rows($busca_ultima_dp) == 0){
						$ultima_dp = $item[$id_matricula][$i]['id_item_dp'];
					}
					else{
						$ultima_dp_r = mysql_fetch_assoc($busca_ultima_dp);
						$ultima_dp = $ultima_dp_r['id_item_dp'];
					}
					//Busca a nota da DP
					$busca_dp = mysql_query("SELECT * FROM notas_matriculas WHERE id_item='".$ultima_dp."' AND id_matricula='".$resSoli['id_matricula']."'");
					while ($resNot = mysql_fetch_array($busca_dp)) {
						$nota = $resNot['nota_matricula'];
						//Período
						$dt = explode("-", $resNot['data_nota']);
					}
				}
				?>
			<td><?php echo $item[$id_matricula][$i]['titulo_item']; ?>	</td><!-- Disciplina -->
			<td> </td><!-- Professor -->
			<td><?php echo $item[$id_matricula][$i]['ch_item']; ?>h</td><!-- Carga Horaria -->
			<td> <?php echo $dt[0]; ?>/<?php if($dt[1] <= 6){echo "1";}else{echo "2";} ?> </td><!-- Período -->
			<td><?php echo "Aprovad".$sexo['letra']; ?></td><!-- Situação -->
			<td><?php if($nota == ""){echo $item[$id_matricula][$i]['nota_matricula'];}else{echo $nota;} ?></td><!-- Nota -->
		<?php } 

		else{ ?>
			<td></td><!-- Disciplina -->
			<td></td><!-- Professor -->
			<td></td><!-- Carga Horaria -->
			<td></td><!-- Período -->
			<td></td><!-- Situação -->
			<td></td><!-- Nota -->
		<?php 
			} 
			$i = $i + 1;
		} 
		?>		
		<td></td> <!-- Observações	--> 
		<td></td><!-- Portaria -->
	</tr>
	<?php } ?>
</table>