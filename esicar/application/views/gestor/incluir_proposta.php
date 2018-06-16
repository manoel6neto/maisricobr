<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>configuracoes/js/thumbnailviewer.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/form-validation.js"></script>
<script type="text/javascript" language="Javascript1.1">
    <!-- Begin 

    var bCancel = false;

    function validateValoresDoProgramaSalvarForm(form) {
        if (bCancel)
            return true;
        else
            var formValidationResult;
        formValidationResult = cptValidateRequired(form);
        return (formValidationResult == 1);
    }

    function manterProgramaPropostaValoresDoProgramaSalvarForm_required() {
        this.a0 = new Array("qualificacaoProponente", "O campo Regra de Contrapartida é obrigatório.", new Function("varName", " return this[varName];"));
        this.a1 = new Array("valorContrapartida", "O campo Total de Contrapartida (R$) é obrigatório.", new Function("varName", " return this[varName];"));
        this.a2 = new Array("objetos", "O campo Objetos é obrigatório.", new Function("varName", " return this[varName];"));
        this.a3 = new Array("valorContrapartidaFinanceira", "O campo Contrapartida Financeira (R$) é obrigatório.", new Function("varName", " return this[varName];"));
        this.a4 = new Array("valorGlobal", "O campo Valor Global do(s) Objeto(s) (R$) é obrigatório.", new Function("varName", " return this[varName];"));
        this.a5 = new Array("valorRepasse", "O campo Valor de Repasse (R$) é obrigatório.", new Function("varName", " return this[varName];"));
    }

    //End -->
</script>

<script type="text/javascript" language="Javascript1.1">
    var HINTS_ITEMS = {
        'manter.programa.proposta.valores.do.programa.salvar.param.id.programa.title': 'idPrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.id.proposta.programa.title': 'idPropostaPrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.codigo.programa.title': 'codigoPrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.nome.programa.title': 'nomePrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.qualificacao.proponente.title': 'qualificacaoProponente is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.global.title': 'valorGlobal is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.repasse.title': 'valorRepasse is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.title': 'valorContrapartida is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.financeira.title': 'valorContrapartidaFinanceira is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.bens.servicos.title': 'valorContrapartidaBensServicos',
        'manter.programa.proposta.valores.do.programa.salvar.param.objetos.title': 'objetos is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.aceita.despesa.administrativa.title': 'aceitaDespesaAdministrativa',
        'manter.programa.proposta.valores.do.programa.salvar.param.percentual.maximo.contrapartida.bens.title': 'percentualMaximoContrapartidaBens',
        'manter.programa.proposta.valores.do.programa.salvar.param.percentual.minimo.contrapartida.title': 'percentualMinimoContrapartida',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.max.repasse.title': 'valorMaxRepasse',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.maximo.repasse.title': 'valorMaximoRepasse',
        'manter.programa.proposta.valores.do.programa.salvar.param.id.title': 'id',
        'salvar': 'Salvar',
        'salvar_no': 'You are not allowed to call this action',
        'salvar_reset': 'Reset',
        'salvar_noreset': 'You are not allowed to reset',
        'cancelar': 'Cancelar',
        'cancelar_no': 'You are not allowed to call this action',
        'cancelar_reset': 'Reset',
        'cancelar_noreset': 'You are not allowed to reset',
        'calendar.popup': 'Clique aqui para abrir um calend\u00E1rio e escolher uma data'
    };

    var HINTS_COMBO_ITEMS = {

    }

    var hints = new THints(HINTS_CFG, HINTS_ITEMS);

    var HINTS_COMBO_CFG = {
        'top': 5,
        'left': 30,
        'css': 'hintsClass',
        'show_delay': 400,
        'hide_delay': 3000,
        'wise': false,
        'follow': false,
        'z-index': 110
    };

    var combohints = new THints(HINTS_COMBO_CFG, HINTS_COMBO_ITEMS);

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
<script type="text/javascript">
    function UpdateDoubleSelect(combo, valor) {
        eval('combo = document.' +
            combo + ';');
        for (index = 0; index < combo.length; index++) {
            if (combo[index].value == valor)
                combo.selectedIndex = index;
        }
    }

    function descriptionDestination(key, componenteNome) {
        var HINTS_DESTINATION = {}
        var componenteDestino = document.getElementsByName(componenteNome);
        if (componenteDestino[0] != null) {
            componenteDestino[0].value = HINTS_DESTINATION[key];
        }
    }
