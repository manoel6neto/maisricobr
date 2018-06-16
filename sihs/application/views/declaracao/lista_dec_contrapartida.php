<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Listagem de Relatório de Contrapartida</h1>
	<div class="bg-white">
		<div style="padding-top: 1%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
				<?php if($dados_rel != null):?>
				<table class="table">
					<thead>
						<tr style="color: #428bca; font-size: 16px;">
							<th>Descrição</th>
							<th>Estado</th>
							<th>Município</th>
							<th>Programa</th>
							<th></th>
						</tr>
					</thead>
					
					<tbody>
					<?php foreach ($dados_rel as $rel):?>
						<tr>
							<td><?php echo $rel->descricao_rel; ?></td>
							<td><?php echo $rel->estado; ?></td>
							<td><?php echo $proponente_siconv_model->get_municipio_nome($rel->municipio)->municipio; ?></td>
							<td><?php echo $rel->codigo_programa; ?></td>
							<td style="text-align: right;">
								<a title="Baixar Arquivo" class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>index.php/declaracao/gera_rel_contrapartida?id=<?php echo $rel->id_rel; ?>"><i class="fa fa-download"></i> Download .pdf</a>
								&nbsp;
								<a title="Baixar Arquivo" class="btn btn-success" target="_blank" href="<?php echo base_url(); ?>index.php/declaracao/gera_rel_contrapartida_word?id=<?php echo $rel->id_rel; ?>"><i class="fa fa-download"></i> Download .doc</a>
								&nbsp;
								<a title="Excluir Arquivo" onclick="return confirm('Deseja deletar este arquivo?');" class="btn btn-primary" href="<?php echo base_url("index.php/declaracao/deleta_declaracao_contrapartida?id={$rel->id_rel}"); ?>"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php else:?>
				<h1 style="text-align: center;">Nenhum registro encontrado.</h1>
				<?php endif;?>
				
				<a href="<?php echo base_url('index.php/declaracao/nova_contrapartida'); ?>" class="btn btn-primary">Novo Documento</a>
				<a href="<?php echo base_url('index.php/declaracao/'); ?>" class="btn btn-primary">Voltar</a>
			</div>
		</div>
	</div>
</div>