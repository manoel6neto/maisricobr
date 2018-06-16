<?php

class banco_proposta_model extends CI_Model {

    function get_all() {
        $query = $this->db->get('banco_proposta');
        return $query->result();
    }

    function get_by_id($id_proposta) {
        $this->db->where('id_proposta', $id_proposta);
        $query = $this->db->get('banco_proposta');

        return $query->row(0);
    }

    function get_by_codigo_siconv($codigo) {
        $this->db->where('codigo_siconv', $codigo);
        $query = $this->db->get('banco_proposta');

        if ($query->num_rows > 0) {
            return $query->row(0)->id_siconv;
        } else {
            return null;
        }
    }
    
    function get_by_proponente_anos($proponente, $anos) {
        $this->db->where('proponente', $proponente);
        $this->db->where_in('ano', $anos);
        
        $query = $this->db->get('banco_proposta');
        
        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function atualiza_proposta($id, $options = array()) {

        $this->db->where('id_proposta', $id);
        $this->db->update('banco_proposta', $options);
    }

    function get_by_id_siconv($id_siconv) {
        $this->db->where('id_siconv', $id_siconv);
        $query = $this->db->get('banco_proposta');

        return $query->result();
    }

    function get_propostas_by_proponentes($cnpjs) {
        $this->db->where_in('proponente', $cnpjs);
        $query = $this->db->get('banco_proposta');

        return $query->result();
    }
    
    function get_propostas_by_proponentes_anos($cnpjs, $anos = null) {
        $this->db->where_in('proponente', $cnpjs);
        if ($anos == null) {
            $this->db->where('ano', '2015');
        } else {
            $this->db->where_in('ano', $anos);
        }
        
        $query = $this->db->get('banco_proposta');

        return $query->result();
    }

    public function get_propostas_proponente_programa($cnpjs, $programa) {
        $this->db->where('codigo_programa', $programa);
        $this->db->where_in('proponente', $cnpjs);

        $this->db->where_not_in('codigo_siconv', array('056847/2014', '049240/2014', '048697/2014', '048523/2014', '048520/2014', '048469/2014', '048468/2014', '048426/2014', '048424/2014', '048328/2014', '048327/2014', '048326/2014',
            '048324/2014', '006172/2014', '006167/2014', '006166/2014', '006163/2014', '006162/2014', '006161/2014', '006160/2014', '056599/2013', '056595/2013', '056593/2013', '056591/2013', '056589/2013', '051279/2013',
            '056583/2013', '056579/2013', '056577/2013', '056574/2013', '056572/2013', '056570/2013', '056569/2013', '056567/2013', '056564/2013', '056144/2013', '056005/2013', '056004/2013', '056001/2013', '055999/2013',
            '055998/2013', '055997/2013', '055996/2013', '055995/2013', '055994/2013', '055993/2013', '055992/2013', '055991/2013', '055990/2013', '055989/2013', '055988/2013', '055987/2013', '055981/2013', '055980/2013',
            '055969/2013', '055968/2013', '055967/2013', '055966/2013', '055770/2013', '055764/2013', '060633/2013', '060632/2013', '060630/2013', '060629/2013', '060616/2013', '059723/2013', '059722/2013', '059721/2013',
            '059720/2013', '059719/2013', '059718/2013', '059717/2013', '059716/2013', '059714/2013', '059713/2013', '059657/2013', '059656/2013', '059654/2013', '059652/2013', '057500/2013', '057499/2013', '057498/2013',
            '057496/2013', '057486/2013', '057459/2013', '057454/2013', '057449/2013', '057378/2013', '063506/2013', '088753/2013', '041873/2014', '028396/2012', '028296/2012', '064536/2011', '044658/2014', '044657/2014',
            '044656/2014', '044337/2014', '044336/2014', '044335/2014', '044334/2014', '044333/2014', '044331/2014', '044327/2014', '044326/2014', '044325/2014', '044321/2014', '044318/2014', '044305/2014', '044303/2014',
            '044196/2014', '044194/2014', '044193/2014', '044189/2014', '044186/2014', '044183/2014', '044179/2014', '044178/2014', '044166/2014', '044123/2014', '039519/2014', '039517/2014', '039459/2014', '065058/2013',
            '064360/2013', '064359/2013', '064358/2013', '062226/2013', '062224/2013', '062223/2013', '148477/2009', '148211/2009', '148036/2009', '083414/2008', '083055/2008', '062592/2013', '062591/2013', '062590/2013',
            '062589/2013', '062229/2013', '062225/2013', '052457/2014', '052056/2013', '052054/2013', '052046/2013', '052043/2013', '052040/2013', '052025/2013', '052020/2013', '052008/2013', '052002/2013', '051998/2013',
            '051991/2013', '051984/2013', '051978/2013', '051973/2013', '051972/2013', '051968/2013', '051964/2013', '051942/2013', '051917/2013', '051915/2013', '051914/2013', '051913/2013', '051912/2013', '051911/2013',
            '051909/2013', '051908/2013', '051907/2013', '051903/2013', '051902/2013', '051900/2013', '051897/2013', '051896/2013', '051894/2013', '051893/2013', '051892/2013', '051890/2013', '051889/2013', '051888/2013',
            '051886/2013', '051885/2013', '051884/2013', '051883/2013', '051882/2013', '051881/2013', '051876/2013', '051182/2013', '051176/2013', '051174/2013', '046849/2013', '051421/2013', '051412/2013', '051278/2013',
            '051874/2013', '051873/2013', '051868/2013', '051866/2013', '051865/2013', '051864/2013', '051862/2013', '051861/2013', '051860/2013', '051859/2013', '051858/2013', '051857/2013', '051856/2013', '051189/2013',
            '051851/2013', '051849/2013', '051846/2013', '051830/2013', '051797/2013', '051687/2013', '051684/2013', '051682/2013', '051672/2013', '051670/2013', '051627/2013', '051622/2013', '051618/2013', '051244/2013',
            '051535/2013', '051455/2013', '051454/2013', '051453/2013', '051452/2013', '051451/2013', '051450/2013', '051448/2013', '051447/2013', '051446/2013', '051445/2013', '051444/2013', '051443/2013', '051253/2013',
            '051442/2013', '051441/2013', '051440/2013', '051439/2013', '051438/2013', '051436/2013', '051434/2013', '051433/2013', '051432/2013', '051431/2013', '051428/2013', '051425/2013', '051424/2013', '051257/2013',
            '051424/2013', '051424/2013', '051411/2013', '051410/2013', '051409/2013', '051408/2013', '051401/2013', '051400/2013', '051399/2013', '051398/2013', '051397/2013', '051396/2013', '051395/2013', '051258/2013',
            '051393/2013', '051384/2013', '051383/2013', '051382/2013', '051380/2013', '051373/2013', '051369/2013', '051368/2013', '051365/2013', '051360/2013', '051356/2013', '051355/2013', '051323/2013', '051197/2013',
            '051319/2013', '051317/2013', '051316/2013', '051315/2013', '051291/2013', '051288/2013', '051288/2013', '051288/2013', '051275/2013', '051274/2013', '051268/2013', '051267/2013', '051265/2013', '051190/2013',
            '051232/2013', '051227/2013', '051213/2013', '051211/2013', '051200/2013', '051195/2013'));

        $this->db->where_not_in('UPPER(situacao)', array(strtoupper('Proposta/Plano de Trabalho em rascunho'), strtoupper('HistÓrico')));

        $query = $this->db->get('banco_proposta');

        return $query->result();
    }

    //Inserir ou atualizar uma proposta no banco de propostas
    function insert_or_update($options) {
        $this->db->where('id_siconv', $options['id_siconv']);
        $query = $this->db->get('banco_proposta');

        if ($query->num_rows > 0) {
            $this->db->where('id_proposta', $query->row(0)->id_proposta);
            $this->db->update('banco_proposta', $options);
            return $query->row(0)->id_proposta;
        } else {
            $this->db->insert('banco_proposta', $options);
            return $this->db->insert_id();
        }
    }

    function update_by_id_proposta($options) {
        $this->db->where('id_proposta', $options['id_proposta']);
        $this->db->update('banco_proposta', $options);
    }

    public function get_dados_by_ids($ids) {
        $this->db->where_in('id_proposta', $ids);
        $this->db->order_by('ano', 'DESC');
        $this->db->order_by('codigo_siconv', 'DESC');
        return $this->db->get('banco_proposta')->result();
    }

    public function busca_programas_pareceres($filtros, $busca_cnpj_proponente = false, $filtro_proponente = array()) {
        $this->load->model('usuariomodel');
        $this->load->model('programa_model');

        if (!$busca_cnpj_proponente)
            $cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
        else {
            $this->load->model('proponente_siconv_model');
            $cnpjs = $this->proponente_siconv_model->get_all_cnpj_estado($filtro_proponente['estado'], $filtro_proponente['esfera']);
        }

        if ((!empty($cnpjs) && $this->session->userdata('nivel') != 1) || $this->session->userdata('nivel') == 1) {
            $this->db->distinct();
            $this->db->select('banco_proposta.*');

            if (isset($filtros['anos'])) {
                if (!in_array("TODOS", $filtros['anos']))
                    $this->db->where_in('ano', $filtros['anos']);
            } else
                $this->db->where('ano', date("Y"));

            if ($this->session->userdata('nivel') != 1) {
                $filtroPesquisa = "";
                if ($filtros['pesquisa'] != "") {
                    $filtroPesquisa .= "(banco_proposta.orgao LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.objeto LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.tipo LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.situacao LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.codigo_siconv LIKE '%{$filtros['pesquisa']}%')";

                    $this->db->where($filtroPesquisa);
                }

                $listaCnpjs = array();
                foreach ($cnpjs as $cnpj)
                    $listaCnpjs[] = preg_replace('/[^0-9]/', '', (string) $cnpj->cnpj);

                if (count($listaCnpjs) > 0)
                    $this->db->where_in('proponente', $listaCnpjs);
            }else {
                $filtroPesquisa = "";
                if ($filtros['pesquisa'] != "") {
                    $filtroPesquisa .= "(banco_proposta.orgao LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.objeto LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.tipo LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.situacao LIKE '%{$filtros['pesquisa']}%'";
                    $filtroPesquisa .= " OR banco_proposta.codigo_siconv LIKE '%{$filtros['pesquisa']}%')";

                    $this->db->where($filtroPesquisa);
                }

                $listaCnpjs = array();
                foreach ($cnpjs as $cnpj)
                    $listaCnpjs[] = preg_replace('/[^0-9]/', '', (string) $cnpj->cnpj);

                if (count($listaCnpjs) > 0)
                    $this->db->where_in('proponente', $listaCnpjs);
                
                $this->db->limit(50);
            }

            $this->db->where_not_in('codigo_siconv', array('056847/2014', '049240/2014', '048697/2014', '048523/2014', '048520/2014', '048469/2014', '048468/2014', '048426/2014', '048424/2014', '048328/2014', '048327/2014', '048326/2014',
                '048324/2014', '006172/2014', '006167/2014', '006166/2014', '006163/2014', '006162/2014', '006161/2014', '006160/2014', '056599/2013', '056595/2013', '056593/2013', '056591/2013', '056589/2013', '051279/2013',
                '056583/2013', '056579/2013', '056577/2013', '056574/2013', '056572/2013', '056570/2013', '056569/2013', '056567/2013', '056564/2013', '056144/2013', '056005/2013', '056004/2013', '056001/2013', '055999/2013',
                '055998/2013', '055997/2013', '055996/2013', '055995/2013', '055994/2013', '055993/2013', '055992/2013', '055991/2013', '055990/2013', '055989/2013', '055988/2013', '055987/2013', '055981/2013', '055980/2013',
                '055969/2013', '055968/2013', '055967/2013', '055966/2013', '055770/2013', '055764/2013', '060633/2013', '060632/2013', '060630/2013', '060629/2013', '060616/2013', '059723/2013', '059722/2013', '059721/2013',
                '059720/2013', '059719/2013', '059718/2013', '059717/2013', '059716/2013', '059714/2013', '059713/2013', '059657/2013', '059656/2013', '059654/2013', '059652/2013', '057500/2013', '057499/2013', '057498/2013',
                '057496/2013', '057486/2013', '057459/2013', '057454/2013', '057449/2013', '057378/2013', '063506/2013', '088753/2013', '041873/2014', '028396/2012', '028296/2012', '064536/2011', '044658/2014', '044657/2014',
                '044656/2014', '044337/2014', '044336/2014', '044335/2014', '044334/2014', '044333/2014', '044331/2014', '044327/2014', '044326/2014', '044325/2014', '044321/2014', '044318/2014', '044305/2014', '044303/2014',
                '044196/2014', '044194/2014', '044193/2014', '044189/2014', '044186/2014', '044183/2014', '044179/2014', '044178/2014', '044166/2014', '044123/2014', '039519/2014', '039517/2014', '039459/2014', '065058/2013',
                '064360/2013', '064359/2013', '064358/2013', '062226/2013', '062224/2013', '062223/2013', '148477/2009', '148211/2009', '148036/2009', '083414/2008', '083055/2008', '062592/2013', '062591/2013', '062590/2013',
                '062589/2013', '062229/2013', '062225/2013', '052457/2014', '052056/2013', '052054/2013', '052046/2013', '052043/2013', '052040/2013', '052025/2013', '052020/2013', '052008/2013', '052002/2013', '051998/2013',
                '051991/2013', '051984/2013', '051978/2013', '051973/2013', '051972/2013', '051968/2013', '051964/2013', '051942/2013', '051917/2013', '051915/2013', '051914/2013', '051913/2013', '051912/2013', '051911/2013',
                '051909/2013', '051908/2013', '051907/2013', '051903/2013', '051902/2013', '051900/2013', '051897/2013', '051896/2013', '051894/2013', '051893/2013', '051892/2013', '051890/2013', '051889/2013', '051888/2013',
                '051886/2013', '051885/2013', '051884/2013', '051883/2013', '051882/2013', '051881/2013', '051876/2013', '051182/2013', '051176/2013', '051174/2013', '046849/2013', '051421/2013', '051412/2013', '051278/2013',
                '051874/2013', '051873/2013', '051868/2013', '051866/2013', '051865/2013', '051864/2013', '051862/2013', '051861/2013', '051860/2013', '051859/2013', '051858/2013', '051857/2013', '051856/2013', '051189/2013',
                '051851/2013', '051849/2013', '051846/2013', '051830/2013', '051797/2013', '051687/2013', '051684/2013', '051682/2013', '051672/2013', '051670/2013', '051627/2013', '051622/2013', '051618/2013', '051244/2013',
                '051535/2013', '051455/2013', '051454/2013', '051453/2013', '051452/2013', '051451/2013', '051450/2013', '051448/2013', '051447/2013', '051446/2013', '051445/2013', '051444/2013', '051443/2013', '051253/2013',
                '051442/2013', '051441/2013', '051440/2013', '051439/2013', '051438/2013', '051436/2013', '051434/2013', '051433/2013', '051432/2013', '051431/2013', '051428/2013', '051425/2013', '051424/2013', '051257/2013',
                '051424/2013', '051424/2013', '051411/2013', '051410/2013', '051409/2013', '051408/2013', '051401/2013', '051400/2013', '051399/2013', '051398/2013', '051397/2013', '051396/2013', '051395/2013', '051258/2013',
                '051393/2013', '051384/2013', '051383/2013', '051382/2013', '051380/2013', '051373/2013', '051369/2013', '051368/2013', '051365/2013', '051360/2013', '051356/2013', '051355/2013', '051323/2013', '051197/2013',
                '051319/2013', '051317/2013', '051316/2013', '051315/2013', '051291/2013', '051288/2013', '051288/2013', '051288/2013', '051275/2013', '051274/2013', '051268/2013', '051267/2013', '051265/2013', '051190/2013',
                '051232/2013', '051227/2013', '051213/2013', '051211/2013', '051200/2013', '051195/2013'));

            if (isset($filtros['status_prop']) && $filtros['status_prop'] != "") {
                switch ($filtros['status_prop']) {
                    case 1:
                        $this->db->where_in('situacao', $this->retorna_status_cadastrado());
                        break;
                    case 2:
                        $this->db->where_in('situacao', $this->retorna_status_enviado());
                        break;
                    case 3:
                        $this->db->where_in('situacao', $this->retorna_status_aprovado());
                        break;
                }
            }

            $this->db->where_not_in('UPPER(situacao)', array(strtoupper('Proposta/Plano de Trabalho em rascunho'), strtoupper('HistÓrico')));

            $this->db->order_by('ano', 'DESC');
            $this->db->order_by('codigo_siconv', 'DESC');
            $query = $this->db->get('banco_proposta')->result();

            return $query;
        }

        return null;
    }

    public function get_anos_by_usuario() {
        $this->db->distinct();
        $this->db->select('ano');
        $this->db->where('ano IS NOT NULL');
        $this->db->order_by('ano', 'DESC');
        $query = $this->db->get('banco_proposta')->result();

        return $query;
    }

    public function get_parecer_proposta($idProposta, $idParecer) {
        $this->db->where('id_proposta', $idProposta);
        $this->db->where('id_parecer', $idParecer);
        return $this->db->get('parecer_proposta_banco_proposta')->row(0);
    }

    public function get_parecer_plano_trabalho($idProposta, $idParecer) {
        $this->db->where('id_proposta', $idProposta);
        $this->db->where('id_parecer', $idParecer);
        return $this->db->get('parecer_plano_trabalho_banco_proposta')->row(0);
    }

    public function get_parecer_ajuste_plano_trabalho($idProposta, $idParecer) {
        $this->db->where('id_proposta', $idProposta);
        $this->db->where('id_parecer', $idParecer);
        return $this->db->get('parecer_ajuste_pl_trabalho_banco_proposta')->row(0);
    }

    public function get_all_parecer_proposta($idProposta) {
        $this->db->where('id_proposta', $idProposta);
        return $this->db->get('parecer_proposta_banco_proposta')->result();
    }

    public function get_all_parecer_plano_trabalho($idProposta) {
        $this->db->where('id_proposta', $idProposta);
        return $this->db->get('parecer_plano_trabalho_banco_proposta')->result();
    }

    public function get_all_parecer_ajuste_plano_trabalho($idProposta) {
        $this->db->where('id_proposta', $idProposta);
        return $this->db->get('parecer_ajuste_pl_trabalho_banco_proposta')->result();
    }

    public function verifica_proposta_aprovada($situacao) {
        return in_array(strtoupper($situacao), $this->retorna_status_aprovado());
    }

    public function verifica_proposta_enviada($situacao) {
        return in_array(strtoupper($situacao), $this->retorna_status_enviado());
    }

    public function verifica_proposta_cadastrado($situacao) {
        return in_array(strtoupper($situacao), $this->retorna_status_cadastrado());
    }

    public function retorna_status_aprovado() {
        return array(
            strtoupper('Em execução'),
            strtoupper('Proposta/Plano de Trabalho Aprovados'),
            strtoupper('Prestação de Contas Aprovada'),
            strtoupper('Aguardando Prestação de Contas'),
            strtoupper('Prestação de Contas enviada para Análise'),
            strtoupper('Prestação de Contas em Análise'),
            strtoupper('Prestação de Contas em Complementação'),
            strtoupper('Proposta Aprovada e Plano de Trabalho em Análise'),
            strtoupper('Prestação de Contas Aprovada com Ressalvas'),
            strtoupper('Proposta Aprovada e Plano de Trabalho em Complementação'),
            strtoupper('Prestação de Contas Iniciada por Antecipação'),
            strtoupper('Proposta Aprovada e Plano de Trabalho Complementado em Análise'),
            strtoupper('Proposta Aprovada e Plano de Trabalho Complementado enviado para Análise'),
            strtoupper('Assinado'),
            strtoupper('Proposta/Plano de Trabalho Aprovados (Cronogramas pendente de Ajustes)'),
            strtoupper('Em execução (Cronogramas pendente de Ajustes)'),
            strtoupper('Assinatura Pendente Registro TV Siafi'),
            strtoupper('Proposta Aprovada/Contratação Prejudicada'),
            strtoupper('Prestação de Contas Iniciada por Antecipação')
        );
    }

    public function retorna_status_enviado() {
        return array(
            strtoupper('Proposta/Plano de Trabalho enviado para Análise'),
            strtoupper('Proposta/Plano de Trabalho complementado enviada para Análise'),
            strtoupper('Proposta/Plano de Trabalho em Análise'),
            strtoupper('Proposta/Plano de Trabalho Recebidos com pendência'),
            strtoupper('Proposta/Plano de Trabalho em Análise'),
            strtoupper('Proposta/Plano de Trabalho em Complementação'),
            strtoupper('Proposta/Plano de Trabalho complementado enviada para Análise'),
            strtoupper('Proposta/Plano de Trabalho complementado em Análise'),
            strtoupper('Proposta/Plano de Trabalho Rejeitados'),
            strtoupper('INADIMPLENTE'),
            strtoupper('Convênio Anulado'),
            strtoupper('Convênio Rescindido'),
            strtoupper('Convênio Extinto'),
            strtoupper('Proposta/Plano de Trabalho Rejeitados por Impedimento técnico'),
            strtoupper('Concluído no SIAFi'),
            strtoupper('Adimplente no SIAFi'),
        );
    }

    public function retorna_status_cadastrado() {
        return array(
            strtoupper('Proposta/Plano de Trabalho Cadastrados'),
            strtoupper('Proposta/Plano de Trabalho Cancelados'),
            strtoupper('Cancelado')
        );
    }

    public function atualiza_data_visualizado($tabela, $options, $where) {
        $this->db->where($where);
        $this->db->update($tabela, $options);
    }

    public function get_propostas_by_emenda($emenda, $codigo_programa) {
        $this->db->where('codigo_emenda', $emenda);
        $this->db->where('banco_proposta.codigo_programa', $codigo_programa);
        $this->db->join('programa_banco_proposta p', 'id_proposta = id_proposta_banco_proposta');
        $this->db->join('emenda_banco_proposta e', 'p.id_programa_banco_proposta = e.id_programa_banco_proposta');

        $this->db->where_not_in('UPPER(situacao)', array(strtoupper('Proposta/Plano de Trabalho em rascunho'), strtoupper('HistÓrico')));

        $this->db->order_by('nome_proponente', 'ASC');
        $this->db->order_by('codigo_siconv', 'DESC');
        $query = $this->db->get('banco_proposta');

        return $query->result();
    }
    
    public function atualiza_parecer_proposta($id_proposta, $options){
    	$this->db->where('id_proposta', $id_proposta);
    	$this->db->update('banco_proposta', $options);
    }

}
