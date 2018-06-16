<?php

class proponente_siconv_model extends CI_Model {

    public function getListaEstados() {
        $lista_estados = array(
            "AC" => "AC",
            "AL" => "AL",
            "AM" => "AM",
            "AP" => "AP",
            "BA" => "BA",
            "CE" => "CE",
            "DF" => "DF",
            "ES" => "ES",
            "GO" => "GO",
            "MA" => "MA",
            "MG" => "MG",
            "MS" => "MS",
            "MT" => "MT",
            "PA" => "PA",
            "PB" => "PB",
            "PE" => "PE",
            "PI" => "PI",
            "PR" => "PR",
            "RJ" => "RJ",
            "RN" => "RN",
            "RO" => "RO",
            "RR" => "RR",
            "RS" => "RS",
            "SC" => "SC",
            "SE" => "SE",
            "SP" => "SP",
            "TO" => "TO"
        );

        $estados_bloquear = array();
        if ($this->session->userdata('nivel') == 4) {
            $this->load->model('estados_direito_vendedor_model');

            $estados_bloquear = $this->estados_direito_vendedor_model->get_lista_estados_bloqueados($this->session->userdata('id_usuario'));

            foreach ($lista_estados as $e) {
                if (!in_array($e, $estados_bloquear))
                    unset($lista_estados[$e]);
            }
        }

        if ($this->session->userdata('nivel') == 2) {
            $this->load->model('usuariomodel');
            $estados = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

            $lista_estados_gestor = array();
            foreach ($estados as $es) {
                if (!in_array($es->sigla, $lista_estados_gestor))
                    $lista_estados_gestor[] = $es->sigla;
            }

            foreach ($lista_estados as $e) {
                if (!in_array($e, $lista_estados_gestor))
                    unset($lista_estados[$e]);
            }
        }

        return $lista_estados;
    }

    public function getListaEstadosBuscaProponente() {
        $lista_estados = array(
            "Selecione" => "Selecione",
            "AC" => "AC",
            "AL" => "AL",
            "AM" => "AM",
            "AP" => "AP",
            "BA" => "BA",
            "CE" => "CE",
            "DF" => "DF",
            "ES" => "ES",
            "GO" => "GO",
            "MA" => "MA",
            "MG" => "MG",
            "MS" => "MS",
            "MT" => "MT",
            "PA" => "PA",
            "PB" => "PB",
            "PE" => "PE",
            "PI" => "PI",
            "PR" => "PR",
            "RJ" => "RJ",
            "RN" => "RN",
            "RO" => "RO",
            "RR" => "RR",
            "RS" => "RS",
            "SC" => "SC",
            "SE" => "SE",
            "SP" => "SP",
            "TO" => "TO"
        );

        return $lista_estados;
    }

    public function getListaEsferasVendasDiretas() {

        return array("MUNICIPAL" => "MUNICIPAL",
            "PRIVADA" => "PRIVADA",
            "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA" => "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA",
            "CONSORCIO PUBLICO" => "CONSORCIO PUBLICO"
        );
    }

    public function getListaEsferasBuscaProponente() {

        return array("TODAS" => "TODAS",
            "MUNICIPAL" => "MUNICIPAL",
            "ESTADUAL" => "ESTADUAL",
            "PRIVADA" => "PRIVADA",
            "FEDERAL" => "FEDERAL",
            "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA" => "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA",
            "CONSORCIO PUBLICO" => "CONSORCIO PUBLICO",
            "ORGANISMO INTERNACIONAL" => "ORGANISMO INTERNACIONAL"
        );
    }

    public function getListaEsferas() {
        $this->db->distinct();
        $this->db->select('esfera_administrativa');
        $esferas = $this->db->get('proponente_siconv')->result();

        $listaEsferas = array();
        foreach ($esferas as $esfera)
            $listaEsferas[$esfera->esfera_administrativa] = $esfera->esfera_administrativa;

// 		$esferas_bloquear = array();
// 		if($this->session->userdata('nivel') == 4){
// 			$this->load->model('esfadm_direito_vendedor_model');
// 			$esferas_bloquear = $this->esfadm_direito_vendedor_model->get_lista_esferas_bloqueadas($this->session->userdata('id_usuario'));
// 			foreach ($listaEsferas as $e){
// 				if(!in_array($e, $esferas_bloquear))
// 					unset($listaEsferas[$e]);
// 			}
// 		}

        switch ($this->session->userdata('sistema')) {
            case "M":
                return array("MUNICIPAL" => "MUNICIPAL",
                    "PRIVADA" => "PRIVADA",
                    "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA" => "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA",
                    "CONSORCIO PUBLICO" => "CONSORCIO PUBLICO"
                );
                break;
            case "E":
                return array(
                    "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA" => "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA",
                    "ESTADUAL" => "ESTADUAL"
                );
                break;
            default:
                unset($listaEsferas['MUNICIPAL']);
                return $listaEsferas;
                break;
        }
    }

