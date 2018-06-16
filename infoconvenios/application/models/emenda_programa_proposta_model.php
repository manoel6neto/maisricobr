<?php

class emenda_programa_proposta_model extends CI_Model {

    //Insert
    public function insert_emenda_programa_proposta($options) {
        $this->db->insert_batch('emenda_programa_proposta', $options);
    }

    //Retorna as emendas de um programa proposta - utilizadas
    public function get_all_emendas_from_programa_proposta($id_programa_proposta) {
        $this->db->where('id_programa_proposta', $id_programa_proposta);
        $query = $this->db->get('emenda_programa_proposta');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    //Retorna as emendas de um programa proposta - utilizadas
    public function get_all_emendas_from_proposta($id_proposta) {
        $this->db->join('programa_proposta pp', 'pp.id_programa_proposta = epp.id_programa_proposta');
        $this->db->where('pp.id_proposta', $id_proposta);
        $query = $this->db->get('emenda_programa_proposta epp');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    //Retorna o valor total usado das emendas ligadas a programa proposta 
    public function get_valor_utilizado_emendas_from_programa_proposta($id_programa_proposta) {
        $this->db->select('valor_utilizado');
        $this->db->where('id_programa_proposta', $id_programa_proposta);
        $query = $this->db->get('emenda_programa_proposta');

        $total = 0;
        if ($query != null) {
            foreach ($query->result() as $emenda) {
                $total = $total + $emenda->valor_utilizado;
            }
        }

        return $total;
    }

    //Retorna o total ja utilizado de uma emenda especifica para um programa em uma beneficiario
    public function get_valor_utilizado_emenda_from_emenda_programa_beneficiario($programa, $emenda, $beneficiario, $id_usuario) {
        $this->load->model('proposta_model');
        $this->load->model('programa_proposta_model');

        $this->db->select('emenda, cnpj');
        $this->db->where('codigo_programa', $programa);
        $this->db->where('emenda', $emenda);
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $beneficiario");
        $query = $this->db->get('siconv_beneficiario');

        if ($query->num_rows > 0) {
            $propostas = array();
            if ($this->proposta_model->get_all_ativo_enviadas($id_usuario, null)) {
                $propostas = $this->proposta_model->get_all_ativo_enviadas($id_usuario, null);
            }
            if ($this->proposta_model->get_all_ativo_cadastradas($id_usuario, null)) {
                $propostas = array_merge($propostas, $this->proposta_model->get_all_ativo_cadastradas($id_usuario, null));
            }

            if (count($propostas) > 0) {
                $array_programas_usados_propostas = array();
                foreach ($propostas as $prop) {
                    if ($this->programa_proposta_model->get_programas_by_proposta($prop->idProposta)) {
                        $array_programas_usados_propostas = array_merge($array_programas_usados_propostas, $this->programa_proposta_model->get_programas_by_proposta($prop->idProposta));
                    }
                }

                if (count($array_programas_usados_propostas) > 0) {
                    $array_emendas_utilizadas_programas_propostas = array();
                    foreach ($array_programas_usados_propostas as $prog) {
                        if ($this->get_all_emendas_from_programa_proposta($prog->id_programa_proposta)) {
                            $array_emendas_utilizadas_programas_propostas = array_merge($array_emendas_utilizadas_programas_propostas, $this->get_all_emendas_from_programa_proposta($prog->id_programa_proposta));
                        }
                    }

                    if (count($array_emendas_utilizadas_programas_propostas) > 0) {
                        $valor_gasto_emenda = 0;
                        foreach ($array_emendas_utilizadas_programas_propostas as $emenda_utilizada) {
                            if ($emenda_utilizada->numero_emenda == $emenda) {
                                $valor_gasto_emenda = $valor_gasto_emenda + $emenda_utilizada->valor_utilizado;
                            }
                        }

                        return doubleval($valor_gasto_emenda);
                    }
                }
            }
        }

        return doubleval(0);
    }

    //Retorna as emendas disponíveis para cada programa e beneficiario
    public function get_emendas_disponiveis_from_programa_beneficiario($programa, $beneficiario) {
        $this->db->select('emenda, valor, parlamentar');
        $this->db->where('codigo_programa', $programa);
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $beneficiario");
        $query = $this->db->get('siconv_beneficiario');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    //Retorna quanto ainda há disponivel para utilizar de uma emenda pelo numero da emenda e beneficiario
    public function get_valor_disponivel_emenda_from_emenda_beneficiario($programa, $emenda, $beneficiario, $id_usuario) {
        $this->load->model('proposta_model');
        $this->load->model('programa_proposta_model');
        $this->load->model('banco_proposta_model');
        $this->load->model('programa_banco_proposta_model');
        $this->load->model('emenda_banco_proposta_model');

        $this->db->select('valor, emenda, cnpj');
        $this->db->where('codigo_programa', $programa);
        $this->db->where('emenda', $emenda);
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $beneficiario");
        $query = $this->db->get('siconv_beneficiario');

        if ($query->num_rows > 0) {
            $valor_total = $query->result();
            $valor_total = trim(str_replace("R$", "", $valor_total[0]->valor));
            $valor_total = trim(str_replace(".", "", $valor_total));
            $valor_total = trim(str_replace(",", ".", $valor_total));
            $valor_total = doubleval($valor_total);

            if ($valor_total != null && $valor_total > 0) {

                //propostas cadastradas no esicar
                $propostas = array();
                if (count($this->proposta_model->get_all_ativo_enviadas($id_usuario, null)) > 0) {
                    $propostas = $this->proposta_model->get_all_ativo_enviadas($id_usuario, null);
                }

                if (count($this->proposta_model->get_all_ativo_cadastradas($id_usuario, null)) > 0) {
                    $propostas = array_merge($propostas, $this->proposta_model->get_all_ativo_cadastradas($id_usuario, null));
                }

                if (count($propostas) > 0) {
                    $array_programas_usados_propostas = array();
                    foreach ($propostas as $prop) {
                        if (count($this->programa_proposta_model->get_programas_by_proposta($prop->idProposta)) > 0) {
                            $array_programas_usados_propostas = array_merge($array_programas_usados_propostas, $this->programa_proposta_model->get_programas_by_proposta($prop->idProposta));
                        }
                    }

                    if (count($array_programas_usados_propostas) > 0) {
                        $array_emendas_utilizadas_programas_propostas = array();
                        foreach ($array_programas_usados_propostas as $prog) {
                            if (count($this->get_all_emendas_from_programa_proposta($prog->id_programa_proposta)) > 0) {
                                $array_emendas_utilizadas_programas_propostas = array_merge($array_emendas_utilizadas_programas_propostas, $this->get_all_emendas_from_programa_proposta($prog->id_programa_proposta));
                            }
                        }

                        if (count($array_emendas_utilizadas_programas_propostas) > 0) {
                            $valor_gasto_emenda = 0;
                            foreach ($array_emendas_utilizadas_programas_propostas as $emenda_utilizada) {
                                if ($emenda_utilizada->numero_emenda == $emenda) {
                                    $valor_gasto_emenda = $valor_gasto_emenda + $emenda_utilizada->valor_utilizado;
                                }
                            }

                            $valor_total = doubleval($valor_total - $valor_gasto_emenda);
                        }
                    }
                }

                //propostas no banco de propostas
                $valor_gasto_emenda_banco_propostas = doubleval(0);
                $propostas_banco_propostas = $this->banco_proposta_model->get_propostas_by_proponentes(array($beneficiario));
                if (count($propostas_banco_propostas) > 0) {
                    $array_programas_banco_proposta = array();
                    foreach ($propostas_banco_propostas as $prop_banco) {
                        if (count($this->programa_banco_proposta_model->get_by_id_proposta($prop_banco->id_proposta)) > 0) {
                            $array_programas_banco_proposta = array_merge($array_programas_banco_proposta, $this->programa_banco_proposta_model->get_by_id_proposta($prop_banco->id_proposta));
                        }
                    }

                    if (count($array_programas_banco_proposta) > 0) {
                        $array_emendas_banco_proposta = array();
                        foreach ($array_programas_banco_proposta as $prog_banco) {
                            if (count($this->emenda_banco_proposta_model->get_by_id_programa($prog_banco->id_programa_banco_proposta)) > 0) {
                                $array_emendas_banco_proposta = array_merge($array_emendas_banco_proposta, $this->emenda_banco_proposta_model->get_by_id_programa($prog_banco->id_programa_banco_proposta));
                            }
                        }

                        if (count($array_emendas_banco_proposta) > 0) {
                            foreach ($array_emendas_banco_proposta as $eme_banco) {
                                if ($eme_banco->codigo_emenda == $emenda) {
                                    $valor_eme = trim($eme_banco->valor_emenda);
                                    $temp_double = trim(str_replace("R$", "", $valor_eme));
                                    $temp_double = trim(str_replace(".", "", $temp_double));
                                    $temp_double = trim(str_replace(",", ".", $temp_double));
                                    $valor_eme = number_format(doubleval($temp_double), 2, '.', "");
                                    $valor_eme = doubleval($valor_eme);
                                    $valor_gasto_emenda_banco_propostas = $valor_gasto_emenda_banco_propostas + $valor_eme;
                                }
                            }
                        }
                    }
                    
                    $valor_total = doubleval($valor_total - $valor_gasto_emenda_banco_propostas);
                }
            }

            return $valor_total;
        } else {
            return doubleval(0);
        }
    }

}
