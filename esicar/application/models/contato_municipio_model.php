<?php

class contato_municipio_model extends CI_Model {

    public function insert_or_update($options) {
        if ($options['id_contato_municipio']) {
            $this->db->where('id_contato_municipio', $options['id_contato_municipio']);
            $this->db->update('contato_municipio', $options);

            return $options['id_contato_municipio'];
        } else {
            $this->db->insert('contato_municipio', $options);

            return $this->db->insert_id();
        }
    }

    public function getStatusContato($statusContato = "") {
        $status = array(
            'VISITA_1' => '1ª VISITA',
            'VISITA_2' => '2ª VISITA',
            'VISITA_3' => '3ª VISITA',
            'VISITA_4' => '4ª VISITA',
            'VISITA_5' => '5ª VISITA',
            'ESCLARECIMENTO_EDITAL' => 'ESCLARECIMENTO EDITAL',
            'PREVISAO_PUBLICACAO' => 'PREVISÃO DA PUBLICAÇÃO',
            'PUBLICACAO' => 'PUBLICAÇÃO',
            'LICITACAO' => 'LICITAÇÃO',
            'ASSINATURA_CONTRATO' => 'ASSINATURA DO CONTRATO'
        );

        if ($statusContato != "")
            return $status[$statusContato];

        return $status;
    }

    function formataCelular($numero) {
        $tamanhoTelefone = strlen($numero);

        if ($tamanhoTelefone < 8)
            return $numero;
        else if ($tamanhoTelefone == 8)
            return substr($numero, 2, 4) . "-" . substr($numero, 6, 4);
        else if ($tamanhoTelefone == 10)
            return "(" . substr($numero, 0, 2) . ") " . substr($numero, 2, 4) . "-" . substr($numero, 6, 4);
        else if ($tamanhoTelefone == 11)
            return "(" . substr($numero, 0, 2) . ") " . substr($numero, 2, 5) . "-" . substr($numero, 7, 4);
    }

    public function get_dados_contato() {
        $this->load->model('usuariomodel');

        //TODO: Exibir a esfera administrativa
//        $dados_cnpj = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
//
//        $listaCNPJs = "";
//        foreach ($dados_cnpj as $c)
//            $listaCNPJs .= "'" . $c->cnpj . "', ";
//
//        $listaCNPJs = trim($listaCNPJs, ", ");
//        $this->db->distinct('contato_municipio.*,proponente_siconv.esfera_administrativa');
        $this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
//        $this->db->join('proponente_siconv', 'contato_municipio.id_municipio = proponente_siconv.codigo_municipio');
        $contatos = $this->db->get('contato_municipio')->result();

        return $contatos;
    }
    
    public function get_dados_contato_by_user_id($userId) {
        $this->load->model('usuariomodel');
        $this->db->where('id_usuario_cadastrou', $userId);
        $contatos = $this->db->get('contato_municipio')->result();

        return $contatos;
    }

    public function get_by_id($id) {
        $this->db->where('id_contato_municipio', $id);
        return $this->db->get('contato_municipio')->row(0);
    }

    public function atualiza($id, $options) {
        $this->db->where('id_contato_municipio', $id);
        $this->db->update('contato_municipio', $options);
    }

    public function verifica_alerta_retorno($id = null) {
        $data_pesquisa = date('Y-m-d', strtotime("+1 day"));

        $this->db->where('data_retorno = ', $data_pesquisa);
        if ($id != null)
            $this->db->where('contato_municipio.id_contato_municipio', $id);
        $this->db->where('data_retorno IS NOT NULL', null, FALSE);
        $this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
        $this->db->join('historico_contato_municipio', 'contato_municipio.id_contato_municipio = historico_contato_municipio.id_contato_municipio');
        $this->db->order_by('id_historico_contato_municipio', 'DESC');
        $query = $this->db->get('contato_municipio');

        if ($id != null) {
            if (count($query->result()) > 0)
                return $query->row(0)->data_retorno == "";

            return false;
        }

        $temDataAberta = false;
        foreach ($query->result() as $q) {
            if ($query->row(0)->data_retorno == "") {
                $temDataAberta = true;
                break;
            }
        }

        return $temDataAberta;
    }

    public function verifica_alerta_marca_retorno($id = null) {
        if ($id != null)
            $this->db->where('contato_municipio.id_contato_municipio', $id);
        //$this->db->where('data_retorno IS NULL', null, FALSE);
        //$this->db->where('DATE_ADD(data_cadastro, INTERVAL -2 DAY) <= ', date('Y-m-d'), FALSE);
        $this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
        $this->db->join('historico_contato_municipio', 'contato_municipio.id_contato_municipio = historico_contato_municipio.id_contato_municipio');
        $this->db->order_by('id_historico_contato_municipio', 'DESC');
        $query = $this->db->get('contato_municipio');

        if ($id != null) {
            if (count($query->result()) > 0)
                return $query->row(0)->data_retorno == "";
        }

        $temDataAberta = false;
        foreach ($query->result() as $q) {
            if ($query->row(0)->data_retorno == "") {
                $temDataAberta = true;
                break;
            }
        }

        return $temDataAberta;
    }