    public function get_municipio($uf) {
        $this->db->_protect_identifiers = false;

        if ($this->session->userdata('nivel') == 2) {
            $this->load->model('usuariomodel');
            $estados = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

            $this->db->flush_cache();

            $lista_municipio_gestor = array();
            foreach ($estados as $es) {
                if (!in_array($es->id_cidade, $lista_municipio_gestor))
                    $lista_municipio_gestor[] = $es->id_cidade;
            }

            $this->db->where_in('codigo_municipio', $lista_municipio_gestor);
        }

        $this->db->distinct();
        $this->db->select("codigo_municipio, municipio");
        $this->db->where('municipio_uf_sigla', $uf);
        $this->db->order_by("municipio", "ASC");

// 		if($this->session->userdata('nivel') == 4)
// 			$this->db->join('proponente_direito_vendedor', "proponente = cnpj and id_vendedor = {$this->session->userdata('id_usuario')}");

        return $this->db->get('proponente_siconv')->result();
    }

    public function get_proponentes($esfera, $municipio, $uf, $tipo = "", $id_usuario = 0, $vinculaParaSubgestor = false) {
        $this->load->model('usuariomodel');
        $this->load->model('programa_model');

        if ($tipo == "GESTOR" && $id_usuario > 0) {
            $cnpjs = $this->usuariomodel->get_cnpjs_gestor_by_usuario($id_usuario);
            $listaCNPJs = array();
            $listaCNPJsSub = array();
            foreach ($cnpjs as $cnpj) {
                $listaCNPJs[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);

                if ($id_usuario == $cnpj->id)
                    $listaCNPJsSub[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);
            }

            if (!empty($listaCNPJs)) {
                if (!$vinculaParaSubgestor)
                    $this->db->where_not_in('cnpj', $listaCNPJs);
                else {
                    $this->db->where_in('cnpj', $listaCNPJs);
                    if (!empty($listaCNPJsSub))
                        $this->db->where_not_in('cnpj', $listaCNPJsSub);
                }
            }
        }

        $this->db->select("cnpj, nome");

        if ($this->session->userdata('sistema') != "E") {
            $this->db->where("codigo_municipio", $municipio);
        }

        $this->db->where("municipio_uf_sigla", $uf);

        if ($esfera != "") {
            $this->db->where_in('esfera_administrativa', $esfera);

            $this->db->order_by("nome", "ASC");
            $query = $this->db->get('proponente_siconv')->result();
            //echo $this->db->last_query();
            return $query;
        } else
            return array();
    }

    public function get_all_cnpj_estado($estado, $esfera = null) {
        $this->db->distinct();
        $this->db->select('cnpj');
        if ($esfera != null)
            $this->db->where('esfera_administrativa', $esfera);
        $this->db->where('municipio_uf_sigla', $estado);
        $query = $this->db->get('proponente_siconv')->result();

        return $query;
    }

    public function get_instituicao_nome($cnpj, $formata = false) {
        if ($formata) {
            $this->load->model('programa_model');
            $this->db->where('cnpj', $this->programa_model->formatCPFCNPJ($cnpj));
        } else
            $this->db->where('cnpj', $cnpj);
        return $this->db->get('proponente_siconv')->row(0)->nome;
    }
    
    public function get_instituicao($cnpj, $formata = false) {
        if ($formata) {
            $this->load->model('programa_model');
            $this->db->where('cnpj', $this->programa_model->formatCPFCNPJ($cnpj));
        } else
            $this->db->where('cnpj', $cnpj);
        return $this->db->get('proponente_siconv')->row(0);
    }

    public function get_instituicao_esfera($cnpj, $retornaOBJCompleto = false) {
        $this->db->where('cnpj', $cnpj);
        if (!$retornaOBJCompleto)
            return $this->db->get('proponente_siconv')->row(0)->esfera_administrativa;
        else
            return $this->db->get('proponente_siconv')->row(0);
    }

