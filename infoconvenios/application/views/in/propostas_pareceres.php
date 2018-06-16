<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>
<?php $filtro = $this->session->userdata('filtros'); ?>
<div id="content" class="innerAll bg-white">
<h1 class="bg-white content-heading border-bottom">Propostas e Pareceres</h1>

<style>
.panel-heading:hover {
	background-color: #DDD !important;
}
</style>


		<form class="form-horizontal" role="form" name="carrega_dados" method="post" id="carrega_dados" action="visualiza_propostas_pareceres"></form>
		
		<div class="input-group input-lg ">
			<input name="pesquisa" type="text" class="form-control"
				placeholder="Pesquisar" form="carrega_dados"
				<?php if(isset($filtro['pesquisa'])){echo "value=\"{$filtro['pesquisa']}\"";}?> />
			<div class="input-group-btn">
				<button class="btn btn-info" type="submit" form="carrega_dados" id="pesquisa_dados">
					<i class="fa fa-search"></i>
				</button>
			</div>
		</div>


<?php if(count($dados_propostas) > 0):?>
<h1 class="bg-white" style="text-align: right;">
	<form method="post" id="gera_pdf" action="<?php echo base_url();?>index.php/in/dados_siconv/gerapdf_lista_propostas"  target="_blank">
	<?php if($permissoes->relatorio_programa):?>
	<input type="submit" class="btn btn-primary" id="gerarPdf" style="float: left;" value="Gerar PDF"/>
	<?php endif;?>
	</form>
	<br>
</h1>
<?php endif;?>

<form method="post" action="<?php echo base_url('index.php/in/get_propostas/atualiza_pareceres'); ?>" id="atualiza_pareceres">
</form>

<h1 class="bg-white" style="text-align: left;">
	<p>
		<input type="checkbox" name="anos[]" form="carrega_dados" <?php if(isset($filtro['anos']) && in_array("TODOS", $filtro['anos'])){ echo "checked='checked'"; }?> value="TODOS" class="selecionarTodos">&nbsp;<span style="color: #428bca; font-size: 14px;">Todos os anos</span>
		<?php foreach ($anos as $ano):?>
		&nbsp;&nbsp;<input type="checkbox" form="carrega_dados" <?php if(isset($filtro['anos']) && in_array($ano->ano, $filtro['anos'])){ echo "checked='checked'"; }?> class="anos" name="anos[]" <?php echo (!isset($filtro['anos']) && $ano->ano == date("Y")) ? "checked='checked'" : ""; ?> value="<?php echo $ano->ano; ?>">&nbsp;<span style="color: #428bca; font-size: 14px;"><?php echo $ano->ano; ?></span>
		<?php endforeach;?>
		<br/>
		<span style="color: #428bca; font-size: 14px;">Status</span> 
		<select name="status_prop" form="carrega_dados">
			<option value="">Todas Propostas</option>
			<option value="1" <?php if(isset($filtro['status_prop']) && $filtro['status_prop'] == "1") {echo "selected='selected'";} ?>>Cadastradas e não enviadas</option>
			<option value="2" <?php if(isset($filtro['status_prop']) && $filtro['status_prop'] == "2") {echo "selected='selected'";} ?>>Enviadas para análise</option>
			<option value="3" <?php if(isset($filtro['status_prop']) && $filtro['status_prop'] == "3") {echo "selected='selected'";} ?>>Enviadas e aprovadas</option>
		</select>
		<input type="hidden" value="<?php if(isset($filtro['status_prop'])) {echo $filtro['status_prop'];} ?>" name="statusProp" form="gera_pdf">
		<br/>
		<input type="submit" id="pesquisar" value="Pesquisar" class="btn btn-primary">
<!-- 		<a class="btn btn-info" title="Atualizar Informações" id="atualizaParecer"><i class="fa fa-refresh"></i></a> -->
        
        <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
	</p>
</h1>

