/**
 * Multiplica dois valores passados na função, formata o valor gerado e joga o valor no campo de destino.
 */


// number formatting function
// copyright Stephen Chapman 24th March 2006, 22nd August 2008
// permission to use this function is granted provided
// that this copyright notice is retained intact

function formatNumber(num,dec,thou,pnt,curr1,curr2,n1,n2) {
	
	var x = Math.round(num * Math.pow(10,dec));
	
	if (x >= 0) n1=n2='';
	
	var y = (''+Math.abs(x)).split('');
	var z = y.length - dec; if (z<0) z--; 
	
	for(var i = z; i < 0; i++) 
		y.unshift('0'); 
		
	if (z<0) z = 1; 
	
	y.splice(z, 0, pnt); 
	
	if(y[0] == pnt) y.unshift('0'); 
	
	while (z > 3) {
		z-=3; 
		y.splice(z,0,thou);
	}
	
	var r = curr1+n1+y.join('')+n2+curr2;
	
	return r;

}

function calculaValor(campoDestino, quantidade, valor) {
	
	if ((quantidade.value != "") && (valor.value != "")){
		
		quantidade = quantidade.replace(/\./g,"");
		quantidade = quantidade.replace(",",".");
					
		valor = valor.replace(/\./g,"");
		valor = valor.replace(",",".");
				
		var total = quantidade * valor;
		total = Math.round(total*100)/100;
		
		total = formatNumber(total,2,".",",","","","-","")
					
		campoDestino.value = total;
	}
	
}

function calculaValorUnitario(campoDestino, quantidade, valorTotal) {
	
	if ((quantidade != "") && (valorTotal != "")){
		quantidade = quantidade.replace(/\./g,"");
		quantidade = quantidade.replace(",",".");
					
		valorTotal = valorTotal.replace(/\./g,"");
		valorTotal = valorTotal.replace(",",".");
				
		var valor = valorTotal / quantidade;
		valor = Math.round(valor*100)/100;
		
		valor = formatNumber(valor,2,".",",","","","-","")
					
		campoDestino.value = valor;
	} else 
		campoDestino.value = '';
	
}