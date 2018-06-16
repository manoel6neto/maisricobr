<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
<div id="content" style="background:none;">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			<form name="" method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?= $id;?>">
				<input type="hidden" name="tipo" value="<?= $tipo;?>">
				<h1>Opções de pacotes</h1>
				<div class="table" id="metas">
				<br />
				
				<select name="opcao" id="opcao">
					<?php
						if ($tipo == 5){
					?>
					<option value="0">10 propostas enviadas (R$100,00)</option>
					<option value="1">20 propostas enviadas (R$180,00)</option>
					<option value="2">30 propostas enviadas (R$250,00)</option>
					<?php
						} else if ($tipo == 6 || $tipo == 7 || $tipo == 8){
					?>
					<option value="0">1 mês (R$100,00)</option>
					<option value="1">2 meses (R$180,00)</option>
					<option value="2">3 meses (R$250,00)</option>
					<?php
						}
					?>
				</select>
			<br />
			<input type="submit" id="form_submit" onclick="" value="Confirma" name="cadastra">
			<br /><br />
        <a href="index"><< voltar</a>
        </div>
		</form>
	</div>
</div>
</div>
</div>
</div>
