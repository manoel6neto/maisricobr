# -*- coding: utf-8 -*-

import requests, sys
from datetime import date

try:
    url_consulta = str('http://localhost/esicar/index.php/in/get_propostas/update_status_individual_proposta_by_proponente?proponente='+str(sys.argv[1])+'&ano='+str(sys.argv[2]))
    response = requests.get(url_consulta, timeout=299)
except:
    pass

sys.exit(0)
