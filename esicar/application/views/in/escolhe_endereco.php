<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">

<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" src="<?= base_url();?>configuracoes/js/thumbnailviewer.js"></script>
	
<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">

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

function transferir_endereco() {
var ind = document.getElementById('endereco_select').selectedIndex;
var valor = document.getElementById('endereco_select')[ind].innerHTML;
endereco = document.getElementById('alterarEndereco');
cep = document.getElementById('alterarCep');
sigla = document.getElementById('municipio_sigla');
var n=valor.split(" # ");
sigla.value = n[0];
endereco.value = n[1];
cep.value = n[2];
}

function transferir_uf() {
var valor = document.getElementById('uf_nome').value;
campo = document.getElementById('fornecimento');
campo.value = valor;
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

	if(ValidaCep(cep) == false){
		alert('Verifique se o CEP está no formato válido XXXXX-XXX');
		return false;
	}
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'obrigatorio' && valor == '') podeEnviar = false;
    }
    
    var campos = form.getElementsByTagName('select');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'obrigatorio' && valor == '') podeEnviar = false;
    }
    
    var campos = form.getElementsByTagName('textarea');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'obrigatorio' && valor == '') podeEnviar = false;
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
</script>

    	<div id="container">
<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">

<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
<form enctype="multipart/form-data" method="post" name="" onSubmit="return enviardados_form(this);">
				<input type="hidden" name="usuario_siconv" value="<?= $usuario_siconv;?>">
				<input type="hidden" name="senha_siconv" value="<?= $senha_siconv;?>">
				<input type="hidden" name="id" value="<?= $id;?>">
				<input type="hidden" name="valorGlobal" value="<?= $valorGlobal;?>">
				<input type="hidden" name="agencia" value="<?= $agencia;?>">
				<input type="hidden" name="digito" value="<?= $digito;?>">
				<input type="hidden" name="banco" value="<?= $banco;?>">
				<input type="hidden" name="cnpjProponente" value="<?=$cnpjProponente;?>">
<h1>Endereço</h1>
	<table>
		<tbody onKeyDown="return altera_botao(event)">
				<tr id="tr-incluirUf" class="uf">
					<td class="label">UF</td>

					<td class="field"> 
							<select id="incluirUf" onkeypress="keySubmit(event)" onkeyup="verifica()" onmouseup="verifica()" onmouseout="hints.hide()" onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.uf.title')" name="UF">
							<option value=""></option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '1') echo "selected=\"true\"";?>value="1">AC</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '2') echo "selected=\"true\"";?>value="2">AL</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '4') echo "selected=\"true\"";?>value="4">AM</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '3') echo "selected=\"true\"";?>value="3">AP</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '5') echo "selected=\"true\"";?>value="5">BA</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '6') echo "selected=\"true\"";?>value="6">CE</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '27') echo "selected=\"true\"";?>value="27">DF</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '7') echo "selected=\"true\"";?>value="7">ES</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '8') echo "selected=\"true\"";?>value="8">GO</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '9') echo "selected=\"true\"";?>value="9">MA</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '12') echo "selected=\"true\"";?>value="12">MG</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '11') echo "selected=\"true\"";?>value="11">MS</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '10') echo "selected=\"true\"";?>value="10">MT</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '13') echo "selected=\"true\"";?>value="13">PA</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '14') echo "selected=\"true\"";?>value="14">PB</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '16') echo "selected=\"true\"";?>value="16">PE</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '17') echo "selected=\"true\"";?>value="17">PI</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '15') echo "selected=\"true\"";?>value="15">PR</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '18') echo "selected=\"true\"";?>value="18">RJ</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '19') echo "selected=\"true\"";?>value="19">RN</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '21') echo "selected=\"true\"";?>value="21">RO</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '22') echo "selected=\"true\"";?>value="22">RR</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '20') echo "selected=\"true\"";?>value="20">RS</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '23') echo "selected=\"true\"";?>value="23">SC</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '25') echo "selected=\"true\"";?>value="25">SE</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '24') echo "selected=\"true\"";?>value="24">SP</option>
<option <?php if ((isset($meta->UF) !== false) && ($meta->UF) == '26') echo "selected=\"true\"";?> value="26">TO</option></select>
					</td>
				</tr>
				<tr id="tr-cadastrarParticipeCodigoMunicipio" class="codigoMunicipio">
					<td class="label"><nobr>Munícipio</nobr></td>
					<td class="field">
					 <input readonly="readonly" type="text" value="<?php if (isset($meta->municipio_sigla) !== false) echo $meta->municipio_sigla;?>" id="municipio_sigla" maxlength="5" size="5" name="municipio_sigla">
					<select name="municipio_nome" id="municipio_nome" onchange="transferir_cidade()">
						<option value=""></option>
						<?php
							foreach ($cidades as $cidade) {
								echo '<option value="'.$cidade->Codigo.'">'.$cidade->Nome.'</option>';
							}
						?>
					</select>
					</td>
				</tr>

				<tr id="tr-alterarEndereco" class="endereco">
					<td class="label">Endereço</td>
					<td class="field">
					<select name="endereco_select" id="endereco_select" onchange="transferir_endereco()">
						<option value=""></option>
						<?php
							foreach ($enderecos as $endereco) {
								echo '<option value="'.$endereco->endereco.'">'.$endereco->municipio_sigla.' # '.$endereco->endereco.' # '.$endereco->cep.'</option>';
							}
						?>
					</select><br />
					<textarea id="alterarEndereco" onkeypress="keySubmit(event)" onkeyup="verifica()" onmouseup="verifica()" cols="60" onmouseout="hints.hide()" onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.endereco.title')" name="endereco">
<?php if (isset($meta->endereco) !== false) echo $meta->endereco;?></textarea></td>
				</tr>

				<tr id="tr-alterarCep" class="cep">
					<td class="label">CEP</td>
					<td class="field"> <input type="text" value="<?php if (isset($meta->cep) !== false) echo $meta->cep;?>" id="alterarCep" onmouseout="hints.hide()" onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.cep.title')" onkeypress="keySubmit(event)" onkeyup="verifica()" onmouseup="verifica()" name="cep"></td>
				</tr>

			<tr>
				<td></td>
				<td class="FormLinhaBotoes">
					<input type="submit" name="cadastra" value="Salvar" id="cadastrar">
					
				</td>
			</tr>
		</tbody>
	</table>
</form>

<br /><input class="buttonLink" type="button" value="Voltar" onclick="location.href='<?= base_url();?>index.php/in/gestor/gerencia_proposta';">
</div>
</div>
</div>
</div>
</div>
</div>

