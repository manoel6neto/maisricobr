# -*- coding: utf-8 -*-

import requests, time, datetime

## Ids iniciais da pesquisa ##
idInicial = 130000
idFinal = 130005

while idFinal < 200000:
	try:
		## Url para consulta ##
		url_consulta = str('http://esicar.physisbrasil.com.br/esicar/index.php/in/get_propostas/get_propostas_siconv?id_inicial=' + str(idInicial) + '&id_final=' + str(idFinal))	
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
