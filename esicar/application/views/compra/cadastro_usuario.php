<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>

<style type="text/css">
.error{
	color: red;
}
</style>

<div class="login spacing-x2">
	<div class="col-md-8 col-sm-6 col-sm-offset-2">
		<h1 class="content-heading border-bottom">Novo Usuário</h1>	
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			
			<?php echo form_open(); ?>
			
                        <input type="hidden" name="obj_dados" value='<?php if(isset($obj_dados)) {echo $obj_dados;}?>'>
                        <input type="hidden" name="gratuito" value='<?php if(isset($gratuito)) {echo $gratuito;} ?>'>
			
			<?php echo validation_errors(); ?>
			
			<div class="form-group">
				<?php echo form_label('Nome *', 'nome', array('class'=>(form_error('nome') != "" ? "error" : "")));?>
				<?php echo form_input(array('name'=>'nome', 'class'=>'form-control '), set_value('nome', isset($usuario) ? $usuario->nome : '')); ?>
			</div>
			
			<div class="form-group">
				<?php echo form_label('Email *', 'email', array('class'=>(form_error('email') != "" ? "error" : "")));?>
				<?php echo form_input(array('name'=>'email', 'class'=>'form-control', 'id'=>'email', 'oncontextmenu'=>"return false;", 'autocomplete'=>'off'), set_value('email', isset($usuario) ? $usuario->email : '')); ?>
			</div>
			
			<?php if(!isset($_GET['id'])): ?>
			<div class="form-group">
				<?php echo form_label('Confirmar Email *', 'confirmar_email', array('class'=>(form_error('confirmar_email') != "" ? "error" : "")));?>
				<?php echo form_input(array('name'=>'confirmar_email', 'class'=>'form-control', 'id'=>'confirmar_email', 'oncontextmenu'=>"return false;", 'autocomplete'=>'off'), set_value('confirma_email')); ?>
			</div>
			<?php endif;?>
			
			<div class="form-group">
				<?php echo form_label('Telefone<span style="font-size: x-small;">(somente numeros)</span>', 'telefone', array('class'=>(form_error('telefone') != "" ? "error" : "")));?>
				<?php echo form_input(array('name'=>'telefone', 'class'=>'form-control campoNumerico', 'maxlength'=>11), set_value('telefone', isset($usuario) ? $usuario->telefone : '')); ?>
			</div>
			
			<div class="form-group">
				<?php echo form_label('Celular<span style="font-size: x-small;">(somente numeros)</span>', 'celular', array('class'=>(form_error('celular') != "" ? "error" : "")));?>
				<?php echo form_input(array('name'=>'celular', 'class'=>'form-control campoNumerico', 'maxlength'=>11), set_value('celular', isset($usuario) ? $usuario->celular : '')); ?>
			</div>
			
			<div class="form-group">
				<?php echo form_label('Login - CPF *<span style="font-size: x-small;">(somente numeros)</span>', 'login', array('class'=>(form_error('login') != "" ? "error" : "")));?>
				<?php echo form_input(array('name'=>'login', 'class'=>'form-control campoNumerico', 'maxlength'=>11, 'autocomplete'=>'off'), set_value('login', isset($usuario) ? $usuario->login : '')); ?>
			</div>
			
			<hr>
			<legend>Dados do CNPJ</legend>
			<div class="form-group">
				<?php echo form_label('Estado', 'estado'); ?>
				<?php echo form_dropdown('estado', $proponente_siconv_model->getListaEstados(), set_value('estado', isset($cnpj_siconv) ? $cnpj_siconv->estado : ''), "id='estado' class='form-control'"); ?>
			</div>
			
			<div class="form-group">
				<?php echo form_label('Múnicipio', 'municipio'); ?>
				<?php echo form_dropdown('municipio', array(""=>"– Escolha um estado –"), '', "id='municipio' class='form-control'"); ?>
			</div>
			
			<div class="form-group">
				<?php echo form_label('Esfera Administrativa', 'esfera'); ?>
				<?php echo form_dropdown('esfera', $proponente_siconv_model->getListaEsferasVendasDiretas(), '', "id='esfera' class='form-control' style='display: none;'"); ?>
			&nbsp;
				<?php echo form_label('Proponente', 'proponente'); ?>
				<?php echo form_dropdown('proponente[]', array(""=>"- Escolha uma esfera -"), '', "id='proponente' class='form-control' style='display: none;'"); ?>
			</div>
				
			<div class="form-group">
				<input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
			</div>
				<label id="info" style="color: #428bca;"><b>Processando...</b></label>
			
			<?php echo form_close(); ?>
			
                        <br/>
                        <br/>
                        <br/>
                                
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$("#info").hide();

	$("#esfera").multiselect({
		nonSelectedText:"Escolha",
		numberDisplayed: 0,
		nSelectedText: "Selecionados",
		buttonClass: "form-control",
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		allSelectedText: "Todos Selecionados",
		includeSelectAllOption: true,
		selectAllText: "Selecionar Todos"
	});

	$("#estado_restrito").multiselect({
		nonSelectedText:"Escolha",
		numberDisplayed: 0,
		nSelectedText: "Selecionados",
		buttonClass: "form-control",
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		allSelectedText: "Todos Selecionados",
		includeSelectAllOption: true,
		selectAllText: "Selecionar Todos"
	});

	$("#esfera_restrita").multiselect({
		nonSelectedText:"Escolha",
		numberDisplayed: 0,
		nSelectedText: "Selecionados",
		buttonClass: "form-control",
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		allSelectedText: "Todos Selecionados",
		includeSelectAllOption: true,
		selectAllText: "Selecionar Todos"
	});

	<?php if(!isset($_GET['id'])):?>
	$("#estado_restrito").multiselect('selectAll', false);
	$("#esfera_restrita").multiselect('selectAll', false);

	$("#estado_restrito").multiselect("updateButtonText");
	$("#esfera_restrita").multiselect("updateButtonText");
	<?php endif;?>

	$("#nivel_usuario").change(function(){
		verificaNivelUsuario($(this).val());
	});

	$(".tipo_gestor").click(function(){
		exibeDadosCNPJ($(this).val());
	});

	$('#estado').change(function(){
		if( $(this).val() ) {
			carregaCidades($(this).val());
		} else {
			$('#municipio').html('<option value="">– Escolha um estado –</option>');
		}
	});

	function exibeDadosCNPJ(valor){
		if(valor == "0")
			$("#dadosCNPJ").slideDown();
		else
			$("#dadosCNPJ").slideUp();
	}

	exibeDadosCNPJ(retornaRadioChecked());

	function retornaRadioChecked(){
		var valorRetorno;
		$(".tipo_gestor").each(function(){
			if($(this).is(":checked"))
				valorRetorno = $(this).val();
		});

		return valorRetorno;
	}

	function carregaCidades(valorUF, id_cidade){
		if(valorUF != ""){
			$('#municipio').html('<option value="">Carregando...</option>');
			$.ajax({
				url:"<?php echo base_url("index.php/compra/get_lista_cidades"); ?>",
				dataType:"html",
				data:{
					uf:valorUF,
					municipio:'<?php echo isset($dados_post['municipio']) ? $dados_post['municipio'] : '';?>'
				},
				type:"post",
				beforeSend:function(){
					$("#info").show();
				},
				success:function(data){
					$('#municipio').html(data);
					$("#esfera").val("");
					$("#proponente").val("");
					<?php if(isset($dados_post['esfera'])): ?>
							$("#esfera").val('<?php echo $dados_post['esfera']; ?>');
							$("#esfera").trigger('change');
					<?php endif;?>

					$("#info").hide();
				}
			});
		}
	}

	$("#municipio").change(function(){
		$("#esfera").val("");
		$("#esfera").multiselect("rebuild");
		$("#proponente").html("");
		$("#proponente").multiselect("destroy");
		$("#proponente").attr('style','display: none;');
	});

	$("#esfera").change(function(){
		$('#proponente').html('<option value="">Carregando...</option>');
		$.ajax({
			url:"<?php echo base_url("index.php/compra/get_lista_proponentes"); ?>",
			dataType:"json",
			data:{
				esfera:$(this).val(),
				uf:$("#estado").val(),
				municipio:$("#municipio").val(),
				tipo:"GESTOR",
				id:'<?php echo $this->input->get('id'); ?>'
			},
			type:"post",
			beforeSend:function(){
				$("#info").show();
			},
			success:function(data){
				$("#proponente").multiselect({
					nonSelectedText:"Escolha",
					numberDisplayed: 0,
					nSelectedText: "Selecionados",
					buttonClass: "form-control",
					enableFiltering: true,
					enableCaseInsensitiveFiltering: true,
					allSelectedText: "Todos Selecionados",
					includeSelectAllOption: true,
					selectAllText: "Selecionar Todos"
				});
				
				$("#proponente").multiselect("dataprovider", data);
				$("#proponente").multiselect("rebuild");

				$("#info").hide();
			}
		});
	});

	carregaCidades($('#estado').val());
	
	$("#email").keypress(function(e) {
		if((e.ctrlKey && e.which == 99) || (e.ctrlKey && e.which == 118))
	    	return false
	});

	$("#confirmar_email").keypress(function(e) {
		if((e.ctrlKey && e.which == 99) || (e.ctrlKey && e.which == 118))
	    	return false
	});

	$('.campoNumerico').bind('keydown',soNums);

	$('.campoNumerico').blur(function(){
		$(this).val(mnum($(this).val()));
	});

	function mnum(v){
	    v=v.replace(/\D/g,"");
	    return v;
	} 
	 
	function soNums(e){
	 
	    //teclas adicionais permitidas (tab,delete,backspace,setas direita e esquerda)
	    keyCodesPermitidos = new Array(8,9,37,39,46);
	     
	    //numeros e 0 a 9 do teclado alfanumerico
	    for(x=48;x<=57;x++){
	        keyCodesPermitidos.push(x);
	    }
	     
	    //numeros e 0 a 9 do teclado numerico
	    for(x=96;x<=105;x++){
	        keyCodesPermitidos.push(x);
	    }
	     
	    //Pega a tecla digitada
	    keyCode = e.which;
	     
	    //Verifica se a tecla digitada é permitida
	    if ($.inArray(keyCode,keyCodesPermitidos) != -1){
	        return true;
	    }   
	    return false;
	}
});
</script>