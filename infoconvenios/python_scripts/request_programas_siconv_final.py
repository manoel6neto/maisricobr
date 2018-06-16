# -*- coding: utf-8 -*-

import requests, time, sys

offset = 0
num_registros = 0

url_consulta = str('http://esicar.physisbrasil.com.br/esicar/index.php/get_programas/get_ids_programas?ano=2015')
response = requests.get(url_consulta)
if response.status_code == 200:
	print ' - IDS de programas cadastrados com sucesso\n'
else:
	print ' - Falha ao cadastrar IDS dos programas\n'

url_consulta = str('http://esicar.physisbrasil.com.br/esicar/index.php/get_programas/retorna_num_ids?ano=2015')
response = requests.get(url_consulta)
if response.status_code == 200:
	print ' - Total de IDS de programas cadastrados, Num regs = ' + str(response.text) + '\n'
	num_registros = int(response.text)
else:
	print ' - Falha ao buscar IDS dos programas\n'

if num_registros > 0:
	while offset <= num_registros:
		try:
			url_consulta = str('http://esicar.physisbrasil.com.br/esicar/index.php/get_programas/get_programas_siconv?ano=2015&offset='  + str(offset))
			response = requests.get(url_consulta)
			if response.status_code == 200:
				offset = offset + 1
				print ' - Programas baixados com sucesso, proximo offset = ' + str(offset) + '\n'
			else:
				print ' - Falha ao baixar programas\n'
		except:
			print ' - Falha ao conectar no servidor Tentando novamente em 30 segundos'
			time.sleep(30)
			continue
else:
	print ' - Nao foi possivel baixar os programas\n'
			
print 'Consulta finalizada'
