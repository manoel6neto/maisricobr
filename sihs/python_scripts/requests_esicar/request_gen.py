# -*- coding: utf-8 -*-

import time, datetime, sys
from subprocess import Popen, PIPE

## Ids iniciais da pesquisa ##
idInicial = sys.argv[1]
idFinal = sys.argv[2]

while(True):
	try:
		## Url para consulta ##
		url_consulta = str('"http://localhost/sihs/index.php/in/get_propostas/get_propostas_siconv?id_inicial=' + str(idInicial) + '&id_final=' + str(idFinal) + '&only_add=1"')
		#response = requests.get(url_consulta, timeout=290)
		print "Executando!!"
		child = Popen('/usr/bin/wget -q ' + url_consulta, stdout = PIPE, shell = True).wait()
		exitcodepopen = child
		print "Retorno igual a : " + str(exitcodepopen)
		if exitcodepopen != 0:
			print (str(datetime.datetime.now()) + ' - Falha ao consultar ID INICIAL: %d | ID FINAL: %d. Tentando novamente em 30 segundos.\n') % (int(idInicial), int(idFinal),)
			time.sleep(30)
		else:
			sys.exit(0)
	except Exception, e:
		print str(datetime.datetime.now()) + ' - Falha ao conectar no servidor Tentando novamente em 60 segundos - ex: ' + str(e)
		time.sleep(60)
		continue


