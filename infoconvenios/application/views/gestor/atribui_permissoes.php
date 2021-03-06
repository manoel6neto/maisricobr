<div id="content">
	<div class="innerAll spacing-x2">

		<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">Gerenciamento de Propostas</h4>
			</div>
		<div class="widget-body center">
<script language="JavaScript" type="text/javascript">
   function mascaraData(campoData){
              var data = campoData.value;
              if (data.length == 2){
                  data = data + '/';
                  campoData.value = data;
      return true;              
              }
              if (data.length == 5){
                  data = data + '/';
                  campoData.value = data;
                  return true;
              }
         }
</script>
<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
			
				<div class="table" id="metas">
    
<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
<thead>
<tr>
<th >Id Proposta</th>
<th>Nome Proposta</th>
<th>Programa</th>
<th >Justificativa</th>
<th>Cronograma físico e de desembolso</th>
<th>Plano de aplicação detalhado</th>
<th>Data Prevista</th>
<th >Data fin. Programa</th>
<th>Ações</th>
</tr></thead>
<tbody id="tbodyrow">

<?php
	foreach ($propostas as $proposta){
		$data = null;
?>

<tr class="odd">
		<td>
<form name="atribui" method="post">
            <div class="numero"><?= $proposta->idProposta;?></div>
        </td>
		<td>
            <div class="numero"><?= $proposta->nome;?></div>
        </td>
		<td>
            <div class="especificacao"><?= $proposta->nome_programa;?></div>
        </td>
        <td>
            <div class="especificacao">
            <input type="hidden" name="id" value="<?= $proposta->idProposta;?>">
            <?php
				$aux1 = $trabalhos->obter_por_proposta(1, $proposta->idProposta);
				$status_trabalho = null;
				if (count($aux1) > 0){
					$status_trabalho = $aux1[0]->Status_idstatus;
				}
			?>
            <select id="justificativa_<?= $proposta->idProposta;?>" style="width:80px" name="justificativa" 
            <?php
            if ($status_trabalho != 1 && $status_trabalho != 0 && $status_trabalho != null) echo "disabled='disabled'"
            ?>
            >
				<option value="0"> - </option>
				<?php
					foreach ($usuarios as $usuario){
						
						echo "<option value=\"".$usuario->idPessoa."\"";
						$aux = $trabalhos->obter_por_proposta(1, $proposta->idProposta);
						if (count($aux) > 0){
							if ($usuario->idPessoa == $aux[0]->Pessoa_idPessoa){
								$data = $aux[0]->data;
								echo " selected=\"true\" ";
							}
						}
						echo">".$usuario->nome."</option>";
					}
				?>
			</select>
			</div>
        </td>
        <td>
            <div class="especificacao">
            <?php
				$aux1 = $trabalhos->obter_por_proposta(3, $proposta->idProposta);
				$status_trabalho = null;
				if (count($aux1) > 0){
					$status_trabalho = $aux1[0]->Status_idstatus;
				}
			?>
            <select id="cronograma_<?= $proposta->idProposta;?>" style="width:80px" name="cronograma" 
            <?php
            if ($status_trabalho != 1 && $status_trabalho != 0 && $status_trabalho != null) echo "disabled='disabled'"
            ?>
            >
				<option value="0"> - </option>
				<?php
					foreach ($usuarios as $usuario){
						echo "<option value=\"".$usuario->idPessoa."\"";
						$aux = $trabalhos->obter_por_proposta(3, $proposta->idProposta);
						if (count($aux) > 0){
							if ($usuario->idPessoa == $aux[0]->Pessoa_idPessoa){
								$data = $aux[0]->data;
								echo " selected=\"true\" ";
							}
						}
						echo">".$usuario->nome."</option>";
					}
				?>
			</select>
			</div>
        </td>
        <td>
            <div class="especificacao">
            <?php
				$aux1 = $trabalhos->obter_por_proposta(4, $proposta->idProposta);
				$status_trabalho = null;
				if (count($aux1) > 0){
					$status_trabalho = $aux1[0]->Status_idstatus;
				}
			?>
            <select id="bens_<?= $proposta->idProposta;?>" style="width:80px" name="bens" 
            <?php
            if ($status_trabalho != 1 && $status_trabalho != 0 && $status_trabalho != null) echo "disabled='disabled'"
            ?>
            >
				<option value="0"> - </option>
				<?php
					foreach ($usuarios as $usuario){
						echo "<option value=\"".$usuario->idPessoa."\"";
						$aux = $trabalhos->obter_por_proposta(4, $proposta->idProposta);
						if (count($aux) > 0){
							if ($usuario->idPessoa == $aux[0]->Pessoa_idPessoa){
								echo " selected=\"true\" ";
							}
						}
						echo">".$usuario->nome."</option>";
					}
				?>
			</select>
			</div>
        </td>
		<td>
            <div class="especificacao">
            <input size="7px" type="text" name="data" value="<?php if (isset($data) !== false) echo implode("/",array_reverse(explode("-",$data)));?>" maxlength="10" OnKeyUp="mascaraData(this);" id="data"></div>
        </td>
		<td>
            <div class="especificacao">
            <?php $data_fim = $proposta_model->obter_programa_siconv($proposta->codigo_programa);
            if (count($data_fim) != 0) $data_fim->data_fim; ?>
        </td>
		<td>
			<div class="btn-group">
				<a class="btn btn-inverse" href="<?= base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?= $proposta->idProposta;?>"><i class="fa fa-eye"></i></a>
				<input type="submit" class="btn btn-inverse" id="form_submit" name="salvar"><i class="fa fa-email"></i></input>
			</div>
        </td>
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
</div>
