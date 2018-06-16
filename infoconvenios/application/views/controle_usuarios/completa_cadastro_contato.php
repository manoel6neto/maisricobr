<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>

<style type="text/css">
.error{
	color: red;
}
</style>

<div id="content" class="innerAll bg-white col-md-6">
	<h1 class="bg-white content-heading border-bottom">Dados do Cadastro <span style="font-size: 14px; color: #428bca;">(<?php echo $proponente_siconv_model->get_municipio_nome($dados_usuario->id_municipio)->municipio;?>)</span></h1>
	
	<form action="" method="post">
		<?php echo validation_errors(); ?>
		
		<div class="form-group">
			<?php echo form_label('Nome do Contato *', 'nome_contato', array('class'=>(form_error('nome_contato') != "" ? "error" : "")));?>
			<?php echo form_input('nome_contato', set_value('nome_contato', isset($dados_usuario->nome_contato) ? $dados_usuario->nome_contato : ""), 'class="form-control"');?>
		</div>
		
		<div class="form-group">
			<?php echo form_label('Email do Contato *', 'email_contato', array('class'=>(form_error('email_contato') != "" ? "error" : "")));?>
			<?php echo form_input('email_contato', set_value('email_contato', isset($dados_usuario->email_contato) ? $dados_usuario->email_contato : ""), 'class="form-control"');?>
		</div>
		
		<div class="form-group">
			<?php echo form_label('Telefone 1 <span style="font-size: x-small;">(somente numeros)</span>', 'telefone_contato', array('class'=>(form_error('telefone_contato') != "" ? "error" : "")));?>
			<?php echo form_input('telefone_contato', set_value('telefone_contato', isset($dados_usuario->telefone_contato) ? $dados_usuario->telefone_contato : ""), 'class="form-control" id="telefone_contato" maxlength="11"');?>
			<?php echo form_label('Telefone 2 <span style="font-size: x-small;">(somente numeros)</span>', 'telefone_contato', array('class'=>(form_error('telefone_contato') != "" ? "error" : "")));?>
			<?php echo form_input('celular_contato', set_value('celular_contato', isset($dados_usuario->celular_contato) ? $dados_usuario->celular_contato : ""), 'class="form-control" id="celular_contato" maxlength="11"');?>
			<?php echo form_label('Telefone 3 <span style="font-size: x-small;">(somente numeros)</span>', 'telefone_contato', array('class'=>(form_error('telefone_contato') != "" ? "error" : "")));?>
			<?php echo form_input('comercial_contato', set_value('comercial_contato', isset($dados_usuario->comercial_contato) ? $dados_usuario->comercial_contato : ""), 'class="form-control" id="comercial_contato" maxlength="11"');?>
		</div>
		
		<fieldset>
		<legend>Histórico de Visitas</legend>
			<table class="table">
				<tr>
					<th>Status</th>
					<th>Data da Visita</th>
					<th>Data de Retorno</th>
					<th>Classificação</th>
					<th>Observações Gerais</th>
				</tr>
				<?php foreach ($dados_historico as $i=>$historico):?>
				<tr>
					<td><input type="hidden" name="id_historico[]" value="<?php echo $historico->id_historico_contato_municipio; ?>"><?php echo $contato_municipio_model->getStatusContato($historico->status_contato); ?></td>
					<td><?php echo form_input('data_visita[]', set_value('data_visita', implode("/", array_reverse(explode("-", $historico->data_visita)))), 'class="data_visita"'); ?></td>
					<td><?php echo form_input('data_retorno[]', set_value('data_retorno', implode("/", array_reverse(explode("-", $historico->data_retorno)))), 'class="data_retorno"'); ?></td>
					
					<td>
					<?php echo form_radio("class_contato[{$i}]", 'P', set_value('class_contato', (isset($historico->class_contato) && $historico->class_contato == "P" ? true : false))); ?>
					<?php echo form_label('Positivo');?>
					<br>
					<?php echo form_radio("class_contato[{$i}]", 'N', set_value('class_contato', (isset($historico->class_contato) && $historico->class_contato == "N" ? true : false))); ?>
					<?php echo form_label('Negativo');?>
					</td>
					
					<td><?php echo form_textarea('obs_gerais[]', set_value('obs_gerais', $historico->obs_gerais), "class='obs_gerais'"); ?></td>
				</tr>
				<?php endforeach;?>
			</table>
		</fieldset>
		
		<div class="form-group">
			<?php if(!isset($dados_usuario->data_retorno)):?>
			<input type="submit" value="Salvar" class="btn btn-primary">
			<?php endif;?>
			<a class="btn btn-primary" href="<?php echo base_url('index.php/controle_usuarios/cadastro_visitas'); ?>">Voltar</a>
		</div>
	</form>
</div>

<script type="text/javascript">
function mascaraData(campoData) {
    var data = campoData.value;
    if (data.length == 2) {
        data = data + '/';
        campoData.value = data;
        return true;
    }
    if (data.length == 5) {
        data = data + '/';
        campoData.value = data;
        return true;
    }
}

$(document).ready(function(){
	$(".data_visita").mask("99/99/9999");
	$(".data_retorno").mask("99/99/9999");

	$(".obs_gerais").each(function(){
		$(this).attr({'cols':50, 'rows':2});
	});
	
	$("#status_contato").change(function(){
		if($(this).val() == "REJEICAO")
			$("#data_retorno").css('border', '1px solid #a7a7a7');
		else
			$("#data_retorno").css('border', '1px solid red');
	});

	function valida_data_preenchida(){
		if($("#data_retorno").val() == "")
			$("#data_retorno").css('border', '1px solid red');

		if($("#status_contato").val() == "REJEICAO")
			$("#data_retorno").css('border', '1px solid #a7a7a7');
	}

	valida_data_preenchida();
});
</script>