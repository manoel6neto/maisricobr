<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">Lista de Sugestões</h1>

	<div class="innerAll">
		<table class="table">
			<tr>
				<th>Usuário</th>
				<th>Estado</th>
				<th>Município</th>
				<th>Sugestão</th>
				<th>Data Envio</th>
				<th></th>
			</tr>
			
			<?php foreach ($sugestoes as $sugestao):?>
			<tr>
				<td><?php echo $usuariomodel->get_by_id($sugestao->id_usuario)->nome;?></td>
				<td><?php echo $sugestao->uf;?></td>
				<td><?php echo $proponente_siconv_model->get_municipio_nome($sugestao->id_municipio)->municipio;?></td>
				<td><?php echo $sugestao->sugestao;?></td>
				<td><?php echo date('d/m/Y H:i:s', strtotime($sugestao->data_envio));?></td>
				
				<?php $temResposta = $sugestao_model->checa_tem_resposta($sugestao->id_sugestao); ?>
				
				<th>
				<?php if($this->session->userdata('nivel') == 1):?>
					<?php if(!$temResposta):?>
					<a href="<?php echo base_url('index.php/in/usuario/responde_sugestao?id='.$sugestao->id_sugestao);?>" title="Responder Sugestão"><i class="fa fa-reply"></i></a>
					<?php endif;?>
				<?php endif;?>
				</th>
			</tr>
			
			<?php if($temResposta):?>
			<?php $resposta = $sugestao_model->get_resposta_by_id($sugestao->id_sugestao); ?>
			<tr style="color: red;">
				<td><i title="Resposta da Sugestão" class="fa fa-mail-forward"></i></td>
				<td>Resposta</td>
				<td><?php echo $usuariomodel->get_by_id($resposta->id_usuario)->nome;?></td>
				<td colspan="2"><?php echo $resposta->resposta_sugestao;?></td>
				<td><?php echo date('d/m/Y H:i:s', strtotime($resposta->data_envio));?></td>
			</tr>
			<?php endif;?>
			
			<?php endforeach;?>
		</table>
		<a href="<?php echo base_url('index.php/in/usuario/cria_sugestao'); ?>" class="btn btn-primary">Nova Sugestão</a>
	</div>
</div>