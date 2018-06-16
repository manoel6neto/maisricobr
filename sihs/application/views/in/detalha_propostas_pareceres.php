<div id="content" class="innerAll bg-white">
<h1 class="bg-white content-heading border-bottom">Detalhes Proposta <span style="color: #428bca; font-size: 24px;"><a style="color: #428bca;" href="https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=<?php echo $dados_proposta->id_siconv; ?>&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest" target="_blank"><?php echo $dados_proposta->codigo_siconv; ?></a></span></h1>

<div style="font-size: 13px;">
<b>Objeto:</b> <br/><?php echo $dados_proposta->objeto; ?><br/><br/>
<b>Justificativa:</b> <br><div style="text-align: justify;"><?php echo $dados_proposta->justificativa; ?></div><br/><br/>
<b>Modalidade:</b> <?php echo $dados_proposta->modalidade; ?><br/><br/>
<b>Orgão:</b> <?php echo $dados_proposta->orgao; ?><br/><br/>
<b>Programa:</b> <?php echo $dados_proposta->codigo_programa." - ".$dados_proposta->nome_programa; ?><br/><br/>
<b>Data Criação:</b> <?php echo implode("/", array_reverse(explode("-", $dados_proposta->data))); ?><br/><br/>
<b>Início:</b> <?php echo implode("/", array_reverse(explode("-", $dados_proposta->data_inicio))); ?><br/><br/>
<b>Fim:</b> <?php echo implode("/", array_reverse(explode("-", $dados_proposta->data_fim))); ?><br/><br/>
<b>Valor Global:</b> <?php echo str_replace("R$ ", "", $dados_proposta->valor_global); ?><br/><br/>
<b>Repasse:</b> <?php echo str_replace("R$ ", "", $dados_proposta->valor_repasse); ?><br/><br/>
<b>Contrapartida Financeira:</b> <?php echo str_replace("R$ ", "", $dados_proposta->valor_contrapartida_financeira); ?><br/><br/>
<b>Contrapartida Bens:</b> <?php echo str_replace("R$ ", "", $dados_proposta->valor_contrapartida_bens); ?><br/><br/>
<b>Situação:</b> <?php echo $dados_proposta->situacao; ?><br/><br/>
<b>Convênio:</b> <?php echo $dados_proposta->convenio; ?><br/><br/>

<div id="parecer_proposta">
<?php echo $dados_proposta->parecer; ?><br/>
</div>

<div id="parecer_proposta_plano_trabalho">
<?php echo $dados_proposta->parecer_plano_trabalho; ?><br/>
</div>

<div id="parecer_ajuste_proposta_plano_trabalho">
<?php echo $dados_proposta->parecer_ajuste_plano_trabalho; ?><br/>
</div>

<?php if($dados_empenho != null):?>
<h1 class="bg-white content-heading border-bottom">Empenhos</h1>
<?php 
foreach ($dados_empenho as $empenho)
	echo "<b>Tipo Empenho:</b> ".$empenho->especie_empenho."<br><table class='table'>{$empenho->tabela_cronograma_empenho}</table>"; 
?>

<br/><br/>
<?php endif;?>
</div>
<input type="hidden" value="<?php echo $dados_proposta->id_siconv; ?>" id="id_siconv">
<input class="btn btn-primary" type="button" value="Gerar PDF" onclick="window.open('<?php echo base_url(); ?>index.php/in/dados_siconv/gerapdf_detalhe_proposta?id=<?php echo $dados_proposta->id_proposta; ?>', '_blank');">

<?php if($this->session->userdata('sistema') != 'P'):?>
    <?php if(isset($geral)): ?>
        <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres?geral=1';">
    <?php else: ?>
        <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres';">
<?php endif; ?>

<?php elseif($this->session->userdata('sistema') == 'P'):?>
<input class="btn btn-primary" type="button" value="Voltar" onclick="window.close();">
<?php endif;?>

<?php if($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 6): ?>
<input class="btn btn-primary" type="button" value="Importar" onclick="location.href='<?php echo base_url(); ?>index.php/in/get_propostas/import_from_siconv?codigo_proposta=<?php echo $dados_proposta->codigo_siconv; ?>&banco_atende=Todos&nome_proposta=<?php echo $dados_proposta->codigo_siconv; ?>';">
<?php endif; ?>

