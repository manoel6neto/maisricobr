<div class="login spacing-x2">

	<div class="col-md-4 col-sm-6 col-sm-offset-4">
		<div class="panel panel-default">
			<div class="panel-body innerAll">

<h1 class="bg-white content-heading border-bottom">Confirmar Cadastro</h1>

<?php $action = isset($_GET['token']) ? "confirma_email?token=".$_GET['token'] : "";?>

<?php echo form_open($action); ?>

<?php echo validation_errors(); ?>

<div class="form-group">
	<?php echo form_label('CPF Cadastrado*<span style="font-size: x-small;">(somente numeros)</span>', 'cpf');?>
	<?php echo form_input(array('name'=>'cpf', 'class'=>'form-control', 'id'=>'num_cpf', 'maxlength'=>18, 'onkeypress'=>'return SomenteNumero(event)'), set_value('cpf')); ?>
</div>

<div class="form-group">

	<span id="botoes_padrao">
		<input type="submit" class="btn btn-primary" name="cadastra" value="Confirmar" id="cadastrar">
	</span>
</div>

<?php echo form_close(); ?>

	
			</div>
		</div>
	</div>
	
</div>

<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
}
</script>