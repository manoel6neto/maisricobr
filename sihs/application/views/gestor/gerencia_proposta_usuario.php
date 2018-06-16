<div id="content">
	<div class="innerAll spacing-x2">

		<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">Gerenciamento de Trabalhos</h4>
			</div>
			
		<div class="widget-body center">
    
<table id="row">
<thead>
<tr>
<th class="numero">Nome Proposta</th>
<th class="especificacao">Trabalho</th>
<th class="especificacao">Status</th>
<th class="especificacao">Data da atividade</th>
<th></th></tr></thead>
<tbody id="tbodyrow">
<?php
//$trabalhos_foreach = $trabalhos;
	foreach ($log_trabalho as $log){
		//echo $proposta->nome."<br />";
		$trabalho = $trabalhos->get_by_id($log->Trabalho_idTrabalho);
		$proposta = $propostas->get_by_id($trabalho->id_correspondente);

?>
<tr class="odd">
<td>
            <div class="numero"><?= $proposta->nome;?></div>
        </td>
        <td>
            <div class="especificacao"><?= $trabalhos->obter_tipo_trabalho($trabalho->Tipo_trabalho_idTrabalho);?></div>
        </td>
        <td>
            <div class="especificacao"><?= $trabalhos->obter_status_trabalho($log->Status_idstatus);?></div>
        </td>
		<td>
            <div class="especificacao"><?= date("d/m/Y à\s H:i:s", strtotime($log->data_acao));?></div>
        </td>
<td>
<a class="buttonLink" href="<?= base_url();?>index.php/in/usuario/<?= $trabalhos->obter_nomenclatura_trabalho($trabalho->Tipo_trabalho_idTrabalho);?>?id=<?= $proposta->idProposta;?>&gerencia=1">Gerenciar Trabalho</a>

</td></tr>
<?php
	}
?>
        </tbody></table>
        <a href="<?= base_url();?>index.php/in/gestor" ><< Home Gestor</a>
        </div>
	</div>
</div>
</div>
</div>