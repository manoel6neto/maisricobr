var camposRepasse = new Array();
var mensagemRepasse = new Array();
var maximoRepasse = new Array();
var outrosRepasse = new Array();
var erro = true;

function enviardados(){
	if (parseFloat(document.getElementById('salvarValorGlobal').value.replace(",",".")) < 100000){
		alert('Valor não pode ser inferior a R$ 100.000,00 (cem mil reais)');
	}
	
	if (erro == false){
		alert("Corrija os dados antes de prosseguir!");
		return false;
	}
	
	var dtini = document.getElementById('inicioVigencia').value;
    var dtfim = document.getElementById('terminoVigencia').value;
   
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
	
	var soma = parseFloat(document.getElementById('percentual').value.replace(",","."))*parseFloat(document.getElementById('salvarValorGlobal').value.replace(",","."));
	if (soma < parseFloat(document.getElementById('salvarValorContrapartida').value.replace(",",".")))
		alert('Contrapartida maior do que o previsto. Cadastro efetuado mesmo assim.');
	
	return true;
}
/* Retorna um inteiro com o valor em centavos da string que for passada como parâmetro. */
function valorEmCentavos(texto){
	var x = texto.split('.');
	if (x.length == 1)
		texto+=".00";
	else if (x[1].length == 1)
		texto+="0";
	
	var resultado = texto;
	
	resultado = resultado.replace(/\./g,""); //Remove todos os pontos (.) do texto.
	resultado = resultado.replace(",",".");
	resultadoInt = Math.round(resultado * 100);
	return resultadoInt;
}

/* Formata o valor  indicado em centavos para R$ e, depois, exibe o valor formatado no campo indicado (com padrão JQuery). */
function exibirValorCentavosFormatado(idJQueryCampo, valorEmCentavos){
	$(idJQueryCampo).val(formatarValoresEmCentavos(valorEmCentavos));	
}

/* Formata o valor  indicado em centavos para R$. */
function formatarValoresEmCentavos(valorEmCentavos)
{
	var nStr = valorEmCentavos / 100;
	nStr += '';
	var x = nStr.split('.');
	var x1 = x[0];
	
	var x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
			
	switch(x2.length) {
		case 0: x2 = x2 + ",00"; break;
		case 1: x2 = x2 + "00"; break;
		case 2: x2 = x2 + "0"; break;
	}
	
	valorFormatado = x1 + x2;
	return valorFormatado;
}

function registraRepasse(campo, messagem, maximo, outros) {
	camposRepasse[camposRepasse.length] = campo;
	mensagemRepasse[mensagemRepasse.length] = messagem;
	if (maximo != '' && maximo != 'null') { 
		maximoRepasse[maximoRepasse.length] = maximo*100;
	} else {
		maximoRepasse[maximoRepasse.length] = null;	
	}
	outrosRepasse[outrosRepasse.length] = outros;
	var valValorRepasse =  valorEmCentavos($("#salvarValorRepasse"+campo).val());
	
	if (valValorRepasse >= 0) {
		exibirValorCentavosFormatado("#salvarValorRepasse"+campo, valValorRepasse);
	} else {
		$("#salvarValorRepasse"+campo).val("0,00");
	}
}

