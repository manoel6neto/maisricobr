<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>configuracoes/js/thumbnailviewer.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url();?>configuracoes/js/form-validation.js"></script>
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

    function enviardadosProposta(form) {
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
                alert('Digito Verificador Inválido');
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
                alert('Digito Verificador Inválido');
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
            if (classe == 'obrigatorio' && valor == '') podeEnviar = false;
        }

        var campos = form.getElementsByTagName('select');
        for (i = 0; i < campos.length; i++) {
            var classe = campos[i].className;
            var valor = campos[i].value;
            if (classe == 'obrigatorio' && valor == '') podeEnviar = false;
        }

        if (podeEnviar == true) {
            return true;
        } else {
            alert('existem campos obrigatórios em branco!');
            return false;
        }


        if (parseFloat(document.getElementById('salvarValorRepasse').value.replace(".", "").replace(",", ".")) < parseFloat(100000.00)) {
            alert('Valor de repasse não pode ser inferior a R$ 100.000,00 (cem mil reais)');
            return false;
        }

        if (erro == false) {
            alert("Corrija os dados antes de prosseguir!");
            return false;
        }

        var dtini = document.getElementById('inicioVigencia').value;
        var dtfim = document.getElementById('terminoVigencia').value;
        if (ValidaData(dtini) == false || ValidaData(dtfim) == false) {
            alert('Verifique se as datas estão no formato válido dd/mm/aaaa');
            return false;
        }
        datInicio = new Date(dtini.substring(6, 10),
            dtini.substring(3, 5),
            dtini.substring(0, 2));
        datInicio.setMonth(datInicio.getMonth() - 1);

        datFim = new Date(dtfim.substring(6, 10),
            dtfim.substring(3, 5),
            dtfim.substring(0, 2));

        datFim.setMonth(datFim.getMonth() - 1);


        if (datInicio > datFim) {
            alert('Data final deve ser maior do que a data inicial.');
            return false;
        }

        var soma = parseFloat(document.getElementById('percentual').value.replace(".", "").replace(",", ".")) * parseFloat(document.getElementById('salvarValorGlobal').value.replace(".", "").replace(",", "."));
        if (soma < parseFloat(document.getElementById('salvarValorContrapartida').value.replace(",", ".")))
            alert('Contrapartida maior do que o previsto. Cadastro efetuado mesmo assim.');

        return true;
    }
</script>

<div id="content">
    <h1 class="bg-white content-heading border-bottom">INCLUIR PROJETO</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
                <form name="proposta" method="post" action="escolhe_endereco">
                    <input type="hidden" name="usuario_siconv" value="<?= $usuario_siconv;?>">
		    <input type="hidden" name="senha_siconv" value="<?= $senha_siconv;?>">
			<input type="hidden" name="id" value="<?= $id;?>">
			<input type="hidden" name="cnpjProponente" value="<?php if (isset($cnpjProponente) !== false) echo $cnpjProponente;?>">
                    <div class="form-group">
                        <label>
                            Valor Global do(s) Objeto(s) (R$) *

                        </label>

                        <input type="text" class="form-control obrigatorio" type="text" value="<?php if (isset($proposta) !== false) echo number_format($proposta->valor_global,2,",",".");?>" name="valorGlobal" onmouseup="verifica()" onkeyup="verifica()" onmouseover="hints.show(&#39;manter.programa.proposta.valores.do.programa.salvar.param.valor.global.title&#39;)"
                        onmouseout="hints.hide()" id="salvarValorGlobal" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" class="campoNumerico">
                    </div>
                    <div class="form-group">
                        Dados Bancários
                        <br>
                        <label>
                        Banco *

                        </label>
                        <span id="nomeBancoSpan" style="visibility: hidden"></span>

                        <select name="banco" class='obrigatorio' id="cadastrarPropostaBanco">
                            <option value="">Selecione</option>
                            <?php foreach ($bancos as $banco){ echo "<option value=\"{$banco->idbanco}\""; if (isset($proposta) !==false && $banco->idbanco == $proposta->banco) echo "selected=\"true\""; echo ">".$banco->nome."</option>"; } ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <label>
                            Agência e dígito
                        </label>

                        <input style="width: 10%;" type="text" class="form-control obrigatorio" type="text" value="<?php if (isset($proposta) !== false) echo strtok($proposta->agencia, '-');?>" name="agencia" value="" onmouseup="verifica()" onkeyup="verifica()" onkeypress="verificar(this.value,event)"
                        size="4" maxlength="4" onmouseover="hints.show(&#39;incluir.dados.proposta.incluir.proposta.cadastrar.proposta.param.agencia.title&#39;)" onmouseout="hints.hide()" id="cadastrarPropostaAgencia"> -
                        <input style="width: 5%;" type="text" class="form-control" class='obrigatorio' value="<?php if (isset($proposta) !== false) echo strtok('-');?>" name="digito" value="" onmouseup="verifica()" onkeyup="verifica()" onkeypress="keySubmit(event)" size="1" maxlength="1" onmouseover="hints.show(&#39;incluir.dados.proposta.incluir.proposta.cadastrar.proposta.param.digito.title&#39;)"
                        onmouseout="hints.hide()" id="cadastrarPropostaDigito">

                    </div>
                    
                    <?php if (isset($proposta) !==false){ ?>
                    <input type="submit" value="Salvar" id="form_submit" name="editar">
                    <?php }else{ ?>
                    <input type="submit" value="Salvar" id="form_submit" name="salvar">
                    <?php }?>
                    <input type="button" value="Voltar" onclick="location.href='<?php echo  base_url();?>index.php/in/gestor';">


                </form>
            </div>
        </div>
    </div>
</div>
<br class="clr">

<script type="text/javascript" language="Javascript1.1" src="<?= base_url();?>configuracoes/js/preenchimento_valores_proposta.js"></script>
	<script type="text/javascript"> registraRepasse('voluntario', 'Valor Repasse Voluntário (R$)', 'null', 'n');</script>
<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>