    public function get_municipio_nome($id_cidade) {
        $this->db->distinct();
        $this->db->select("municipio, municipio_uf_sigla");
        $this->db->where('codigo_municipio', $id_cidade);

        return $this->db->get('proponente_siconv')->row(0);
    }

    public function get_municipio_by_cnpj($cnpj, $formataCnpj = true, $filtro = null, $montaDropDown = false) {
        $this->load->model('programa_model');

        if (!is_null($filtro)) {
            if ($this->session->userdata('nivel') == 4) {
                $this->db->where('municipio_uf_sigla', $this->session->userdata('estado_parlamentar'));
            } else if ($this->session->userdata('nivel') == 2) {
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                $query = $this->db->get('gestor')->row(0);

                $this->db->flush_cache();

                $this->db->where('municipio_uf_sigla', $query->estado_parlamentar);
            }
        }

        $this->db->distinct();
        $this->db->select("codigo_municipio, municipio, cnpj, esfera_administrativa");

        if ($formataCnpj)
            $this->db->where('cnpj', $this->programa_model->formatCPFCNPJ($cnpj));
        else
            $this->db->where('cnpj', $cnpj);

        if (!$montaDropDown) {
            if (!is_null($filtro)) {
                if (isset($filtro['proponente']))
                    $this->db->where_in('codigo_municipio', $filtro['proponente']);

                if (isset($filtro['esfera']))
                    $this->db->where_in('esfera_administrativa', $filtro['esfera']);
            }
        }

        $query = $this->db->get('proponente_siconv')->row(0);

        return $query;
    }

    public function get_estado_sigla($id_cidade) {
        $this->db->distinct();
        $this->db->select("municipio_uf_nome");
        $this->db->where('codigo_municipio', $id_cidade);

        return $this->db->get('proponente_siconv')->row(0);
    }

    public function get_entidades($filtro) {
        $this->db->like('nome', $filtro);
        $this->db->order_by('municipio_uf_nome, municipio');
        return $this->db->get('proponente_siconv')->result();
    }

    public function get_entidades_multi_filtro($nome, $esfera, $municipio, $estado, $situacao = null) {
        if ($estado != null && $estado != false) {
            if ($estado != 'Selecione') {
                $this->db->where('municipio_uf_sigla', $estado);
            }
        }

        if ($municipio != null && $municipio != false) {
            if ($municipio != '') {
                $this->db->where('codigo_municipio', $municipio);
            }
        }

        if ($esfera != null && $esfera != false) {
            if ($esfera != 'TODAS') {
                $this->db->where('esfera_administrativa', $esfera);
            }
        }

        if ($nome != null && $nome != false) {
            $this->db->like('nome', $nome);
        }

        if ($situacao != null) {
            if ($situacao == "aprovadas") {
                $this->db->where('situacao_aprovacao IS NOT NULL', null, false);
            } else if ($situacao == "reprovadas") {
                $this->db->where(array('situacao_aprovacao' => null));
            }
        }

        $this->db->order_by('municipio_uf_nome, municipio');
        return $this->db->get('proponente_siconv')->result();
    }

    public function get_entidades_by_id($filtro) {
        $this->db->where_in('id_proponente_siconv', $filtro);
        $this->db->order_by('municipio_uf_nome, municipio');
        return $this->db->get('proponente_siconv')->result();
    }

    public function busca_situacao_apta($cnpj) {
        $this->db->where('cnpj', $cnpj);
        return $this->db->get('situacao_proponente_siconv')->row(0);
    }

    public function busca_nome_proponente($cnpj) {
        $this->load->model('programa_model');
        $cnpj = $this->programa_model->formatCPFCNPJ($cnpj);

        $this->db->where('cnpj', $cnpj);
        $query = $this->db->get('proponente_siconv');

        if ($query->num_rows > 0) {
            $array_nome = $query->result();
            $obj_nome = $array_nome[0];
            return $obj_nome->nome;
        }

        return null;
    }

    public function get_codigo_municipio($municipio, $estado, $sigla) {
        $this->db->where('municipio', $municipio);
        $this->db->where('municipio_uf_nome', $estado);
        $this->db->where('municipio_uf_sigla', $sigla);

        $query = $this->db->get('MV_CODIGOS_MUNICIPIOS');
        if ($query->num_rows > 0) {
            $result = $query->result();
            $codigo = $result[0]->codigo_municipio;

            return $codigo;
        }

        return "0";
    }

}
