# -*- coding: utf-8 -*-

import requests, time, datetime

## Ids iniciais da pesquisa ##
idInicial = 330000
idFinal = 330005

while idFinal < 400000:
	try:
		## Url para consulta ##
		url_consulta = str('http://secretaria.physisbrasil.com.br/sihs/index.php/in/get_propostas/get_propostas_siconv?id_inicial=' + str(idInicial) + '&id_final=' + str(idFinal))	
		response = requests.get(url_consulta, timeout=290)
		if response.status_code == 200:
			print (str(datetime.datetime.now()) + ' - ID INICIAL: %d | ID FINAL: %d \n') % (idInicial, idFinal,)
			idInicial = idFinal + 1
			idFinal = idFinal + 5
		else:
			print (str(datetime.datetime.now()) + ' - Falha ao consultar ID INICIAL: %d | ID FINAL: %d. Tentando novamente em 30 segundos.\n') % (idInicial, idFinal,)
			time.sleep(30)
	except:
		print str(datetime.datetime.now()) + ' - Falha ao conectar no servidor Tentando novamente em 30 segundos'
		time.sleep(30)
		continue

print 'Consulta finalizada'
