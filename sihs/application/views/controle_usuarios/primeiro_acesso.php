<div class="login spacing-x2">

	<div class="col-md-4 col-sm-6 col-sm-offset-4">
		<div class="panel panel-default">
			<div class="panel-body innerAll">

<h1>Alterar Senha</h1>

<?php echo form_open(); ?>

<?php echo validation_errors(); ?>

<div class="form-group">
	<?php echo form_label('Senha Atual *', 'cnpj');?>
	<?php echo form_password(array('name'=>'senha_atual', 'class'=>'form-control'), set_value('senha_atual')); ?>
</div>

<div class="form-group">
	<?php echo form_label('Nova Senha *', 'cnpj');?>
	<?php echo form_password(array('name'=>'nova_senha', 'class'=>'form-control'), set_value('nova_senha')); ?>
</div>

<div class="form-group">
	<?php echo form_label('Repetir Nova Senha *', 'cnpj');?>
	<?php echo form_password(array('name'=>'repetir_senha', 'class'=>'form-control'), set_value('repetir_senha')); ?>
</div>

<div class="form-group">

<span id="botoes_padrao">
	<input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/login';">
</div>

<?php echo form_close(); ?>

	
			</div>
		</div>
	</div>
	
</div>