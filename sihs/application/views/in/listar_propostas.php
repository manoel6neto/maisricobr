<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
    	<div id="content">

<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
<table id="row">
<thead>
<tr>
<th class="numero">Nome Proposta</th>
<th class="especificacao">Programa</th>
<th></th></tr></thead>
<tbody id="tbodyrow">
<?php
	foreach ($propostas as $proposta){
		//echo $proposta->nome."<br />";
?>
<tr class="odd" >
<td>
            <div class="numero"><?= $proposta->nome;?></div>
        </td>

<td>
            <div class="especificacao"><?= $proposta->nome_programa;?></div>
        </td> 
</tr>
<?php
	}
?>
</tbody></table>

        <input type="button" value="Voltar" onclick="location.href='<?= base_url();?>index.php/in/gestor';">
        <input type="button" value="Cadastrar/comprar" onclick="location.href='<?= base_url();?>index.php/inicio/adiciona?tipo_usuario=<?= $tipo_usuario;?>';">
	</div>
</div>
<br class="clr">