    public function get_ultimo_contato($idCidade, $uf, $cnpj, $esfera) {
        if ($idCidade != "" && $uf != "" && $cnpj != "") {
            $this->db->_protect_identifiers = false;

            $listaEsferas = "";
            foreach ($esfera as $e)
                $listaEsferas .= "'" . $e . "', ";

            $listaEsferas = trim($listaEsferas, ", ");

//            $listaCNPJs = "";
//            foreach ($cnpj as $c)
//                $listaCNPJs .= "'" . $c . "', ";
//
//            $listaCNPJs = trim($listaCNPJs, ", ");

            $this->db->where('id_municipio', $idCidade);
            $this->db->where('sigla_uf', $uf);
            $this->db->where_in('ps.cnpj', $cnpj);
            $this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
            $this->db->join('proponente_siconv ps', "id_municipio = codigo_municipio", FALSE);
            $this->db->join('cnpj_contato_municipio', "contato_municipio.id_contato_municipio = cnpj_contato_municipio.id_contato_municipio AND cnpj_contato IN (select cnpj from proponente_siconv where esfera_administrativa IN (" . $listaEsferas . ") and codigo_municipio  =  '{$idCidade}' AND `sigla_uf` =  '{$uf}')");
            $this->db->join('historico_contato_municipio', 'contato_municipio.id_contato_municipio = historico_contato_municipio.id_contato_municipio');


//            $query = $this->db->query('SELECT DISTINCT * FROM (contato_municipio) 
//                JOIN proponente_siconv ps ON id_municipio = codigo_municipio 
//                JOIN cnpj_contato_municipio ON contato_municipio.id_contato_municipio = cnpj_contato_municipio.id_contato_municipio 
//                    AND cnpj_contato IN 
//                        (select cnpj from proponente_siconv where esfera_administrativa IN (' . $listaEsferas . ')
//                            AND codigo_municipio = ' . $idCidade . '
//                            AND `municipio_uf_sigla` = \'' . $uf . '\')
//                    AND ps.cnpj = ' . $listaCNPJs . ' 
//                    AND `id_usuario_cadastrou` = ' . $this->session->userdata('id_usuario') . '
//                    AND `status` != \'FINALIZADO\';');

            $dados = $this->db->get('contato_municipio')->row(0);
//            $dados = $query->result_array();

            if ($dados != null)
            /* Não existe mais essa lista de status */
//                return array_merge((array) $dados, array('lista_status' => $this->removeStatusUtilizados($dados->id_contato_municipio)));
                return $dados;
            else
                return array('nome_contato' => '', 'email_contato' => '', 'telefone_contato' => '', 'id_contato_municipio' => '', 'status_contato' => '', 'lista_status' => '');
        } else
            return array('nome_contato' => '', 'email_contato' => '', 'telefone_contato' => '', 'id_contato_municipio' => '', 'status_contato' => '', 'lista_status' => '');
    }
    
    public function get_visita($cidade_id, $usuario_id){
        $this->db->where('id_municipio', $cidade_id);
        $this->db->where('id_usuario_cadastrou', $usuario_id);
        return $this->db->get('contato_municipio')->row(0);
    }

    public function removeStatusUtilizados($idContato) {
        $this->db->where('id_contato_municipio', $idContato);
        $listaStatus = $this->db->get('historico_contato_municipio')->result();

        $statusGeral = $this->getStatusContato();

        foreach ($listaStatus as $status)
            unset($statusGeral[$status->status_contato]);

        return $statusGeral;
    }

    public function checkTemContatoCadastrado() {
        $sql = "SELECT distinct contato_municipio.* FROM contato_municipio join proponente_siconv ps on id_municipio = codigo_municipio 
					join cnpj_siconv on REPLACE(REPLACE(REPLACE(ps.cnpj, '/', ''), '-', ''), '.', '') = cnpj_siconv.cnpj
					join usuario_cnpj on (id_cnpj = id_cnpj_siconv and id_usuario = {$this->session->userdata('id_usuario')})
					where id_usuario_cadastrou = {$this->session->userdata('id_usuario')}";

        $query = $this->db->query($sql);
        if ($query->num_rows > 0) {
            $dados = $query->row(0);
            if ($dados->nome_contato == "" || $dados->email_contato == "")
                return "SEM_CONTATO#" . $dados->id_contato_municipio;

            return "";
        } else
            return "SEM_CADASTRO";
    }

