# -*- coding: utf-8 -*-

import requests, time, sys

offset = 1
num_registros = 0

url_consulta = str('http://localhost/esicar/index.php/get_programas/get_ids_programas_aptos?ano=2018')
response = requests.get(url_consulta)
if response.status_code == 200:
	print ' - IDS de programas cadastrados com sucesso\n'
else:
	print ' - Falha ao cadastrar IDS dos programas\n'

url_consulta = str('http://localhost/esicar/index.php/get_programas/retorna_num_ids_aptos?ano=2018')
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
			url_consulta = 'http://localhost/esicar/index.php/get_programas/get_programas_siconv?ano=2018&offset=' + str(offset)
			print str(url_consulta)
			response = requests.get(url_consulta, timeout=3600)
			if response.status_code == 200:
			#child = Popen('/usr/bin/wget -q ' + url_consulta, stdout = PIPE, shell = True).wait()
			#exitcodepopen = child
			#if exitcodepopen == 0:
				offset = offset + 1
				print ' - Programas baixados com sucesso, proximo offset = ' + str(offset) + '\n'
				response.close()
			else:
				print ' - Falha ao baixar programas - status: ' + str(response.status_code) + '\n'
				response.close()
			time.sleep(10)
		except:
			print ' - Falha ao conectar no servidor Tentando novamente em 30 segundos'
			time.sleep(30)
			continue
else:
	print ' - Nao foi possivel baixar os programas\n'

print 'Consulta finalizada'

url_consulta_marcar_excluidos = str('http://localhost/esicar/index.php/get_programas/marcar_programas_excluidos?ano=2018')
response = requests.get(url_consulta_marcar_excluidos)

print 'Script finalizado'

sys.exit(0)
