# -*- coding: utf-8 -*-

import requests, sys

idInicial = sys.argv[1]
idFinal = sys.argv[2]

try:
        ## Url para consulta ##
        url_consulta = str('"http://localhost/esicar/index.php/in/get_propostas/get_propostas_siconv?id_inicial=' + str(idInicial) + '&id_final=' + str(idFinal) + '&only_add=1"')
        response = requests.get(url_consulta)
        if response.status_code == 200:
                print 'Atualizacao realizada com sucesso'
        else:
                print 'Falha ao atualiza'
except Exception, e:
        print str(e)
        pass
