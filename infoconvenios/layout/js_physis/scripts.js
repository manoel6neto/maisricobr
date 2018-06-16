    function adicionaElemento(idElementoEntrada, idElementoSaida) {
		var entrada = document.getElementById(idElementoEntrada);
		var oOption = document.createElement("OPTION");
    	var saida = document.getElementById(idElementoSaida);

    	if(!(entrada.value == "")) {
	    	saida.options.add(oOption);
	    	oOption.innerText = saida.options.length;
	    	oOption.value = entrada.value;
	    	oOption.text = entrada.value;
	    	entrada.value = "";
    	}
    }

    function removeElemento(idElemento) {
    	var elemento = document.getElementById(idElemento);
		elemento.options[elemento.options.selectedIndex] = null;
    }

    function armazenaElementos(idElemento, idElementoAuxiliar) {
  		var elemento = document.getElementById(idElemento);
	    var aux = document.getElementById(idElementoAuxiliar);

	    if(elemento.length > 0) {
    	   var i;
	       aux.value = elemento.options[0].value;
    	   for(i = 1; i < elemento.length; i++) {
        	  aux.value += ", " + elemento.options[i].value;
	       }
    	}
    }

    function desabilitaTodosOutrosCampos(idCampo) {
		var i;
		var campo = document.getElementById(idCampo);
		var form  = document.forms[0];

		for(i=0; i < form.elements.length; i++) {
		if(!(campo.name == form.elements[i].name) && (campo.value.length > 0)) {
				if(!(form.elements[i].type == "submit") && !(form.elements[i].type == "button") && !(form.elements[i].type == "reset")){
					form.elements[i].disabled = true;
				}
		}
		else {
				document.forms[0].elements[i].disabled = false;
		}
		}
	}

		function MM_jumpMenu(targ,selObj,restore){ //v3.0 função de combo do cabeçalho
		eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
		 if (restore) selObj.selectedIndex=0;
	}


	function alteraAbas(id, idm) { // funcao para ABAS (id do div a ser mostrado , id do elemento pai)
		// document.getElementById(id).style.display=='none' ? document.getElementById(id).style.display='' : document.getElementById(id).style.display='none';
		document.getElementById(id).style.display=='' ? document.getElementById(id).style.display='none' : document.getElementById(id).style.display='';
		document.getElementById(idm).className=='checkInativo' ? document.getElementById(idm).className='checkAtivo' : document.getElementById(idm).className = 'checkInativo';
		return false

	}

	
	function limita(idElemento, limite)   { // função que limita os caracteres de um TextArea
		if(idElemento.value.length>limite)
		{
			alert("O tamanho do campo descricao não pode ser superior a " + limite + " caracteres");
			idElemento.value=idElemento.value.substring(0,limite-1)
		}else if(idElemento.value.length>limite)
			{
				alert("O tamanho do campo observacao não pode ser superior a " + limite + " caracteres");
				idElemento.value=idElemento.value.substring(0,limite-1)
		} 
		return true;
	}

/*////////////////////////////////////////////////
FUNÇÕES RELACIONADAS A ARRASTAR A JANELA POPUP 
////////////////////////////////////////////////*/	


function initDragDrop() {
__dragX = 0;
__dragY = 0;
__dragId = '';
__dragging = false;
document.body.onmousedown = __dragDown;
document.body.onmousemove = __dragMove;
document.body.onmouseup = function() { __dragging = false; };
}

function __dragDown(e) {
e = e ? e : window.event;
__dragEl = document.getElementById(__dragId) || null;
var _target = document.all ? e.srcElement : e.target;
  if(!__dragEl || !(/drag/.test(_target.className))) return;
__dragX = e.clientX - __dragEl.offsetLeft;
__dragY = e.clientY - __dragEl.offsetTop;
__dragging = true;
};

function __dragMove(e) {
	if(typeof __dragging == 'undefined' || !__dragging) return;
e = e ? e : window.event;
__dragEl.style.left = (e.clientX - __dragX)+'px';
__dragEl.style.top = (e.clientY - __dragY)+'px';
};

/* FIM DAS FUNÇÕES RELACIONADAS A ARRASTAR A JANELA POPUP */


