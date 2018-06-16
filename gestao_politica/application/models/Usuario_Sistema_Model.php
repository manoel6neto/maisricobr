<?php

class Usuario_Sistema_Model extends CI_Model {

    public function get_usuarios_sistema() {
        $query = $this->db->get('usuario_sistema');
        return $query->result();
    }

    public function get_usuario_from_cpf_senha($cpf, $senha) {
        $this->db->where('cpf', $cpf);
        $this->db->where('senha', $senha);
        $query = $this->db->get('usuario_sistema');
        if (count($query->result()) > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    public function get_usuario_sistema_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('usuario_sistema');
        if (count($query->result()) > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    public function get_sistemas_usuario_from_id_usuario_sistema($id_usuario_sistema) {
        $sistemas = array();

        //sistema aplicativo cidadao
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_aplicativo_cidadao');
        if (count($query->result()) > 0) {
            $usuario_aplicativo_cidadao = $query->row(0);
        } else {
            $usuario_aplicativo_cidadao = NULL;
        }
        $sistemas['usuario_aplicativo_cidadao'] = $usuario_aplicativo_cidadao;

        //sistema assistencia social
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_assistencia_social');
        if (count($query->result()) > 0) {
            $usuario_assistencia_social = $query->row(0);
        } else {
            $usuario_assistencia_social = NULL;
        }
        $sistemas['usuario_assistencia_social'] = $usuario_assistencia_social;

        //sistema cad imobiliario
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_cad_imobiliario');
        if (count($query->result()) > 0) {
            $usuario_cad_imobiliario = $query->row(0);
        } else {
            $usuario_cad_imobiliario = NULL;
        }
        $sistemas['usuario_cad_imobiliario'] = $usuario_cad_imobiliario;

        //sistema cad unico
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_cad_unico');
        if (count($query->result()) > 0) {
            $usuario_cad_unico = $query->row(0);
        } else {
            $usuario_cad_unico = NULL;
        }
        $sistemas['usuario_cad_unico'] = $usuario_cad_unico;

        //sistema comunicacao social
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_comunicacao_social');
        if (count($query->result()) > 0) {
            $usuario_comunicacao_social = $query->row(0);
        } else {
            $usuario_comunicacao_social = NULL;
        }
        $sistemas['usuario_comunicacao_social'] = $usuario_comunicacao_social;

        //sistema convenios
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_convenios');
        if (count($query->result()) > 0) {
            $usuario_convenios = $query->row(0);
        } else {
            $usuario_convenios = NULL;
        }
        $sistemas['usuario_convenios'] = $usuario_convenios;

        //sistema educacao
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_educacao');
        if (count($query->result()) > 0) {
            $usuario_educacao = $query->row(0);
        } else {
            $usuario_educacao = NULL;
        }
        $sistemas['usuario_educacao'] = $usuario_educacao;

        //sistema politica publica
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_politica_publica');
        if (count($query->result()) > 0) {
            $usuario_politica_publica = $query->row(0);
        } else {
            $usuario_politica_publica = NULL;
        }
        $sistemas['usuario_politica_publica'] = $usuario_politica_publica;

        //sistema saude
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_saude');
        if (count($query->result()) > 0) {
            $usuario_saude = $query->row(0);
        } else {
            $usuario_saude = NULL;
        }
        $sistemas['usuario_saude'] = $usuario_saude;

        //sistema terceiro setor
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_terceiro_setor');
        if (count($query->result()) > 0) {
            $usuario_terceiro_setor = $query->row(0);
        } else {
            $usuario_terceiro_setor = NULL;
        }
        $sistemas['usuario_terceiro_setor'] = $usuario_terceiro_setor;

        //sistema pesquisa
        $this->db->where('id_usuario_sistema', $id_usuario_sistema);
        $query = $this->db->get('usuario_pesquisa');
        if (count($query->result()) > 0) {
            $usuario_pesquisa = $query->row(0);
        } else {
            $usuario_pesquisa = NULL;
        }
        $sistemas['usuario_pesquisa'] = $usuario_pesquisa;

        return $sistemas;
    }

    public function insert_or_update($options_sistema, $options_aplicativo_cidadao, $options_assistencia_social, $options_cad_imobiliario, $options_cad_unico, $options_comunicacao_social, $options_convenios, $options_educacao, $options_politica_publica, $options_saude, $options_terceiro_setor, $options_pesquisa) {
        if (isset($options_sistema['id'])) {
            $this->db->flush_cache();
            $this->db->where('id', $options_sistema['id']);
            $this->db->update('usuario_sistema', $options_sistema);

            $this->db->flush_cache();
            //Inserindo aplicativo cidadao
            if ($options_aplicativo_cidadao != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_aplicativo_cidadao');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_aplicativo_cidadao', $options_aplicativo_cidadao);
                } else {
                    $options_aplicativo_cidadao['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_aplicativo_cidadao', $options_aplicativo_cidadao);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_aplicativo_cidadao');
            }

            //Inserindo assistencia social
            if ($options_assistencia_social != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_assistencia_social');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_assistencia_social', $options_assistencia_social);
                } else {
                    $options_assistencia_social['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_assistencia_social', $options_assistencia_social);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_assistencia_social');
            }

            //Inserindo cad imobiliario
            if ($options_cad_imobiliario != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_cad_imobiliario');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_cad_imobiliario', $options_cad_imobiliario);
                } else {
                    $options_cad_imobiliario['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_cad_imobiliario', $options_cad_imobiliario);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_cad_imobiliario');
            }

            //Inserindo cad unico
            if ($options_cad_unico != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_cad_unico');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_cad_unico', $options_cad_unico);
                } else {
                    $options_cad_unico['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_cad_unico', $options_cad_unico);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_cad_unico');
            }

            //Inserindo comunicacao social
            if ($options_comunicacao_social != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_comunicacao_social');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_comunicacao_social', $options_comunicacao_social);
                } else {
                    $options_comunicacao_social['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_comunicacao_social', $options_comunicacao_social);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_comunicacao_social');
            }