<a class="btn btn-info" title="Atualizar Informações" id="atualizaParecer"><i class="fa fa-refresh"></i></a>
<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
</div>


<script type="text/javascript">
$(document).ready(function(){
	$("#loader").hide();
	
	$("#parecer_proposta .buttonLink").each(function(){
		var href = $(this).attr('href');
		
		var parteLink = href.split("?");
		var parteId = parteLink[1].split("&");
		
		var url = window.location.pathname;

		$(this).attr('href', $(location).attr('href')+"&"+parteId[0]+"&"+parteId[1]+"&tipo=parecer");
	});

	$("#parecer_proposta_plano_trabalho .buttonLink").each(function(){
		var href = $(this).attr('href');
		
		var parteLink = href.split("?");
		var parteId = parteLink[1].split("&");
		
		var url = window.location.pathname;

		$(this).attr('href', $(location).attr('href')+"&"+parteId[0]+"&"+parteId[1]+"&tipo=plano_trabalho&idProposta=<?php echo $dados_proposta->id_siconv; ?>");
	});

	$("#parecer_ajuste_proposta_plano_trabalho .buttonLink").each(function(){
		var href = $(this).attr('href');
		
		var parteLink = href.split("?");
		var parteId = parteLink[1].split("&");
		
		var url = window.location.pathname;

		$(this).attr('href', $(location).attr('href')+"&"+parteId[0]+"&"+parteId[1]+"&tipo=ajuste_plano_trabalho&idProposta=<?php echo $dados_proposta->id_siconv; ?>");
	});

	$(".valor").each(function(){
		var valor = $(this).html();
		if(valor != "Valor"){
			for(var i = 0; i < valor.length; i++)
				valor = valor.replace(",", "").replace("R", "").replace("$", "");
			$(this).html(formatNumber(parseFloat(valor)));
		}
	});

	$(".dataVencimento").each(function(){
		if($(this).html() != "Data Vencimento"){
			var data = $(this).html().split("/");
			$(this).html(data[1]+"/"+data[0]+"/"+data[2]);
		}
	});

	$(".dataRecebimento").each(function(){
		if($(this).html() != "Data Recebimento"){
			var data = $(this).html().split("/");
			$(this).html(data[1]+"/"+data[0]+"/"+data[2]);
		}
	});

	function formatNumber(number){
	    number = number.toFixed(2) + '';
	    x = number.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? ',' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + '.' + '$2');
	    }
	    return x1 + x2;
	}

	var dialog;
	
	dialog = $( "#dialog-message" ).dialog({
		height: 320,
		width: 550,
		modal: true,
		buttons: {
			"Fechar": function() {
				$( this ).dialog( "close" );
			}
		}
	}).position({
	    my: "center",
	    at: "center",
	    of: window
	});

	dialog.dialog( "close" );
	
	$(".buttonLink").click(function(){
		$("#msgParecer").html("");

		var linha = $(this);
		
		var data_parecer = $(this).parent().parent().parent().children("td:nth-child(1)").html().replace('<div class="data">', "").replace("</div>", "");
		dialog.dialog("option", "title", "Parecer - "+data_parecer);
		$.ajax({
			url:$(this).attr('href'),
			dataType:'html',
			success:function(data){
				linha.parent().parent().parent().children("td:nth-last-child(2)").html('<?php echo date("d/m/Y"); ?>');
				$("#msgParecer").html(data);
			}
		});
		
		dialog.dialog("open");

		return false;
	});

	$("#atualizaParecer").click(function(){
		var url = $(location).attr('href');
		var idProposta = url.split("?id=");
		var urlParecer = '<?php echo base_url().'index.php/in/get_propostas/get_parecer_empenho_banco_proposta_siconv/'?>'+idProposta[1];
		$.ajax({
			url:'<?php echo base_url().'index.php/in/get_propostas/get_propostas_siconv?'?>id_inicial='+$("#id_siconv").val()+'&id_final='+$("#id_siconv").val(),
			type:'get',
			dataType:'html',
			beforeSend:function(){
				$("#loader").slideDown();
			},
			success:function(data){
				$.ajax({
					url:urlParecer,
					type:'get',
					dataType:'html',
					beforeSend:function(){
						
					},
					success:function(data){
						location.href=$(location).attr('href');
					}
				});
			}
		});
		return false;
	});

	function insere_coluna_parecer(){
		var table = $("#parecer_proposta").find('table');
		table.attr('id', 'table_parecer');

		$("#table_parecer thead tr th:last").before("<th>Visualizado em</th>");

		$("#table_parecer tbody tr").each(function(index, value){
			$("#table_parecer tbody tr:nth-child("+(index+1)+") td:last").before("<td></td>");
			
			var href = $("#table_parecer tbody tr:nth-child("+(index+1)+") td:last a").attr('href');
			
			var parteLink = href.split("?");
			var parteId = parteLink[1].split("&");
			
			$.ajax({
				url:"<?php echo base_url('index.php/in/dados_siconv/get_data_visualizado/');?>?"+parteId[0]+"&"+parteId[1]+"&"+parteId[2],
				type:"get",
				dataType:"html",
				success:function(dataRetorno){
					$("#table_parecer tbody tr:nth-child("+(index+1)+") td:nth-last-child(2)").append(dataRetorno);
				}
			});
		});

		var table = $("#parecer_proposta_plano_trabalho").find('table');
		table.attr('id', 'table_parecer_plano_trabalho');

		$("#table_parecer_plano_trabalho thead tr th:last").before("<th>Visualizado em</th>");

		$("#table_parecer_plano_trabalho tbody tr").each(function(index, value){
			$("#table_parecer_plano_trabalho tbody tr:nth-child("+(index+1)+") td:last").before("<td></td>");
			
			var href = $("#table_parecer_plano_trabalho tbody tr:nth-child("+(index+1)+") td:last a").attr('href');
			
			var parteLink = href.split("?");
			var parteId = parteLink[1].split("&");
			/*
			$.ajax({
				url:"<?php echo base_url('index.php/in/dados_siconv/get_data_visualizado/');?>?"+parteId[0]+"&"+parteId[1]+"&"+parteId[2],
				type:"get",
				dataType:"html",
				success:function(dataRetorno){
					$("#table_parecer_plano_trabalho tbody tr:nth-child("+(index+1)+") td:nth-last-child(2)").append(dataRetorno);
				}
			});*/
		});

		var table = $("#parecer_ajuste_proposta_plano_trabalho").find('table');
		table.attr('id', 'table_parecer_ajuste_plano_trabalho');

		$("#table_parecer_ajuste_plano_trabalho thead tr th:last").before("<th>Visualizado em</th>");

		$("#table_parecer_ajuste_plano_trabalho tbody tr").each(function(index, value){
			$("#table_parecer_ajuste_plano_trabalho tbody tr:nth-child("+(index+1)+") td:last").before("<td></td>");
			
			var href = $("#table_parecer_ajuste_plano_trabalho tbody tr:nth-child("+(index+1)+") td:last a").attr('href');
			
			var parteLink = href.split("?");
			var parteId = parteLink[1].split("&");
			/*
			$.ajax({
				url:"<?php echo base_url('index.php/in/dados_siconv/get_data_visualizado/');?>?"+parteId[0]+"&"+parteId[1]+"&"+parteId[2],
				type:"get",
				dataType:"html",
				success:function(dataRetorno){
					$("#table_parecer_ajuste_plano_trabalho tbody tr:nth-child("+(index+1)+") td:nth-last-child(2)").append(dataRetorno);
				}
			});*/
		});
	}

	function altera_titulo(){
		$("h3").each(function(){
			if($(this).html() == "Lista Pareceres de Proposta")
				$(this).html("Lista Pareceres da Proposta").css({"text-transform":"uppercase"});

			if($(this).html() == "Lista Pareceres do Plano de Trabalho")
				$(this).html("Lista Pareceres do Plano de Trabalho").css({"text-transform":"uppercase"});
			
			if($(this).html() == "Lista Pareceres das Solicitações de Ajuste do Plano de Trabalho")
				$(this).html("Lista Pareceres das Solicitações de Ajuste do Plano de Trabalho").css({"text-transform":"uppercase"});
		});
	}

	altera_titulo();

	insere_coluna_parecer();
});
</script>

<div id="dialog-message" title="Parecer">
<p style="font-size: 16px;" id="msgParecer"></p>
</div>