]<script src="<?php echo base_url('configuracoes/js/jquery.mask.js'); ?>"></script>

<div id="content" class="innerAll bg-white">
	<h1 class="bg-white content-heading border-bottom">Nova Proposta</h1>
	
<div class="col-md-3">
	
<?php 
$parcelas = array(''=>'Selecione');
for($i = 1; $i <= 6; $i++)
	$parcelas[$i] = $i." parcela(s)";
?>
	
<?php echo form_open(); ?>

<?php echo validation_errors(); ?>

<div class="form-group">
	<?php echo form_label('Descrição Proposta *', 'descricao_proposta_comercial');?>
	<?php echo form_input('descricao_proposta_comercial', set_value('descricao_proposta_comercial', isset($proposta) ? $proposta->descricao_proposta_comercial : ''), 'class="form-control" maxlength="150"'); ?>
</div>

<div class="form-group">
	<?php echo form_label('Tipo Proposta *', 'tipo_proposta');?>
	<?php echo form_dropdown('tipo_proposta', $tipo_proposta, set_value('tipo_proposta', isset($proposta) ? $proposta->tipo_proposta : ''), 'class="form-control" id="tipo_proposta"'); ?>
</div>

<div class="form-group">
	<?php echo form_label('Tipo da Entidade *', 'nome_entidade');?>
	<?php echo form_input('nome_entidade', set_value('nome_entidade', isset($proposta) ? $proposta->descricao_proposta_comercial : ''), 'class="form-control" maxlength="255"'); ?>
</div>

<div id="dados_estadual">
	<div class="form-group">
		<?php echo form_label('Valor da Proposta *', 'valor_proposta_comercial');?>
		<?php echo form_input('valor_proposta_comercial', set_value('valor_proposta_comercial', isset($proposta) ? $proposta->valor_proposta_comercial : ''), 'class="form-control" id="valor_proposta_comercial"'); ?>
	</div>
</div>

<div id="dados_interesse">
	<div class="form-group">
		<?php echo form_label('Entidade *', 'entidade');?>
		<?php echo form_dropdown('entidade', $entidade_interesse, set_value('entidade', isset($proposta) ? $proposta->tipo_proposta : ''), 'class="form-control"'); ?>
	</div>
</div>

<div id="dados_consorcio">
	<div class="form-group">
		<?php echo form_label('Nº Entes Consorciados *', 'num_associado');?>
		<?php echo form_input('num_associado', set_value('num_associado', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
	</div>
</div>

<div id="dados_parlamentar">
	<div class="form-group">
		<?php echo form_label('Nº CNPJs *', 'num_parlamentar');?>
		<?php echo form_input('num_parlamentar', set_value('num_parlamentar', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
	</div>
</div>

<div id="dados_casadinha">
	<div class="form-group" id="eh_capital">
		<?php echo form_checkbox('capital', '1', FALSE, 'id="capital"'); ?>
		<?php echo form_label('Capital?', 'capital');?>
	</div>
	
	<div class="form-group" id="eh_capital">
		<?php echo form_checkbox('cnpj_extra', '1', FALSE, 'id="cnpj_extra"'); ?>
		<?php echo form_label('CNPJs Extras', 'cnpj_extra');?>
	</div>
	
	<div id="nums_cnpj_extra">
		<div class="form-group">
			<?php echo form_label('Nº CNPJ Extra Eco Mista', 'num_cnpj');?>
			<?php echo form_input('num_cnpj', set_value('num_cnpj', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
		</div>
		<!-- 
		<div class="form-group">
			<?php echo form_label('Nº CNPJ Extra Autarquias', 'num_cnpj_autarquias');?>
			<?php echo form_input('num_cnpj_autarquias', set_value('num_cnpj_autarquias', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
		</div>
		 -->
		<div class="form-group">
			<?php echo form_label('Nº CNPJ Extra P.S/FINS LUCRAT.', 'num_cnpj_sem_fim');?>
			<?php echo form_input('num_cnpj_sem_fim', set_value('num_cnpj_sem_fim', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
		</div>
	</div>
</div>

<div class="form-group">
	<?php echo form_label('Período *', 'periodo');?>
	<?php echo form_dropdown('periodo', array('1'=>'1 mês', '2'=>'2 meses', '3'=>'3 meses', '4'=>'4 meses', '5'=>'5 meses', '6'=>'6 meses', '7'=>'7 meses', '8'=>'8 meses', '9'=>'9 meses', '10'=>'10 meses', '11'=>'11 meses', '12'=>'12 meses'), set_value('periodo', isset($proposta) ? $proposta->periodo_proposta_comercial : ''), 'class="form-control"'); ?>
</div>

<div class="form-group">
	<?php echo form_label('Parcelas *', 'parcelas');?>
	<?php echo form_dropdown('parcelas', $parcelas, set_value('parcelas', isset($proposta) ? $proposta->parcelas_proposta_comercial : ''), 'class="form-control"'); ?>
</div>

<div class="form-group">
	<?php echo form_checkbox('percentDesc', '1', FALSE, 'id="percentDesc"'); ?>
	<?php echo form_label('%', 'percentDesc');?>
</div>

<div id="percDesc">
	<div class="form-group">
		<?php echo form_input('percentual_desconto', set_value('percentual_desconto', isset($proposta) ? $proposta->percentual_desconto : ''), 'class="form-control" id="percentual_desconto" maxlength="5"'); ?>
	</div>
</div>

<div class="form-group">
	<input type="submit" class="btn btn-primary" value="Salvar" id="cadastrar">
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/proposta_comercial';">
</div>

<?php echo form_close(); ?>

</div>

</div>

<script type="text/javascript">
$(document).ready(function(){
	oculta_campos();

	$("#percDesc").hide();
	$("#percentual_desconto").mask("#9.99", {reverse: true});

	$("#percentual_desconto").blur(function(){
		if($(this).val() > 50){
			alert("Percentual acima do permitido");
			$(this).val("");
		}
	});

	$("#percentDesc").click(function(){
		if($(this).is(":checked"))
			$("#percDesc").show();
		else
			$("#percDesc").hide();
	});
	
	function mostra_info_adicional(valor){
		oculta_campos();
		
		if(valor != ""){
			if(valor == "Governos Municipais"){
				$("#dados_casadinha").show();
				$("#eh_capital").show();
				$("#dados_consorcio").hide();
			}else if(valor == "Empresas Interesse Público"){
				$("#dados_interesse").show();
				$("#eh_capital").hide();
				$("#dados_consorcio").hide();
			}else if(valor == "Consórcios Públicos"){
				$("#dados_consorcio").show();
				$("#dados_casadinha").hide();
				$("#eh_capital").hide();
			}else if(valor == "Parlamentar"){
				$("#dados_parlamentar").show();
			}else if(valor == "Governos Estaduais"){
				$("#dados_estadual").show();
			}
		}
	}

	$("#valor_proposta_comercial").mask("#.##0,00", {reverse: true});

	$("#cnpj_extra").click(function(){
		$("#nums_cnpj_extra").hide();
		if($(this).is(":checked"))
			$("#nums_cnpj_extra").show();
	});

	function oculta_campos(){
		$("#dados_consorcio").hide();
		$("#dados_casadinha").hide();
		$("#dados_interesse").hide();
		$("#dados_parlamentar").hide();
		$("#nums_cnpj_extra").hide();
		$("#eh_capital").hide();
		$("#capital").attr('checked', false);
		$("#dados_estadual").hide();
	}

	$("#tipo_proposta").change(function(){
		mostra_info_adicional($(this).val());
	});

	mostra_info_adicional($("#tipo_proposta").val());
});
</script>