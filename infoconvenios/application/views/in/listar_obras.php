<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/form-validation.js"></script>

<script>  
function mudarAction() {
	if(document.getElementById("incluirBemTipoDespesa").value > 0)
		document.form_obras.action = "<?php echo base_url(); ?>index.php/in/usuario/incluir_bens_da_proposta?id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>";
	else{
		alert("Informe um tipo de despesa");
		return false;
	} 
}

function testa_tipo_despesa(){
	if(document.getElementById("incluirBemTipoDespesa").value > 0)
		document.form_obras.action = "<?php echo base_url(); ?>index.php/in/usuario/incluir_bens_da_proposta?id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>";
	else{
		alert("Informe um tipo de despesa");
		return false;
	}
}
</script>
<h1 class="bg-white content-heading border-bottom" style="color: #428bca; padding-bottom: 10px;">Plano de Aplicação Detalhado</h1>
<div id="tituloListagem" class="innerALl col-md-12 col-sm-12">    
  <?php
if(isset($idTrabalho)) {
  if ($idTrabalho->Status_idstatus == 4){ //aceito e esperando alterações
    echo "Observações: ".$observacao;
  } 
}
?>
<form enctype="multipart/form-data" method="get" name="form_obras" id="form_obras" onsubmit="return testa_tipo_despesa();">					
<input type="hidden" name="id" value="<?php echo $id; ?>">
Tipo Despesa
<select id="incluirBemTipoDespesa" name="tipoDespesa" >
<option value="0"></option>
<?php
	foreach ($tipo_despesas as $despesa){
		if($despesa->idTipo_despesa != 5)
			echo "<option value=\"".$despesa->idTipo_despesa."\">".$despesa->nome."</option>";
	}
	?>
</select>

<?php if (($edita_gestor == 1 || $idTrabalho->Status_idstatus == 2 || $idTrabalho->Status_idstatus == 4) && $leitura_pessoa == false){ //aceito e esperando alterações ?>
<input class="btn btn-sm btn-primary" type="submit" id="incluir" value="Incluir" name="incluir" >
<?php } ?>
<input class="btn btn-sm btn-primary" type="submit" id="filtrar" value="Filtrar" name="filtrar">
</form>

<span class="botaoTabelaSemLinha"></span>

<div style="overflow-x: auto;" id="bens">
<table class="table" id="row">
<thead>
<tr>
<th class="tipoDespesa">Tipo Despesa</th>
<th class="descricao">Descrição</th>
<th class="natureza.codigoOitoDigitos">Cód. Natureza Despesa</th>
<th class="naturezaAquisicaoString">Natureza Aquisição</th>
<th class="unidade">Un.</th>
<th class="quantidade">Qtde</th>
<th class="valorUnitario">Valor Unitário</th>
<th class="valorTotal">Valor Total</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<tbody id="tbodyrow">
<?php
function retorna_natureza_aquisicao($nat){
	switch($nat){
		case '1':
			return "Recursos do convênio";
			break;
		case '2':
			return "Contrapartida bens e serviços";
			break;
	}
	return " ";
}
	foreach ($despesas as $i=>$despesa){
	?>
<tr class="odd">
<td>
<div class="tipoDespesa"><?php echo $tipo_despesas[$despesa->Tipo_despesa_idTipo_despesa - 1]->nome ?></div>
</td>
<td>
<?php echo $despesa->descricao ?>
</td>
<td>
<div class="natureza.codigoOitoDigitos"><?php echo $despesa->natureza_despesa ?></div>
</td>
<td>
<div class="naturezaAquisicaoString"><?php echo retorna_natureza_aquisicao($despesa->natureza_aquisicao) ?></div>
</td>
<td>
<div class="unidade"><?php echo $despesa->UF ?></div>
</td>
<td>
<div class="quantidade"><?php echo $despesa->quantidade ?></div>
</td>
<td>
<div id="5217862" class="valorUnitario">R$ <?php echo number_format($despesa->valor_unitario,2,",","."); ?></div>
           
</td>
<td>
<div id="5217862" class="valorTotal">R$ <?php echo number_format($despesa->total,2,",","."); ?></div>

</td>

<td>
</td>
<?php
if(isset($idTrabalho)) {
if ($idTrabalho->Status_idstatus == 2 || $idTrabalho->Status_idstatus == 4 || ($voltar_gestor == 1 && $idTrabalho->Status_idstatus != 5) || $edita_gestor == 1){
?>
<td>
<a class="buttonLink" href="<?php echo base_url(); ?>index.php/in/usuario/incluir_bens_da_proposta?idDespesa=<?php echo $despesa->idDespesa ?>&id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>">Alterar</a></nobr>
</td>
<td>
<a onclick="return confirm ('Tem certeza que deseja excluir esse Bem?')" class="buttonLink" href="<?php echo base_url(); ?>index.php/in/usuario/listar_obras?despesa=<?php echo $despesa->idDespesa ?>&id=<?php echo $id ?>&acao=apagar&edita_gestor=<?php echo $edita_gestor?>">Excluir</a></nobr>
</td>
<?php } ?>
</tr>
<?php
}
}
?>
</tbody>
</table>
</div>

