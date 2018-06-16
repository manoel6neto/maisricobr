<script type="text/javascript" src="<?php echo base_url();?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>
<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>
<!-- <div id="content" class="bg-white"> -->
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

<style type="text/css">
.class-with-tooltip:hover:after{
    content: 'Marcar esta opção, diz que você fará uma apresentação para um potencial cliente. Portanto será necessário preencher um cadastro deste cliente com três informações básicas. Ao final da apresentação deverá preencher dados adicionais (no menu principal na aba “Área do Vendedor”). OBJETIVO: Ter controle de visitas e eventuais retornos.';
    position: absolute;
    padding: 5px;
    border: 1px solid gray;
    background: whitesmoke;
    font-size: 14px;
    text-align: justify;
    width: 250px;
    color: green;
}

.error{
	color: red;
}
</style>

<div class="login spacing-x2">

	<div class="col-md-4 col-sm-6 col-sm-offset-4">
		<div class="panel panel-default">
			<div class="panel-body innerAll">

<h1 class="bg-white content-heading border-bottom">Vincular CNPJ</h1>

<?php echo form_open('', 'id="form_vincular"'); ?>

<?php echo validation_errors(); ?>

<?php echo form_hidden('id_usuario', set_value('nome', isset($dados_usuario) ? $dados_usuario->id_usuario : ''));?>

<div class="form-group">
	<?php echo form_label('Estado', 'estado'); ?>
	<?php echo form_dropdown('estado', $proponente_siconv_model->getListaEstados(), '', "id='estado' class='form-control'"); ?>
</div>

<?php if($this->session->userdata('sistema') != "E"):?>
<div class="form-group">
	<?php echo form_label('Múnicipio', 'municipio', array('class'=>(form_error('municipio') != "" ? "error" : ""))); ?>
	<?php echo form_dropdown('municipio', array(""=>"– Escolha um estado –"), '', "id='municipio' class='form-control'"); ?>
</div>
<?php endif;?>

<div class="form-group">
	<?php echo form_label('Esfera Administrativa', 'esfera', array('class'=>(form_error('esfera') != "" ? "error" : ""))); ?>
	<?php echo form_multiselect('esfera[]', $proponente_siconv_model->getListaEsferas(), '', "id='esfera' class='form-control' multiple='multiple' style='display: none;'"); ?>
&nbsp;
	<?php echo form_label('Proponente', 'proponente', array('class'=>(form_error('proponente') != "" ? "error" : ""))); ?>
	<?php echo form_dropdown('proponente[]', array(""=>"- Escolha uma esfera -"), '', "id='proponente' class='form-control'  multiple='multiple' style='display: none;'"); ?>
</div>


<div id="dados_contato_municipio">
	<div class="form-group">
		<?php echo form_checkbox('visita', '1', set_value('visita', true), "id='visita'"); ?>
		<?php echo form_label('Desmarque este campo caso esta sessão não seja para apresentação.', 'visita', array('style'=>"color: red;")); ?> <i class="fa fa-question-circle class-with-tooltip" style="color: red; cursor: pointer; font-size: 16px;"> </i>
	</div>
	
	<div id="contato_municipio">
		<div class="form-group">
			<?php echo form_label('Nome do Responsável *', 'nome_contato', array('class'=>(form_error('nome_contato') != "" ? "error" : "")));?>
			<?php echo form_input('nome_contato', set_value('nome_contato'), 'class="form-control" id="nome_contato"');?>
		</div>
		
		<!-- <div class="form-group">
			<?php echo form_label('Telefone *<span style="font-size: x-small;">(somente numeros)</span>', 'telefone_contato', array('class'=>(form_error('telefone_contato') != "" ? "error" : "")));?>
			<?php echo form_input('telefone_contato', set_value('telefone_contato'), 'class="form-control" id="telefone_contato" maxlength="11"');?>
		</div>
		
		<div class="form-group">
			<?php echo form_label('Email *', 'email_contato', array('class'=>(form_error('email_contato') != "" ? "error" : "")));?>
			<?php echo form_input('email_contato', set_value('email_contato'), 'class="form-control" id="email_contato"');?>
		</div>-->
		
		<div class="form-group">
			<?php echo form_label('Selecione o Status *', 'status_contato', array('class'=>(form_error('status_contato') != "" ? "error" : "")));?>
			<?php echo form_dropdown('status_contato', array_merge(array(''=>'Escolha'), $contato_municipio_model->getStatusContato()), set_value('status_contato'), 'class="form-control" id="status_contato"');?>
		</div>
	</div>
</div>

<div id="botoes_padrao">
	<input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/login/sair';">
