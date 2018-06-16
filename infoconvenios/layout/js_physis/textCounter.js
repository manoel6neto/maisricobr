/** 
 * Não permite que a quantidade de caracteres ultrapasse a capacidade máxima do campo.	
 * Uso: evento onkeydown, chamada: ex:textCounter(this.form.motivo,this.form.remLen,1024) 
 */ 

function textCounter(field, countfield, maxlimit) {
	
	if (field.value.length > maxlimit){
	    field.value = field.value.substring(0, maxlimit);
		alert('Voce ultrapassou o limite de caracteres permitido no campo ' + field.name + '. Revise o texto, pois o mesmo foi cortado.');
	}
	else{ 
	    countfield.value = maxlimit - field.value.length;
	}
	
}