/** 
 * Não permite letras ou caracteres especiais em campos do tipo CPF.	
 * Uso: evento onKeyPress, chamada: keyPressCpf(this, event) 
 */
function keyPressCpf(input, event) {

	// pega tecla pressionada
	whichCode = (window.Event) ? event.which : event.keyCode;
        caracteresBloqueados = ' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz,.:;@-\+!-=()[]{}*/<>#$%&?ç`^~';
		
	// evita execucao do evento caso o caracter seja um caracter nao aceito pelo campo
	if (caracteresBloqueados.indexOf(String.fromCharCode(whichCode))!=-1) {
		if (event.preventDefault) { //standart browsers
			event.preventDefault();
		} else{ // internet explorer
			event.returnValue = false;
		}
		return false;
	}
	
}