<h1 class="bg-white content-heading border-bottom" style="color: #428bca;">Valores Totais</h1> 
<div id="valoresTotais">
<table class="table" id="row">
<thead>
<tr>
<th class="descricao">&nbsp;</th>
<th class="valorTotal">Valor total</th>
<th class="valorContrapartida">Com Recurso do convênio</th>
<th class="valorBensServicos">Contrapartida em bens/serviços</th>
<th class="valorAplicacao">Rend. Aplicação</th>
</tr>
</thead>
<tbody id="tbodyrow">
<tr class="odd">
<td>
<div class="descricao">TOTAL em Bens</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[1][0]) !== false) echo number_format($total[1][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[1][1]) !== false) echo number_format($total[1][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[1][2]) !== false) echo number_format($total[1][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[1][3]) !== false) echo number_format($total[1][3],2,",","."); else echo "0,00"; ?></div>
</td></tr>
<tr class="even">
<td>
<div class="descricao">TOTAL em Serviços</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[2][0]) !== false) echo number_format($total[2][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[2][1]) !== false) echo number_format($total[2][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[2][2]) !== false) echo number_format($total[2][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[2][3]) !== false) echo number_format($total[2][3],2,",","."); else echo "0,00"; ?></div>
</td></tr>
<tr class="odd">
<td>
<div class="descricao">TOTAL em Obras</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[3][0]) !== false) echo number_format($total[3][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[3][1]) !== false) echo number_format($total[3][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[3][2]) !== false) echo number_format($total[3][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[3][3]) !== false) echo number_format($total[3][3],2,",","."); else echo "0,00"; ?></div>
</td>
</tr>
<tr class="even">
<td>
<div class="descricao">TOTAL em Tributos</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[4][0]) !== false) echo number_format($total[4][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[4][1]) !== false) echo number_format($total[4][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[4][2]) !== false) echo number_format($total[4][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[4][3]) !== false) echo number_format($total[4][3],2,",","."); else echo "0,00"; ?></div>
</td>
</tr>
<tr class="odd">
<td>
<div class="descricao">TOTAL em Outros</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[6][0]) !== false) echo number_format($total[6][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[6][1]) !== false) echo number_format($total[6][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[6][2]) !== false) echo number_format($total[6][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[6][3]) !== false) echo number_format($total[6][3],2,",","."); else echo "0,00"; ?></div>
</td>
</tr>
<tr class="even">
<td>
<div class="descricao">TOTAL em Despesa Administrativa</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[5][0]) !== false) echo number_format($total[5][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[5][1]) !== false) echo number_format($total[5][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[5][2]) !== false) echo number_format($total[5][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[5][3]) !== false) echo number_format($total[5][3],2,",","."); else echo "0,00"; ?></div>
</td></tr>
<tr class="odd">
<td>
<div class="descricao">TOTAL GERAL</div>
</td>
<td>
<div class="valorTotal">R$ <?php if (isset($total[0][0]) !== false) echo number_format($total[0][0],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorContrapartida">R$ <?php if (isset($total[0][1]) !== false) echo number_format($total[0][1],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorBensServicos">R$ <?php if (isset($total[0][2]) !== false) echo number_format($total[0][2],2,",","."); else echo "0,00"; ?></div>
</td>
<td>
<div class="valorAplicacao">R$ <?php if (isset($total[0][3]) !== false) echo number_format($total[0][3],2,",","."); else echo "0,00"; ?></div>
</td>
</tr>
</tbody>
</table>
</div>
<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url().'index.php/in/usuario/listar_cronograma?id='.$_GET['id'].'&edita_gestor=1'; ?>';">
<?php if(number_format($valor_global,2,",",".") == number_format($total[0][0],2,",",".")):?>
<a class="btn btn-success" href="<?php echo base_url(); ?>index.php/in/usuario/visualiza_proposta?id=<?php echo $id; ?>">Finalizar Trabalho</a>
<?php endif;?>
</div>
&nbsp;