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

    <form name="" method="post" action="selecionar_programas_padrao" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $id;?>"/>
        <h1>Escolher Órgão</h1>

		<div class="form-group">
			<label for="orgao">Orgão</label>
			<input type="text" class="form-control" id="orgao" name="orgao" placeholder="Digite o Proponente" />
		</div>
		
		<!-- Group -->
		<div class="form-group">
				<div class="row innerLR">
					<select style="width: 100%;" id="select2_1" name="orgao_nome" onchange="transferir_orgao()">
					<option value=""></option>
					<?php foreach ($orgaos as $orgao){ echo '<option value="'.$orgao->codigo.'">'.$orgao->nome.'</option>'; } ?>
				</select>
			</div>
		</div>
		
		<button type="submit" formaction="selecionar_programas" id="form_submit" class="btn btn-primary btn-block">Selecionar</button>

    </form>
</div>
</div>
</div>
</div>
</div>
    
