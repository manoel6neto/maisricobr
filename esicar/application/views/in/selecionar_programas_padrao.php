<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			<form name="" method="post" action="incluir_proposta" enctype="multipart/form-data">
				<input type="hidden" name="usuario_siconv" value="<?= $usuario_siconv;?>">
				<input type="hidden" name="senha_siconv" value="<?= $senha_siconv;?>">
				<input type="hidden" name="cnpjProponente" value="<?= $cnpjProponente;?>">
				<input type="hidden" name="id" value="<?= $id;?>">
				<h1>Selecionar Programas para Proposta</h1>
				<div class="table" id="metas">
			<table><?= $tabela;?></table>
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
</div>
</div>
</div>
