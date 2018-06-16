<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/calculaValor.js"></script>
<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>
<style type="text/css">
.f-navExp{  /* To fix main menu container */
    z-index: 9998;
    position: fixed;
    top: 116px;
    width: 100%;
    padding: 15px;
}

.dados{
	width: 50%;
}
</style>
	
<script type="text/javascript" language="Javascript1.1">
            var HINTS_ITEMS = {
                'manter.programa.proposta.valores.do.programa.salvar.param.id.programa.title':'idPrograma',
                'manter.programa.proposta.valores.do.programa.salvar.param.id.proposta.programa.title':'idPropostaPrograma',
                'manter.programa.proposta.valores.do.programa.salvar.param.codigo.programa.title':'codigoPrograma',
                'manter.programa.proposta.valores.do.programa.salvar.param.nome.programa.title':'nomePrograma',
                'manter.programa.proposta.valores.do.programa.salvar.param.qualificacao.proponente.title':'qualificacaoProponente is required',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.global.title':'valorGlobal is required',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.repasse.title':'valorRepasse is required',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.title':'valorContrapartida is required',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.financeira.title':'valorContrapartidaFinanceira is required',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.bens.servicos.title':'valorContrapartidaBensServicos',
                'manter.programa.proposta.valores.do.programa.salvar.param.objetos.title':'objetos is required',
                'manter.programa.proposta.valores.do.programa.salvar.param.aceita.despesa.administrativa.title':'aceitaDespesaAdministrativa',
                'manter.programa.proposta.valores.do.programa.salvar.param.percentual.maximo.contrapartida.bens.title':'percentualMaximoContrapartidaBens',
                'manter.programa.proposta.valores.do.programa.salvar.param.percentual.minimo.contrapartida.title':'percentualMinimoContrapartida',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.max.repasse.title':'valorMaxRepasse',
                'manter.programa.proposta.valores.do.programa.salvar.param.valor.maximo.repasse.title':'valorMaximoRepasse',
                'manter.programa.proposta.valores.do.programa.salvar.param.id.title':'id',
                'salvar':'Salvar',
                'salvar_no':'You are not allowed to call this action',
                'salvar_reset':'Reset',
                'salvar_noreset':'You are not allowed to reset',
                'cancelar':'Cancelar',
                'cancelar_no':'You are not allowed to call this action',
                'cancelar_reset':'Reset',
                'cancelar_noreset':'You are not allowed to reset',
                'calendar.popup':'Clique aqui para abrir um calend\u00E1rio e escolher uma data'
            };
            
            var HINTS_COMBO_ITEMS = {            

            }
            
            var hints = new THints (HINTS_CFG, HINTS_ITEMS);
            
            var HINTS_COMBO_CFG = {
		'top'        : 5,	'left'       : 30,	'css'        : 'hintsClass',
		'show_delay' : 400,	'hide_delay' : 3000,	'wise'       : false,
		'follow'     : false,	'z-index'    : 110
            };

	    var combohints = new THints (HINTS_COMBO_CFG, HINTS_COMBO_ITEMS);

            function getComboHintTitle(combo, title) {
            	return title + combo.selectedIndex;
            }

            var comboTimerDelay = 10;

            function handleComboMouseOut(event) {
            	if (event.target.type == 'select-one')
            		window.setTimeout('combohints.hide()', comboTimerDelay);
            }	    
        //-->
    	</script>
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
                 
function transferir_cidade() {
var valor = document.getElementById('municipio_nome').value;
campo = document.getElementById('municipio_sigla');
campo.value = valor;
}

function transferir_uf() {
var valor = document.getElementById('uf_nome').value;
campo = document.getElementById('fornecimento');
campo.value = valor;
}

function transferir_endereco() {
var ind = document.getElementById('endereco_select').selectedIndex;
var valor = document.getElementById('endereco_select')[ind].innerHTML;
endereco = document.getElementById('alterarEndereco');
cep = document.getElementById('alterarCep');
sigla = document.getElementById('municipio_sigla');
var n=valor.split(" # ");
//sigla.value = n[0];
endereco.value = n[0];
cep.value = n[1];
}

