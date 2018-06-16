<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>configuracoes/css/fancybox/fancybox.css" media="screen" />

<div id="content" class="innerAll bg-white col-md-4">
	<h1 class="bg-white content-heading border-bottom">Nova Declaração de Capacidade Técnica</h1>
	
	<?php echo validation_errors(); ?>
	
	<?php echo form_open('', array('enctype'=>'multipart/form-data')); ?>
	
	<div class="form-group">
	<?php echo form_label('Descrição *', 'descricao_rel'); ?>
	<?php echo form_input(array('name'=>'descricao_rel', 'class'=>'form-control'), set_value('descricao_rel')); ?>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Brasão *', 'brasao_prefeitura'); ?>
	<?php echo form_upload(array('name'=>'brasao_prefeitura', 'class'=>'form-control'), set_value('brasao_prefeitura')); ?>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Município *', 'municipio'); ?>
	<?php echo form_dropdown('municipio', $municipios, set_value('municipio'), 'class="form-control"'); ?>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Nome do Prefeito(a) *', 'nome_prefeito'); ?>
	<?php echo form_input(array('name'=>'nome_prefeito', 'class'=>'form-control'), set_value('nome_prefeito')); ?>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Código do Programa *', 'codigo_programa'); ?>
	<?php echo form_input(array('name'=>'codigo_programa', 'class'=>'form-control', 'id'=>'codigo_programa'), set_value('codigo_programa')); ?>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Nome do Programa *', 'nome_programa'); ?>&nbsp;<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
	<?php echo form_input(array('name'=>'nome_programa', 'class'=>'form-control', 'readonly'=>true, 'id'=>'nome_programa')); ?>
	
	</div>
	
	<div class="form-group">
	<?php echo form_checkbox(array('name'=>'dados_engenharia'), '1', set_value('dados_engenharia'), 'id="dados_engenharia"'); ?>
	<?php echo form_label('Dados do Engenheiro(a) (Quando construção)', 'dados_engenharia'); ?>
	</div>
	
	<div id="dados_engenharia_hide">
		<div class="form-group">
		<?php echo form_label('Nome do Engenheiro(a)', 'nome_engenheiro'); ?>
		<?php echo form_input(array('name'=>'nome_engenheiro', 'class'=>'form-control'), set_value('nome_engenheiro')); ?>
		</div>
		
		<div class="form-group">
		<?php echo form_label('CREA do Engenheiro(a)', 'crea_engenheiro'); ?>
		<?php echo form_input(array('name'=>'crea_engenheiro', 'class'=>'form-control'), set_value('crea_engenheiro')); ?>
		</div>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Tipo de Assinatura *'); ?>
	<?php echo "<br/>"; ?>
	<?php echo form_radio(array('name'=>'tipo_assinatura', 'checked'=>set_radio('tipo_assinatura', 'SEM_ASSINATURA', FALSE), 'class'=>'tipo_ass'), 'SEM_ASSINATURA'); ?>
	<?php echo form_label('Sem Assinatura Digitalizada', 'tipo_assinatura'); ?>
	<?php echo "<br/>"; ?>
	<?php echo form_radio(array('name'=>'tipo_assinatura', 'checked'=>set_radio('tipo_assinatura', 'SOMENTE_ASSINATURA', FALSE), 'class'=>'tipo_ass'), 'SOMENTE_ASSINATURA'); ?>
	<?php echo form_label('Assinatura Digitalizada', 'tipo_assinatura'); ?> <i style="cursor: pointer; color: red;" class="fa fa-search abrePreview" id="so_ass" title="Visualizar Modelo"></i>
	<?php echo "<br/>"; ?>
	<?php echo form_radio(array('name'=>'tipo_assinatura', 'checked'=>set_radio('tipo_assinatura', 'ASSINATURA_NOME', FALSE), 'class'=>'tipo_ass'), 'ASSINATURA_NOME'); ?>
	<?php echo form_label('Assinatura e Nome Digitalizados', 'tipo_assinatura'); ?> <i style="cursor: pointer; color: red;" class="fa fa-search abrePreview" id="nome_ass" title="Visualizar Modelo"></i>
	<?php echo "<br/>"; ?>
	<?php echo form_radio(array('name'=>'tipo_assinatura', 'checked'=>set_radio('tipo_assinatura', 'ASSINATURA_COMPLETA', FALSE), 'class'=>'tipo_ass'), 'ASSINATURA_COMPLETA'); ?>
	<?php echo form_label('Assinatura Completa Digitalizada(Assinatura, Nome e Prefeitura)', 'tipo_assinatura'); ?> <i style="cursor: pointer; color: red;" class="fa fa-search abrePreview" id="ass_completa" title="Visualizar Modelo"></i>
	</div>
	
	<div id="up_assinatura">
		<div class="form-group">
		<?php echo form_label('Assinatura Digitalizada', 'arquivo_assinatura'); ?>
		<?php echo form_upload(array('name'=>'arquivo_assinatura', 'class'=>'form-control'), set_value('arquivo_assinatura')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
		<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/declaracao/lista_rel_capacidade';">
	</div>
</div>
	<?php echo form_close(); ?>
</div>

<div style="visibility: collapse;">
<a class="fancybox" rel="fancybox-button" id="mostraPreview" href=""><img alt="" id="imgPreview" src="" width="100" height="150"/></a>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#dados_engenharia_hide").hide();
	$("#loader").hide();
	$("#up_assinatura").hide();

	$("#dados_engenharia").click(function(){
		if($("#dados_engenharia").is(":checked"))
			$("#dados_engenharia_hide").show();
		else
			$("#dados_engenharia_hide").hide();
	});

	$("#codigo_programa").blur(function(){
		busca_nome_programa($(this).val());
	});

	$(".tipo_ass").click(function(){
		oculta_info_ass($(this).val());
	});

	function oculta_info_ass(valor){
		if(valor != ""){
			if(valor == "SEM_ASSINATURA")
				$("#up_assinatura").hide();
			else
				$("#up_assinatura").show();
		}
	}

	oculta_info_ass($(".tipo_ass").val());

	function busca_nome_programa(codigo_programa){
		if(codigo_programa != ""){
			$.ajax({
				url:'<?php echo base_url('index.php/declaracao/busca_programa_nome'); ?>',
				dataType:'html',
				type:'post',
				data:{
					codigo_programa:codigo_programa
				},
				beforeSend:function(data){
					$("#loader").show();
				},
				success:function(data){
					$("#loader").hide();
					if(data != "ERRO")
						$("#nome_programa").val(data);
					else{
						alert("Programa inválido.");
						$("#codigo_programa").val("");
					}
				}
			});
		}
	}

	busca_nome_programa($("#codigo_programa").val());

	$(".abrePreview").click(function(){
		$("#imgPreview").attr('href', '<?php echo base_url('layout/assets/images/')?>/'+$(this).attr('id')+'.png');
		$("#mostraPreview").attr('href', '<?php echo base_url('layout/assets/images/')?>/'+$(this).attr('id')+'.png');
		$("#mostraPreview").trigger('click');
	});
	
	$(".fancybox").fancybox({
		closeBtn: true,
		arrows: false
	});
});
</script>