<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
    	<div id="content">

<script type="text/javascript">
function transferir_orgao() {
	var valor = document.getElementById('orgao_nome').value;
	campo = document.getElementById('orgao');
	campo.value = valor;
}
</script>
<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
			<form name="" method="post" action="incluir_proposta" enctype="multipart/form-data">
				<input type="hidden" name="usuario_siconv" value="<?= $usuario_siconv;?>">
				<input type="hidden" name="senha_siconv" value="<?= $senha_siconv;?>">
				<input type="hidden" name="id" value="<?= $id;?>">
				<h1>Escolher Proponente</h1>
				<div class="table" id="metas">
    
<table>
                <tbody>
    	   
	                       <tr id="tr-escolherProponenteListaProponentes" class="listaProponentes">
                        <td class="label">Proponente</td>
                        <td class="label">
                           <table>
                               <tbody>
                               <?= $tabela;?>
                               
                           </tbody></table>
                        </td>
                    </tr>
	                       <tr>
                    <td>&nbsp;</td>
                        				<td class="FormLinhaBotoes">
																	                              
                              <input type="submit" id="form_submit" value="Selecionar">
                        				</td>
                    				</tr>
                		</tbody>
            		</table>
        </div>
		</form>
	</div>
</div>
<br class="clr">
