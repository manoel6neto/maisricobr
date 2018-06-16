function campoMoeda(valor){
	
	var i = 0;
	var count = 0;
	var result = "";
	
		for (i = 0;i<valor.value.length;i++){

	
			if(valor.value.charAt(i) == ","){
				count++;								
			}
			if(count > 1){					
				valor.value="";
				result = "";
				alert("O valor de dinheiro possui caracteres inválidos");
				break;
			}
			if(valor.value.charAt(i) == "." && valor.value.charAt(i+1) == "."){
				
					valor.value="";
					result="";
					alert("O valor de dinheiro possui caracteres inválidos");
					break;
				
			}
			if(valor.value.charAt(i) == "0" || valor.value.charAt(i) == "1" || valor.value.charAt(i) == "2" || valor.value.charAt(i) == "3" || valor.value.charAt(i) == "4" || valor.value.charAt(i) == "5" || valor.value.charAt(i) == "6" || valor.value.charAt(i) == "7" || valor.value.charAt(i) == "8" || valor.value.charAt(i) == "9" || valor.value.charAt(i) == "." || valor.value.charAt(i) == ","){

				result += valor.value.charAt(i);

				

			}
			

			
		}
		
		
		for(i=0;i<result.length;i++){
			if(result.charAt(i)=="." && result.charAt(i+1)=="." ){
				valor.value="";
				result="";
				alert("O valor de dinheiro possui caracteres inválidos");
				break;
			}
		}
		valor.value=result;
		
		
}

