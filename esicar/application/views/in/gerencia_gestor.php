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
				<h1>Gerenciamento de Gestores</h1>
				<div class="table" id="metas">
    
<table id="row">
<thead>
<tr>
<th class="numero">Nome</th>
<th class="especificacao">Email</th>
<th class="valorTexto">Telefone</th>
<th></th></tr></thead>
<tbody id="tbodyrow">
<tr class="odd">
<td>
            <div class="numero">Fulano de Tal</div>
        </td>
<td>
            <div class="especificacao">teste@prototipo.com</div>
        </td>
<td>
            <div class="valorTexto">00 0000-0000</div>
        </td>

<td>
              
            <nobr><a class="buttonLink" href="javascript:document.location='/siconv/CadastrarMetaCronoFisico/MetaIncluirEtapa.do?idProposta=672247&amp;id=1274662';">Editar Gestor</a>
            <a class="buttonLink" href="">Excluir Gestor</a>
            </nobr>
               
        </td></tr></tbody></table>
        <a href="gestor/adiciona">Adiciona Gestor</a>
        </div>
		</form>
	</div>
</div>
<br class="clr">
