<script type="text/javascript" src="<?php echo base_url();?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>

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

<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>
<div id="content" class="innerAll bg-white">
<h1 class="bg-white content-heading border-bottom">CNPJ Vinculado <?php if($dados_usuario->id_nivel == 6 && $this->session->userdata('nivel') == 2){echo " - Subgestor";} ?></h1>
<?php $action = isset($_GET['id']) ? "controle_usuarios/vincular_cnpj?id=".$_GET['id'] : "" ; ?>
<table class="table">
	<thead>
	<tr><th style="color: #428bca; font-size: 16px;">CNPJs Cadastrados para <?php echo $dados_usuario->nome;?></th></tr>
	<?php 
	$num_cnpj = 0;
	
	foreach ($dados_cnpj as $cnpj):
		$num_cnpj++; 
	?>
	<tr>
		<th><?php echo $programa_model->formatCPFCNPJ($cnpj->cnpj); ?></th>
		<th><?php echo strtoupper($cnpj->cnpj_instituicao); ?></th>
		<th><?php echo $cnpj->sigla; ?></th>
		<th><?php echo $cnpj->nome; ?></th>
		<th>
		<?php if($this->session->userdata('nivel') == 1 || $usuariomodel->verifica_eh_parlamentar() || ($dados_usuario->id_nivel == 6 && $this->session->userdata('nivel') == 2)):?>
			<?php if($permissoes->editar_cnpj_usuario):?>
				<?php if(!$proposta_model->verifica_cnpj_utilizado($cnpj->cnpj, $_GET['id'])): ?>
				<a title="Excluir CNPJ" class="btn btn-sm btn-primary edita_cnpj" href="<?php echo base_url('index.php/controle_usuarios/remove_cnpj?id='.$this->input->get('id', TRUE).'&id_cnpj='.$cnpj->id_cnpj_siconv); ?>" onclick="return confirm('Deseja realmente excluir este CNPJ?');"><i class="fa fa-trash-o"></i></a>
				<?php endif; ?>
			<?php endif;?>
		<?php endif;?>
		</th>
	</tr>
	<?php endforeach; ?>
	</thead>     
</table>

<?php if($this->session->userdata('usuario_sistema') == 'P' || ($dados_usuario->id_nivel == 6 && $this->session->userdata('nivel') == 2)):?>
<br/>

<?php echo form_open($action); ?>

<?php echo validation_errors(); ?>

<h4 style="color: #428bca;">Novo CNPJ</h4>

<?php echo form_hidden('id_usuario', set_value('nome', isset($dados_usuario) ? $dados_usuario->id_usuario : ''));?>

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
	<?php echo form_dropdown('esfera', $proponente_siconv_model->getListaEsferas(), '', "id='esfera' class='form-control' multiple='multiple' style='display: none;'"); ?>
&nbsp;
	<?php echo form_label('Proponente', 'proponente'); ?>
	<?php echo form_dropdown('proponente[]', array(""=>"- Escolha uma esfera -"), '', "id='proponente' class='form-control'  multiple='multiple' style='display: none;'"); ?>
</div>

<span id="botoes_padrao">
<?php if($num_cnpj < $dados_gestor->quantidade_cnpj): ?>
    <input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
<?php else: ?>
    <h4 style="color: #428bca;">Número máximo de CNPJs atingido.</h4>
<?php endif; ?>
</span>
	<?php if($this->session->userdata('nivel') === "1"): ?>
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/controle_usuarios/vincular_cnpj';">
	<?php elseif($dados_usuario->id_nivel == 6): ?>
	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/controle_usuarios/';">
	<?php endif; ?>
	<br>
	<label id="info" style="color: #428bca;"><b>Processando...</b></label>
</div>


<?php echo form_close(); ?>

<?php endif;?>

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
	
	$('#estado').change(function(){
		if( $(this).val() ) {
			carregaCidades($(this).val());
		} else {
			$('#municipio').html('<option value="">– Escolha um estado –</option>');
		}
	});

	function carregaCidades(valorUF, id_cidade){
		if(valorUF != ""){
			$('#municipio').html('<option value="">Carregando...</option>');
			$.ajax({
				url:"<?php echo base_url("index.php/proponente_siconv/get_lista_cidades"); ?>",
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
			url:"<?php echo base_url("index.php/proponente_siconv/get_lista_proponentes"); ?>",
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
});
</script>