<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			
    <script type="text/javascript">
        function transferir_orgao() {
            var valor = document.getElementById('select2_1').value;
            campo = document.getElementById('orgao');
            campo.value = valor;
        }
    </script>

    <form name="" method="post" action="incluir_proposta" enctype="multipart/form-data">
        <input type="hidden" name="usuario_siconv" value="<?php echo $usuario_siconv;?>"/>
		<input type="hidden" name="senha_siconv" value="<?php echo $senha_siconv;?>"/>
		<input type="hidden" name="id" value="<?php echo $id;?>"/>
        <h1>Inserir dados</h1>
		<!-- Group -->
		<br>
		<div class="form-group">
		<table class="table">
            <tbody>
				<?php echo $tabela; ?>
			</tbody>
		</table>
		</div>
		
		<button type="submit" formaction="incluir_proposta" id="form_submit" class="btn btn-primary btn-block">Selecionar Cidade</button>

    </form>
</div>
</div>
</div>
</div>
</div>
    