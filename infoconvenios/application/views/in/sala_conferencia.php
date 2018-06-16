<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
    	<div id="content">

<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
			<form name="manterProgramaPropostaValoresDoProgramaSalvarForm" method="post" action="https://www.convenios.gov.br/siconv/ManterProgramaProposta/ValoresDoProgramaSalvar.do" enctype="multipart/form-data">
				

<input type="hidden" name="invalidatePageControlCounter" value="3">
				<input type="hidden" name="idPrograma" value="24988" id="salvarIdPrograma">
<input type="hidden" name="idPropostaPrograma" value="" id="salvarIdPropostaPrograma">
<input type="hidden" name="valorMaximoRepasse" value="" id="salvarValorMaximoRepasse">
<input type="hidden" name="id" value="-9" id="salvarId">
 
				<input type="hidden" id="id" value="-9">
				<h1>Salas de videoconferência</h1>
				<div class="table" id="metas">
    
<table id="row">
<thead>
<tr>
<th class="numero">Nome do Gestor</th>
<th></th></tr></thead>
<tbody id="tbodyrow">
<?php
	foreach($usuarios as $key => $usuario){
?>
<tr class="odd">
<td>
            <div class="numero"><?= $usuario->nome ?></div>
        </td>
<td>
            <div class="numero"><a href="http://demo.bigbluebutton.org/demo/create.jsp?action=enter&meetingID=<?= urlencode($usuario->nome." - Physis's meeting") ?>&username=<?= $nome_usuario ?>">Sala de <?= $usuario->nome ?></a></div>
        </td>
</tr>
<?php
	}
?>
        </tbody></table>
        <a href="index"><< voltar</a>
        </div>
		</form>
	</div>
</div>
<br class="clr">
