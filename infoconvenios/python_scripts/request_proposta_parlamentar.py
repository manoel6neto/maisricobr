# -*- coding: utf-8 -*-

import requests, time, sys
from datetime import date

hoje = date.today()
for i in (0, 1):
        ## Url para consulta ##
        url_consulta = str('http://localhost/esicar/index.php/in/get_propostas/get_propostas_by_parlamentar?cod_parlamentar='+str(sys.argv[1]))
        response = requests.get(url_consulta)
        if response.status_code == 200:
			print str(i)+' - Atualizacao realizada com sucesso'
        else:
        	print str(i)+' - Falha ao atualiza'

print 'Consulta finalizada'
