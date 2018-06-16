<div id="content" class="innerAll bg-white">
	<h1 class="bg-white content-heading border-bottom">Área do Representante</h1>
	
	<div class="panel-heading">
		<h4 class="panel-title"><a href="<?php echo base_url('index.php/controle_usuarios/cadastro_visitas'); ?>"><span style="color: #428bca;">Cadastro de Visita</span></a></h4>
	</div>
	
	<div class="panel-heading">
		<h4 class="panel-title"><a href="<?php echo base_url('index.php/proposta_comercial'); ?>" id="link_proposta"><span style="color: #428bca;">Proposta Comercial</span></a></h4>
	</div>
	
	<div class="panel-heading">
		<h4 class="panel-title"><a target="_blank" href="<?php echo base_url('arquivos_download/tabela_precos.pdf'); ?>"><span style="color: #428bca;">Tabela de Preços</span></a></h4>
	</div>
	
	<div class="panel-heading">
		<h4 class="panel-title"><a href="<?php echo base_url('relatorio/rel_visita_representante'); ?>"><span style="color: #428bca;">Resumo de Visitas Realizadas</span></a></h4>
	</div>
	
	<div class="panel-heading">
		<h4 class="panel-title"><a href="<?php echo base_url('index.php/in/usuario/utilizacao_sistema_cliente'); ?>"><span style="color: #428bca;">Utilização do Sistema Pelos Seus Clientes</span></a></h4>
	</div>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#link_proposta").click(function(){
		$.ajax({
			url:"<?php echo base_url('index.php/controle_usuarios/checkTemContatoAtivo'); ?>",
			success:function(data){
				if(data == "SEM_CADASTRO"){
					if(confirm("Para criar uma proposta comercial, primeiro realize um cadastro de visita do município.\r\nDeseja realizar o cadastro agora?")){
						$.ajax({
							url:"<?php echo base_url('index.php/controle_usuarios/cria_cadastro_visita')?>",
							success:function(data){
								location.href="<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=')?>"+data;
							}
						});
					}

					return false;
				}else if(data.indexOf("SEM_CONTATO") != -1){
					if(confirm("Para criar uma proposta comercial, deve ser informado um contato do município.\r\nDeseja informar os dados do contato agora?")){
						id = data.split("#");
						location.href="<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=')?>"+id[1];
					}

					return false;
				}else
					location.href="<?php echo base_url('index.php/proposta_comercial')?>";
			}
		});
		
		return false;
	});
});
</script>