</div>

	<label id="info" style="color: #428bca;"><b>Processando...</b></label>

<?php echo form_close(); ?>

	
			</div>
		</div>
	</div>
	
</div>


<!-- </div> -->

<script>
$(document).ready(function(){
	//$("#contato_municipio").hide();
	//$("#dados_contato_municipio").hide();

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

	//$("#telefone_contato").mask("(99)99999-9999");

	$("#visita").click(function(){
		mostraCamposContato($(this).is(":checked"));
	});

	function mostraCamposContato(valor){
		if(valor) {
			$("#contato_municipio").show();
                } else {
			$("#contato_municipio").hide();
                }
	}
	
	$('#estado').change(function(){
		if( $(this).val() ) {
			carregaCidades($(this).val());
		} else {
			$('#municipio').html('<option value="">– Escolha um estado –</option>');
		}
	});

	$("#novo_cnpj").click(function(){
		$("#botoes_padrao").slideDown();
		$("#novo_cnpj").slideUp();
		$("#botao_atualizar").slideUp();

		$("#num_cnpj").val("");
		$('#municipio').val("");
		
		return false;
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
					//$("#dados_contato_municipio").slideUp();
					
					$("#visita").attr("checked", true);
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

	$("#municipio").change(function(){
		$("#esfera").val("");
		$("#esfera").multiselect("rebuild");
		$("#proponente").html("");
		$("#proponente").multiselect("destroy");
		$("#proponente").attr('style','display: none;');
		
		//$("#dados_contato_municipio").slideUp();
		
		$("#visita").attr("checked", true);
	});

	$("#esfera").change(function(){
		$('#proponente').html('<option value="">Carregando...</option>');
		$.ajax({
			url:"<?php echo base_url("index.php/proponente_siconv/get_lista_proponentes"); ?>",
			dataType:"json",
			data:{
				esfera:$(this).val(),
				uf:$("#estado").val(),
				municipio:$("#municipio").val()
			},
			type:"post",
			beforeSend:function(){
				$("#info").show();
				//$("#dados_contato_municipio").slideUp();
				
				$("#visita").attr("checked", true);
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

	$("#proponente").change(function(){
		$.ajax({
			url:'<?php echo base_url('index.php/controle_usuarios/busca_ultimo_contato_municipio'); ?>',
			dataType:'json',
			data:{
				municipio:$("#municipio").val(),
				estado:$("#estado").val(),
				proponente:$("#proponente").val(),
				esfera:$("#esfera").val()
			},
			type:'post',
			beforeSend:function(){
				$("#info").show();
			},
			success:function(data){
				if(data.id_contato_municipio != "" && data.id_contato_municipio > 0){
					$("#dados_contato_municipio").slideDown();
					
					$("#visita").attr("checked", true);
					$("#nome_contato").val(data.nome_contato);
					
					$("#form_vincular input[name='id_contato_municipio']").val(data.id_contato_municipio);
	
					if(data.lista_status != ""){
						var option = "";
						$.each(data.lista_status, function(k, v){
							option += "<option value='"+k+"'>"+v+"</option>";
						});
	
						$("#status_contato").html(option);
						document.getElementById('status_contato').size=6;
					}
				}else{
					//$("#dados_contato_municipio").slideUp();
					
					$("#visita").attr("checked", true);
				}

				$("#info").hide();
			}
		});
	});

	$("#num_cnpj").keyup(function(){
		if ($(this).val().length == 2) {
			$(this).val($(this).val()+ '.');
            return true;
        }
        if ($(this).val().length == 6) {
        	$(this).val($(this).val()+ '.');
            return true;
        }
        if ($(this).val().length == 10) {
        	$(this).val($(this).val()+ '/');
            return true;
        }
        if ($(this).val().length == 15) {
        	$(this).val($(this).val()+ '-');
            return true;
        }
	});

	$("#num_cnpj").focusout(function(){
		formataCNPJ($(this).val());
	});

	function formataCNPJ(value){
		var cnpjAUX = "";
		if(value.length == 14){
			for(var i = 0; i < value.length; i++){
				cnpjAUX += value[i];
				if(cnpjAUX.length == 2)
					cnpjAUX += ".";
				else if(cnpjAUX.length == 6)
					cnpjAUX += ".";
				else if(cnpjAUX.length == 10)
					cnpjAUX += "/";
				else if(cnpjAUX.length == 15)
					cnpjAUX += "-";
			}

			$("#num_cnpj").val(cnpjAUX);
		}
	}

	carregaCidades($('#estado').val());
	//mostraCamposContato($("#visita").is(":checked"));

	$("#info").hide();
});
</script>