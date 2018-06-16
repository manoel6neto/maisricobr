<?php

/*
 * @author: Giovanne Almeida
 */

class avaliacao_visita_model extends CI_Model {
    /*
     * `contato_municipio` é a tabela no banco que armazena os contatos
     * criados pelo representante atual (verificar rega de criação que prevê
     * duplicidade)
     */

    /*
     * Na primeira visita, todas as avaliações de atividades de visitas são 
     * criadas no banco, porém sem data e status. Estas visitas serão 
     * complementadas futuramente, quando forem realizadas de fato.
     */
    /* Armazena as avaliações de contatos/visitas iniciados(as) */

    private $TBL_AVALIACAO_CONTATO = "avaliacao_contato";
    /* Armazena quem participou de uma visita avaliada */
    private $TBL_AVALIACAO_CONTATO_PARTICIPANTE = "avaliacao_contato_etapa_participante";
    /* Armazena as atividades que serão realizadas nas visitas */
    private $TBL_AVALIACAO_ATIVIDADE = "avaliacao_atividade";
    /* Armazena as etapas as quais as atividades estão inseridas */
    private $TBL_AVALIACAO_ETAPA = "avaliacao_etapa";
    /* Armazena o status das etapas pra saber se podem ser alteradas ou não */
    private $TBL_AVALIACAO_ETAPA_STATUS = "avaliacao_etapa_status";
    /* Armazena os tipos de participantes participam de cada meta */
    private $TBL_AVALIACAO_META_PARTICIPANTE = "avaliacao_meta_participante";
    /* Armazena as metas as quais as entapas estão inseridas */
    private $TBL_AVALIACAO_META = "avaliacao_meta";
    /* Armazena os tipos de participantes (prefeito, secretário, etc.) */
    private $TBL_AVALIACAO_PARTICIPANTE = "avaliacao_participante";

    /*
     * Cadastra a primeira avaliação de visita, criando todas as atividades no 
     * banco de uma vez. O representante vai atualizando estas atividades à 
     * medida que for realizando as visitas.
     * 
     * Se novas atividades/etapas/metas forem criadas, essa função deve ser
     * revisada.
     */

    function cadastra_primeira_visita($id_contato) {
        //Verificação pra evitar erro na nova versão no MySQL
        if($id_contato == "")
            $id_contato = null;
        
        /* Pega todas as atividades cadastradas */
        $atividades = $this->db->get($this->TBL_AVALIACAO_ATIVIDADE);
//        $primeira_atividade = true;
        foreach ($atividades->result() as $atividade) {
            $data = array(
                'id_atividade' => $atividade->id_atividade,
                'id_contato' => $id_contato,
//                'habilitado' => $primeira_atividade ? true : false //O que é habilitado agora é a etapa
            );
            $this->db->insert($this->TBL_AVALIACAO_CONTATO, $data);
//            $primeira_atividade = false;
        }
        $data = array(
            'id_etapa' => 1,
            'id_contato' => $id_contato,
            'status' => true
        );
        /* Habilita a primeira etapa */
        $this->db->insert($this->TBL_AVALIACAO_ETAPA_STATUS, $data);
    }

    /**
     * Retorna etapas de atividades de uma meta.
     * 
     * @param type $meta_id
     */
    function get_epatas_from_meta($meta_id, $contato_id) {
        $etapas = $this->db->get_where($this->TBL_AVALIACAO_ETAPA, array('id_meta' => $meta_id))->result();

        /* Percorre as etapas da meta */
        foreach ($etapas as $etapa) {
            /* Verifica habilidade */
            $this->db->select('status');
            $status = $this->db->get_where($this->TBL_AVALIACAO_ETAPA_STATUS, array('id_etapa' => $etapa->id_etapa, 'id_contato' => $contato_id))->result();

            if ($status) {
                $etapa->habilitada = $status[0]->status == 1;
            } else {
                $etapa->habilitada = false;
            }

            /* Pega todas as atividades da etapa */
            $etapa->atividades = $this->db->get_where($this->TBL_AVALIACAO_ATIVIDADE, array('id_etapa' => $etapa->id_etapa))->result();

            /* Percorre todas as atividades da etapa atual (descobre se ela tá completa) */
            foreach ($etapa->atividades as $atividade) {
                //Preenche a data, status e a habilidade desta atividade
                $this->db->select('data,status,habilitado');
                $avaliacao = $this->db->get_where($this->TBL_AVALIACAO_CONTATO, array('id_atividade' => $atividade->id_atividade, 'id_contato' => $contato_id))->result();

                $atividade->data = $avaliacao[0]->data;
                $atividade->status = $avaliacao[0]->status;
//                $atividade->habilitado = $avaliacao[0]->habilitado; //A etapa agora que é habilitada ou não
            }
        }

        return $etapas;
    }

