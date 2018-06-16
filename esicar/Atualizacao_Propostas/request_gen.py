# -*- coding: utf-8 -*-

import time, datetime, sys, requests

## Ids iniciais da pesquisa ##
ID_INICIAL = int(sys.argv[1])
INCREMENTO = int(sys.argv[2])

while(True):
	try:
		## Url para consulta ##
		url_consulta = str('http://localhost/esicar/index.php/in/get_propostas/get_propostas_siconv?id_inicial=' + str(ID_INICIAL) + '&id_final=' + str(int(ID_INICIAL + INCREMENTO)) + '&only_add=1')
                print "Executando a chamada dentro do siconv... \n"
		response = requests.get(url_consulta, timeout=299)
		print "Retorno chamada siconv igual a : " + str(response.status_code) + "\n"
		if response.status_code != 200:
			print (str(datetime.datetime.now()) + ' - Falha ao consultar ID INICIAL: %d | ID FINAL: %d. Tentando novamente em 30 segundos.\n') % (int(ID_INICIAL), int(ID_INICIAL + INCREMENTO),)
                        print "\n"
			time.sleep(30)
		else:
			sys.exit(0)
	except Exception, e:
		print str(datetime.datetime.now()) + ' - Falha ao conectar no servidor Tentando novamente em 60 segundos - ex: ' + str(e)
                print "\n"
		time.sleep(60)
		continue