function ValidaCep(cep){
	exp = /\d{5}\-\d{3}/
	if(!exp.test(cep))
		return false;
	return true;	
}

//valida data
function ValidaData(data){
	exp = /\d{2}\/\d{2}\/\d{4}/
	if(!exp.test(data))
		return false;
	return true;
}

function enviardados_form(form){
	var cep = document.getElementById('alterarCep').value;
	if(ValidaCep(cep) == false  && cep != ''){
		alert('Verifique se o CEP está no formato válido XXXXX-XXX');
		return false;
	}
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if(valor != "Avançar" && valor != "Salvar" && valor != "Voltar"){
	        if ((classe.indexOf('obrigatorio') != -1) && (valor == '')){
	            podeEnviar = false;
	            campos[i].style.color = "#fff";
	            campos[i].style.backgroundColor = "#FF7777";
	        }else{
	        	campos[i].style.color = "#a7a7a7";
	        	campos[i].style.backgroundColor = "#fff";
	        }
        }
    }
    
    var campos = form.getElementsByTagName('select');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if ((classe.indexOf('obrigatorio') != -1) && (valor == '')){
            podeEnviar = false;
            campos[i].style.color = "#fff";
            campos[i].style.backgroundColor = "#FF7777";
        }else{
        	campos[i].style.color = "#a7a7a7";
        	campos[i].style.backgroundColor = "#fff";
        }
    }
    
    var campos = form.getElementsByTagName('textarea');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if ((classe.indexOf('obrigatorio') != -1) && (valor == '')){ 
            podeEnviar = false;
            campos[i].style.color = "#fff";
            campos[i].style.backgroundColor = "#FF7777";
        }else{
        	campos[i].style.color = "#a7a7a7";
        	campos[i].style.backgroundColor = "#fff";
        }
    }
    
	var dtini = document.getElementById('data_inicio').value;
    var dtfim = document.getElementById('data_termino').value;
   if(ValidaData(dtini) == false || ValidaData(dtfim) == false){
		alert('Verifique se as datas estão no formato válido dd/mm/aaaa');
		return false;
	}
    datInicio = new Date(dtini.substring(6,10),
                         dtini.substring(3,5),
                         dtini.substring(0,2));
    datInicio.setMonth(datInicio.getMonth() - 1);

    datFim = new Date(dtfim.substring(6,10),
                      dtfim.substring(3,5),
                      dtfim.substring(0,2));
                     
    datFim.setMonth(datFim.getMonth() - 1);

    if(datInicio > datFim){
		alert('Data final deve ser maior do que a data inicial.');
		return false;
	}
	
	uf = document.getElementById('incluirUf').value;
    municipio = document.getElementById('municipio_sigla').value;
	if (uf != '' && municipio == '') {
		alert('Ao escolher a UF, o municipio torna-se obrigatório');
        return false;
	}
	 if (podeEnviar == true) {
        return true;
    } else {
        alert('existem campos obrigatórios em branco!')
        return false;
    }
	return true;
}

function altera_botao(e){
if (e.keyCode == '13'){
	return false; 
	}
}
function desativa_formulario(){
   var x=document.getElementById("form_etapa");
 for (var i=0;i<x.length;i++)
 {
   x.elements[i].setAttribute('readonly','readonly');
 }
 document.getElementById("cadastrar").style.display = "none";
 document.getElementById("inserir").style.display = "none";
}
</script>

<div class="dados innerAll" style="background-color: white;">
<table class="table">
<thead>
<tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr>
<tr>
<th>Especificação</th>
<th>Valor da Meta</th>
<th>Soma das Etapas</th>
<th>Valor Restante</th>
</tr>