    /**
     * Verifica se a etapa passada (que é a primeira de uma meta) deve estar habilitada
     * verificando se ela está completa. Se estiver habilitada, retorna true. 
     * False, caso contrário.
     * Esta função é importante pois se a meta passada não for a primeira, é 
     * preciso verificar se todas as etapas da meta anterior estão completas. 
     * Se estiverem, retorna true pois a atual deve ser habilitada.
     * 
     * @param type $meta_id
     * @param type $etapa
     */
    private function verifica_primeira_etapa($meta_id) {

        if ($meta_id == 1) {
            /* Pega a primeira etapa da primeira meta */
            $this->db->limit(1);
            $etapa = $this->db->get_where($this->TBL_AVALIACAO_ETAPA, array('id_meta' => $meta_id))->result()[0];
            /* Pega todas as atividades da etapa */
            $etapa->atividades = $this->db->get_where($this->TBL_AVALIACAO_ATIVIDADE, array('id_etapa' => $etapa->id_etapa))->result();
            foreach ($etapa->atividades as $atividade) {
                $this->db->select('status');
                $avaliacao = $this->db->get_where($this->TBL_AVALIACAO_CONTATO, array('id_atividade' => $atividade->id_atividade))->result();
                /* Se qualquer status for nulo, quer dizer que a etapa não está completa. Então tem que ser habilitada. */
                if ($avaliacao[0]->status == null) {
                    return true;
                }
            }

            return false;
        } else {
            /* Pega a última etapa da meta anterior */
            $this->db->order_by('id_etapa', 'DESC');
            $this->db->limit(1);
            $etapa = $this->db->get_where($this->TBL_AVALIACAO_ETAPA, array('id_meta' => $meta_id - 1))->result()[0];

            /* Pega todas as atividades da etapa */
            $etapa->atividades = $this->db->get_where($this->TBL_AVALIACAO_ATIVIDADE, array('id_etapa' => $etapa->id_etapa))->result();
            foreach ($etapa->atividades as $atividade) {
                $this->db->select('status');
                $avaliacao = $this->db->get_where($this->TBL_AVALIACAO_CONTATO, array('id_atividade' => $atividade->id_atividade))->result();
                /* Se qualquer status for nulo, quer dizer que esta etapa da meta anterior não está completa e tem que ser habilitada.
                  Logo, a etapa da meta atual não pode ser habilitada até a anterior estar completa. */
                if ($avaliacao[0]->status == null) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * Retorna os participantes de uma meta.
     * 
     * @param type $meta_id
     */
    function get_participantes_from_meta($meta_id) {
        $this->db->select('id_participante');
        /* Busca os ids dos participantes dessa meta */
        $participantes_ids = $this->db->get_where($this->TBL_AVALIACAO_META_PARTICIPANTE, array('id_meta' => $meta_id))->result();

        $participantes_ids_array = array();
        foreach ($participantes_ids as $id) {
            $participantes_ids_array[] = $id->id_participante;
        }

        /* Busca os participantes a partir do id */
        $this->db->from($this->TBL_AVALIACAO_PARTICIPANTE);
        $this->db->where_in('id_participante', $participantes_ids_array);
        $participantes = $this->db->get()->result();

        return $participantes;
    }

    /**
     * Retorna as avaliações de um contato.
     * 
     * @param type $contato_id
     */
    function get_avaliacoes_from_contato($contato_id) {
        return $this->db->get_where($this->TBL_AVALIACAO_CONTATO, array('id_contato' => $contato_id))->result();
    }

    /**
     * Verifica se o participante participou da etapa passada por parâmetro.
     *
     */
    function is_participante_in_etapa($id_participante, $id_etapa, $id_contato) {
        if ($this->db->get_where($this->TBL_AVALIACAO_CONTATO_PARTICIPANTE, array('id_participante' => $id_participante, 'id_etapa' => $id_etapa, 'id_contato' => $id_contato))->num_rows()) {
            return true;
        }
        return false;
    }

    /**
     * Retorna o contato do participante (nome, e-mail, telefone)
     */
    function get_participante_contato($contato_id, $etapa_id, $participante_id) {
        $this->db->select('nome , email , telefone');
        $result = $this->db->get_where($this->TBL_AVALIACAO_CONTATO_PARTICIPANTE, array('id_contato' => $contato_id, 'id_etapa' => $etapa_id, 'id_participante' => $participante_id))->result();
        if ($result) {
            return $result[0];
        }

        return null;
    }

    /**
     * Obtem o id de uma avaliação a partir o id de uma atividade e do contato, da tabela `avaliacao_contato`
     * 
     * @param type $id_atividade
     * @param type $id_contato
     * @return type
     */
    function get_avaliacao_from_atividade($id_atividade, $id_contato) {
        $this->db->select('id_avaliacao');
        return $this->db->get_where($this->TBL_AVALIACAO_CONTATO, array('id_atividade' => $id_atividade, 'id_contato' => $id_contato))->result()[0]->id_avaliacao;
    }

    /*
     * Delete todos os participantes da etapa do contato passado
     */

    function delete_all_contato_paticipante($id_etapa, $id_contato) {
        $this->db->delete($this->TBL_AVALIACAO_CONTATO_PARTICIPANTE, array(
            'id_etapa' => $id_etapa,
            'id_contato' => $id_contato));
    }

    /**
     * Atrela os participantes a uma etapa de visita a um contato.
     * 
     */
    function insere_contato_participante($participantes) {
        foreach ($participantes as $p) {
            $this->db->insert($this->TBL_AVALIACAO_CONTATO_PARTICIPANTE, $p);
        }
    }

    /**
     * Obtém o id da etapa a qual a atividade passada por parâmetro faz parte.
     */
    function get_etapa_from_atividade($id_atividade) {
        $this->db->select('id_etapa');
        return $this->db->get_where($this->TBL_AVALIACAO_ATIVIDADE, array('id_atividade' => $id_atividade))->result()[0]->id_etapa;
    }
    
    /**
     * Obtém todas as metas
     */
    function get_all_metas(){
        return $this->db->get($this->TBL_AVALIACAO_META)->result();
    }
    
    /**
     * Obtém todas as atividades
     */
    function get_all_atividades(){
        return $this->db->get($this->TBL_AVALIACAO_ATIVIDADE)->result();
    }

}
