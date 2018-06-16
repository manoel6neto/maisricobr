<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>

<div id="content" class="innerAll bg-white">

    <h1 class="bg-white content-heading border-bottom">Gerar Certificado</h1>

    <br>
    
	<?php echo form_open();?>
	
	<?php echo validation_errors();?>
	
	<?php
	if(!$podeSalvar)
		echo $mensagemErro; 
	?>
	
	<div class="form-group">
	<?php echo form_label('Estado *', 'estado'); ?>
	<?php echo form_dropdown('estado', $proponente_siconv_model->getListaEstados(), '', "id='estado' class='form-control'"); ?>
	</div>
	
	<div class="form-group">
	<?php echo form_label('Múnicipio *', 'municipio', array('class'=>(form_error('municipio') != "" ? "error" : ""))); ?>
	<?php echo form_dropdown('municipio', array(""=>"– Escolha um estado –"), '', "id='municipio' class='form-control'"); ?>
	</div>
	
	<div class="form-group">
    <?php echo form_label('Data do Curso *', 'data_curso'); ?>
    <?php echo form_input(array('name'=>'data_curso', 'class'=>'form-control', 'id'=>'data_curso'), set_value('data_curso', '')); ?>
	</div>
	
	<div class="form-group">
    <?php echo form_label('CPFs dos Participantes (informar os CPFs separados por ";" ponto e vígula) *', 'cpf_usuario'); ?>
    <?php echo form_textarea(array('name'=>'cpf_usuario', 'class'=>'form-control '), set_value('cpf_usuario', '')); ?>
	</div>
	
	<div class="form-group">
	<input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
	</div>
	
	<?php echo form_close();?>
    
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#data_curso").mask("99/99/9999");

	$('#estado').change(function(){
		if( $(this).val() ) {
			carregaCidades($(this).val());
		} else {
			$('#municipio').html('<option value="">– Escolha um estado –</option>');
		}
	});

	function carregaCidades(valorUF){
		if(valorUF != ""){
			$('#municipio').html('<option value="">Carregando...</option>');
			$.ajax({
				url:"<?php echo base_url("index.php/proponente_siconv/get_lista_cidades"); ?>",
				dataType:"html",
				data:{
					uf:valorUF
				},
				type:"post",
				beforeSend:function(){
					$("#info").show();
					$("#dados_contato_municipio").slideUp();
					
					$("#visita").attr("checked", false);
				},
				success:function(data){
					$('#municipio').html(data);
					$("#esfera").val("");
					$("#proponente").val("");
					$("#info").hide();
				}
			});
		}
	}

	carregaCidades($("#estado").val());
});
</script>