    /**
     * [VERIFICAR NECESSIDADE - DEPRECATED]
     * Função usada para determinar se existe um cadastro de contato no banco.
     * Usada na vinculação do CNPJ.
     * Havendo um contato, o usuário era obrigado a selecionar um novo status de visita (2ª VISITA, 3ª VISITA, RETORNO, etc.)
     * 
     * Essas informações não são mais passadas no momento da vinculação.
     * TODO: Verificar a necessidade dessa função.
     * 
     * @return string
     */
    public function check_contato_avaliacao_pendente() {
        $sql = "SELECT distinct contato_municipio.* FROM contato_municipio join proponente_siconv ps on id_municipio = codigo_municipio 
					join cnpj_siconv on REPLACE(REPLACE(REPLACE(ps.cnpj, '/', ''), '-', ''), '.', '') = cnpj_siconv.cnpj
					join usuario_cnpj on (id_cnpj = id_cnpj_siconv and id_usuario = {$this->session->userdata('id_usuario')})
					where id_usuario_cadastrou = {$this->session->userdata('id_usuario')}";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            $dados = $query->result();
            foreach ($dados as $dado) {
                if ($dado->nome_contato == "" || $dado->email_contato == "") {
                    return "SEM_CONTATO#" . $dado->id_contato_municipio;
                }

                $sql = "SELECT * FROM avaliacao_contato WHERE avaliacao_contato.id_contato = {$dado->id_contato_municipio}";
                if ($this->db->query($sql)->num_rows == 0) {
                    return "";
                }
            }
            return "SEM_CADASTRO";
        } else {
            return "SEM_CADASTRO";
        }
    }

    /**
     * Atualiza uma avaliação.
     * 
     * @param type $avaliacao_id
     * @param type $options
     * @return type
     */
    public function atualiza_avaliacao_contato($avaliacao_id, $options) {
        $this->db->where('id_avaliacao', $avaliacao_id);
        $this->db->update('avaliacao_contato', $options);
    }

    /**
     * TODO: Mudar esta função para o model 'avaliacao_visita_model'
     * Verifica se a etapa foi concluída. Em caso afirmativo, habilita a próxima.
     * Se for a última, altera o status do contato para "FINALIZADO"
     */
    function fetch_etapa($id_contato) {
        $etapa_habilitada = $this->db->get_where('avaliacao_etapa_status', array('status' => true, 'id_contato' => $id_contato))->result()[0];
        /* Pega todas as atividades da etapa */
        $etapa_habilitada->atividades = $this->db->get_where('avaliacao_atividade', array('id_etapa' => $etapa_habilitada->id_etapa))->result();

        /* Percorre todas as atividades da etapa atual (descobre se ela tá completa) */
        foreach ($etapa_habilitada->atividades as $atividade) {
            /* verifica o status das atividades deste contato */
            $this->db->select('status');
            $avaliacao = $this->db->get_where('avaliacao_contato', array('id_atividade' => $atividade->id_atividade, 'id_contato' => $id_contato))->result();
            /* Se algum status de atividade é null, a etapa não foi concluída */
            if ($avaliacao[0]->status == null) {
                return;
            }
        }
        /* Se chegou aqui, não foi encontrado status == null, então a etapa foi concluída. */
        /* Desabilita a etapa atual */
        $this->db->where(array('id_etapa' => $etapa_habilitada->id_etapa, 'id_contato' => $id_contato));
        $this->db->update('avaliacao_etapa_status', array('status' => false));

        /* Verifica se existe uma próxima etapa. */
        $query = $this->db->get_where('avaliacao_etapa', array('id_etapa > ' => $etapa_habilitada->id_etapa));

        if ($query->num_rows() > 0) {
            /* Habilita a próxima etapa */
            $data = array(
                'id_etapa' => $query->result()[0]->id_etapa,
                'id_contato' => $id_contato,
                'status' => true
            );
            $this->db->insert('avaliacao_etapa_status', $data);
        } else {/* Se não existe, a atual é a última. Atualiza o status do contato para "FINALIZADO" */
            $this->db->where(array('id_contato_municipio' => $id_contato));
            $this->db->update('contato_municipio', array('status' => 'FINALIZADO'));
        }
    }

    /**
     * Verifica se existem contatos pendentes <b>para o usuário logado</b>. 
     * @return Retorna TRUE se não existem contatos pendentes. FALSE, caso contrário.
     */
    public function check_contato_status() {
        $this->db->select('status');
        $query = $this->db->get_where('contato_municipio', array('id_usuario_cadastrou' => $this->session->userdata('id_usuario')))->result();
        foreach ($query as $contato) {
            if ($contato->status == 'PENDENTE') {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Verifica o status do contato passado por parâmetro.
     * 
     * @param type $id_contato_municipio - Id do contato
     * @return string status
     */
    public function get_status($id_contato_municipio) {
        $this->db->select('status');
        $this->db->where('id_contato_municipio', $id_contato_municipio);
        return $this->db->get('contato_municipio')->row(0)->status;
    }
    
}
