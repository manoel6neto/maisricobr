<?php

class get_programas_model extends CI_Model {

    public function limpar_excluidos($ano) {
        try {
            //Programas
            $this->db->where('ano', $ano);
            $this->db->update('siconv_programa', array('excluido' => 0));

            //Beneficiarios
            $this->db->where("year(data_fim_benef) = {$ano}");
            $this->db->or_where("year(data_fim_parlam) = {$ano}");
            $this->db->update('siconv_beneficiario', array('excluido' => 0));
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas_model/insert_or_update", "17", $ex->getMessage());
        }
    }

    public function insert_or_update($options) {
        try {
            $this->db->where('codigo', $options['codigo']);
            $query = $this->db->get('siconv_programa');

            $options['excluido'] = 0;
            if (count($query->result()) > 0) {
                $temAtualizacao = false;
                if ($options['data_fim'] != $query->row(0)->data_fim) {
                    $temAtualizacao = true;
                } else if ($options['data_fim_benef'] != $query->row(0)->data_fim_benef) {
                    $temAtualizacao = true;
                } else if ($options['data_fim_parlam'] != $query->row(0)->data_fim_parlam) {
                    $temAtualizacao = true;
                } else if ($options['data_disp'] != $query->row(0)->data_disp) {
                    $temAtualizacao = true;
                } else if ($options['data_inicio'] != $query->row(0)->data_inicio) {
                    $temAtualizacao = true;
                } else if ($options['data_inicio_parlam'] != $query->row(0)->data_inicio_parlam) {
                    $temAtualizacao = true;
                } else if ($options['data_inicio_benef'] != $query->row(0)->data_inicio_benef) {
                    $temAtualizacao = true;
                }

                $options['programa_novo'] = false;
                $options['tem_atualizacao'] = $temAtualizacao;

                $this->db->where('codigo', $options['codigo']);
                $this->db->update('siconv_programa', $options);
            } else {
                $options['programa_novo'] = true;
                $options['tem_atualizacao'] = false;
                $this->db->insert('siconv_programa', $options);
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas_model/insert_or_update", "17", $ex->getMessage());
        }
    }

    public function insert_anexos($options) {
        try {
            $this->db->select("link");
            $this->db->where('id_programa', $options['id_programa']);
            $query = $this->db->get('anexos')->result_array();

            if (count($query) > 0) {
                foreach ($query as $value) {
                    $antigo[] = $value['link'];
                }


                foreach ($options['link'] as $key => $value) {
                    if (in_array($value, $antigo) === FALSE) {
                        $this->db->insert('anexos', array("id_programa" => $options['id_programa'], "nome_arquivo" => utf8_encode($value), "data_anexo" => $options['data_anexo'][$key], "descricao" => utf8_encode($options['descricao'][$key]), "link" => $options['link'][$key]));
                    }
                }

                foreach ($antigo as $key => $value) {
                    if (!in_array($value, $options)) {
                        $this->db->delete("anexos", array("id_programa" => $options['id_programa'], "nome_arquivo" => $value));
                    }
                }
            } else {
                foreach ($options['nome_arquivo'] as $key => $value) {
                    $this->db->insert('anexos', array("id_programa" => $options['id_programa'], "nome_arquivo" => utf8_encode($value), "data_anexo" => $options['data_anexo'][$key], "descricao" => utf8_encode($options['descricao'][$key]), "link" => $options['link'][$key]));
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas_model/insert_or_update", "17", $ex->getMessage());
        }
    }

    public function insert_editais($options) {
        try {
            $this->db->select("link");
            $this->db->where('id_programa', $options['id_programa']);
            $query = $this->db->get('editais')->result_array();

            if (count($query) > 0) {
                foreach ($query as $value) {
                    $antigo[] = $value['link'];
                }


                foreach ($options['link'] as $key => $value) {
                    if (in_array($value, $antigo) === FALSE) {
                        $this->db->insert('editais', array("id_programa" => $options['id_programa'], "nome_arquivo" => utf8_encode($value), "link" => $options['link'][$key]));
                    }
                }

                foreach ($antigo as $key => $value) {
                    if (!in_array($value, $options)) {
                        $this->db->delete("editais", array("id_programa" => $options['id_programa'], "nome_arquivo" => $value));
                    }
                }
            } else {
                foreach ($options['nome_arquivo'] as $key => $value) {
                    $this->db->insert('editais', array("id_programa" => $options['id_programa'], "nome_arquivo" => utf8_encode($value), "link" => $options['link'][$key]));
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas_model/insert_or_update", "17", $ex->getMessage());
        }
    }

    public function insert_or_update_benef($options, $ehParlamentar = false) {
        try {
            foreach ($options as $op) {
                $this->db->where('codigo_programa', $op['codigo_programa']);
                $this->db->where('cnpj', $op['cnpj']);

                $options['excluido'] = 0;

                if (!$ehParlamentar) {
                    $this->db->where('data_inicio_parlam is null', null);
                    $this->db->where('emenda', '');
                } else {
                    $this->db->where('emenda', $op['emenda']);
                    $this->db->where('data_inicio_benef is null', null);
                }

                $query = $this->db->get('siconv_beneficiario');

                if (count($query->result()) > 0) {
                    $this->db->where('codigo_programa', $op['codigo_programa']);
                    $this->db->where('cnpj', $op['cnpj']);

                    if (!$ehParlamentar) {
                        $this->db->where('data_inicio_parlam is null', null);
                        $this->db->where('emenda', '');
                    } else {
                        $this->db->where('emenda', $op['emenda']);
                        $this->db->where('data_inicio_benef is null', null);
                    }

                    $this->db->update('siconv_beneficiario', $op);
                } else {
                    $this->db->insert('siconv_beneficiario', $op);
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas_model/insert_or_update", "17", $ex->getMessage());
        }
    }

    public function update_excluido($codes) {
        try {
            $this->db->where_in('codigo', $codes);
            $this->db->update('siconv_programa', array('excluido' => 1));
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas_model/insert_or_update", "17", $ex->getMessage());
        }
    }

}

?>