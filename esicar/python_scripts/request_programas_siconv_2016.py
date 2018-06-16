# -*- coding: utf-8 -*-

import requests, time, sys
from subprocess import Popen, PIPE

offset = 1
num_registros = 0

url_consulta = str('http://localhost/esicar/index.php/get_programas/get_ids_programas?ano=2016')
response = requests.get(url_consulta)
if response.status_code == 200:
	print ' - IDS de programas cadastrados com sucesso\n'
else:
	print ' - Falha ao cadastrar IDS dos programas\n'

url_consulta = str('http://localhost/esicar/index.php/get_programas/retorna_num_ids?ano=2016')
response = requests.get(url_consulta)
if response.status_code == 200:
	print ' - Total de IDS de programas cadastrados, Num regs = ' + str(response.text) + '\n'
	num_registros = int(response.text)
else:
	print ' - Falha ao buscar IDS dos programas\n'

if num_registros > 0:
	num_registros = num_registros + 1
	while offset <= num_registros:
		try:
			url_consulta = str(str('\"') + 'http://localhost/esicar/index.php/get_programas/get_programas_siconv?ano=2016&offset=' + str(offset) + str('\"'))
			print str(url_consulta)
			#response = requests.get(url_consulta)
			#if response.status_code == 200:
			child = Popen('/usr/bin/wget -q ' + url_consulta, stdout = PIPE, shell = True).wait()
			exitcodepopen = child
			if exitcodepopen == 0:
				offset = offset + 1
				print ' - Programas baixados com sucesso, proximo offset = ' + str(offset) + '\n'
			else:
				print ' - Falha ao baixar programas\n'
			time.sleep(1)
		except:
			print ' - Falha ao conectar no servidor Tentando novamente em 30 segundos'
			time.sleep(30)
			continue
else:
	print ' - Nao foi possivel baixar os programas\n'
			
print 'Consulta finalizada'
