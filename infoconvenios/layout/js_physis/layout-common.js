function getElementValueById(id) {
	var element = getDiv(id);
	if (element.value)
		return element.value

	return false;
}

function getDiv(divID) {
	if (document.layers) // Netscape layers
		return document.layers[divID];

	if (document.getElementById) // DOM; IE5, NS6, Mozilla, Opera
		return document.getElementById(divID);

	if (document.all) // Proprietary DOM; IE4
		return document.all[divID];

	if (document[divID]) // Netscape alternative
		return document[divID];

	return false;
}

function openWindow(href, name, centered, attributes, width, height) {
	var w = (width) ? width : 400;
	var h = (height) ? height : 300;

	var windowWidth = (document.all) ? document.body.clientWidth
			: window.outerWidth;
	var windowHeight = (document.all) ? document.body.clientHeight
			: window.outerHeight;

	x = (windowWidth - w) / 2;
	y = (windowHeight - h) / 2;

	var properties = (attributes) ? "toolbar=yes,directories=yes,status=yes,menubar=yes,copyhistory=yes,location=yes"
			: "toolbar=no,directories=no,status=no,menubar=no,copyhistory=no,location=no";

	properties = "width=" + w + ",height=" + h + ",top=" + y + ",left=" + x
			+ ",scrollbars=yes,resizable=yes," + properties;

	window.open(href, name, properties);
}

function MM_findObj(n, d) { //v4.01
	var p, i, x;
	if (!d)
		d = document;
	if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
		d = parent.frames[n.substring(p + 1)].document;
		n = n.substring(0, p);
	}
	if (!(x = d[n]) && d.all)
		x = d.all[n];
	for (i = 0; !x && i < d.forms.length; i++)
		x = d.forms[i][n];
	for (i = 0; !x && d.layers && i < d.layers.length; i++)
		x = MM_findObj(n, d.layers[i].document);
	if (!x && d.getElementById)
		x = d.getElementById(n);
	return x;
}

function MM_showHideLayers() { //v6.0
	var i, p, v, obj, args = MM_showHideLayers.arguments;
	for (i = 0; i < (args.length - 2); i += 3)
		if ((obj = MM_findObj(args[i])) != null) {
			v = args[i + 2];
			if (obj.style) {
				obj = obj.style;
				v = (v == 'show') ? 'visible' : (v == 'hide') ? 'hidden' : v;
			}
			obj.visibility = v;
		}
}

function MM_swapImgRestore() { //v3.0
	var i, x, a = document.MM_sr;
	for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++)
		x.src = x.oSrc;
}

function MM_preloadImages() { //v3.0
	var d = document;
	if (d.images) {
		if (!d.MM_p)
			d.MM_p = new Array();
		var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
		for (i = 0; i < a.length; i++)
			if (a[i].indexOf("#") != 0) {
				d.MM_p[j] = new Image;
				d.MM_p[j++].src = a[i];
			}
	}
}

function MM_swapImage() { //v3.0
	var i, j = 0, x, a = MM_swapImage.arguments;
	document.MM_sr = new Array;
	for (i = 0; i < (a.length - 2); i += 3)
		if ((x = MM_findObj(a[i])) != null) {
			document.MM_sr[j++] = x;
			if (!x.oSrc)
				x.oSrc = x.src;
			x.src = a[i + 2];
		}
}

documentall = document.all;
function formatamoney(c) {
	var t = this;
	if (c == undefined)
		c = 2;
	var p, d = (t = t.split("."))[1].substr(0, c);
	for (p = (t = t[0]).length; (p -= 3) >= 1;) {
		t = t.substr(0, p) + "." + t.substr(p);
	}
	return t + "," + d + Array(c + 1 - d.length).join(0);
}

String.prototype.formatCurrency = formatamoney

function demaskvalue(valor, currency) {
	/*
	 * Se currency ?false, retorna o valor sem apenas com os nmeros. Se ?true, os dois ltimos caracteres s? considerados as 
	 * casas decimais
	 */
	var val2 = '';
	var strCheck = '0123456789';
	var len = valor.length;
	if (len == 0) {
		return 0.00;
	}

	if (currency == true) {
		/* Elimina os zeros ?esquerda 
		 * a vari?el  <i> passa a ser a localiza?o do primeiro caractere ap? os zeros e 
		 * val2 cont? os caracteres (descontando os zeros ?esquerda)
		 */

		for ( var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ','))
				break;

		for (; i < len; i++) {
			if (strCheck.indexOf(valor.charAt(i)) != -1)
				val2 += valor.charAt(i);
		}

		if (val2.length == 0)
			return "0.00";
		if (val2.length == 1)
			return "0.0" + val2;
		if (val2.length == 2)
			return "0." + val2;

		var parte1 = val2.substring(0, val2.length - 2);
		var parte2 = val2.substring(val2.length - 2);
		var returnvalue = parte1 + "." + parte2;
		return returnvalue;

	} else {
		/* currency ?false: retornamos os valores COM os zeros ?esquerda, 
		 * sem considerar os ltimos 2 algarismos como casas decimais 
		 */
		val3 = "";
		for ( var k = 0; k < len; k++) {
			if (strCheck.indexOf(valor.charAt(k)) != -1)
				val3 += valor.charAt(k);
		}
		return val3;
	}
}

/*
 Trata evento onkeypress em um input do tipo "reais".
 Interrompe execucao do evento em caso de caracteres invalidos e formata campo apos a inclusao de caracteres validos (numeros).
 */
