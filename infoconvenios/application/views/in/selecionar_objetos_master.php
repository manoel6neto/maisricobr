<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			<form name="" method="post" action="escolhe_programa_master" enctype="multipart/form-data">
				<input type="hidden" name="usuario_siconv" value="<?= $usuario_siconv;?>">
				<input type="hidden" name="senha_siconv" value="<?= $senha_siconv;?>">
				<input type="hidden" name="cnpjProponente" value="<?= $cnpjProponente;?>">
				<input type="hidden" name="orgao" value="<?= $orgao;?>">
				<input type="hidden" name="idRowSelectionAsArray" value="<?= $idRowSelectionAsArray;?>">
				<input type="hidden" name="id" value="<?= $id;?>">
				<h1>Selecionar Objetos para Proposta</h1>
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
<script type="text/javascript">

var listaMarcados = document.getElementsByTagName("INPUT");  
  for (loop = 0; loop < listaMarcados.length; loop++) {  
     var item = listaMarcados[loop];
     if (item.type == "checkbox") {
       item.name = "objetos[]"; 
     }  
  }
    
</script>