<?php if(count($dados_propostas) > 0):?>
<table class="table">
	<tr>
		<th></th>
		<th>Ano</th>
		<th>Orgão Superior</th>
		<th>Objeto</th>
		<?php if($num_cnpj > 1):?>
		<th>Instituição</th>
		<?php endif;?>
		<th>Nº Proposta</th>
		<th>Convênio</th>
                <th>Qualificação Proposta</th>
		<th>Situação</th>
		<th>Final Vigência</th>
		<th>Valor Global</th>
		<th>Valor Repasse</th>
		<th>Contrapartida</th>
		<th></th>
	</tr>
	
	<?php
	$total = 0;
	$totalValorGlobal = 0;
	$totalValorRepasse = 0;
	$totalValorContrapartida = 0;
	$i = 0;
	$ano = "";
	
	$totalAprv = 0;
	$totalValorGlobalAprv = 0;
	$totalValorRepasseAprv = 0;
	$totalValorContrapartidaAprv = 0;
	
	
	$totalEnviado = 0;
	$totalValorGlobalEnviado = 0;
	$totalValorRepasseEnviado = 0;
	$totalValorContrapartidaEnviado = 0;
	
	$totalCadastrado = 0;
	$totalValorGlobalCadastrado = 0;
	$totalValorRepasseCadastrado = 0;
	$totalValorContrapartidaCadastrado = 0;
	
	$totalEnviadoGeral = 0;
	$totalValorGlobalEnviadoGeral = 0;
	$totalValorRepasseEnviadoGeral = 0;
	$totalValorContrapartidaEnviadoGeral = 0;
	
	
	$totalGeral = 0;
	$totalValorGlobalGeral = 0;
	$totalValorRepasseGeral = 0;
	$totalValorContrapartidaGeral = 0;
	
	$totalAprvGeral = 0;
	$totalValorGlobalAprvGeral = 0;
	$totalValorRepasseAprvGeral = 0;
	$totalValorContrapartidaAprvGeral = 0;
	
	$totalCadastradoGeral = 0;
	$totalValorGlobalCadastradoGeral = 0;
	$totalValorRepasseCadastradoGeral = 0;
	$totalValorContrapartidaCadastradoGeral = 0;
	
	$indiceProposta = 1;
	?>
	<?php foreach ($dados_propostas as $propostas):?>
	
	<?php 
	if($ano == "" || $ano != $propostas->ano){
		$total = 0;
		$totalValorGlobal = 0;
		$totalValorRepasse = 0;
		$totalValorContrapartida = 0;
		
		$totalAprv = 0;
		$totalValorGlobalAprv = 0;
		$totalValorRepasseAprv = 0;
		$totalValorContrapartidaAprv = 0;
		
		$totalEnviado = 0;
		$totalValorGlobalEnviado = 0;
		$totalValorRepasseEnviado = 0;
		$totalValorContrapartidaEnviado = 0;
		
		$totalCadastrado = 0;
		$totalValorGlobalCadastrado = 0;
		$totalValorRepasseCadastrado = 0;
		$totalValorContrapartidaCadastrado = 0;
		
		$indiceProposta = 1;
	}
	?>
	
	<?php $valorContrapartida = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_contrapartida_financeira)))+str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_contrapartida_bens))); ?>
	<?php $totalValorGlobal += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global))); ?>
	<?php $totalValorRepasse += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse))); ?>
	<?php $totalValorContrapartida += $valorContrapartida; ?>
	
	<?php 
	$total++;
	
	if($banco_proposta_model->verifica_proposta_aprovada($propostas->situacao)){
		$totalAprv++;
		$totalValorGlobalAprv += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
		$totalValorRepasseAprv += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
		$totalValorContrapartidaAprv += $valorContrapartida;
		
		$totalAprvGeral++;
	}
	
	if($banco_proposta_model->verifica_proposta_enviada($propostas->situacao)){
		$totalEnviado++;
		$totalValorGlobalEnviado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
		$totalValorRepasseEnviado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
		$totalValorContrapartidaEnviado += $valorContrapartida;
		
		$totalEnviadoGeral++;
	}
	
	if($banco_proposta_model->verifica_proposta_cadastrado($propostas->situacao)){
		$totalCadastrado++;
		$totalValorGlobalCadastrado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
		$totalValorRepasseCadastrado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
		$totalValorContrapartidaCadastrado += $valorContrapartida;
	
		$totalCadastradoGeral++;
	}
	
	$totalGeral++;
	
	$acrecimo = 1;
	if($num_cnpj > 1)
		$acrecimo = 2;
	?>
	<tr>
		<td><?php echo $indiceProposta; ?></td>
		<td>
			<input type="hidden" name="ids_siconv[]" class="ids_siconv" value="<?php echo $propostas->id_siconv; ?>"/>
			<input type="hidden" form="gera_pdf" name="ids[]" class="ids" value="<?php echo $propostas->id_proposta; ?>"/>
			<?php echo $propostas->ano; ?>
		</td>
		<td style="min-width: 180px;"><?php echo $propostas->orgao; ?></td>
		<td><?php echo $propostas->objeto; ?></td>
		<?php if($num_cnpj > 1):?>
		<td><?php echo $programa_model->formatCPFCNPJ($propostas->proponente) ." - ". $cnpj_siconv->get_nome_by_cnpj($propostas->proponente, (isset($filtro['pesq_tipo']) && $filtro['pesq_tipo'] == "PAR")); ?></td>
		<?php endif;?>
		<td><a href="https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=<?php echo $propostas->id_siconv; ?>&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest" target="_blank"><?php echo $propostas->codigo_siconv; ?></a></td>
		<td><?php echo $propostas->convenio; ?></td>
                <?php if(isset($propostas->tipo)): ?>
                    <td><?php echo $propostas->tipo; ?></td>
                <?php else: ?>
                    <td>Não verificado</td>
                <?php endif; ?>
		<td><?php echo $propostas->situacao; ?></td>
		<td style="text-align: center;"><?php echo implode("/", array_reverse(explode("-", $propostas->data_fim))); ?></td>
		<td style="text-align: center;"><?php echo str_replace("R$ ", "", $propostas->valor_global); ?></td>
		<td style="text-align: center;"><?php echo str_replace("R$ ", "", $propostas->valor_repasse); ?></td>
		<td style="text-align: center;"><?php echo number_format($valorContrapartida, 2, ",", "."); ?></td>
		<td style="min-width: 80px;"><a href="detalha_propostas_pareceres?id=<?php echo $propostas->id_proposta; ?>">Detalhes e Pareceres</a></td>
	</tr>
	
	<?php $indiceProposta++;?>
	
	<?php $ano = $propostas->ano;?>
	
	<?php if($ano == "" || (isset($dados_propostas[$i+1]->ano) && $ano == $dados_propostas[$i+1]->ano)):?>
	
	<?php else:?>
	
	<?php 
	$totalValorGlobalGeral += $totalValorGlobal;
	$totalValorRepasseGeral += $totalValorRepasse;
	$totalValorContrapartidaGeral += $totalValorContrapartida;
	
	$totalValorGlobalAprvGeral += $totalValorGlobalAprv;
	$totalValorRepasseAprvGeral += $totalValorRepasseAprv;
	$totalValorContrapartidaAprvGeral += $totalValorContrapartidaAprv;
	
	$totalValorGlobalEnviadoGeral += $totalValorGlobalEnviado;
	$totalValorRepasseEnviadoGeral += $totalValorRepasseEnviado;
	$totalValorContrapartidaEnviadoGeral += $totalValorContrapartidaEnviado;
	
	$totalValorGlobalCadastradoGeral += $totalValorGlobalCadastrado;
	$totalValorRepasseCadastradoGeral += $totalValorRepasseCadastrado;
	$totalValorContrapartidaCadastradoGeral += $totalValorContrapartidaCadastrado;
	?>
	
	<tr><td colspan="<?php echo 12+$acrecimo; ?>">&nbsp;</td></tr>
	
	<tr>
		<td colspan='<?php echo 2+$acrecimo; ?>'><b>Total de Propostas:</b> <?php echo $total; ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Propostas Ano:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobal, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasse, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartida, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	
	<?php if(!isset($filtro['status_prop']) || $filtro['status_prop'] == "" || (isset($filtro['status_prop']) && $filtro['status_prop'] == "1")):?>
	<tr style="color: red;">
		<td colspan='<?php echo 2+$acrecimo; ?>'><b>Cadastradas e não Enviadas:</b> <?php echo $totalCadastrado; ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Cadastrado Ano:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalCadastrado, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseCadastrado, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaCadastrado, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php endif;?>
	
	<?php if(!isset($filtro['status_prop']) || $filtro['status_prop'] == "" || (isset($filtro['status_prop']) && $filtro['status_prop'] == "2")):?>
	<tr style="color: #428bca;">
		<td colspan='<?php echo 2+$acrecimo; ?>'><b>Enviadas para Análise:</b> <?php echo $totalEnviado; ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Enviadas Análise Ano:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalEnviado, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseEnviado, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaEnviado, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php endif;?>
	
	<?php if(!isset($filtro['status_prop']) || $filtro['status_prop'] == "" || (isset($filtro['status_prop']) && $filtro['status_prop'] == "3")):?>
	<tr style="color: green;">
		<td colspan='<?php echo 2+$acrecimo; ?>'><b>Enviadas e Aprovadas:</b> <?php echo $totalAprv; ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Aprovado:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalAprv, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseAprv, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaAprv, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php endif;?>
	
	<tr><td colspan="<?php echo 12+$acrecimo; ?>">&nbsp;</td></tr>
	
	<?php endif;?>
	<?php $i++;?>
	<?php endforeach;?>
	
	<tr><td colspan="<?php echo 12+$acrecimo; ?>">&nbsp;</td></tr>
	<tr><td colspan='<?php echo 12+$acrecimo; ?>' style="font-size: 14px;"><b>------------------------ DADOS GERAIS ------------------------</b></td></tr>
	
	<tr>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Propostas:</b> <?php echo $totalGeral; ?></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Propostas Ano:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaGeral, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php if(!isset($filtro['status_prop']) || $filtro['status_prop'] == "" || (isset($filtro['status_prop']) && $filtro['status_prop'] == "1")):?>
	<tr style="color: red;">
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Cadastradas e não Enviadas:</b> <?php echo $totalCadastradoGeral; ?></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Cadastrado Ano:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalCadastradoGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseCadastradoGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaCadastradoGeral, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php endif;?>
	
	<?php if(!isset($filtro['status_prop']) || $filtro['status_prop'] == "" || (isset($filtro['status_prop']) && $filtro['status_prop'] == "2")):?>
	<tr style="color: #428bca;">
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Enviadas para Análise:</b> <?php echo $totalEnviadoGeral; ?></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Em Análise Ano:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalEnviadoGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseEnviadoGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaEnviadoGeral, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php endif;?>
	
	<?php if(!isset($filtro['status_prop']) || $filtro['status_prop'] == "" || (isset($filtro['status_prop']) && $filtro['status_prop'] == "3")):?>
	<tr style="color: green;">
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Enviadas e Aprovadas:</b> <?php echo $totalAprvGeral; ?></td>
		<td></td>
		<td></td>
		<td colspan='<?php echo 3+$acrecimo; ?>'><b>Total Geral Aprovado:</b></td>
		<td style='text-align:center;'><?php echo number_format($totalValorGlobalAprvGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorRepasseAprvGeral, 2, ",", "."); ?></td>
		<td style='text-align:center;'><?php echo number_format($totalValorContrapartidaAprvGeral, 2, ",", "."); ?></td>
		<td></td>
	</tr>
	<?php endif;?>