<tr>
<td style="width: 40%;"><?php echo $meta->especificacao; ?></td>
<td><?php echo "R$ ".number_format($meta->total,2,",","."); ?></td>
<td><?php echo "<span style='color:red;'>R$ ".number_format($valorTotalEtapa,2,",",".")."</span>"; ?></td>
<td><?php echo "<span style='color:green;'>R$ ".number_format(round($meta->total,2)-round($valorTotalEtapa,2),2,",",".")."</span>"; ?></td>
</tr>
</thead>     
</table>
</div>
<br/>
<table class="table">
<thead>
<tr><th style="color: red; font-size: 16px;" colspan="2">Etapas Incluídas</th></tr>
<?php foreach ($dadosEtapas as $dadosEtapa): ?>
<tr>
<th style="border: 0;">Especificação: <?php echo $dadosEtapa['especificacao']; ?></th>
<th style="border: 0;">Valor: <?php echo "R$ ".number_format($dadosEtapa['total'],2,",","."); ?></th>
</tr>
<?php endforeach; ?>
</thead>     
</table>


<h1 class="bg-white content-heading border-bottom" style="color: #428bca;">Incluir Etapa</h1>
<div>
<div class="col-md-12 bg-white innerAll spacing-x2">
		<div id="ConteudoDiv">
		<div class="action" id="incluir">
				<div class="trigger" onKeyDown="return altera_botao(event)">
		
