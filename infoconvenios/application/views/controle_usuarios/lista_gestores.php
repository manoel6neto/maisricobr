<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Listagem de Gestores do Sistema</h1>
	<div class="bg-white">
		<div style="padding-top: 1%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
				<?php if($gestores != null):?>
				<table class="table">
					<thead>
						<tr style="color: #428bca; font-size: 16px;">
							<th>Nome</th>
							<th>Email</th>
							<th>Telefone</th>
							<th>Celular</th>
							<th></th>
						</tr>
					</thead>
					
					<tbody>
					<?php foreach ($gestores as $gestor):?>
						<tr>
							<td><?php echo $gestor->nome; ?></td>
							<td><?php echo $gestor->email; ?></td>
							<td><?php echo $gestor->telefone; ?></td>
							<td><?php echo $gestor->celular; ?></td>
							<td style="text-align: right;">
								<a title="Vincular CNPJ" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/controle_usuarios/vincular_cnpj?id=<?php echo $gestor->id_usuario; ?>"><i class="fa fa-link"></i></a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php else:?>
				<h1 style="text-align: center;">Nenhum registro encontrado.</h1>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>