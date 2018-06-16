<script language="JavaScript" type="text/javascript">
    function mascaraData(campoData) {
        var data = campoData.value;
        if (data.length == 2) {
            data = data + '/';
            campoData.value = data;
            return true;
        }
        if (data.length == 5) {
            data = data + '/';
            campoData.value = data;
            return true;
        }
    }
</script>

<div id="content">
	<div class="innerAll spacing-x2">

		<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">Gerenciamento de Usuários de Município</h4>
			</div>
			
		<div class="widget-body center">
		<div id="ConteudoDiv">
		<div id="salvar" class="action">
			<div class="trigger">

			<div class="table" id="metas">

			<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
			<thead>
			<tr>
			<th>Tipo</th>
			<th>Nome</th>
			<th>Email</th>
			<th>Validade</th>
			<th>Qtd Propostas</th>
			<th></th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach($usuarios as $key => $usuario){
			?>
			
			<tr>
			
			<td><form name="atribui" method="post">
			
			<div><?= $usuario->tipoPessoa ?></div>
			</td>
			<td>
			<div><?= $usuario->nome ?></div>
			</td>
			<td>
			<div><?= $usuario->email ?></div>
			</td>
			<?php
			if ($usuario->tipoPessoa == 3) {
			?>
			<td>
			</td>
			<td>
			
			<a href="<?= base_url();?>index.php/in/dados_siconv/programas?usuario=<?= $usuario->idPessoa?>">Editar Programas</a>
			</td>
			
			<td>
			</td>
			<?php
			} else {
			?>
			<td>
			<div>
			<input type="hidden" name="id" value="<?= $usuario->idPessoa;?>">
			<input type="text" size=10 class="form-control" name="validade" value="<?php echo implode("/",array_reverse(explode("-",$usuario->validade))); ?>" maxlength="10" OnKeyUp="mascaraData(this);" id="validade">
			</div>
			</td>
			<td>
			<input type="text" size=4 class="form-control" id="quantidade" name="quantidade" value="<?= $usuario->quantidade ?>" /></div>
			</td>
			<td>
			<div><input type="submit" class="btn btn-inverse" id="form_submit" name="Atualizar" value="Atualizar"></div>
			</td>
			<?php
			}
			?>
			
			</tr>
			</form>
			<?php
			}
			?>
			</tbody>
		</table>
			</div>
			
			</div>
			</div>
		</div>
	</div>
	</div>
	</div>

</div>