</table>
<?php else:?>
	<h1 style="text-align: center;">Nenhum dado encontrado.</h1>
<?php endif;?>
</div>

<script type="text/javascript">
function mascaraData(campoData){
    var data = campoData.value;
    if (data.length == 2){
        data = data + '/';
        campoData.value = data;
		return true;              
    }
    if (data.length == 5){
        data = data + '/';
        campoData.value = data;
        return true;
    }
}
					
$(document).ready(function(){
	$("#loader").hide();
	$("#cod_parlamentar").hide();

    $("#atualizaParecer").click(function(){
        var qtdIDs = 0;
        var numIDs = 0;
        $(".ids_siconv").each(function(){
            $(this).attr('form', 'atualiza_pareceres');
//            var urlParecer = '<?php echo base_url().'index.php/in/get_propostas/get_parecer_empenho_banco_proposta_siconv/'?>'+$(this).val();
//             $.when(
//                 $.ajax({
//                     url:urlParecer,
//                     type:'get',
//                     dataType:'html',
//                     beforeSend:function(){
//                         $("#loader").slideDown();
//                     },
//                     success:function(data){
                        
//                     }
//                 });
//             ).done(function(){
//                 qdtIDs++;
//             });

//             numIDs++;
        });

        $("#loader").slideDown();

        $("#atualiza_pareceres").submit();

//         location.href=$(location).attr('href');
        return false;
    });
	
	$(".selecionarTodos").click(function(){
		$(".anos").each(function(){
			if($(".selecionarTodos").is(":checked"))
				$(this).attr("checked", $(".selecionarTodos").is(":checked"));
			else{
				if($(this).val() != "<?php echo date("Y"); ?>")
					$(this).attr("checked", $(".selecionarTodos").is(":checked"));
			}
		});
	});

	$(".anos").click(function(){
		if(!$(this).is(":checked")){
			if($(".selecionarTodos").is(":checked"))
				$(".selecionarTodos").attr("checked", false);
		}
	});

	$("#pesquisar").click(function(){
		var pesquisa = false;
		$(".anos").each(function(){
			if($(this).is(':checked'))
				pesquisa = true;
		});

		if(!pesquisa){
			alert('Selecione ao menos um ano para a busca');
			return false;
		}else
			$("#carrega_dados").submit();
	});
});
</script>