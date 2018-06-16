# -*- coding: utf-8 -*-

import requests, sys

try:
        ## Url para consulta ##
        id_proposta = str(sys.argv[1])
        id_usuario = str(sys.argv[2])
        url_consulta = str('http://localhost/sihs/index.php/in/get_propostas/update_proposta_fake_gestor?id_proposta=' + id_proposta + '&id_usuario=' + id_usuario)
        response = requests.get(url_consulta)
        if response.status_code == 200:
                print 'Atualizacao realizada com sucesso'
        else:
                print 'Falha ao atualiza'
except Exception, e:
        print str(e)
        pass