function reais(obj, event) {
	// pega tecla pressionada
	var whichCode = (window.Event) ? event.which : event.keyCode;

	// ignora processamento no caso de teclas especiais
	if (whichCode == 0)
		return true;
	if (whichCode == 9)
		return true; // tecla tab
	if (whichCode == 13)
		return true; // tecla enter
	if (whichCode == 16)
		return true; // shift internet explorer
	if (whichCode == 17)
		return true; // control no internet explorer
	if (whichCode == 27)
		return true; // tecla esc
	if (whichCode == 34)
		return true; // tecla end
	if (whichCode == 35)
		return true; // tecla end
	// if (whichCode == 36 ) return true; //tecla home

	// evita execucao do evento caso o caracter seja um caracter nao aceito pelo
	// campo
	var caracteresBloqueados = ' ABC?DEFGHIJKLMN?OPQRSTUVWXYZabc?defghijklmn?opqrstuvwxyz????????????????????????,.:;@-\'+-=()*/<>#$%&???`^~';
	if (caracteresBloqueados.indexOf(String.fromCharCode(whichCode)) != -1) {
		if (event.preventDefault) { //standart browsers
			event.preventDefault();
		} else { // internet explorer
			event.returnValue = false;
		}
		return false;
	}

	// caso nao seja nenhuma das teclas anteriores.. formata campo apos alteracao
	setTimeout( function reaisClosure() {
		FormataReais(obj, '.', ',', whichCode);
	}, 0);
}// end reais

/*
 * Trata evento onkeydown em um input do tipo "reais". Como no evento onkeypress
 * sao tratados somente as teclas alfanumericas, tratamos o backspace e o delete
 * no evento onkeydown
 */
function backspace(obj, event) {
	// verifica se foi pressionado codigo 8: backspace ou codigo 46: delete
	var whichCode = (window.Event) ? event.which : event.keyCode;
	if (whichCode != 8 && whichCode != 46)
		return true;

	// caso seja uma das teclas tratadas (backspace e del).. formata campo apos
	// alteracao
	setTimeout( function reaisClosure() {
		FormataReais(obj, '.', ',', whichCode);
	}, 0);
}// end backspace

/*
 * Formata o texto do input "fld" para o formato "reais". Atualiza a posi??o do
 * cursor do teclado para a tecla atual.
 */
function FormataReais(fld, milSep, decSep, whichCode) {

	// pega posicao inicial do cursor do teclado
	var cursorTeclado = 0;
	if (fld.selectionStart) {
		cursorTeclado = fld.selectionStart;
	} else if (document.selection && document.selection.createRange) {
		var range = document.selection.createRange();
		var bookmark = range.getBookmark();
		cursorTeclado = bookmark.charCodeAt(2) - 1 - bookmark.charCodeAt(0);
	}

	// pega o comprimento e o texto iniciais do campo
	var lenInicio = fld.value.length;
	var textoInicio = fld.value;

	// formata valor do campo
	var bodeaux = demaskvalue(fld.value,true).formatCurrency();
	if (bodeaux != textoInicio) {
		fld.value = bodeaux;
	}

	// pega comprimento e texto do campo apos formatacao
	var lenFim = fld.value.length;
	var textoFim = fld.value;

	// altera posicao do cursor do teclado (caso tenha havido alteracao no texto
	// do campo)
	// isso evita que o cursor seja posicionado no final do valor
	if (textoInicio != textoFim) {

		cursorTeclado += (lenFim - lenInicio);

		if (fld.setSelectionRange) {
			fld.focus();
			var length = fld.value.length;
			fld.setSelectionRange(cursorTeclado, cursorTeclado);
		} else if (fld.createTextRange) {
			var range = fld.createTextRange();
			range.move('character', cursorTeclado);
			range.select();
		}
	}

} // end FormataReais

function replaceAll(string, token, newtoken) {
	while (string.indexOf(token) != -1) {
		string = string.replace(token, newtoken);
	}
	return string;
}
function moedaToNumber(strValor) {
	if (strValor.indexOf(",") != -1) {
		strValor = replaceAll(strValor, ".", "");
		strValor = replaceAll(strValor, ",", ".");
	}
	return strValor;
}

function transformaCampos() {
	document.all.valorUnitario.value = moedaToNumber(document.all.valorUnitario.value);
	return true;
}

function telefone(objeto, event) {

	var whichCode = (window.Event) ? event.which : event.keyCode;

	// ignora processamento no caso de teclas especiais
	if (whichCode == 0)
		return true;
	if (whichCode == 8)
		return true; // tecla tab
	if (whichCode == 9)
		return true; // tecla tab
	if (whichCode == 13)
		return true; // tecla enter
	if (whichCode == 16)
		return true; // shift internet explorer
	if (whichCode == 17)
		return true; // control no internet explorer
	if (whichCode == 27)
		return true; // tecla esc
	if (whichCode == 34)
		return true; // tecla end
	if (whichCode == 35)
		return true; // tecla end
	// if (whichCode == 36 ) return true; //tecla home

	// Somente aceitar números
/*	if (whichCode < 48 || whichCode > 57){
		return false;
	} */
	
	objeto.value = objeto.value.replace(/\D/g,"")

	if (objeto.value.length == 0)
		objeto.value = '(' + objeto.value;
	if (objeto.value.length == 3)
		objeto.value = objeto.value + ')';
	if (objeto.value.length == 8)
		objeto.value = objeto.value + '-';
}

/*fim moeda*/

