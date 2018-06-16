<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">Sugestões</h1>

	<div class="innerAll col-sm-6">
		<?php echo form_open(); ?>
		<?php echo validation_errors(); ?>
		<div class="form-group">
			<?php echo form_label('Sugestão *', 'sugestao'); ?>
			<?php echo form_textarea('sugestao', set_value('sugestao'), 'class="form-control"'); ?>
		</div>
		
		<input type="submit" class="btn btn-primary" value="Enviar">
		<a class="btn btn-primary" href="<?php echo base_url('index.php/in/usuario/lista_sugestoes');?>">Voltar</a>
		
		<?php echo form_close(); ?>
	</div>
</div>