<form id="form_etapa" method="post" enctype="multipart/form-data" 
	onSubmit="return enviardados_form(this);">
	<input class="form-control" type="hidden" name="idMeta"
		value="<?php echo  $meta_id;?>"> <input class="form-control"
		type="hidden" name="idEtapa"
		value="<?php if (isset($etapa->idEtapa) !== false) echo $etapa->idEtapa;?>">
	<input type="hidden" id="cnpjProponente" value="<?php echo $proposta->proponente; ?>">

	<div class="col-sm-6">
	<div class="form-group">
		<label>Especificação *</label><br>
			<textarea class='obrigatorio form-control obrigatorio' id="especificacao"
				onkeyup="verifica();textCounter(this.form.especificacao,this.form.remLen,5000);"
				onchange="textCounter(this.form.especificacao,this.form.remLen,5000);"
				onkeydown="textCounter(this.form.especificacao,this.form.remLen,5000);"
				rows="5" cols="60" maxlength="5000" onkeypress="keySubmit(event)"
				onmouseup="verifica();" onmouseout="hints.hide()"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.especificacao.title')"
				name="especificacao"><?php if (isset($etapa->especificacao) !== false) echo $etapa->especificacao;?></textarea>
				<br>
			<div id="caracteresRestantes">
				Caracteres restantes: <input type="text"
					value="5000" maxlength="4" size="4" name="remLen" readonly="readonly">
			</div>
	</div>
	
	<div class="form-group">
		<label>Unidade Fornecimento *</label><br>
		<select class="form-control"
				name="uf_nome" id="uf_nome" onchange="transferir_uf()">
				<option value=""></option>
						<?php
						foreach ( $ufs as $uf ) {
							echo '<option value="' . $uf->Codigo . '">' . $uf->Nome . '</option>';
						}
						?>
					</select>
				<input class="form-control obrigatorio" type="text"
				value="<?php if (isset($etapa->fornecimento) !== false) echo $etapa->fornecimento;?>"
				id="fornecimento" onmouseout="hints.hide()"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.cod.unidade.fornecimento.title')"
				onkeypress="keySubmit(event)" onkeyup="verifica()"
				onmouseup="verifica()" name="fornecimento" size="9">
	</div>
	
	
	<div class="form-group">
			<label>Valor Total (R$) *</label> <input class="form-control obrigatorio" type="text"
				value="<?php if (isset($etapa->total) !== false) echo number_format($etapa->total,2,",",".");?>"
				onkeydown="backspace(this,event)"
				onblur="calculaValorUnitario(valorUnitario,quantidade.value,valor.value)"
				onkeypress="reais(this,event)" id="total" onmouseout="hints.hide()" maxlength="14"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.valor.title')"
				onkeyup="verifica()" onmouseup="verifica()" name="valor">
	</div>
	
	<div class="form-group">
			<label>Quantidade *</label> <input class="form-control obrigatorio" type="text"
				value="<?php if (isset($etapa->quantidade) !== false) echo number_format($etapa->quantidade,2,",",".");?>"
				onkeydown="backspace(this,event)"
				onblur="calculaValorUnitario(valorUnitario,quantidade.value,valor.value)"
				onkeypress="reais(this,event)" id="incluirQuantidade"
				onmouseout="hints.hide()" maxlength="14"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.quantidade.title')"
				onkeyup="verifica()" onmouseup="verifica()" name="quantidade">
	</div>
	
	<div class="form-group">
			<label>Valor Unitário (R$)</label> <input class="form-control obrigatorio" type="text"
				value="<?php if (isset($etapa->valorUnitario) !== false) echo number_format($etapa->valorUnitario,2,",",".");?>"
				onkeydown="backspace(this,event)" onkeypress="reais(this,event)"
				id="incluirValorUnitario" readonly="readonly"
				onmouseout="hints.hide()"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.valor.unitario.title')"
				onkeyup="verifica()" onmouseup="verifica()" name="valorUnitario">
	</div>
	
	<div class="form-group">
			<label>Data de Início *</label> <input class="form-control obrigatorio" type="text" OnKeyUp="mascaraData(this);"
				value="<?php if (isset($etapa->data_inicio) !== false) echo implode("/",array_reverse(explode("-",$etapa->data_inicio)));?>"
				id="data_inicio" maxlength="10" name="data_inicio">
	</div>
	
	<div class="form-group">
			<label>Data de Término *</label> <input class="form-control obrigatorio" type="text" OnKeyUp="mascaraData(this);"
				value="<?php if (isset($etapa->data_termino) !== false) echo implode("/",array_reverse(explode("-",$etapa->data_termino)));?>"
				id="data_termino" maxlength="10" name="data_termino">
	</div>
	
	<div class="form-group">
			<label>UF *</label> <select id="incluirUf" class="form-control obrigatorio"
				onkeypress="keySubmit(event)" onkeyup="verifica()"
				onmouseup="verifica()" onmouseout="hints.hide()"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.uf.title')"
				name="UF">
				<option value=""></option>
					<?php if (isset($etapa->UF) !== false){ ?>
					<option <?php if ($etapa->UF == '1') echo "selected=\"true\"";?>
					value="1">AC</option>
				<option <?php if (($etapa->UF) == '2') echo "selected=\"true\"";?>
					value="2">AL</option>
				<option <?php if (($etapa->UF) == '4') echo "selected=\"true\"";?>
					value="4">AM</option>
				<option <?php if (($etapa->UF) == '3') echo "selected=\"true\"";?>
					value="3">AP</option>
				<option <?php if (($etapa->UF) == '5') echo "selected=\"true\"";?>
					value="5">BA</option>
				<option <?php if (($etapa->UF) == '6') echo "selected=\"true\"";?>
					value="6">CE</option>
				<option <?php if (($etapa->UF) == '27') echo "selected=\"true\"";?>
					value="27">DF</option>
				<option <?php if (($etapa->UF) == '7') echo "selected=\"true\"";?>
					value="7">ES</option>
				<option <?php if (($etapa->UF) == '8') echo "selected=\"true\"";?>
					value="8">GO</option>
				<option <?php if (($etapa->UF) == '9') echo "selected=\"true\"";?>
					value="9">MA</option>
				<option <?php if (($etapa->UF) == '12') echo "selected=\"true\"";?>
					value="12">MG</option>
				<option <?php if (($etapa->UF) == '11') echo "selected=\"true\"";?>
					value="11">MS</option>
				<option <?php if (($etapa->UF) == '10') echo "selected=\"true\"";?>
					value="10">MT</option>
				<option <?php if (($etapa->UF) == '13') echo "selected=\"true\"";?>
					value="13">PA</option>
				<option <?php if (($etapa->UF) == '14') echo "selected=\"true\"";?>
					value="14">PB</option>
				<option <?php if (($etapa->UF) == '16') echo "selected=\"true\"";?>
					value="16">PE</option>
				<option <?php if (($etapa->UF) == '17') echo "selected=\"true\"";?>
					value="17">PI</option>
				<option <?php if (($etapa->UF) == '15') echo "selected=\"true\"";?>
					value="15">PR</option>
				<option <?php if (($etapa->UF) == '18') echo "selected=\"true\"";?>
					value="18">RJ</option>
				<option <?php if (($etapa->UF) == '19') echo "selected=\"true\"";?>
					value="19">RN</option>
				<option <?php if (($etapa->UF) == '21') echo "selected=\"true\"";?>
					value="21">RO</option>
				<option <?php if (($etapa->UF) == '22') echo "selected=\"true\"";?>
					value="22">RR</option>
				<option <?php if (($etapa->UF) == '20') echo "selected=\"true\"";?>
					value="20">RS</option>
				<option <?php if (($etapa->UF) == '23') echo "selected=\"true\"";?>
					value="23">SC</option>
				<option <?php if (($etapa->UF) == '25') echo "selected=\"true\"";?>
					value="25">SE</option>
				<option <?php if (($etapa->UF) == '24') echo "selected=\"true\"";?>
					value="24">SP</option>
				<option <?php if (($etapa->UF) == '26') echo "selected=\"true\"";?>
					value="26">TO</option>
			</select>
					<?php } else { ?>
					<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '1') echo "selected=\"true\"";?>
				value="1">AC</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '2') echo "selected=\"true\"";?>
				value="2">AL</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '4') echo "selected=\"true\"";?>
				value="4">AM</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '3') echo "selected=\"true\"";?>
				value="3">AP</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '5') echo "selected=\"true\"";?>
				value="5">BA</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '6') echo "selected=\"true\"";?>
				value="6">CE</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '27') echo "selected=\"true\"";?>
				value="27">DF</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '7') echo "selected=\"true\"";?>
				value="7">ES</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '8') echo "selected=\"true\"";?>
				value="8">GO</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '9') echo "selected=\"true\"";?>
				value="9">MA</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '12') echo "selected=\"true\"";?>
				value="12">MG</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '11') echo "selected=\"true\"";?>
				value="11">MS</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '10') echo "selected=\"true\"";?>
				value="10">MT</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '13') echo "selected=\"true\"";?>
				value="13">PA</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '14') echo "selected=\"true\"";?>
				value="14">PB</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '16') echo "selected=\"true\"";?>
				value="16">PE</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '17') echo "selected=\"true\"";?>
				value="17">PI</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '15') echo "selected=\"true\"";?>
				value="15">PR</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '18') echo "selected=\"true\"";?>
				value="18">RJ</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '19') echo "selected=\"true\"";?>
				value="19">RN</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '21') echo "selected=\"true\"";?>
				value="21">RO</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '22') echo "selected=\"true\"";?>
				value="22">RR</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '20') echo "selected=\"true\"";?>
				value="20">RS</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '23') echo "selected=\"true\"";?>
				value="23">SC</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '25') echo "selected=\"true\"";?>
				value="25">SE</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '24') echo "selected=\"true\"";?>
				value="24">SP</option>
			<option
				<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '26') echo "selected=\"true\"";?>
				value="26">TO</option>
			</select>
		<?php } ?>
	</div>
	
	<div class="form-group">
		<label for="cod_cidades">Cidade:</label> <span class="carregando">Aguarde,
				carregando...</span> <select name="municipio_nome"
				id="municipio_nome" onchange="transferir_cidade()" class="form-control obrigatorio">
				<option value="">-- Escolha um estado --</option>
			</select><br />
			<label>Código Munícipio *</label> <input class="form-control" type="text" readonly="readonly"
				value="<?php if (isset($etapa->municipio_sigla) !== false) echo $etapa->municipio_sigla; else if (isset($endereco_proposta->municipio_sigla) !== false) echo $endereco_proposta->municipio_sigla;?>"
				id="municipio_sigla" maxlength="5" size="5" name="municipio_sigla">
			
	</div>
	
	<div class="form-group">
			<label style="color: red;">Recuperar Endereço</label> <select name="endereco_select" class="form-control"
				id="endereco_select" onchange="transferir_endereco()">
				<option value=""></option>
						<?php
						foreach ( $enderecos as $endereco ) {
							echo '<option value="' . $endereco->endereco . '">' . $endereco->endereco . ' # ' . $endereco->cep . '</option>';
						}
						?>
					</select><br />
					<label>Endereço de Localização *</label><br>
			<textarea id="alterarEndereco" onkeypress="keySubmit(event)" class="obrigatorio"
				onkeyup="verifica()" onmouseup="verifica()" cols="60"
				onmouseout="hints.hide()"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.endereco.title')"
				name="endereco"><?php if (isset($etapa->endereco) !== false) echo $etapa->endereco; else if (isset($endereco_proposta->endereco) !== false) echo $endereco_proposta->endereco;?></textarea>
				<div id="pageHelpSection">
						<blockquote style="color: red;">Endereço de execução do serviço, da instalação do bem
							ou de localização da obra</blockquote>
					</div>
	</div>
	
	<div class="form-group">
			<label>CEP *</label> <input class="form-control obrigatorio" type="text"
				value="<?php if (isset($etapa->cep) !== false) echo $etapa->cep; else if (isset($endereco_proposta->cep) !== false) echo $endereco_proposta->cep;?>"
				id="alterarCep" onmouseout="hints.hide()"
				onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.cep.title')"
				onkeypress="keySubmit(event)" onkeyup="verifica()" maxlength="9"
				onmouseup="verifica()" name="cep">
	</div>
	
	<div class="form-group">
			<input type="submit" class="btn btn-primary" name="cadastra"
				value="Salvar" id="cadastrar">
				
			<input class="btn btn-primary" type="button" value="Voltar"
			onclick="location.href='<?php echo base_url(); ?>index.php/in/usuario/listar_etapas?meta=<?php echo $meta_id; ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>';">
	</div>
