# -*- coding: utf-8 -*-

import requests, time, sys
from datetime import date

try:
    url_consulta = str('http://localhost/esicar/index.php/in/get_propostas/update_status_individual_proposta_by_proponente?proponente='+str(sys.argv[1]))
    response = requests.get(url_consulta)
    if response.status_code == 200:
        print 'Atualizacao realizada com sucesso'
    else:
        print 'Falha ao atualizar - tentando novamente em 10 segundos'
        time.sleep(10)
except:
    time.sleep(10)
    pass

print 'Consulta finalizada'
