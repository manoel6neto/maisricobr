# -*- coding: utf-8 -*-

import requests, time, sys
from datetime import date

count = 0;

try:
    url_consulta = str('http://localhost/esicar/index.php/in/get_propostas/update_status_individual_proposta_anos?ano='+str(sys.argv[1]))
    response = requests.get(url_consulta)
    if response.status_code == 200:
        print 'Atualizacao realizada com sucesso'        
    else:
        print 'Falha ao atualizar'           
except:
    pass

print 'Consulta finalizada'
