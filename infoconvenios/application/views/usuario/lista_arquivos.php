<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>configuracoes/css/fancybox/fancybox.css" media="screen" />
<style>
.panel-heading {
	margin: 10px;
}

.panel-heading:hover {
	background-color: #DDD !important;
}

#form-search {
	z-index: 1000;
	display: block;
	position: relative;
}

.codigo {
	font-size: small;
	float: right;
}

.media {
	width: 100%;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$(".fancybox").fancybox({
		closeBtn: true,
		arrows: false
	});
});
</script>

<div id="content" class="bg-white">
	
	<h1 class="bg-white content-heading border-bottom">Documentos</h1>
	
	<br/>
	
	<?php if($this->session->userdata('nivel') === "1"): ?>
	<input class="btn btn-primary" type="button" value="Novo Documento" onclick="location.href='<?php echo base_url(); ?>index.php/in/usuario/novo_documento'">
	<?php endif; ?>

	<div class="bg-white">
		<div class="login spacing-x2" style="padding-top: 2%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
				<h3 style="color: #428bca; margin-bottom: 8px;">Modelos de Declarações</h3>
				<div style="height: 300px; overflow: auto; border: solid 1px black;">
				<?php if($arquivos != null){ ?>
				<table class="table">
					<thead>
						<tr style="color: red;">
							<th>Descrição</th>
							<th>Data</th>
							<th>Preview</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<?php foreach ($arquivos as $arquivo):?>
					<tbody>
						<tr>
							<th><?php echo $arquivo->descricao; ?></th>
							<th><?php echo date("d/m/Y", strtotime($arquivo->data_hora_envio)); ?></th>
							<th><?php if($arquivo->print_arquivo != ""){?><a class="fancybox" rel="fancybox-button" href="<?php echo $arquivo->print_arquivo; ?>"><img alt="" src="<?php echo $arquivo->print_arquivo; ?>" width="100" height="150"/></a><?php }?></th>
							<th style="text-align: right;"><input class="btn btn-info" type="button" value="Download" onclick="window.open('<?php echo base_url(); ?>index.php/in/usuario/abre_arquivo?id=<?php echo $arquivo->idArquivo; ?>', '_blank');"></th>
							<th style="text-align: center;">
								<?php if ($this->session->userdata('nivel') == 1) { ?>
								<a onclick="return confirm('Tem certeza que deseja excluir esse arquivo?')" class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/deleta_arquivo?id=<?php echo $arquivo->idArquivo; ?>"><i class="fa fa-trash-o"></i></a>
								<?php } ?>
							</th>
						</tr>
					</tbody>
					<?php endforeach;?>
				</table>
				<?php }else{ ?>
				<h1 style="text-align: center;">Nenhum arquivo encontrado.</h1>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<div class="bg-white">
		<div class="login spacing-x2" style="padding-top: 3%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
				<h3 style="color: #428bca; margin-bottom: 8px;">Documentos Informativos</h3>
				<div style="height: 300px; overflow: auto; border: solid 1px black;">
				<?php if($arquivos_informacoes != null){ ?>
				<table class="table">
					<thead>
						<tr style="color: red;">
							<th>Descrição</th>
							<th>Data</th>
							<th>Preview</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<?php foreach ($arquivos_informacoes as $arquivo):?>
					<tbody>
						<tr>
							<th><?php echo $arquivo->descricao; ?></th>
							<th><?php echo date("d/m/Y", strtotime($arquivo->data_hora_envio)); ?></th>
							<th><?php if($arquivo->print_arquivo != ""){?><a class="fancybox" rel="fancybox-button" href="<?php echo $arquivo->print_arquivo; ?>"><img alt="" src="<?php echo $arquivo->print_arquivo; ?>" width="100" height="150"/></a><?php }?></th>
							<th style="text-align: right;"><input class="btn btn-info" type="button" value="Download" onclick="window.open('<?php echo base_url(); ?>index.php/in/usuario/abre_arquivo?id=<?php echo $arquivo->idArquivo; ?>', '_blank');"></th>
							<th style="text-align: center;">
								<?php if ($this->session->userdata('nivel') == 1) { ?>
								<a onclick="return confirm('Tem certeza que deseja excluir esse arquivo?')" class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/deleta_arquivo?id=<?php echo $arquivo->idArquivo; ?>"><i class="fa fa-trash-o"></i></a>
								<?php } ?>
							</th>
						</tr>
					</tbody>
					<?php endforeach;?>
				</table>
				<?php }else{ ?>
				<h1 style="text-align: center;">Nenhum arquivo encontrado.</h1>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>