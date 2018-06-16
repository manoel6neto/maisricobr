<div id="content" class="innerAll bg-white">
	<h1 class="bg-white content-heading border-bottom">Lista de Cadastros</h1>
	
	<a id="nova_visita" class="btn btn-primary">Nova Visita</a>
	<br/><br/>
	
	<?php if($lista_contatos != null):?>
		<table class="table">
			<tr style="color: #428bca; font-size: 16px;">
            	<th>Município</th>
                <th>Estado</th>
                <th>Nome</th>
                <th>Telefones</th>
                <th>Email</th>
                <th>Data de Retorno</th>
                <th>Status</th>
                <th></th>
             </tr>
		<?php foreach ($lista_contatos as $contato):?>
			<tr>
            	<td><?php echo $proponente_siconv_model->get_municipio_nome($contato->id_municipio)->municipio;?></td>
                <td><?php echo $contato->sigla_uf;?></td>
                <td><?php echo $contato->nome_contato;?></td>
                <td><?php echo $contato_municipio_model->formataCelular($contato->telefone_contato)." / ".$contato_municipio_model->formataCelular($contato->celular_contato)." / ".$contato_municipio_model->formataCelular($contato->comercial_contato);?></td>
                <td><?php echo $contato->email_contato;?></td>
                <td><?php echo implode("/", array_reverse(explode("-", $historico_contato_municipio_model->get_ultima_data_retorno($contato->id_contato_municipio))));?></td>
                <td>
                	&nbsp;&nbsp;&nbsp;
                	<?php if($this->contato_municipio_model->verifica_alerta_retorno($contato->id_contato_municipio)):?>
						<i title="Você tem visita agendada para esse cliente no dia <?php echo implode("/", array_reverse(explode("-", $contato->data_retorno))); ?>" class="btn-sm btn-info fa fa-warning"></i>
					<?php elseif($this->contato_municipio_model->verifica_alerta_marca_retorno($contato->id_contato_municipio)):?>
						<i title="Você visitou esse cliente e não informou a data de retorno" class="btn-sm btn-primary fa fa-warning "></i>
					<?php else:?>
						<i title="" class="btn-sm btn-success fa fa-check-square"></i>
					<?php endif;?>
                </td>
                <td><a href="<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id='.$contato->id_contato_municipio);?>">Atualizar Informações</a></td>
             </tr>
		<?php endforeach;?>
		
		</table>
	<?php else:?>
	
	<?php endif;?>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#nova_visita").click(function(){
		$.ajax({
			url:"<?php echo base_url('index.php/controle_usuarios/checkTemContatoAtivo'); ?>",
			success:function(data){
				if(data == "SEM_CADASTRO"){
					if(confirm("Deseja realizar um cadastro de visita do município?")){
						$.ajax({
							url:"<?php echo base_url('index.php/controle_usuarios/cria_cadastro_visita')?>",
							success:function(data){
								location.href="<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=')?>"+data;
							}
						});
					}

					return false;
				}else if(data.indexOf("SEM_CONTATO") != -1){
					if(confirm("Já existe um cadastro de visita ativo, porém incompleto.\r\nDeseja completá-lo agora?")){
						id = data.split("#");
						location.href="<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=')?>"+id[1];
					}

					return false;
				}else
					alert("Já existe um cadastro de visita ativo.");
			}
		});
		
		return false;
	});
});
</script>