            //Inserindo convenios
            if ($options_convenios != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_convenios');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_convenios', $options_convenios);
                } else {
                    $options_convenios['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_convenios', $options_convenios);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_convenios');
            }

            //Inserindo educacao
            if ($options_educacao != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_educacao');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_educacao', $options_educacao);
                } else {
                    $options_educacao['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_educacao', $options_educacao);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_educacao');
            }

            //Inserindo politica publica
            if ($options_politica_publica != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_politica_publica');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_politica_publica', $options_politica_publica);
                } else {
                    $options_politica_publica['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_politica_publica', $options_politica_publica);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_politica_publica');
            }

            //Inserindo saude
            if ($options_saude != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_saude');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_saude', $options_saude);
                } else {
                    $options_saude['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_saude', $options_saude);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_saude');
            }

            //Inserindo terceiro setor
            if ($options_terceiro_setor != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_terceiro_setor');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_terceiro_setor', $options_terceiro_setor);
                } else {
                    $options_terceiro_setor['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_terceiro_setor', $options_terceiro_setor);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_terceiro_setor');
            }

            //Inserindo pesquisa
            if ($options_pesquisa != NULL) {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $query = $this->db->get('usuario_pesquisa');
                if (count($query->result()) > 0) {
                    $this->db->where('id', $query->row(0)->id);
                    $this->db->update('usuario_pesquisa', $options_pesquisa);
                } else {
                    $options_pesquisa['id_usuario_sistema'] = $options_sistema['id'];
                    $this->db->insert('usuario_pesquisa', $options_pesquisa);
                }
            } else {
                $this->db->where('id_usuario_sistema', $options_sistema['id']);
                $this->db->delete('usuario_pesquisa');
            }

            return $options_sistema['id'];
        } else {
            //Inserindo usuário no sistema
            $this->db->insert('usuario_sistema', $options_sistema);
            $return_id = $this->db->insert_id();

            if ($return_id != NULL && $return_id != 0) {

                //Inserindo aplicativo cidadao
                if ($options_aplicativo_cidadao != NULL) {
                    $options_aplicativo_cidadao['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_aplicativo_cidadao', $options_aplicativo_cidadao);
                }

                //Inserindo assistencia social
                if ($options_assistencia_social != NULL) {
                    $options_assistencia_social['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_assistencia_social', $options_assistencia_social);
                }

                //Inserindo cad imobiliario
                if ($options_cad_imobiliario != NULL) {
                    $options_cad_imobiliario['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_cad_imobiliario', $options_cad_imobiliario);
                }

                //Inserindo cad unico
                if ($options_cad_unico != NULL) {
                    $options_cad_unico['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_cad_unico', $options_cad_unico);
                }

                //Inserindo comunicacao social
                if ($options_comunicacao_social != NULL) {
                    $options_comunicacao_social['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_comunicacao_social', $options_comunicacao_social);
                }

                //Inserindo convenios
                if ($options_convenios != NULL) {
                    $options_convenios['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_convenios', $options_convenios);
                }

                //Inserindo educacao
                if ($options_educacao != NULL) {
                    $options_educacao['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_educacao', $options_educacao);
                }

                //Inserindo politica publica
                if ($options_politica_publica != NULL) {
                    $options_politica_publica['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_politica_publica', $options_politica_publica);
                }

                //Inserindo saude
                if ($options_saude != NULL) {
                    $options_saude['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_saude', $options_saude);
                }

                //Inserindo terceiro setor
                if ($options_terceiro_setor != NULL) {
                    $options_terceiro_setor['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_terceiro_setor', $options_terceiro_setor);
                }

                //Inserindo pesquisa
                if ($options_pesquisa != NULL) {
                    $options_pesquisa['id_usuario_sistema'] = $return_id;
                    $this->db->insert('usuario_pesquisa', $options_pesquisa);
                }

                return $return_id;
            }
        }

        return NULL;
    }

    public function delete_user_from_id($user_id) {
        $this->db->where('id', $user_id);
        $this->db->delete('usuario_sistema');

        return $this->db->affected_rows();
    }

    public function insert_or_update_usuario($options_sistema) {
        if (isset($options_sistema['id'])) {
            $this->db->where('id', $options_sistema['id']);
            $this->db->update('usuario_sistema', $options_sistema);

            return $options_sistema['id'];
        } else {
            $this->db->insert('usuario_sistema', $options_sistema);
            $return_id = $this->db->insert_id();

            return $return_id;
        }

        return NULL;
    }

}
