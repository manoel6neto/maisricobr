<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">Sugestões</h1>

	<div class="innerAll col-sm-6">
		<?php echo form_open(base_url('index.php/in/usuario/responde_sugestao?id='.$_GET['id'])); ?>
		
		<div class="form-group">
		<label>Sugestão Informada: </label>
		<?php echo $sugestao->sugestao;?>
		</div>
		
		<?php echo validation_errors(); ?>
		
		<div class="form-group">
			<?php echo form_label('Resposta da Sugestão *', 'resposta_sugestao'); ?>
			<?php echo form_textarea('resposta_sugestao', set_value('resposta_sugestao'), 'class="form-control"'); ?>
		</div>
		
		<input type="submit" class="btn btn-primary" value="Enviar">
		<a class="btn btn-primary" href="<?php echo base_url('index.php/in/usuario/lista_sugestoes');?>">Voltar</a>
		
		<?php echo form_close(); ?>
	</div>
</div>