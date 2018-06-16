<div id="content" class="innerAll bg-white">
<h1 class="bg-white content-heading border-bottom">Informações por Email <span style="font-size: 12px; color: #428bca;">(Cadastro de responsáveis para receber emails informativos do sistema e SICONV)</span></h1>
<?php $action = isset($_GET['id']) ? "controle_usuarios/editar_encarregado?id=".$_GET['id'] : "" ; ?>
<?php $readonly = isset($_GET['id']) ? "readonly='readonly'" : "" ; ?>
<?php echo form_open($action); ?>

<input type="hidden" name="id_gestor" value="<?php echo $id_gestor; ?>">

<?php echo validation_errors(); ?>

<div class="form-group">
	<?php echo form_label('Nome *', 'nome');?>
	<?php echo form_input(array('name'=>'nome', 'class'=>'form-control'), set_value('nome', isset($encarregado) ? $encarregado->nome : '')); ?>
</div>

<div class="form-group">
	<?php echo form_label('Função *', 'funcao');?>
	<?php echo form_input(array('name'=>'funcao', 'class'=>'form-control'), set_value('funcao', isset($encarregado) ? $encarregado->funcao : '')); ?>
</div>

<div class="form-group">
	<?php echo form_label('Email *', 'email');?>
	<?php echo form_input(array('name'=>'email', 'class'=>'form-control', 'id'=>'email', 'oncontextmenu'=>"return false;", 'autocomplete'=>'off'), set_value('email', isset($encarregado) ? $encarregado->email : '')); ?>
</div>

<div class="form-group">
	<input type="submit" class="btn btn-primary" value="Salvar">
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_encarregados?id_gestor=<?php echo $id_gestor; ?>';">
</div>

<?php echo form_close(); ?>

</div>

<script>
$(document).ready(function(){
	$("#email").keypress(function(e) {
		if((e.ctrlKey && e.which == 99) || (e.ctrlKey && e.which == 118))
	    	return false
	});
});
</script>