$(document).ready(function(){

	//Transforma os campos de totais para somente como leitura.
	$("#salvarValorContrapartida").attr("readonly", true);
	$("#salvarValorContrapartida").addClass("campoSomenteLeitura");
	$("#salvarValorRepasse").attr("readonly", true);
	$("#salvarValorRepasse").addClass("campoSomenteLeitura");

	//Após o o carregamento do documento HTML, calcula todos os totais e exibe as mensagens correspondentes a cada campo.
	exibirTotais();

	//Atribui eventos dos campos aos métodos de javaScript.
	//atribuirEventosExibirTotais("#salvarValorGlobal");
	
	atribuirEventosExibirTotais("#salvarValorContrapartidaFinanceira");
	atribuirEventosExibirTotais("#salvarValorContrapartidaBensServicos");

	for (i = 0; i< camposRepasse.length; i++) {
		atribuirEventosExibirTotais("#salvarValorRepasse"+camposRepasse[i]);
	}

	$("input[@name='qualificacaoProponente']:radio").change(function() {
		exibirTotais();
	});
	
	$("input[@name='manterProgramaPropostaValoresDoProgramaSalvarForm']:button").focus(function() {
		exibirTotais();
	});

	/* Associa o método exibirTotais() ao eventos keyup, change e click do campo passado como parâmetro */
	function atribuirEventosExibirTotais(campoJQuery){
		
		$(campoJQuery).keyup(function(){
			
			exibirTotais();
		});
		
		$(campoJQuery).change(function(){
			exibirTotais();
		});
		
		$(campoJQuery).click(function(){
			exibirTotais();
		});
	}

	/* Exibe os valores totais e as mensagens correspondentes a cada campo. */
	function exibirTotais(){
		$("#totalValorGlobal").val($("#salvarValorGlobal").val());
		exibirValorCentavosFormatado("#salvarValorContrapartida", calcularTotalContrapartida());
		exibirValorCentavosFormatado("#totalValorContrapartida", calcularTotalContrapartida());
		
		
		$("#totalValorContrapartidaFinanceira").val($("#salvarValorContrapartidaFinanceira").val());
		$("#totalValorContrapartidaBens").val($("#salvarValorContrapartidaBensServicos").val());
		
		var valValorRepasse = calcularValorRepasse();
		
		if (valValorRepasse >= 0) {
			exibirValorCentavosFormatado("#salvarValorRepasse", valValorRepasse);
			exibirValorCentavosFormatado("#totalValorRepasse", valValorRepasse);
		} else {
			$("#salvarValorRepasse").val("0,00");
			$("#totalValorRepasse").val("0,00");
		}
		
		preencherMensagemValorGlobal();

		var valorGlobalFoiValidado = true;
		
		//preencherMensagemContrapartidaFinanceira(valorGlobalFoiValidado && permitirContrapartidaBensServicos());
		
		//preencherMensagemContrapartidaBensServicos(valorGlobalFoiValidado);
		//preencherMensagemContrapartida(valorGlobalFoiValidado);
		
		//preencherMensagemRepasses();
		validarCamposMenoresQueValorGlobal(valorGlobalFoiValidado);
	}

	/* Retorna o Valor Global em centavos. */
	function obterValorGlobal(){
		return valorEmCentavos($("#salvarValorGlobal").val());
	}

	/* Exibe a mensagem correspondente ao Valor Global */
	function preencherMensagemValorGlobal(){
		exibirMensagemCampo("#mensagemValorGlobal", false, "");

		var valValorGlobal = obterValorGlobal();
		var valContrapartidaFinanceira = obterContrapartidaFinanceira();
		var valContrapartidaBensServicos = obterContrapartidaBensServicos();
		var valTotalContrapartida = valContrapartidaFinanceira + valContrapartidaBensServicos;

		var valValorRepasse = calcularValorRepasse();

		if ((valTotalContrapartida + valValorRepasse) > valValorGlobal){
			exibirMensagemCampo("#mensagemValorGlobal", true, "Diferen&ccedil;a de R$ "+formatarValoresEmCentavos((valTotalContrapartida + valValorRepasse) - valValorGlobal)+" entre o Valor global e o Total de Contrapartida somado ao Valor de Repasse.");
			erro = false;
		}
		else if ((valTotalContrapartida + valValorRepasse) < valValorGlobal){
			exibirMensagemCampo("#mensagemValorGlobal", true, "Diferen&ccedil;a de R$ "+formatarValoresEmCentavos(valValorGlobal - (valTotalContrapartida + valValorRepasse))+" entre o Valor global e o Total de Contrapartida somado ao Valor de Repasse.");
			erro = false;
		} else erro = true;
	}

	/* Verifica se os campos de Contrapartida, Contrapartida em Bens e Serviços e Contrapartida Financeira são menores que o Valor Global.  */
	function validarCamposMenoresQueValorGlobal(exibirMensagem){

		if ( ! exibirMensagem ){
			return false;
		}
		
		var valValorGlobal = obterValorGlobal();
		var valContrapartidaFinanceira = obterContrapartidaFinanceira();
		var valContrapartidaBensServicos = obterContrapartidaBensServicos();
		var valTotalContrapartida = valContrapartidaFinanceira + valContrapartidaBensServicos;

		var validarTotal = true;

		if (valContrapartidaFinanceira > valValorGlobal){
			exibirMensagemCampo("#mensagemContrapartidaFinanceira", true, "Contrapartida Financeira n&atilde;o deve ser maior que o Valor Global.");
			validarTotal = false;
			erro = false;
		} else exibirMensagemCampo("#mensagemContrapartidaFinanceira", true, "");
		
		if (valContrapartidaBensServicos > valValorGlobal){
			exibirMensagemCampo("#mensagemContrapartidaBensServicos", true, "Contrapartida em Bens e Servi&ccedil;os n&atilde;o deve ser maior que o Valor Global.");
			validarTotal = false;
			erro = false;
		} else exibirMensagemCampo("#mensagemContrapartidaBensServicos", true, "");
		
		if ( (true == validarTotal ) && (valTotalContrapartida > valValorGlobal) ){
			exibirMensagemCampo("#mensagemContrapartida", true, "Contrapartida n&atilde;o deve ser maior que o Valor Global.");
			validarTotal = false;
			erro = false;
		} else exibirMensagemCampo("#mensagemContrapartida", true, "");

		for (i = 0; i< camposRepasse.length; i++) {
			var valValorRepasse = obterValorRepasse(camposRepasse[i]);
			
			if (valValorRepasse > valValorGlobal){ 
				exibirMensagemCampo("#mensagemRepasse"+camposRepasse[i], true, mensagemRepasse[i]+" n&atilde;o deve ser maior que o Valor Global.");
				validarTotal = false;
				erro = false;
			}
			else exibirMensagemCampo("#mensagemRepasse"+camposRepasse[i], true, "");
		}

		var valValorRepasse = calcularValorRepasse();
		if (valValorRepasse > valValorGlobal) {
			exibirMensagemCampo("#mensagemRepasse", true, "Valor de Repasse n&atilde;o deve ser maior que o Valor Global.");
			validarTotal = false;
			erro = false;
		}
		 else exibirMensagemCampo("#mensagemRepasse", true, "");
		 
		 return validarTotal;
	}

	function obterValorRepasse(campo){
		campoStr = '#salvarValorRepasse'+campo;
		return valorEmCentavos($(campoStr).val());
	}

	/* Retorna o valor da Contrapartida Financeira em centavos. */
	function obterContrapartidaFinanceira(){
		return valorEmCentavos($("#salvarValorContrapartidaFinanceira").val());
	}
/*
	function preencherMensagemRepasses(){
		exibirMensagemCampo("#mensagemRepasse", false, "");
		for (i = 0; i< camposRepasse.length; i++) {
			exibirMensagemCampo("#mensagemRepasse"+camposRepasse[i], false, "");

			if (maximoRepasse[i] != null) {
				var valValorRepasse = obterValorRepasse(camposRepasse[i]);
			
				var mensagem = '';
				if (outrosRepasse[i] == 's') {
					if (camposRepasse[i] == 'especifico')
						mensagem = "Valor m&aacute;ximo: R$ "+formatarValoresEmCentavos(maximoRepasse[i])+", o valor restante deste Repasse Espec&iacute;fico j&aacute; foi utilizado em outra(s) proposta(s)";
					else
						mensagem = "Valor m&aacute;ximo: R$ "+formatarValoresEmCentavos(maximoRepasse[i])+", o valor restante desta Emenda j&aacute; foi utilizado em outra(s) proposta(s)";
				} else
					mensagem = "Valor m&aacute;ximo: R$ "+formatarValoresEmCentavos(maximoRepasse[i])+".";
				
				var possuiErro = (valValorRepasse > maximoRepasse[i]);
		
				exibirMensagemCampo("#mensagemRepasse"+camposRepasse[i], possuiErro, mensagem);
			}
		}
	}

	/* Exibe a mensagem correspondente à  Contrapartida Financeira */
	/*function preencherMensagemContrapartidaFinanceira(exibirMensagem){

		var percentualMinimo = calcularPercentualMinimoContrapartidaFinanceira();
				
		if ( (!exibirMensagem)  ||  (percentualMinimo == 100) || (percentualMinimo == 0) ){
			exibirMensagemCampo("#mensagemContrapartidaFinanceira", false, "");
			return;
		}
	
		var valContrapartidaFinanceiraMinima = calcularContrapartidaFinanceiraMinima();
		var valContrapartidaFinanceira = obterContrapartidaFinanceira();
		
		var mensagem = "Valor m&iacute;nimo: R$ " + formatarValoresEmCentavos(valContrapartidaFinanceiraMinima) + " ("
				+ percentualMinimo + "% do total de contrapartida).";

		var possuiErro = (valContrapartidaFinanceira < valContrapartidaFinanceiraMinima);
		
		exibirMensagemCampo("#mensagemContrapartidaFinanceira", possuiErro, mensagem);
	}
	*/

	/* Calcula o menor valor que pode ser aceito para o campo de Contrapartida Financeira. */	
	/*function calcularContrapartidaFinanceiraMinima(){
		var percentualMinimo = calcularPercentualMinimoContrapartidaFinanceira();
		if (percentualMinimo > 0){
			var valContrapartidaMinima = calcularContrapartidaMinima();
			var valPercentualMinimoContrapartidaFinanceira = calcularPercentualMinimoContrapartidaFinanceira();

			return Math.round((valContrapartidaMinima * valPercentualMinimoContrapartidaFinanceira) / 100);
		} else {
			return 0;
		}
	}
	*/
	/* Calcula qual é o percentual mí­nimo de Contrapartida Financeira a partir do percentual máximo de Contrapartida em Bens e Serviços. */
	/*function calcularPercentualMinimoContrapartidaFinanceira(){
		var valPercentualMaximoContrapartidaBensServicos = obterPercentualMaximoContrapartidaBensServicos();	
		return (100 - valPercentualMaximoContrapartidaBensServicos);
	}
	*/
	
	/** CONTRAPARTIDA EM BENS E SERVICOS **/
	
	/* Retorna o valor da Contrapartida em Bens e Serviços em centavos. */
	function obterContrapartidaBensServicos(){
		return valorEmCentavos($("#salvarValorContrapartidaBensServicos").val());
	}
	
	/* Exibe a mensagem correspondente à Contrapartida de Bens e Serviços.  */
	/*function preencherMensagemContrapartidaBensServicos(exibirMensagem){
		var percentualMaximoContrapartidaBensServicos = obterPercentualMaximoContrapartidaBensServicos();	
	
		if ( (! exibirMensagem) || (percentualMaximoContrapartidaBensServicos == 100) ){
			exibirMensagemCampo("#mensagemContrapartidaBensServicos", false, "");
			return;
		}
	
		var valContrapartidaBensServicos = obterContrapartidaBensServicos();
		var valContrapartidaBensServicosMaxima = calcularContrapartidaBensServicosMaxima();

		var mensagem = "Valor m&aacute;ximo: R$ " + formatarValoresEmCentavos(valContrapartidaBensServicosMaxima)
			+ " (" + percentualMaximoContrapartidaBensServicos + "% do total de contrapartida)." ;
		
		var possuiErro = (valContrapartidaBensServicos > valContrapartidaBensServicosMaxima);

		exibirMensagemCampo("#mensagemContrapartidaBensServicos", possuiErro, mensagem);
	}*/
	
	/* Calcula o maior valor que pode ser aceito para a Contrapartida em Bens e Serviços. */
	/*function calcularContrapartidaBensServicosMaxima(){
		var percentualMaximoContrapartidaBensServicos = obterPercentualMaximoContrapartidaBensServicos();
		var valContrapartidaFinanceira = obterContrapartidaFinanceira();
		var valContrapartidaFinanceiraMinima = calcularContrapartidaFinanceiraMinima();
		
		if (valContrapartidaFinanceira < valContrapartidaFinanceiraMinima){
			valContrapartidaFinanceira = valContrapartidaFinanceiraMinima;
		}

		return Math.round((percentualMaximoContrapartidaBensServicos * valContrapartidaFinanceira) / (100 - percentualMaximoContrapartidaBensServicos));
	}*/
	
	/* Retorna true se o programa permite alguma contrapartida em bens e serviços. */
	function permitirContrapartidaBensServicos(){
		var permitir = true;
		/*alert("teste");
		if (obterPercentualMaximoContrapartidaBensServicos() == 0){
			permitir = false;
		}
		*/
		return permitir;
	}
	/* Exibe a mensagem correspondente à Contrapartida Total. */
	/*function preencherMensagemContrapartida(exibirMensagem){
	
		var percentualMinimoContrapartida = obterPercentualMinimoContrapartida();
		
		if ( (! exibirMensagem) || (percentualMinimoContrapartida == 0)  ){
			exibirMensagemCampo(campoParaExibirMensagemContrapartida(), false, "");
			return;
		}
		
		if (! permitirContrapartidaBensServicos()){
			exibirMensagemCampo("#mensagemContrapartida", false, "");
		}
		
		var valContrapartidaMinima = calcularContrapartidaMinima();
		var valContrapartida = calcularTotalContrapartida();
		var valValorGlobal = obterValorGlobal();

		var mensagem = "Valor m&iacute;nimo: R$ "  + 	formatarValoresEmCentavos(valContrapartidaMinima)
			+ " (" + percentualMinimoContrapartida + "% do valor global)." ;

		var possuiErro = (valContrapartida < valContrapartidaMinima);
		
		exibirMensagemCampo(campoParaExibirMensagemContrapartida(), possuiErro, mensagem);
		
	}*/

	/* Calcula o menor valor que pode ser aceito para a Contrapartida Total. */
	function calcularContrapartidaMinima(){
		var percentualMinimoContrapartida = obterPercentualMinimoContrapartida();
		var valValorGlobal = obterValorGlobal();
		return Math.round(valValorGlobal * percentualMinimoContrapartida/100);
	}
	
	/* Calcula o Valor da Contrapartida Total. Ou seja, Contrapartida Financeira + Contrapartida em Bens e Serviços. */
	function calcularTotalContrapartida(){
		var valContrapartidaFinanceira = obterContrapartidaFinanceira();
		var valContrapartidaBensServicos = obterContrapartidaBensServicos();
		var valTotalContrapartida = valContrapartidaFinanceira + valContrapartidaBensServicos;
		return valTotalContrapartida;
	}
	
	/* Retorna o campo que foi escolhido p/ exibir as mensagens relacionadas à Contrapartida Total.
	 * Se não é permitidido informar valor de contrartida em bens e serviços, então, as mensagens
	 * relacionadas à Contrapartida Total serão exibidas próximas ao campo de Contrapartida Financeira. */
	function campoParaExibirMensagemContrapartida(){
		var campo = "#mensagemContrapartida";

		if (! permitirContrapartidaBensServicos()){
			campo = "#mensagemContrapartidaFinanceira";
		}
		return campo;
	}

	/* Calcula o Valor de Repasse. Ou seja, Valor Global - Valor da Contrapartida Total. */
	function calcularValorRepasse(){
		valValorRepasseTotal = 0;
		for (i = 0; i< camposRepasse.length; i++) {
			valValorRepasseTotal += obterValorRepasse(camposRepasse[i]);
		}

		return valValorRepasseTotal;
	};
	/* Exibe mensagem no campo indicado. Se o parâmetro mensagemErro for verdadeiro, altera o estilo css para exibir mensagem de erro. */	
	function exibirMensagemCampo(campo, mensagemErro, mensagem){
		$(campo).html(mensagem);
		
		if (mensagemErro){
			$(campo).addClass("mensagemErroCampo");
		} else {
			$(campo).removeClass("mensagemErroCampo");
		} 
	}
		
});        