</script>
<script type="text/javascript">
    function getPath() {
        return "/siconv";
    }
</script>
<script type="text/javascript">
    <!--
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
        eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
        if (restore) selObj.selectedIndex = 0;
    }
    //-->
</script>
<script language="JavaScript" type="text/javascript">
    function mascaraData(campoData) {
        var data = campoData.value;
        if (data.length == 2) {
            data = data + '/';
            campoData.value = data;
            return true;
        }
        if (data.length == 5) {
            data = data + '/';
            campoData.value = data;
            return true;
        }
    }

    function ValidaData(data) {
        exp = /\d{2}\/\d{2}\/\d{4}/
        if (!exp.test(data))
            return false;
        return true;
    }

    function ValidaCep(cep){
    	exp = /\d{5}\-\d{3}/
    	if(!exp.test(cep))
    		return false;
    	return true;	
    }

    function enviardadosProposta(form) {
    	//var cep = document.getElementById('alterarCep').value;
//     	if(ValidaCep(cep) == false && cep != ''){
//     		alert('Verifique se o CEP está no formato válido XXXXX-XXX');
//     		return false;
//     	}
        
		
        var banco_form = document.getElementById('cadastrarPropostaBanco').value;
        var agencia_form = document.getElementById('cadastrarPropostaAgencia').value;
        var digito_form = document.getElementById('cadastrarPropostaDigito').value;
        /*alert(agencia.charAt(0));
	alert(digito.length);*/

        if (banco_form == '1000' || banco_form == '1001') { //BB
            if (agencia_form.length != 4) {
                alert('Verifique a quantidade de caracteres da Agência (deve ser 4). O preenchimento pode conter zeros às esquerda.');
                return false;
            }
            
            var result = (parseInt(agencia_form.charAt(0)) * 5) + (parseInt(agencia_form.charAt(1)) * 4) +
                (parseInt(agencia_form.charAt(2)) * 3) + (parseInt(agencia_form.charAt(3)) * 2);
                
            var resto = result % 11;
            var digito = 11 - resto;
            if (digito == 10) digito = 'X';
            else if (digito == 11) digito = 0;
            
            if (digito != digito_form.toUpperCase()) {
                alert('Agência/Digito Verificador Inválido');
                return false;
            }
            
        }
        
        /*else if (banco_form == '1001'){//BANCO DO NORDESTE DO BRASIL SA
		
	} */
        else if (banco_form == '1002' || banco_form == '1003') { //CAIXA ECONOMICA FEDERAL
            if (agencia_form.length != 4) {
                alert('Verifique a quantidade de caracteres da Agência (deve ser 4). O preenchimento pode conter zeros às esquerda.');
                return false;
            }
            var result = (parseInt(agencia_form.charAt(0)) * 5) + (parseInt(agencia_form.charAt(1)) * 4) +
                (parseInt(agencia_form.charAt(2)) * 3) + (parseInt(agencia_form.charAt(3)) * 2);
            var resto = result % 11;
            var digito = 11 - resto;
            if (digito == 10) digito = 0;
            else if (digito == 11) digito = 0;

            if (digito != digito_form.toUpperCase()) {
                alert('Agência/Digito Verificador Inválido');
                return false;
            }
        }
        /*else if (banco_form == '1003'){//BANCO DA AMAZONIA SA
		
	}*/

        var campos = form.getElementsByTagName('input');
        var podeEnviar = true;
        
        for (i = 0; i < campos.length; i++) {
            var classe = campos[i].className;
            var valor = campos[i].value;
            if(valor != "Avançar" && valor != "Salvar" && valor != "Voltar"){
	            if (classe.indexOf('obrigatorio') != -1 && valor == ''){
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
            if (classe.indexOf('obrigatorio') != -1 && valor == ''){
                podeEnviar = false;
                campos[i].style.color = "#fff";
                campos[i].style.backgroundColor = "#FF7777";
            }else{
            	campos[i].style.color = "#a7a7a7";
            	campos[i].style.backgroundColor = "#fff";
            }
        }

        if (podeEnviar != true) {

            alert('existem campos obrigatórios em branco!');
            return false;
        }

        <?php if(isset($proposta->repasse_especifico) !== true): ?> 
            if (parseFloat(replaceAll(document.getElementById('salvarValorRepasse').value, '.','').replace(",", ".")) < parseFloat(100000.00)) {
                alert('Valor de repasse não pode ser inferior a R$ 100.000,00 (cem mil reais)');
                return false;
            }
        <?php endif; ?>
        //replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", ".")
        
<!--        var valor_percent = parseFloat(replaceAll(document.getElementById('percentual').value, '.','').replace(",", "."))*0.01*
        parseFloat(replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", "."));
		//alert(valor_percent);
		//alert(parseFloat(replaceAll(document.getElementById('salvarValorContrapartida').value, '.','').replace(",", ".")));
		if (valor_percent.toFixed(2) > parseFloat(replaceAll(document.getElementById('salvarValorContrapartida').value, '.','').replace(",", ".")).toFixed(2)) {
            alert('Valor abaixo do percentual de contrapartida');
            return false;
        }-->

        var valida_global = parseFloat(replaceAll(document.getElementById('salvarValorContrapartida').value, '.','').replace(",", ".")) + parseFloat(replaceAll(document.getElementById('salvarValorRepasse').value, '.','').replace(",", "."));
        if ( parseFloat(replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", ".")).toFixed(2) != valida_global.toFixed(2)) {
            alert('Valor global deve ser igual ao repasse + contrapartida');
            return false;
        }

        var dataProj = document.getElementById('data').value;
        var dtini = document.getElementById('inicioVigencia').value;
        var dtfim = document.getElementById('terminoVigencia').value;
        if (ValidaData(dtini) == false || ValidaData(dtfim) == false) {
            alert('Verifique se as datas estão no formato válido dd/mm/aaaa');
            return false;
        }
		dtProj = new Date(dataProj.substring(6, 10),
				dataProj.substring(3, 5),
				dataProj.substring(0, 2));
		dtProj.setMonth(dtProj.getMonth() - 1);
        
        datInicio = new Date(dtini.substring(6, 10),
            dtini.substring(3, 5),
            dtini.substring(0, 2));
        datInicio.setMonth(datInicio.getMonth() - 1);

        datFim = new Date(dtfim.substring(6, 10),
            dtfim.substring(3, 5),
            dtfim.substring(0, 2));

        datFim.setMonth(datFim.getMonth() - 1);

		if(dtProj > datInicio){
			alert('Data inicial deve ser maior ou igual do que a data de cadastro do projeto.');
            return false;
		}
        
        if (datInicio > datFim) {
            alert('Data final deve ser maior do que a data inicial.');
            return false;
        }

        var soma = parseFloat(document.getElementById('percentual').value.replace(".", "").replace(",", ".")) * parseFloat(document.getElementById('salvarValorGlobal').value.replace(".", "").replace(",", "."));
        if (soma < parseFloat(document.getElementById('salvarValorContrapartida').value.replace(",", ".")))
            alert('Contrapartida maior do que o previsto. Cadastro efetuado mesmo assim.');

        return true;
    }
    
function replaceAll(str, s1, s2){
    var exists = false;
	if (str.indexOf(s1) !== -1)
		exists = true; // does 'abc' exist in my string?

	while (exists) // replace 'abc' as long as it exists
	{
		str = str.replace(s1, s2);

		if (str.indexOf(s1) !== -1)
			exists = true;
		else
			exists = false;
	}
	return str;
}
</script>

<?php 
$codigo_programa = unserialize($options_programa);
?>
		
		
<div class="innerAll spacing-x2">

	<form name="proposta-form" method="post" enctype="multipart/form-data" id="proposta-form" onSubmit="return enviardadosProposta(this);">
		<input type="hidden" name="cnpjProponente" value="<?php if (isset($cnpjProponente) !== false) echo $cnpjProponente;?>">
		<input type="hidden" name="orgao" value="<?php if (isset($orgao) !== false) echo $orgao;?>"> 
		<input type="hidden" name="obj_programa" value='<?php echo $options_programa; ?>'>
<!-- 
<input type="hidden" name="id_programa" value="<?php //if (isset($id_programa) !== false) echo $id_programa;?>">
<div class="col-md-12 bg-white">
		<div class="form-group">			
			<h3 style="color: #428bca;">Objetos</h3>
			<hr>
			<table class="table">
				<tbody><?php //if (isset($objetos_tabela) !== false) echo $objetos_tabela;?></tbody>
			</table>
			
			
			<h3 style="color: #428bca;">Regra de Contrapartida</h3>
			<hr>
			<table class="table">
				<tbody><?php //if (isset($qualificacao_tabela) !== false) echo $qualificacao_tabela;?></tbody>		
			</table>
		</div>
</div>
 -->
<div class="col-md-8 bg-white">
		<div class="form-group">		
			<h3 style="color: #428bca;">Dados da Proposta</h3>
			<hr>
			<label>Área da proposta</label> 
			<select name="area" 
				class='obrigatorio form-control' id="cadastrarPropostaArea">
				<option value="">Selecione</option>
                   <?php																												
					foreach ( $areas as $area ) {
						echo "<option value=\"{$area->id}\"";
						if (isset ( $proposta->area ) !== false && $area->id == $proposta->area)
							echo "selected=\"true\"";
						echo ">" . $area->nome . "</option>";
					}
					?>
			</select>
		</div>

		<div class="form-group">
			<label>Nome da proposta *</label> 
			<input type="text"
				class="form-control obrigatorio" type="text" maxlength="190"
				value="<?php if (isset($proposta->nome) !== false) echo $proposta->nome;?>"
				name="proposta" id="proposta">
		</div>
		
		<div class="" style="visibility: hidden;">
				<label>Percentual da contrapartida * </label> <input type="text" readonly="readonly"
					class="obrigatorio" type="text" maxlength="6"
					value="<?php if (isset($proposta) !== false) echo number_format($proposta->percentual, 2, ",", ".");?>"
					name="percentual" id="percentual" class="campoNumerico">
		</div>
		
		<div class="form-group">
			<label>Valor Global do(s) Objeto(s) (R$) * </label> <input
				type="text" class="form-control obrigatorio" type="text" readonly="readonly"
				value="<?php if (isset($proposta) !== false) echo number_format($proposta->valor_global,2,",",".");?>"
				name="valorGlobal" onmouseup="verifica()" onkeyup="verifica()" maxlength="14"
				onmouseover="hints.show(&#39;manter.programa.proposta.valores.do.programa.salvar.param.valor.global.title&#39;)"
				onmouseout="hints.hide()" id="salvarValorGlobal"
				onkeypress="reais(this,event)" onkeydown="backspace(this,event)"
				class="campoNumerico">
		</div>
		
		<div class="form-group">
			<label>Total de Contrapartida (R$) </label> <input type="text"
				class="form-control"
				value="<?php if (isset($proposta) !== false) echo number_format($proposta->total_contrapartida,2,",",".");?>"
				tabindex="1" name="valorContrapartida" onmouseup="verifica()"
				onkeyup="verifica()" maxlength="14"
				onmouseover="hints.show(&#39;manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.title&#39;)"
				onmouseout="hints.hide()" id="salvarValorContrapartida"
				onkeypress="reais(this,event)" onkeydown="backspace(this,event)"
				class="campoNumerico campoSomenteLeitura" readonly="readonly">
		</div>
		
		<div class="form-group">
			<label>Contrapartida Financeira (R$) * </label>
			<input type="text" class="form-control campoNumerico" type="text" readonly="readonly"
				value="<?php if (isset($proposta) !== false) echo number_format($proposta->contrapartida_financeira,2,",",".");?>"
				name="valorContrapartidaFinanceira" onmouseup="verifica()" maxlength="14"
				onkeyup="verifica()" onmouseout="hints.hide()"
				id="salvarValorContrapartidaFinanceira"
				onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
		</div>
		
		<div class="form-group">
			<label>Contrapartida em Bens e Serviços (R$)</label>
			<input type="text" class="form-control campoNumerico"
				value="<?php if (isset($proposta) !== false) echo number_format($proposta->contrapartida_bens,2,",",".");?>"
				name="valorContrapartidaBensServicos" onmouseup="verifica()" maxlength="14" readonly="readonly"
				onkeyup="verifica()"
				onmouseover="hints.show(&#39;manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.bens.servicos.title&#39;)"
				onmouseout="hints.hide()" id="salvarValorContrapartidaBensServicos"
				onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
		</div>
		
		<div class="form-group">
			<label>Valor de Repasse (R$) * </label> <input type="text"
				value="<?php if (isset($proposta) !== false) echo number_format($proposta->repasse,2,",",".");?>"
				tabindex="1" name="valorRepasse" onmouseup="verifica()" maxlength="14"
				onkeyup="verifica()"
				onmouseover="hints.show(&#39;manter.programa.proposta.valores.do.programa.salvar.param.valor.repasse.title&#39;)"
				onmouseout="hints.hide()" id="salvarValorRepasse"
				onkeypress="reais(this,event)" onkeydown="backspace(this,event)"
				class="form-control campoNumerico campoSomenteLeitura obrigatorio" readonly="readonly">
		</div>
		
                <?php if(isset($proposta->repasse_especifico) && $proposta->repasse_especifico > 0): ?>
                    <div class="form-group">
                    <label>Valor Repasse Específico (R$) - <span id="valorDisponivel"></span></label>
                    <input type="text" class="form-control campoNumerico" readonly="readonly"
                            name="valorRepasseespecifico" onmouseup="verifica()"
                            onkeyup="verifica()" maxlength="14"
                            onmouseout="hints.hide()" id="salvarValorRepasseespecifico"
                            value="<?php if (isset($proposta) !== false) echo number_format($proposta->repasse_especifico,2,",",".");?>"
                            onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
                    </div>
                <?php elseif(($this->input->get('id', TRUE) != FALSE && $this->emenda_programa_proposta_model->get_all_emendas_from_proposta($this->input->get('id', TRUE)) != null) || isset($codigo_programa['valores_emendas'])):?>
                	<?php 
                	if(isset($codigo_programa['valores_emendas']))
                		$emendas = $codigo_programa['valores_emendas'];
                	else
                		$emendas = $this->emenda_programa_proposta_model->get_all_emendas_from_proposta($this->input->get('id', TRUE));
                	
                	foreach ($emendas as $emen):
                		$emen = (object)$emen;
                	?>
                	<div class="form-group">
                    <label>Valor Repasse Emenda - <?php echo $emen->numero_emenda; ?> (R$) </label>
                    <input type="text" class="form-control campoNumerico" readonly="readonly"
                           value="<?php if (isset($proposta) !== false && isset($emen) !== false) echo number_format(doubleval($emen->valor_utilizado),2,",",".");?>"
                            name="valorRepassevoluntario" onmouseup="verifica()"
                            onkeyup="verifica()" maxlength="14"
                            onmouseover="hints.show(&#39;Valor Repasse Voluntário (R$)&#39;)"
                            onmouseout="hints.hide()" id="salvarValorRepassevoluntario"
                            onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
                    </div>
                    <?php endforeach;?>
                <?php else: ?>
                    <div class="form-group">
                    <label>Valor Repasse Voluntário (R$) </label>
                    <input type="text" class="form-control campoNumerico" readonly="readonly"
                            value="<?php if (isset($proposta) !== false) echo number_format($proposta->repasse_voluntario,2,",",".");?>"
                            name="valorRepassevoluntario" onmouseup="verifica()"
                            onkeyup="verifica()" maxlength="14"
                            onmouseover="hints.show(&#39;Valor Repasse Voluntário (R$)&#39;)"
                            onmouseout="hints.hide()" id="salvarValorRepassevoluntario"
                            onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
                    </div>
                <?php endif; ?>
		
		<h3 style="color: #428bca;">Dados Bancários</h3>
		<hr>
		
		<label>Banco *</label>
		<select name="banco" class="form-control obrigatorio col-xs-3" id="cadastrarPropostaBanco">
				<option value="">Selecione</option>
                <?php foreach ($bancos as $banco){ echo "<option value=\"{$banco->idbanco}\""; if (isset($proposta->banco) !==false && $banco->idbanco == $proposta->banco) echo "selected=\"true\""; echo ">".$banco->nome."</option>"; } ?>
         </select>
         
		<div class="form-group">
			<div class="row">
				<div class="col-xs-6">
				<label>Agência *</label>
					<input
						type="text" class="form-control obrigatorio" type="text"
						value="<?php if (isset($proposta->agencia) !== false) echo strtok($proposta->agencia, '-');?>"
						name="agencia" value="" onmouseup="verifica()" onkeyup="verifica()"
						onkeypress="verificar(this.value,event)" size="4" maxlength="4"
						onmouseover="hints.show(&#39;incluir.dados.proposta.incluir.proposta.cadastrar.proposta.param.agencia.title&#39;)"
						onmouseout="hints.hide()" id="cadastrarPropostaAgencia"/>
				</div>
	
				<div class="col-xs-6">
					<label class="col-xs-2" >Dígito *</label>
					<input 
						 type="text" class="form-control obrigatorio"
						value="<?php if (isset($proposta) !== false) echo strtok('-');?>"
						name="digito" value="" onmouseup="verifica()" onkeyup="verifica()"
						onkeypress="keySubmit(event)" size="1" maxlength="1"
						onmouseover="hints.show(&#39;incluir.dados.proposta.incluir.proposta.cadastrar.proposta.param.digito.title&#39;)"
						onmouseout="hints.hide()" id="cadastrarPropostaDigito"/>
				</div>
			</div>
		</div>
		<h3 style="color: #428bca;">Datas</h3>
		<hr>
		<div class="form-group">
			<label>Data</label> 
			<input type="text" class="form-control" name="data" id="data"
				value="<?php if (isset($proposta->data) !== false) echo implode("/",array_reverse(explode("-",$proposta->data))); else echo date("d/m/Y ");?>"
				readonly="readonly" id="inserirPropostaData"/>
		</div>
		<div class="form-group">
			<label>Data Início Vigência * </label> 
			<input 
				class="form-control obrigatorio" type="text"
				value="<?php if (isset($proposta->data_inicio) !== false) echo implode("/",array_reverse(explode("-",$proposta->data_inicio)));?>"
				name="inicioVigencia" maxlength="10" OnKeyUp="mascaraData(this);"
				id="inicioVigencia"/>
		</div>
		<div class="form-group">
			<label>Data Término Vigência * </label> 
			<input 
				class="form-control obrigatorio" type="text"
				value="<?php if (isset($proposta->data_termino) !== false) echo implode("/",array_reverse(explode("-",$proposta->data_termino)));?>"
				name="terminoVigencia" maxlength="10" OnKeyUp="mascaraData(this);"
				id="terminoVigencia"/>
		</div>
		<h3 style="color: #428bca;">Valores</h3>
		<hr>
		<div class="form-group">
			<input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($proposta->valor_global, 2, ",", ".");?>"
				id="totalValorGlobal">Valor Global (R$) *
		</div>
		<div>
			<div style="padding-left: 100px">

				<input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($proposta->repasse, 2, ",", ".");?>"
					id="totalValorRepasse">Valor de Repasse (R$) *
			</div>
			<div style="padding-left: 100px">

				<input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($proposta->contrapartida_financeira+$proposta->contrapartida_bens, 2, ",", ".");?>"
					id="totalValorContrapartida">Valor da Contrapartida (R$) *
			</div>
			<div style="padding-left: 200px">

				<input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($proposta->contrapartida_financeira, 2, ",", ".");?>"
					id="totalValorContrapartidaFinanceira">Valor da Contrapartida
				Financeira (R$) *
			</div>
			<div style="padding-left: 200px">

				<input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($proposta->contrapartida_bens, 2, ",", ".");?>"
					id="totalValorContrapartidaBens">Valor da Contrapartida de Bens e
				Serviços (R$) *
			</div>
		</div>

		<br/>

		
		<input type="submit" id="btn_voltar" class="btn btn-primary place-right" value="Voltar" formaction="<?php echo base_url('index.php/in/gestor/informa_valores_programa'.(isset($_GET['id']) ? "?edit=1&id=".$_GET['id'] : ""));?>" name="voltar">
		<?php if (isset($proposta) !==false){ ?>
        <input type="submit" class="btn btn-primary place-right btn_salvar" value="Salvar" id="form_submit" name="editar">
        <?php }else{ ?>
        <input type="submit" class="btn btn-primary place-right btn_salvar" value="Salvar" id="form_submit" name="salvar">
        <?php }?>
		<input type="submit" class="btn btn-primary place-right btn_salvar" value="Avançar" id="form_submit" name="avancar">
			
		<br><br>
</div>

	</form>
    <br><br>
</div>
<!-- 
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo  base_url();?>configuracoes/js/preenchimento_valores_proposta.js"></script>
	 -->
<script type="text/javascript">

$("#btn_voltar").click(function(){
	$("#proposta-form").attr('onsubmit', '');
});

$(".btn_salvar").click(function(){
	$("#proposta-form").attr('onsubmit', 'return enviardadosProposta(this);');
});
					
var listaMarcados = document.getElementsByTagName("INPUT");  
  for (loop = 0; loop < listaMarcados.length; loop++) {  
     var item = listaMarcados[loop];
     if (item.type == "checkbox") {
       item.name = "objetos[]"; 
     }  
  }

    //registraRepasse('voluntario', 'Valor Repasse Voluntário (R$)', 'null', 'n');
    
</script>
<script type="text/javascript">
    //Se só possuir um objeto, o mesmo já deve vir selecionado
    if (document.getElementById('salvarObjetos') != null) {
        if (document.getElementById('salvarObjetos').elements != null) {
            if (document.getElementById('salvarObjetos').elements.length == 1) {
                if (document.getElementById('salvarObjetos').elements[i].type == "checkbox") {
                    document.getElementById('salvarObjetos').elements[i].checked = 1;
                }
            }
        } else {
            document.getElementById('salvarObjetos').checked = 1;
        }
    }
    /* Retorna o ID da Qualificação do Proponente */
    function obterIdQualificacaoProponente() {
        var value;
        if (document.getElementById('salvarQualificacaoProponente') != null) {
            if (document.getElementById('salvarQualificacaoProponente').elements != null) {
                for (i = 0; i < document.getElementById('salvarQualificacaoProponente').elements.length; i++) {
                    if (document.getElementById('salvarQualificacaoProponente').elements[i].checked || document.getElementById('salvarQualificacaoProponente').elements[i].checked == 1) {
                        value = this.value;
                        break;
                    }
                }
            } else {
                if (document.getElementById('salvarQualificacaoProponente').checked || document.getElementById('salvarQualificacaoProponente').checked == 1) {
                    value = this.value;
                }
            }
        }

        return value;
    }

    $(function () {
        $('#cod_estados').change(function () {

            if ($(this).val()) {
                $('#cod_cidades').hide();
                $('.carregando').show();
                $.getJSON('cidades_ajax?search=', {
                    cod_estados: $(this).val(),
                    ajax: 'true'
                }, function (j) {
                    var options = '<option value=""></option>';
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
                    }
                    $('#cod_cidades').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#cod_cidades').html('<option value="">– Escolha um estado –</option>');
            }
        });
    });
/*
$( "#salvarValorGlobal" )
  .focusout(function() {
    if (document.getElementById("salvarValorGlobal").value != '' && document.getElementById("percentual").value != ''){
		var valor_percent = parseFloat(replaceAll(document.getElementById('percentual').value, '.','').replace(",", "."))*parseFloat(0.01)*
		parseFloat(replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", "."));
		var valor_string1 = valor_percent+" ";
		alert(parseFloat(replaceAll(valor_string1.trim(), '.',',')));
		//document.getElementById("salvarValorContrapartida").value = parseFloat(replaceAll(valor_string1, '.',',').replace(",", "."));
		document.getElementById("salvarValorContrapartidaFinanceira").value = parseFloat(replaceAll(valor_string1.trim(), '.',','));
		//document.getElementById("salvarValorRepasse").value = valor_percent;
		//document.getElementById("salvarValorRepassevoluntario").value = valor_percent;
	}
  });
  */
</script>