# -*- coding: utf-8 -*-

import requests, time, datetime, sys

## Ids iniciais da pesquisa ##
idInicial = sys.argv[1]
idFinal = sys.argv[2]

while(True):
	try:
		## Url para consulta ##
		url_consulta = str('http://localhost/esicar/index.php/in/get_proponentes/get_proponentes_siconv_from_html?id_inicial=' + str(idInicial) + '&id_final=' + str(idFinal))
		response = requests.get(url_consulta, timeout=290)
		if response.status_code != 200:
			print (str(datetime.datetime.now()) + ' - Falha ao consultar ID INICIAL: %d | ID FINAL: %d. Tentando novamente em 10 segundos.\n') % (int(idInicial), int(idFinal),)
			time.sleep(10)
		else:
			sys.exit(0)
	except Exception, e:
		print str(datetime.datetime.now()) + ' - Falha ao conectar no servidor Tentando novamente em 10 segundos - ex: ' + str(e)
		time.sleep(10)
		continue


