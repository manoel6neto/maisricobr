
<div id="content">
	<div class="spacing-x2" style="padding-top: 10%;">
		<div class="col-md-12 col-sm-12">

			<form name="" method="post" action="selecionar_objetos"	enctype="multipart/form-data">
				<input type="hidden" name="usuario_siconv" value="<?php echo $usuario_siconv;?>"> 
				<input type="hidden" name="senha_siconv" value="<?php echo $senha_siconv;?>"> 
				<input type="hidden" name="cnpjProponente" value="<?php echo $cnpjProponente;?>">
				<input type="hidden" name="orgao" value="<?php echo $orgao;?>"> 
				<input type="hidden" name="id" value="<?php echo $id;?>">

				<h1>Selecionar Programas para Proposta</h1>

				<table class="table"><?php echo $tabela;?></table>
				<input type="submit" id="form_submit" value="Selecionar">

			</form>

		</div>
	</div>
</div>

