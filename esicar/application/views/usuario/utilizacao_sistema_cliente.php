<div id="content" class="innerAll bg-white">
	<h1 class="bg-white content-heading border-bottom">Utilização do Sistema pelos Clientes</h1>
	<?php if($usuarios != null):?>
	<table class="table">
		<tr style="color: #428bca;">
			<th>Gestor</th>
			<th>Município</th>
			<th>Penultima Utilização</th>
			<th>Ultima Utilização</th>
			<th>Ultima Ação</th>
			
			<?php if($this->session->userdata('nivel') == 1):?>
			<th>Representante</th>
			<?php endif;?>
		</tr>
		
		<?php foreach ($usuarios as $u):?>
		<tr>
			<td><?php echo $u->nome; ?></td>
			<td><?php echo $proponente_siconv_model->get_municipio_nome($usuariomodel->get_cidade_gestor($u->id_usuario)->id_cidade)->municipio; ?></td>
			<?php $ultimoAcesso = $usuariomodel->get_ultimo_acesso_gestor($u->id_usuario);?>
			
			<td><?php if(isset($ultimoAcesso[1]->data)){echo date("d/m/Y H:i:s", strtotime($ultimoAcesso[1]->data));} ?></td>
			<td><?php if(isset($ultimoAcesso[0]->data)){echo date("d/m/Y H:i:s", strtotime($ultimoAcesso[0]->data));} ?></td>
			<td><?php if(isset($ultimoAcesso[0]->acao)){echo $ultimoAcesso[0]->acao;}?></td>
			
			<?php if($this->session->userdata('nivel') == 1):?>
			<td><?php echo $usuariomodel->get_representante_gestor($u->id_usuario); ?></td>
			<?php endif;?>
		</tr>
		<?php endforeach;?>
	</table>
	<?php else:?>
		<h1 style="text-align: center;">Nenhum dado encontrado.</h1>
	<?php endif;?>
	
	<?php if($this->session->userdata('nivel') != 1): ?>
	<a class="btn btn-primary" href="<?php echo base_url('index.php/controle_usuarios/area_vendedor')?>">Voltar</a>
	<?php else:?>
	<a class="btn btn-primary" href="<?php echo base_url('index.php/relatorio')?>">Voltar</a>
	<?php endif;?>
</div>
