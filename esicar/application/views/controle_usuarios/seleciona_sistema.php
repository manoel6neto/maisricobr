<script type="text/javascript" src="<?php echo base_url();?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>
<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>

<div class="login spacing-x2">

	<div class="col-md-4 col-sm-6 col-sm-offset-4">
		<div class="panel panel-default">
			<div class="panel-body innerAll">

<h1 class="bg-white content-heading border-bottom">Selecionar Sistema</h1>

<?php echo form_open('', 'id="form_vincular"'); ?>

<?php echo validation_errors(); ?>

<?php echo form_hidden('usuario', set_value('nome', isset($usuario) ? $usuario->id_usuario : ''));?>

<div class="form-group">
	<?php echo form_label('Sistema', 'sistema'); ?>
	<?php echo form_dropdown('sistema', $sistemas, '', "id='sistema' class='form-control'"); ?>
</div>

<div id="botoes_padrao">
	<input type="submit" class="btn btn-primary" name="cadastra" value="Continuar" id="cadastrar">
	<input class="btn btn-primary" type="button" value="Voltar" id="buttonVoltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/login/sair';">
</div>

<?php echo form_close(); ?>

	
			</div>
		</div>
	</div>
	
</div>
