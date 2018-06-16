<script type="text/javascript" src="<?php echo base_url();?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>
<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>
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

<div class="login spacing-x2">

	<div class="col-md-4 col-sm-6 col-sm-offset-4">
		<div class="panel panel-default">
			<div class="panel-body innerAll">

<h1 class="bg-white content-heading border-bottom">Vincular Parlamentar</h1>

<?php echo form_open('', 'id="form_vincular"'); ?>

<?php echo validation_errors(); ?>

<?php echo form_hidden('id_usuario', set_value('nome', isset($dados_usuario) ? $dados_usuario->id_usuario : ''));?>

<div class="form-group">
	<?php echo form_label('Estado do Parlamentar', 'estado_parlamentar'); ?>
	<?php echo form_dropdown('estado_parlamentar', $proponente_siconv_model->getListaEstados(), '', "id='estado_parlamentar' class='form-control'"); ?>
</div>

<div class="form-group">
	<?php echo form_label('Parlamentar', 'codigo_parlamentar'); ?>
	<?php echo form_dropdown('codigo_parlamentar', array(''=>'Escolha'), set_value('codigo_parlamentar'), "id='codigo_parlamentar' class='form-control' maxlength='11'"); ?>
</div>

<div class="form-group">
	<?php echo form_label('Nível Acesso', 'nivel_parlamentar', array('class'=>(form_error('nivel_parlamentar') != "" ? "error" : ""))); ?>
	<?php echo form_dropdown('nivel_parlamentar', array(""=>"– Escolha um nível de acesso –", "C"=>"Completo", "P"=>"Relatórios"), '', "id='nivel_parlamentar' class='form-control'"); ?>
</div>

<div id="botoes_padrao">
	<input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/login/sair';">
</div>

<?php echo form_close(); ?>

	
			</div>
		</div>
	</div>
	
</div>

<script>
$(document).ready(function(){
	$("#estado_parlamentar").change(function(){
		get_parlamentar($(this).val());
	});
	
	function get_parlamentar(estadoParlamentar){
		$.ajax({
			url:'<?php echo base_url('index.php/controle_usuarios/get_lista_parlamentar'); ?>',
			type:'post',
			dataType:'json',
			data:{
				estado:estadoParlamentar
			},
			success:function(data){
				$("#codigo_parlamentar").html("<option value=''>Escolha</option>");
				
				var codigo_parlamentar  = $("#codigo_parlamentar");

				$.each(data, function (key, cat) {
				    var group = $('<optgroup>',{label:key});

				    $.each(cat,function(i,item) {
				        $("<option/>",{value:item.codigo,text:item.codigo+" - "+item.nome+" - "+item.partido})
				            .appendTo(group);
				    });

				    group.appendTo( codigo_parlamentar );
				});
			}
		});
	}

	get_parlamentar($("#estado_parlamentar").val());
});
</script>