////////////////////////////////////////////////////////////
// CONSERTA A BORDA DOS CHECKBOXES NO IE
function styleInputs() {
   var cbxs=document.getElementsByTagName('INPUT');
   for (var i=0; i<cbxs.length; i++) {
       if(cbxs[i].type=='checkbox') {
         cbxs[i].style.border='none';
       }
    }
}
function styleInputsRadio() {
   var cbxs=document.getElementsByTagName('INPUT');
   for (var i=0; i<cbxs.length; i++) {
       if(cbxs[i].type=='radio') {
         cbxs[i].style.border='none';
       }
    }
}

// FUNCAO PARA O MENU FUNCIONAR NO IE
function IEmenuHack() {

	//init();

	var navItems = document.getElementById("primary-nav").getElementsByTagName("li");

	for (var i=0; i<navItems.length; i++) {
		if(navItems[i].className == "menuparent2") {
			navItems[i].onmouseover=function() { this.className += " over"; }
			navItems[i].onmouseout=function() { this.className = "menuparent2"; }
		}
		if(navItems[i].className == "menuparent1") {

			navItems[i].onmouseover=function() { EscondeCombo(); this.className += " over"; }
			navItems[i].onmouseout=function() { MostraCombo(); this.className = "menuparent1"; }
		}
		if(navItems[i].className == "menuparent") {
		navItems[i].onmouseover=function() { this.className += " over"; }
		navItems[i].onmouseout=function() { this.className = "menuparent"; }
		}
	}
	/* vinagre: adaptacao do menu para os subniveis no IE*/
}

function setaAcao(nomeAcao, fcValidacao, valida , nomeForm){
		document.forms[0].action = "";

		document.forms[0].action = getPath() + '/' + nomeAcao + '.do';

		document.forms[0].name = nomeForm;
		if(true && valida){
			if(eval(fcValidacao + '(document.forms[0])')){
				document.forms[0].submit();
			}
		}else{
			document.forms[0].submit();
		}

	}

// FUNCOES QUE ESTAVAM NO MENU.JSP
	function keySubmit(e){ }
     // <!--Operacao de Habilitar-->
     function verificaHabilitados() { }
    // <!--Operacao de Desabilitar-->
    function verificaDesabilitados() { }
    // <!--Operacao de tornar visivel-->
    function verificaVisiveis() {   }
    // <!--Operacao de Esconder-->
    function verificaEscondidos() {   }
    function verifica() {
     	verificaHabilitados();
     	verificaDesabilitados();
     	verificaEscondidos();
     	verificaVisiveis();
    }

// funcao para esconder combos sob os menus em bug no IE (dimas 03-03-09)

    function MostraCombo()
    {
    	if(document.all){
	    	var qtdSelect = document.getElementById("ConteudoDiv").getElementsByTagName("select");
		    for (i = 0; i < qtdSelect.length; i++)
				qtdSelect[i].style.visibility = "visible";
			
		}else
			return;
	}

    function EscondeCombo()
    {
    	if(document.all){
    		var qtdSelect = document.getElementById("ConteudoDiv").getElementsByTagName("select");
		     for (i = 0; i < qtdSelect.length; i++)
				qtdSelect[i].style.visibility = "hidden"
		  }else
			return;
	}


function THints (arg1, arg2)
{
  this.arg1 = arg1;
  this.arg2 = arg2;

  this.show = function (arg) {}
  this.hide= function (arg) {}

}

var HINTS_CFG = "";

function changeElementDisplay(idElement) {
	element = document.getElementById(idElement);
	display_style = element.style.display;
	if (display_style == "block")
		element.style.display = "none";
	else
		element.style.display = "block";
}	


function html5_audio(){
	  var a = document.createElement('audio');
	  return !!(a.canPlayType && a.canPlayType('audio/wav;').replace(/no/, ''));
	}
	 
	var play_html5_audio = false;
	if(html5_audio()) play_html5_audio = true;
	 
	function play_sound(url) {
	  if(play_html5_audio){
	    var snd = new Audio(url);
	    snd.load();
	    snd.play();
	  }else{
	    try {
	      var soundEmbed = document.createElement("embed");
	      soundEmbed.setAttribute("src", url);
	      soundEmbed.setAttribute("hidden", true);
	      soundEmbed.setAttribute("autostart", false);
	      soundEmbed.setAttribute("width", 0);
	      soundEmbed.setAttribute("height", 0);
	      soundEmbed.setAttribute("enablejavascript", true);
	      soundEmbed.setAttribute("autostart", true);
	      document.body.appendChild(soundEmbed);
	    }
	    catch (e) {
	    }
	  }
	}