</div>
</form>
</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('.carregando').hide();
		<?php if($this->session->userdata('nivel') == 1):?>

		$(function(){
			$('.carregando').hide();
			
			$('#incluirUf').change(function(){
				if( $(this).val() ) {
					carregaCidades($(this).val());
				} else {
					$('#municipio_nome').html('<option value="">– Escolha um estado –</option>');
				}
			});

			$(window).scroll(function(){
				var topo = $('.exporta').height();

				var scrollTop = $(window).scrollTop();
		 
				if(scrollTop > topo){
		  			$('.dados').addClass('f-navExp');
				}else{
		  			$('.dados').removeClass('f-navExp');
				}               
			});
			
			function carregaCidades(valorUF){
				if(valorUF != ""){
					$('#municipio_nome').hide();
					$('.carregando').show();
					$.getJSON('cidades_ajax?search=',{cod_estados: valorUF, ajax: 'true'}, function(j){
						var options = '<option value=""></option>';	
						for (var i = 0; i < j.length; i++) {
							options += '<option value="' + j[i].Codigo + '">' + j[i].nome + '</option>';
						}	
						$('#municipio_nome').html(options).show();
						$('.carregando').hide();
					});
				}
			}

			carregaCidades($('#incluirUf').val());

			$("#alterarCep").mask("99999-999");
		});

		<?php else:?>

		function carregaDadosEndereco(){
			var url = "<?php echo base_url(); ?>index.php/in/usuario/busca_dados_endereco?id=<?php echo $_GET['id']; ?>&cnpjProp="+$("#cnpjProponente").val();
			var urlProp = "<?php echo base_url(); ?>index.php/in/usuario/busca_dados_proposta?id=<?php echo $_GET['id']; ?>";
			//$("#idProj").val($(this).attr("id"));
			$.getJSON(url, function(data){
				var optionsUF = '';
				var optionsMun = '';
					
		        $.each(data.estado, function(key, value){
		            optionsUF += '<option value="'+key+'">'+value+'</option>';
		        });

		        $.each(data.municipio, function(key, value){
		        	optionsMun += '<option value="'+key+'">'+value+'</option>';
		        });
		        
		        $("#incluirUf").html(optionsUF);
		        $("#municipio_nome").html(optionsMun);
		       	$("#municipio_sigla").val($("#municipio_nome").val());
		       	$("#municipio_sigla").attr({"readonly":true}); 
		    });
		}

		carregaDadosEndereco();

		<?php endif;?>
	</script>
<?php
if ($leitura_pessoa == true) { // aceito e esperando alterações
	?>
<script type="text/javascript">
desativa_formulario();
</script>
<?php } ?>
