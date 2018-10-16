<?php

class usuariomodel extends CI_Model {

    public function get_nome_by_id($id_usuario) {
        $this->db->select('nome');
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('usuario');

        if ($query->num_rows > 0) {
            return $query->row(0)->nome;
        } else {
            return 'Desconhecido';
        }
    }

    public function get_all_admins() {
        $this->db->where('id_nivel', 1);
        $query = $this->db->get('usuario');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function apagar_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $dados = $this->db->get('usuario')->row(0);

        $this->db->cache_delete_all();

        $num_affected_rows = 0;
        switch ($dados->id_nivel) {
            case "15":
                $this->db->select('usuario.id_usuario');
                $this->db->join('usuario_realizou_cadastro ur', "ur.id_usuario_cadastrado = usuario.id_usuario", 'INNER');
                $this->db->where('ur.id_usuario_cadastrou', $dados->id_usuario);
                $this->db->or_where('usuario.id_usuario', $dados->id_usuario);
                $usuarios = $this->db->get('usuario');
                foreach ($usuarios->result() as $value) {
                    $usuarios_id[] = $value->id_usuario;
                }

                $this->db->where_in('id_usuario', $usuarios_id);
                $this->db->delete('aceite_licenca_uso');
                $this->db->where_in('id_usuario', $usuarios_id);
                $this->db->delete('usuario');
                $num_affected_rows = $this->db->affected_rows();
                $this->db->cache_delete_all();
                break;
            default :
                $this->db->where('id_usuario', $id_usuario);
                $this->db->delete('aceite_licenca_uso');
                $this->db->where('id_usuario', $id_usuario);
                $this->db->delete('usuario');
                $num_affected_rows = $this->db->affected_rows();
                $this->db->cache_delete_all();
                break;
        }

        return $num_affected_rows;
    }

    public function check_existe_cpf_or_email($options) {
        $this->db->where('login', $options['login']);
        $this->db->or_where('email', $options['email']);

        $query = $this->db->get('usuario');

        if ($query->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_all() {
        $this->db->order_by('nome');
        $query = $this->db->get('usuario');
        return $query->result();
    }

    function get_by_id($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('usuario');
        return $query->row(0);
    }

    public function get_by_id_array($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('usuario');
        return $query->result();
    }

    function get_by_login($login) {
        $this->db->where('login', $login);
        $query = $this->db->get('usuario');
        return $query->row(0);
    }

    public function marca_usuario_nao_novo($id_usuario, $gratuito = false) {
        $this->db->where('id_usuario', $id_usuario);
        if ($gratuito) {
            $this->db->update('usuario', array('usuario_novo' => 'N', 'primeiro_acesso' => 'N'));
        } else {
            $this->db->update('usuario', array('usuario_novo' => 'N'));
        }
    }

    //Thomas: Retorna todos os usuários de um determinado nivel
    function get_users_by_nivel_id($id_nivel) {
        $this->db->where('id_nivel', $id_nivel);
        $query = $this->db->get('usuario');
        return $query->result();
    }

    function get_cnpjs_by_usuario($id_usuario) {
        $this->load->model('usuario_cnpj');
        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15)
            $direcaoJoin = "LEFT";
        else
            $direcaoJoin = "";

        if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
            $ids = $this->usuario_cnpj->get_all_by_subgestor($id_usuario);
        else
            $ids = $this->usuario_cnpj->get_all_by_usuario($id_usuario, $direcaoJoin);

        $cnpjs = array();
        $this->load->model('cnpj_siconv');
        foreach ($ids as $row) {
            array_push($cnpjs, $this->cnpj_siconv->get_by_id($row->id_cnpj));
        }
        //var_dump($cnpjs);
        return $cnpjs;
    }

    public function get_cnpjs_gestor_by_usuario($id_usuario) {
        $this->load->model('usuario_cnpj');
        if ($this->session->userdata('nivel') == 4)
            $direcaoJoin = "LEFT";
        else
            $direcaoJoin = "";

        if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
            $ids = $this->usuario_cnpj->get_all_by_subgestor($id_usuario);
        else
            $ids = $this->usuario_cnpj->get_all_by_usuario($id_usuario, $direcaoJoin);

        $cnpjs = array();
        $this->load->model('cnpj_siconv');
        foreach ($ids as $row) {
            array_push($cnpjs, $row);
        }
        //var_dump($cnpjs);
        return $cnpjs;
    }

    public function get_lista_cidades_by_cnpj($id_usuario) {
        $cnpjs = $this->get_cnpjs_by_usuario($id_usuario);

        $lista_dados = array();
        $this->load->model('proponente_siconv_model');

        foreach ($cnpjs as $cnpj)
            $lista_dados[$this->proponente_siconv_model->get_municipio_nome($cnpj->id_cidade)->municipio][] = $cnpj->cnpj;

        ksort($lista_dados);
        return $lista_dados;
    }

    public function get_municipio_by_usuario($id_usuario) {
        $cnpjs = $this->get_cnpjs_by_usuario($id_usuario);

        $this->load->model('proponente_siconv_model');

        if ($this->session->userdata('nivel') == 1 && $this->session->userdata('nivel') == 4) {
            if (!empty($cnpjs))
                return $this->proponente_siconv_model->get_municipio_nome($cnpjs[0]->id_cidade);
            else
                return null;
        } else
            return null;
    }

    public function get_municipio_by_usuario_lista($id_usuario) {
        $cnpjs = $this->get_cnpjs_by_usuario($id_usuario);

        return $cnpjs;

//        $this->load->model('proponente_siconv_model');
//
//        if ($this->session->userdata('nivel') == 1 && $this->session->userdata('nivel') == 4) {
//            if (!empty($cnpjs))
//                return $this->proponente_siconv_model->get_municipio_nome($cnpjs[0]->id_cidade);
//            else
//                return null;
//        } else
//            return null;
    }

    public function get_municipio_by_vendedor($id_usuario, $mostaNavigation = false) {
        $cnpjs = $this->get_cnpjs_by_usuario($id_usuario);

        $lista_dados = array();
        $this->load->model('proponente_siconv_model');

        foreach ($cnpjs as $cnpj) {
            if ($mostaNavigation)
                $lista_dados = array($this->proponente_siconv_model->get_municipio_nome($cnpj->id_cidade)->municipio, $this->proponente_siconv_model->get_municipio_nome($cnpj->id_cidade)->municipio_uf_sigla);
            else
                $lista_dados[$this->proponente_siconv_model->get_municipio_nome($cnpj->id_cidade)->municipio][] = $cnpj->cnpj;
        }

        return $lista_dados;
    }

    public function get_lista_cidade_by_admin() {
        $this->db->distinct();
        $this->db->select("cnpj");
        $cnpjs = $this->db->get('cnpj_siconv')->result();

        $lista_dados = array();
        $this->load->model('cnpj_siconv');

        foreach ($cnpjs as $cnpj) {
            if (count($this->cnpj_siconv->get_cidade_by_cnpj_siconv($cnpj->cnpj)) > 0)
                $lista_dados[$this->cnpj_siconv->get_cidade_by_cnpj_siconv($cnpj->cnpj)->Nome][] = $cnpj->cnpj;
        }

        ksort($lista_dados);
        return $lista_dados;
    }

    function get_estados_by_usuario($id_usuario) {
        //carrega todos os cnpjs do usuario
        $cnpjs = $this->get_cnpjs_by_usuario($id_usuario);
        $codigos_estados = array();
        $estados = array();

        //descobre a cidade do cnpj - associa a uma cidade da tabela de cidades e descobre o codigo do estado da cidade
        $this->load->model('cidades_siconv');
        foreach ($cnpjs as $cnpj) {
            array_push($estados, $cnpj);
        }

        return $estados;
    }

    public function altera_senha($senha, $codigo, $id_alterar_senha, $email_alterar_senha) {
        $affected = 0;

        $this->db->where('id_usuario', $id_alterar_senha);
        $this->db->where('email', $email_alterar_senha);
        $this->db->update('usuario', array('senha' => sha1($senha)));

        $affected += $this->db->affected_rows();

        $this->db->where('codigo', $codigo);
        $this->db->delete('codigos');

        $affected += $this->db->affected_rows();

        return $affected;
    }

    function recupera_email($email, $nivel) {

        $this->db->where('email', $email);
        $this->db->where('id_nivel', $nivel);
        $query = $this->db->get('usuario');
        if (count($query->result_array()) > 0) {
            $dados_usuario = $query->row(0);

            $data_expirar = date('Y-m-d H:i:s', strtotime('+1 day'));
            $codigo = base64_encode($dados_usuario->id_usuario . '#' . $dados_usuario->email);
            /*
              $texto = 'Recebemos uma tentativa de recuperação de senha para este e-mail, caso não tenha sido você,
              desconsidere este e-mail, caso contrário clique no link abaixo
              <a href="' . base_url() . 'index.php/in/login/gera_senha?codigo=' . $codigo . '">Recuperar Senha</a></p>'; */

            $urlSistema = base_url() . 'index.php/in/login/gera_senha?codigo=' . $codigo;
            $urlBotao = base_url() . "layout/assets/images/botao_email_senha.png";
            $urlCabecalho = base_url() . "layout/assets/images/esicar_rec_senha.png";
            $urlRodape = base_url() . "layout/assets/images/esicar_rodape.png";

            $texto = "<html>
		            <div align='center' style='background-color: #eeeeee;>
                                <p style='color: #ffffff;'>&nbsp;</p>
                                <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
                                    <img src='{$urlCabecalho}' style='width: 580px;'/>
                                    <div align='center' style='font-family: arial; margin-left: 20px;'>
		             
                                        <div align='left'  style='width: 550px; margin-top: 50px;'>
                                            <div style='margin-left: 20px; padding-right:1px; font-size: 17px; width: 500px;'>
                                                <p style='font-size: 24px; font-weight: bold;  color: #63cf9c;'>Olá, {$dados_usuario->nome}</p>
                                                <p>Sua mensagem foi recebida com sucesso no sistema e-Sicar.</p>
                                                <p>Recebemos uma tentativa de recuperação de senha para este e-mail, caso não tenha sido você, desconsidere este e-mail, caso contrário clique no botão abaixo</p>
                                            </div>
                                            <div style='margin-top: 40px; margin-bottom:40px; margin-right: 35px;' align='center'>
                                                <a href='{$urlSistema}'><img src='{$urlBotao}' width='180'/></a>
                                            </div>
                                            <div style='margin-left: 20px; font-size: 17px;'>
                                                <p>Atenciosamente,</p>
                                                <p>Departamento de Atendimento ao Cliente</p>
                                            </div>
                                         </div>
                                    </div>
                                <img src='{$urlRodape}' style='width: 580px; margin-top: 40px;'/>
                             </div>
                        </div>
		            </html>";

            $this->load->library('email');

            $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
            $this->email->set_newline("\r\n");

            $this->email->set_mailtype('html');
            $this->email->from('suporte@physisbrasil.com.br', 'Physis Brasil');
            $this->email->to($email);

            $this->email->subject('Recuparação de senha');
            $this->email->message($texto);
            $this->email->send();

            $this->db->where('codigo', $codigo);
            $codigos = $this->db->get('codigos');
            if (count($codigos->result()) > 0) {
                $this->db->where('codigo', $codigo);
                $this->db->delete('codigos');
            }
            $options['codigo'] = $codigo;
            $options['data'] = $data_expirar;
            return $this->db->insert('codigos', $options);
        }

        return null;
    }

    function get_tipo_by_id($id) {

        $this->db->where('id_usuario', $id);
        $query = $this->db->get('usuario');

        if ($query != null && $query->num_rows > 0) {
            $this->db->where('id_nivel_usuario', $query->row(0)->id_nivel);
            $query = $this->db->get('nivel_usuario');
            return $query->row(0)->nome;
        }

        return null;
    }

    function get_id_gestor($id) {

        $this->db->where('id_usuario', $id);
        $query = $this->db->get('usuario');

        if ($query != null && $query->num_rows > 0) {
            if ($query->row(0)->id_nivel == 3 || $query->row(0)->id_nivel == 5) {
                $this->db->where('id_usuario', $id);
                $query = $this->db->get('usuario_gestor');
                if ($query != null && $query->num_rows > 0) {
                    return $query->row(0)->id_gestor;
                }
            } elseif ($query->row(0)->id_nivel == 2 || $query->row(0)->id_nivel == 6) {
                $this->db->where('id_usuario', $id);
                $query = $this->db->get('gestor');

                return $query->row(0)->id_gestor;
            } elseif ($query->row(0)->id_nivel == 7 || $query->row(0)->id_nivel == 8) {
                $this->db->where('id_usuario', $id);
                $query = $this->db->get('usuario_subgestor');
                if ($query != null && $query->num_rows > 0) {
                    return $query->row(0)->id_gestor;
                }
            }
        }

        return null;
    }

    function get_ids_grupo($id_gestor) {
        $this->db->where('id_gestor', $id_gestor);
        $query = $this->db->get('usuario_gestor');

        return $query->result();
    }

    function get_ids_grupo_subgestor($id_gestor) {
        $this->db->where('id_gestor', $id_gestor);
        $query = $this->db->get('usuario_subgestor');

        return $query->result();
    }

    function get_gestor_user_id_by_gestor_id($id_gestor) {

        $this->db->where('id_gestor', $id_gestor);
        $query = $this->db->get('gestor');

        return $query->row(0)->id_usuario;
    }

    function add_usuario() {
        $this->load->helper('url');

        $data = array(
            'login' => $this->input->post('login'),
            'senha' => hash('sha1', $this->input->post('senha')),
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'telefone' => $this->input->post('telefone'),
            'celular' => $this->input->post('celular'),
            'login_siconv' => $this->input->post('login_siconv'),
            'senha_siconv' => $this->input->post('senha_siconv'),
            'id_nivel' => $this->input->post('id_nivel')
        );

        $this->db->where('login', $data['login']);
        $query = $this->db->get('usuario');
        $populacao = $query->result_array();

        if (count($populacao) > 0) {
            return null;
        }

        return $this->db->insert('usuario', $data);
    }

    function delete_usuario($id) {
        $this->db->where('id_usuario', $id);
        $this->db->delete('usuario');
        return $this->db->affected_rows();
    }

    function update_usuario($id) {
        $this->load->helper('url');

        $data = array(
            'login' => $this->input->post('login'),
            'senha' => hash('sha1', $this->input->post('senha')),
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'telefone' => $this->input->post('telefone'),
            'celular' => $this->input->post('celular'),
            'login_siconv' => $this->input->post('login_siconv'),
            'senha_siconv' => $this->input->post('senha_siconv'),
            'id_nivel' => $this->input->post('id_nivel')
        );

        $this->db->where('id_usuario', $id);
        return $this->db->update('usuario', $data);
    }

    function add_login_senha_siconv_vendedor_admin($id, $login, $senha) {
        $data = array(
            'login_siconv' => $login,
            'senha_siconv' => base64_encode($senha)
        );

        $this->db->where('id_usuario', $id);
        return $this->db->update('usuario', $data);
    }

    function validar($login_gp = NULL, $senha_gp = NULL) {
        if ($login_gp != NULL && $senha_gp != NULL) {
            $senha = hash('sha1', $senha_gp);
            $this->db->where('login', $login_gp);
            $this->db->where('senha', $senha);
            $query = $this->db->get('usuario');
        } else {
            $senha = hash('sha1', $this->input->post('senha', TRUE));
            $this->db->where('login', $this->input->post('login', TRUE));
            $this->db->where('senha', $senha);
            $query = $this->db->get('usuario');
        }

        if ($query->num_rows > 1) {
            $usuario = $query->result();
            return $usuario;
        } else if ($query->num_rows == 1) {
            if ($query->row(0)->usuario_sistema != 'T') {
                $usuario = $query->row(0);

                $this->load->model('nivel_usuario');
                $nivel_usuario = $this->nivel_usuario->get_by_id($usuario->id_nivel);

                if ($nivel_usuario->nome === 'ADMIN' || $nivel_usuario->nome === 'VENDEDOR' || $nivel_usuario->id_nivel_usuario == 12 || $nivel_usuario->id_nivel_usuario == 13 || $nivel_usuario->id_nivel_usuario == 14) {
                    return $usuario;
                }

                if ($nivel_usuario->id_nivel_usuario == '2' || $nivel_usuario->id_nivel_usuario == '9') {
                    $this->load->model('gestor');
                    $gestor = $this->gestor->get_by_usuario($usuario->id_usuario);
                    if ($gestor == null) {
                        return null;
                    } else {
                        if (strtotime($gestor->validade) > strtotime(date('Y-m-d'))) {
                            if ($gestor->tipo_gestor != 10) {
                                return $usuario;
                            }
                        } else {
                            return null;
                        }
                    }
                } else {
                    $this->load->model('usuario_gestor');
                    $this->load->model('usuario_subgestor_model');
                    if ($usuario->id_nivel == 3 || $usuario->id_nivel == 5 || $usuario->id_nivel == 6)
                        $gestor_do_usuario = $this->usuario_gestor->get_by_usuario($usuario->id_usuario);
                    else if ($usuario->id_nivel == 7 || $usuario->id_nivel == 8)
                        $gestor_do_usuario = $this->usuario_subgestor_model->get_by_usuario($usuario->id_usuario);

                    $this->load->model('gestor');
                    $gestor = $this->gestor->get_by_id($gestor_do_usuario->id_gestor);
                    ## TODO: Criar método para evitar repeticao de codigo na validacao da data
                    if ($gestor == null) {
                        return null;
                    } else {
                        if (strtotime($gestor->validade) > strtotime(date('Y-m-d'))) {
                            return $usuario;
                        } else {
                            return null;
                        }
                    }
                }
            } else {
                return $query->row(0);
            }
        } else if ($query->num_rows == 0) {
            return "ERRO_LOGIN";
        }
    }

    # Verificar ser o usuario esta logado
    # Testar maneira mais simples com ferramentas do codeigniter

    function logged($nivel) {
        $logged = $this->session->userdata('logged');
        if ((!isset($logged) || $logged != true) || ($nivel != $this->session->userdata('nivel'))) {
            return false;
        }
        return true;
    }

    function get_all_gestor($soAtivos = false, $pegaPrefeito = false, $soMunicipal = false) {
        if ($soAtivos)
            $this->db->where('status', 'A');

        if ($pegaPrefeito)
            $this->db->where('(id_nivel = 2 OR id_nivel = 5)', null, false);
        else
            $this->db->where('id_nivel', 2);

        $this->db->where_not_in('usuario.id_usuario', array(25, 34, 37, 38, 41, 58, 124, 117, 99, 98, 63, 59, 91, 92, 93, 26, 35, 94, 118, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149));

        if ($soMunicipal)
            $this->db->where('usuario_sistema', 'M');

        $query = $this->db->get('usuario')->result();

        return $query;
    }

    public function check_validade_gestores() {
        $gestores = array();

        $this->db->where('id_nivel', 2);
        $this->db->where('status', 'A');
        $this->db->where_not_in('usuario.id_usuario', array(25, 34, 37, 38, 41, 58, 124, 117, 99, 98, 63, 59));

        $query = $this->db->get('usuario');

        if ($query->num_rows > 0) {
            $gestores = $query->result();
            foreach ($gestores as $gestor) {
                //get object gestor
                $this->db->where('id_usuario', $gestor->id_usuario);
                $g = $this->db->get('gestor');
                if ($g->num_rows > 0) {
                    $g = $g->result();
                    $phpDate = strtotime($g[0]->validade);
                    $phpDateNow = time();
                    if ($phpDate < $phpDateNow) {
                        $options = array(
                            'status' => 'I'
                        );

                        $this->db->where('id_usuario', $gestor->id_usuario);
                        $this->db->update('usuario', $options);
                    }
                }
            }
        }
    }

    function insere_usuario($options) {
        unset($options['validade']);
        unset($options['quantidade_cnpj']);
        unset($options['id_gestor']);
        unset($options['tipo_gestor']);
        unset($options['cnpj']);
        unset($options['estado']);
        unset($options['municipio']);
        unset($options['cadastra']);
        unset($options['cnpj_instituicao']);
        unset($options['esfera']);
        unset($options['proponente']);
        unset($options['estado_restrito']);
        unset($options['esfera_restrita']);
        unset($options['municipio_restrito']);
        unset($options['tipo_subgestor']);
        $options['senha'] = sha1($options['senha']);
        if (isset($options['senha_siconv']))
            $options['senha_siconv'] = base64_encode($options['senha_siconv']);
        $options['data_cadastro'] = date('Y-m-d');

        $this->db->insert('usuario', $options);

        return $this->db->insert_id();
    }

    public function atualiza_senha($array_senha_id) {
        $this->db->where('id_usuario', $array_senha_id['id_usuario']);
        unset($array_senha_id['id_usuario']);
        $array_senha_id['senha'] = sha1($array_senha_id['senha']);
        $this->db->update('usuario', $array_senha_id);
    }

    public function atualiza_usuario($id, $options) {
        unset($options['validade']);
        unset($options['quantidade_cnpj']);
        unset($options['id_gestor']);
        unset($options['tipo_gestor']);
        unset($options['cnpj']);
        unset($options['estado']);
        unset($options['municipio']);
        unset($options['cadastra']);
        unset($options['estado_restrito']);
        unset($options['municipio_restrito']);
        unset($options['esfera_restrita']);
        unset($options['senha_gp']);
        unset($options['acesso_gp']);

        if (isset($options['id_nivel']) && $options['id_nivel'] === "")
            unset($options['id_nivel']);

        if ($options['senha'] != "")
            $options['senha'] = sha1($options['senha']);
        else
            unset($options['senha']);

        if (isset($options['senha_siconv']) && $options['senha_siconv'] != "")
            $options['senha_siconv'] = base64_encode($options['senha_siconv']);
        else
            unset($options['senha_siconv']);

        $this->db->where('id_usuario', $id);
        $this->db->update('usuario', $options);

        return $this->db->affected_rows();
    }

    public function get_all_usuarios_permissao($filtro = "", $avulso = 0) {
        $this->db->flush_cache();
        $this->db->_protect_identifiers = false;
        switch ($this->session->userdata('nivel')) {
            case 1:
                $this->db->select("u.*, nivel_usuario.nome AS nome_nivel");
                $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = u.id_nivel");
                if ($filtro != "") {
                    $this->db->where("u.nome like '%{$filtro}%'");
                }
                $this->db->order_by("nome_nivel");
                break;
            case 2:
                if ($avulso == 1) {
                    $this->db->select("u.*, nivel_usuario.nome AS nome_nivel");
                    $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = u.id_nivel", 'LEFT');
                    $this->db->where('u.id_usuario', $this->session->userdata('id_usuario'));
                    $query = $this->db->get('usuario')->result();

                    return $query;
                } else {
                    $this->db->distinct();
                    $this->db->select("u.*, nivel_usuario.nome AS nome_nivel");
                    $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = u.id_usuario OR u.id_usuario = ' . $this->session->userdata('id_usuario'), 'LEFT');
                    $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                    $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = u.id_nivel", 'LEFT');
                    $this->db->where('gestor.id_usuario', $this->session->userdata('id_usuario'));
                    $this->db->or_where('u.id_usuario', $this->session->userdata('id_usuario'));
                    if ($filtro != "")
                        $this->db->where("(u.nome like '%{$filtro}%')");
                }
                break;
            case 3:
                break;
            case 4:
                $this->db->select("u.*, nivel_usuario.nome AS nome_nivel");
                $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = u.id_nivel");
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                if ($filtro != "")
                    $this->db->where("(u.nome like '%{$filtro}%')");
                break;
            case 6:
                $this->db->distinct();
                $this->db->select("u.*, nivel_usuario.nome AS nome_nivel");
                $this->db->join('usuario_subgestor', 'usuario_subgestor.id_usuario = u.id_usuario OR u.id_usuario = ' . $this->session->userdata('id_usuario'), 'LEFT');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_subgestor.id_gestor', 'LEFT');
                $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = u.id_nivel", 'LEFT');
                $this->db->where('gestor.id_usuario', $this->session->userdata('id_usuario'));
                $this->db->or_where('usuario.id_usuario', $this->session->userdata('id_usuario'));
                if ($filtro != "")
                    $this->db->where("(u.nome like '%{$filtro}%')");
                break;
            case 7:
                break;
            case 8:
                break;
            case 12:
                $this->db->distinct();
                $this->db->select("u.*, n.nome AS nome_nivel");
                $this->db->join('nivel_usuario n', "n.id_nivel_usuario = u.id_nivel", 'LEFT');
                $this->db->join('usuario_gestor ug', "u.id_usuario = ug.id_usuario OR u.id_usuario ={$this->session->userdata('id_usuario')} ", 'left');
                $this->db->join('gestor g', 'ug.id_gestor = g.id_gestor', 'left');
                $this->db->where('g.id_usuario', $this->session->userdata('id_usuario'));
                $this->db->or_where('u.id_usuario', $this->session->userdata('id_usuario'));
                break;
            case 13:
                $this->db->distinct();
                $this->db->select("u.*, n.nome AS nome_nivel");
                $this->db->join('nivel_usuario n', "n.id_nivel_usuario = u.id_nivel", 'LEFT');
                $this->db->join('usuario_gestor ug', "u.id_usuario = ug.id_usuario OR u.id_usuario ={$this->session->userdata('id_usuario')} ", 'left');
                $this->db->join('gestor g', 'ug.id_gestor = g.id_gestor', 'left');
                $this->db->where('g.id_usuario', $this->session->userdata('id_usuario'));
                $this->db->or_where('u.id_usuario', $this->session->userdata('id_usuario'));
                break;
            case 15:
                $this->db->distinct();
                $this->db->select("u.*, n.nome AS nome_nivel");
                $this->db->join('nivel_usuario n', "n.id_nivel_usuario = u.id_nivel", 'LEFT');
                $this->db->join('usuario_realizou_cadastro ur', "ur.id_usuario_cadastrado = u.id_usuario", 'INNER');
                $this->db->where('ur.id_usuario_cadastrou', $this->session->userdata('id_usuario'));
                $this->db->or_where('u.id_usuario', $this->session->userdata('id_usuario'));
                break;
        }

        $query = $this->db->get('usuario u')->result();

        $lista_final = array();
        foreach ($query as $user) {
            if ($user->id_nivel == 2) {
                $this->db->select('tipo_gestor');
                $this->db->where('id_usuario', $user->id_usuario);
                $query_g = $this->db->get('gestor');

                if ($query_g->num_rows > 0) {
                    if ($query_g->row(0)->tipo_gestor != 10) {
                        array_push($lista_final, $user);
                    }
                }
            } else {
                array_push($lista_final, $user);
            }
        }

        return $lista_final;
    }

    public function muda_status_usuario($id, $status) {
        $this->db->where('id_usuario', $id);
        $dados = $this->db->get('usuario')->row(0);

        $this->db->cache_delete_all();

        $num_affected_rows = 0;
        switch ($dados->id_nivel) {
            case "1":
            case "3":
            case "4":
                $this->db->where('id_usuario', $dados->id_usuario);

                $campos_atualizar = array("status" => $status);

                if ($this->session->userdata('nivel') === "2") {
                    if ($status == 'I')
                        $campos_atualizar['desativado_gestor'] = 'S';
                    else
                        $campos_atualizar['desativado_gestor'] = null;
                }else {
                    if ($this->input->get('desativado_gestor', TRUE) !== FALSE) {
                        if ($this->input->get('desativado_gestor', TRUE) === 'S')
                            return "SEM_PERMISSAO";
                    }
                }

                $this->db->update('usuario', $campos_atualizar);

                $num_affected_rows = $this->db->affected_rows();

                $this->db->cache_delete_all();
                break;

            case "2":
                $this->db->select('usuario.*');
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor');
                $this->db->where('gestor.id_usuario', $dados->id_usuario);
                $this->db->where("(desativado_gestor IS NULL OR desativado_gestor <> 'S')", null, FALSE);
                $usuarios_do_gestor = $this->db->get('usuario')->result();

                $this->db->cache_delete_all();

                foreach ($usuarios_do_gestor as $usuario_comum) {
                    $this->db->where('id_usuario', $usuario_comum->id_usuario);
                    $this->db->update('usuario', array("status" => $status));

                    $num_affected_rows = $this->db->affected_rows();

                    $this->db->cache_delete_all();
                }

                $this->db->where('id_usuario', $dados->id_usuario);
                $this->db->update('usuario', array("status" => $status));

                $num_affected_rows = $this->db->affected_rows();

                $this->db->cache_delete_all();
                break;
            case "12":
                $this->db->select('usuario.*');
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor');
                $this->db->where('gestor.id_usuario', $dados->id_usuario);
                $this->db->where("(desativado_gestor IS NULL OR desativado_gestor <> 'S')", null, FALSE);
                $usuarios_do_gestor = $this->db->get('usuario')->result();

                $this->db->cache_delete_all();

                foreach ($usuarios_do_gestor as $usuario_comum) {
                    $this->db->where('id_usuario', $usuario_comum->id_usuario);
                    $this->db->update('usuario', array("status" => $status));

                    $num_affected_rows = $this->db->affected_rows();

                    $this->db->cache_delete_all();
                }

                $this->db->where('id_usuario', $dados->id_usuario);
                $this->db->update('usuario', array("status" => $status));

                $num_affected_rows = $this->db->affected_rows();

                $this->db->cache_delete_all();
                break;
            case "13":
                $this->db->select('usuario.*');
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor');
                $this->db->where('gestor.id_usuario', $dados->id_usuario);
                $this->db->where("(desativado_gestor IS NULL OR desativado_gestor <> 'S')", null, FALSE);
                $usuarios_do_gestor = $this->db->get('usuario')->result();

                $this->db->cache_delete_all();

                foreach ($usuarios_do_gestor as $usuario_comum) {
                    $this->db->where('id_usuario', $usuario_comum->id_usuario);
                    $this->db->update('usuario', array("status" => $status));

                    $num_affected_rows = $this->db->affected_rows();

                    $this->db->cache_delete_all();
                }

                $this->db->where('id_usuario', $dados->id_usuario);
                $this->db->update('usuario', array("status" => $status));

                $num_affected_rows = $this->db->affected_rows();

                $this->db->cache_delete_all();
                break;
            case "14":
                $this->db->where('id_usuario', $dados->id_usuario);
                $this->db->update('usuario', array("status" => $status));

                $num_affected_rows = $this->db->affected_rows();

                $this->db->cache_delete_all();
                break;
            case "15":
                $this->db->select('usuario.id_usuario');
                $this->db->join('usuario_realizou_cadastro ur', "ur.id_usuario_cadastrado = usuario.id_usuario", 'INNER');
                $this->db->where('ur.id_usuario_cadastrou', $dados->id_usuario);
                $this->db->or_where('usuario.id_usuario', $dados->id_usuario);
                $usuarios = $this->db->get('usuario');
                foreach ($usuarios->result() as $value) {
                    $usuarios_id[] = $value->id_usuario;
                }

                $this->db->where_in('id_usuario', $usuarios_id);
                $this->db->update('usuario', array("status" => $status));
                $num_affected_rows = $this->db->affected_rows();
                $this->db->cache_delete_all();
                break;
        }

        return $num_affected_rows;
    }

    public function atualiza_senha_usuario($id, $options) {
        $this->db->where('id_usuario', $id);
        $this->db->update('usuario', $options);
    }

    function envia_email_usuario_cadastrado($email, $nome, $cpf, $temLoginSiconv, $senha) {
        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
        $this->email->to($email);

        $urlSistema = base_url() . 'index.php/in/login';
        $urlCabecalho = base_url() . "layout/assets/images/esicar_dados_acesso.png";
        $urlRodape = base_url() . "layout/assets/images/esicar_rodape.png";
        $urlBotao = base_url() . "layout/assets/images/botao_dados_acesso.png";

        $mensagem = "<html>
                        <div align='center' style='background-color: #eeeeee;>
                            <p style='color: #ffffff;'>&nbsp;</p>
                            <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
                                <img src='{$urlCabecalho}' style='width: 580px;'/>
                                <div align='center' style='font-family: arial; margin-left: 20px;'>

                                    <div align='left'  style='width: 550px; margin-top: 50px;'>
                                        <div style='margin-left: 20px; padding-right:1px; font-size: 17px; width: 500px;'>
                                            <p style='font-size: 24px; font-weight: bold; color: #63cf9c;'>Olá, {$nome}</p>
                                            <p>Seu cadastro foi efetuado com sucesso no Sistema e-Sicar.</p>
                                            <p>
                                                <b>Dados de acesso:</b><br/>
                                                Login: {$cpf}<br/>
                                                Senha: {$senha}
                                            </p>
                                            <p style='font-size: 14px;'><span style='color: red;'>*</span>No primeiro acesso, será solicitado a alteração de senha.</p>
                                        </div>
                                        <div style='margin-top: 40px; margin-bottom: 40px; margin-right: 35px;' align='center'>
                                            <a href='{$urlSistema}'><img src='{$urlBotao}' width='400'/></a>
                                        </div>
                                        <br>
                                        <div style='margin-left: 20px; font-size: 17px;'>
                                            <p>Atenciosamente,</p>
                                            <p>Departamento de Atendimento ao Cliente</p>
                                        </div>
                                    </div>
                                </div>
                                <img src='{$urlRodape}' style='width: 580px; margin-top: 40px;'/>
                            </div>
                        </div>
                    </html>";

        $this->email->subject("Cadastro efetuado - Dados de Acesso");
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function envia_email_confirma_cadastro($email, $nome, $cpf, $temLoginSiconv, $senha, $reenvio = false) {
        $this->load->model('system_logs');
        $this->load->model('confirma_cadastro');

        if (!$reenvio) {
            $options = array(
                'nome_usuario' => $nome,
                'cpf_usuario' => $cpf,
                'email_usuario' => $email,
                'tem_login_siconv' => $temLoginSiconv,
                'senha_usuario' => base64_encode($senha)
            );

            $this->confirma_cadastro->insere($options);
        }

        $this->load->library('email');
        $this->email->initialize($this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
        $this->email->to($email);

        $urlSistema = base_url() . "index.php/confirma_email?token=" . base64_encode($email);
        $urlCabecalho = base_url() . "layout/assets/images/esicar_conf_email.png";
        $urlRodape = base_url() . "layout/assets/images/esicar_rodape.png";
        $urlBotao = base_url() . "layout/assets/images/botao_conf_email.png";

//        $urlCabecalho = "http://convenios.physisbrasil.com.br/esicar/layout/assets/images/cab_esicar_generico.png";
//        $urlRodape = "http://convenios.physisbrasil.com.br/esicar/layout/assets/images/rod_esicar_generico.png";
//        $urlBotao = "http://convenios.physisbrasil.com.br/esicar/layout/assets/images/botao_email_confirma.png";

        $mensagem = "<html>
                        <div align='center' style='background-color: #eeeeee;>
                            <p style='color: #ffffff;'>&nbsp;</p>
                            <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
			    	<img src='{$urlCabecalho}' style='width: 580px;'/>
                                <div align='center' style='font-family: arial; margin-left: 20px;'>
                                
                                    <div align='left'  style='width: 550px; margin-top: 50px;'>
                                        <div style='margin-left: 20px; padding-right:1px; font-size: 17px; width: 500px;'>
                                            <p style='font-size: 24px; font-weight: bold; color: #63cf9c;'>Parabéns, {$nome}</p>
                                            <p>É necessário a confirmação do seu email. Para confirmar clique no botão abaixo.</p>
                                        </div>
                                        <br>
                                        <div style='margin-top: 40px; margin-bottom:40px; margin-right: 35px;' align='center'>
                                            <a href='{$urlSistema}'><img src='{$urlBotao}' width='400'/></a>
                                        </div>
                                        <br>
                                        <div style='margin-left: 20px; font-size: 17px;'>
                                            <p>Atenciosamente,</p>
                                            <p>Departamento de Atendimento ao Cliente</p>
                                        </div>
                                    </div>
                                </div>
                                <img src='{$urlRodape}' style='width: 580px; margin-top: 50px;'/>
                            </div>
                        </div>
                    </html>";

        $this->email->subject("Cadastro efetuado");
        $this->email->message($mensagem);
        if ($this->email->send())
            $this->system_logs->add_log(ENVIO_EMAIL_USUARIO_CADASTRO . " - Nome: " . $nome . ", Email: " . $email);
        else {
            $this->system_logs->add_log(FALHA_ENVIO_EMAIL_USUARIO_CADASTRO . " - Nome: " . $nome . ", Email: " . $email);
        }
    }

    public function envia_email_confirma_cadastro_importa($email, $nome, $cpf, $temLoginSiconv, $senha, $reenvio = false) {
        $this->load->model('system_logs');
        $this->load->model('confirma_cadastro');

        if (!$reenvio) {
            $options = array(
                'nome_usuario' => $nome,
                'cpf_usuario' => $cpf,
                'email_usuario' => $email,
                'tem_login_siconv' => $temLoginSiconv,
                'senha_usuario' => base64_encode($senha)
            );

            $this->confirma_cadastro->insere($options);
        }

        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('confirmacao@physisbrasil.com.br', 'Physis Brasil');
        $this->email->to($email);

        $urlSistema = base_url() . "index.php/confirma_email?token=" . base64_encode($email);
        $urlCabecalho = base_url() . "layout/assets/images/cabecalho_email_confirma.png";
        $urlRodape = base_url() . "layout/assets/images/rodape.png";
        $urlBotao = base_url() . "layout/assets/images/botao_email_confirma.png";

        $mensagem = "<html>
			    	<div align='center' style='background-color: #f6f6f6;>
			    	<p style='color: #f6f6f6;'>&nbsp;</p>
			    	<div align='center' style='background-color: #6287c4; width: 520px; color:#fff;'>
			    	<img src='{$urlCabecalho}' style='width: 520px; height: 255px;'/>
			    	<div align='center' style='font-family: calibri; margin-left: 20px;'>
			    	 
			    	<div align='left'  style='width: 500px;'>
			    	<div style='margin-left: 20px; padding-right:1px; font-size: 17px; width: 460px;'>
			    	<p style='font-size: 22px;'>Parabéns, {$nome}</p>
			    	<p>Seu cadastro foi efetuado com sucesso no Sistema e-Sicar.</p>
			    	<p>
			    	É necessário a confirmação de seu email. Para confirma-lo clique no botão abaixo.
			    	</p>
			    	 
			    	</div>
			    	 
			    	<div style='margin-top: 50px; margin-bottom:50px; margin-right: 50px;' align='center'>
			    	<a href='{$urlSistema}'><img src='{$urlBotao}' width='150' height='40'/></a>
			    	</div>
			    	 
			    	</div>
			    	 
			    	</div>
			    	<img src='{$urlRodape}' style='width: 520px; height: 70px;'/>
			    	</div>
			    
			    	</div>
			    	</html>";

        $this->email->subject("Cadastro efetuado");
        $this->email->message($mensagem);
        if ($this->email->send())
            $this->system_logs->add_log_importa(ENVIO_EMAIL_USUARIO_CADASTRO . " - Nome: " . $nome . ", Email: " . $email);
        else
            $this->system_logs->add_log_importa(FALHA_ENVIO_EMAIL_USUARIO_CADASTRO . " - Nome: " . $nome . ", Email: " . $email);
    }

    public function envia_email_automatico($email, $nome) {
        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
        $this->email->to($email);

        $urlSistema = base_url();
        $urlCabecalho = base_url() . "layout/assets/images/cab_esicar_generico.png";
        $urlRodape = base_url() . "layout/assets/images/rod_esicar_generico.png";

        $mensagem = "<html>
                        <div align='center' style='background-color: #eeeeee;>
                            <p style='color: #ffffff;'>&nbsp;</p>
                            <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
			    	<img src='{$urlCabecalho}' style='width: 580px;'/>
                                <div align='center' style='font-family: arial; margin-left: 20px;'>
                                
                                    <div align='left'  style='width: 550px; margin-top: 50px;'>
                                        <div style='margin-left: 20px; padding-right:1px; font-size: 17px; width: 500px;'>
                                            <p style='font-size: 24px; font-weight: bold; color: #63cf9c;'>Olá, {$nome}</p>
                                            <p>Sua mensagem foi recebida com sucesso no sistema e-Sicar.</p>
                                            <p>Já foi encaminhada sua solicitação para a área especifica do departamento de suporte, faremos o possível para atende-lo o mais breve. Se não houver nenhuma resposta em 24 horas entre em contato pelo e-mail abaixo:</p>
                                            <p>contato@physisbrasil.com.br</p>
                                        </div>
                                        
                                        <br>
                                        <div style='margin-left: 20px; font-size: 17px;'>
                                            <p>Atenciosamente,</p>
                                            <p>Departamento de Atendimento ao Cliente</p>
                                        </div>
                                    </div>
                                </div>
                                <img src='{$urlRodape}' style='width: 580px; margin-top: 50px;'/>
                            </div>
                        </div>
                    </html>";

        $this->email->subject("Suporte e-Sicar");
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function envia_email_automatico_sugestao($email, $nome) {
        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
        $this->email->to($email);

        $urlSistema = base_url();
        $urlCabecalho = base_url() . "layout/assets/images/cab_esicar_generico.png";
        $urlRodape = base_url() . "layout/assets/images/rod_esicar_generico.png";

        $mensagem = "<html>
                        <div align='center' style='background-color: #eeeeee;>
                            <p style='color: #ffffff;'>&nbsp;</p>
                            <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
			    	<img src='{$urlCabecalho}' style='width: 580px;'/>
                                <div align='center' style='font-family: arial; margin-left: 20px;'>
                                
                                    <div align='left'  style='width: 550px; margin-top: 50px;'>
                                        <div style='margin-left: 20px; padding-right:1px; font-size: 17px; width: 500px;'>
                                            <p style='font-size: 24px; font-weight: bold; color: #63cf9c;'>Olá, {$nome}</p>
                                            <p>Sua mensagem foi recebida com sucesso no sistema e-Sicar.</p>
                                            <p>Já foi encaminhada sua sugestão para a área especifica. Faremos o possível para atende-lo o mais breve. Caso necessite enviar mais informações entre em contato pelo e-mail abaixo:</p>
                                            <p>contato@physisbrasil.com.br</p>
                                        </div>
                                        
                                        <br>
                                        <div style='margin-left: 20px; font-size: 17px;'>
                                            <p>Atenciosamente,</p>
                                            <p>Departamento de Atendimento ao Cliente</p>
                                        </div>
                                    </div>
                                </div>
                                <img src='{$urlRodape}' style='width: 580px; margin-top: 80px;'/>
                            </div>
                        </div>
                    </html>";

        $this->email->subject("Contato");
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function notifica_vendedor_user_ativado($id_usuario_ativado) {
        $this->db->where('id_usuario_cadastrado', $id_usuario_ativado);
        $id_vendedor = $this->db->get('usuario_realizou_cadastro')->row(0);
        if (isset($id_vendedor->id_usuario_cadastrou)) {
            $this->db->flush_cache();

            $dados_vendedor = $this->get_by_id($id_vendedor->id_usuario_cadastrou);

            $this->db->flush_cache();

            $dados_usuario = $this->get_by_id($id_usuario_ativado);

            $this->load->library('email');

            $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
            $this->email->set_newline("\r\n");

            $this->email->set_mailtype('html');
            $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
            $this->email->to($dados_vendedor->email);

            $this->email->subject("Aviso de Ativação de Usuário");
            $this->email->message("Caro " . $dados_vendedor->nome . ", <br><br> O cliente " . $dados_usuario->nome . " que você cadastrou já foi ativado no sistema e-SICAR. Favor informá-lo.<br><br> Att,");
            $this->email->send();
        }
    }

    public function notifica_admin_usuario_cadastrado($id_usuario) {
        $dados_usuario = $this->get_by_id($id_usuario);

        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
        $this->email->to("manoel.carvalho.neto@gmail.com");

        $this->email->subject("Aviso de Ativação de Usuário");
        $this->email->message("Caro administrador, <br><br> O usuário " . $dados_usuario->nome . " foi cadastrado no sistema e-SICAR.<br><br> Att,");
        $this->email->send();
    }

    public function enviar_email_cron($mensagem, $parteTitulo = "") {
        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('physisbrasil@gmail.com', 'Physis Brasil');
        $this->email->to('manoel.carvalho.neto@gmail.com');

        $this->email->subject("Execução Serviço CRON " . $parteTitulo);
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function enviar_email_cron_eliumar($mensagem, $parteTitulo = "") {
        $this->load->library('email');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->set_mailtype('html');
        $this->email->from('contato@physisbrasil.com.br', 'Physis Brasil');
        $this->email->to('eliumar_carlos@yahoo.com.br');

        $this->email->subject("Execução Serviço CRON " . $parteTitulo);
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function verifica_possui_usuario_siconv() {
        $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
        $dados_usuario = $this->db->get('usuario')->row(0);

        if ($this->session->userdata('nivel') == 2) {
            $this->load->model('usuario_gestor');
            $tipo_gestor = $this->usuario_gestor->get_tipo_gestor($this->session->userdata('id_usuario'));
            if ($tipo_gestor == 0)
                return ($dados_usuario->login_siconv === "" || !isset($dados_usuario->login_siconv)) || ($dados_usuario->senha_siconv === "" || !isset($dados_usuario->senha_siconv));
            else
                return false;
        } else
            return ($dados_usuario->login_siconv === "" || !isset($dados_usuario->login_siconv)) || ($dados_usuario->senha_siconv === "" || !isset($dados_usuario->senha_siconv));
    }

    public function get_dados_cpf_email($cpf, $email) {
        $this->db->where('login', $cpf);
        $this->db->where('email', $email);
        return $this->db->get('usuario')->row(0);
    }

    public function atualiza_aceite_termos() {
        $this->db->insert('aceite_licenca_uso', array('id_usuario' => $this->session->userdata('id_usuario')));
    }

    public function realaciona_usuarios_cadastro($options) {
        $this->db->insert('usuario_realizou_cadastro', $options);
    }

    public function busca_data_aceite($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('aceite_licenca_uso')->row(0);

        if (!empty($query))
            return date('d/m/Y H:m:i', strtotime($query->data));
        else
            return null;
    }

    public function inicializa_config_email($email) {
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "bra14.hostgator.com.br";
        $config['smtp_port'] = "587";
        $config['smtp_user'] = "contato@physisbrasil.com.br";
        $config['smtp_pass'] = "Physis_2013";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";

        return $config;
    }

    public function inicializa_config_email_gmail($email) {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'physisbrasil';
        $config['smtp_pass'] = 'phy_2015';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';

        return $config;
    }

    public function verifica_eh_parlamentar() {
        $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
        $query = $this->db->get('gestor')->row(0);

        return $query->tipo_gestor;
    }

    public function get_usuarios_vendedor_cadastrou() {
        if ($this->session->userdata('nivel') == 4)
            $this->db->where("id_usuario_cadastrou", $this->session->userdata('id_usuario'));
        else if ($this->session->userdata('nivel') == 1) {
            $this->db->where('id_nivel', 4);
            $this->db->where('status', 'A');
            $vendedores = $this->db->get('usuario')->result();

            $this->db->flush_cache();

            $lista = array();
            foreach ($vendedores as $v)
                $lista[] = $v->id_usuario;

            $this->db->where_in('id_usuario_cadastrou', $lista);
        }

        $this->db->distinct();
        $this->db->select("id_usuario, nome");
        $this->db->join("usuario_realizou_cadastro", "id_usuario = id_usuario_cadastrado");
        $this->db->where('id_nivel', 2);
        $query = $this->db->get('usuario')->result();

        return $query;
    }

    public function get_representante_gestor($id) {
        $this->db->where('id_usuario_cadastrado', $id);
        $id_representante = $this->db->get('usuario_realizou_cadastro')->row(0)->id_usuario_cadastrou;

        $this->db->flush_cache();

        $representante = $this->get_by_id($id_representante);

        return $representante->nome;
    }

    public function get_cidade_gestor($id_gestor) {
        $this->db->join("usuario_cnpj", "usuario_cnpj.id_cnpj = cnpj_siconv.id_cnpj_siconv");
        $this->db->where('id_usuario', $id_gestor);
        return $this->db->get("cnpj_siconv")->row(0);
    }

    public function get_ultimo_acesso_gestor($id_gestor) {
        $this->db->distinct();
        $this->db->select("usuario.id_usuario");
        $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario OR usuario.id_usuario = ' . $id_gestor, 'LEFT');
        $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
        $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = usuario.id_nivel", 'LEFT');
        $this->db->where('gestor.id_usuario', $id_gestor);
        $this->db->or_where('usuario.id_usuario', $id_gestor);
        $usuarios = $this->db->get('usuario')->result();

        $lista_ids = array();
        foreach ($usuarios as $id)
            $lista_ids[] = $id->id_usuario;

        $this->load->model('system_logs');
        return $this->system_logs->get_log_ultimo_acesso($lista_ids);
    }

    /**
     * Atualiza o modo em que o vendedor logou no sistema, se é apresentação ou não. Se não for,
     * todo o perfil dele será bloqueado.
     * @param integer $id_usuario ID do vendedor a ser atualizado
     * @param string $modo Modo de entrada, S = Vai realizar apresentação / N = Não vai realizar apresentação
     */
    public function update_modo_vendedor($id_usuario, $modo = 'S') {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('usuario', array('vendedor_visita' => $modo));
    }

    /**
     * Verifica se o usuário é um vendedor e vai retornar se ele vai ou não realizar uma apresentação.
     * Se não for vendedor, retornar que o usuário é de um nivel diferente de vendedor
     * @return boolean
     */
    public function ativa_modo_normal_ou_vendedor() {
        if ($this->session->userdata('nivel') == 4) {
            $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
            $modo = $this->db->get('usuario')->row(0);

            $podeAcessar = $modo->vendedor_visita == 'S';

            return $podeAcessar;
        } else
            return $this->session->userdata('nivel') != 4;
    }

    public function set_codigo_parlamentar_vendedor($id_usuario, $options) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('usuario', $options);
    }

    public function get_proponentes_by_gestor($id_usuario) {
        $this->db->distinct();
        $this->db->select('cs.*');
        $this->db->join('usuario_cnpj uc', 'uc.id_cnpj = cs.id_cnpj_siconv');
        $this->db->where('uc.id_usuario', $id_usuario);

        $query = $this->db->get('cnpj_siconv cs');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_all_parlamentar($uf) {
        $this->db->where('uf', $uf);
        $this->db->order_by('cargo_parlamentar', "DESC");
        $this->db->order_by('nome_parlamentar', "ASC");
        $parlamentares = $this->db->get('nomes_parlamentar')->result();

        foreach ($parlamentares as $p)
            $lista[$p->cargo_parlamentar][] = array('codigo' => $p->codigo_parlamentar, 'nome' => $p->nome_parlamentar, 'partido' => $p->partido);

        return $lista;
    }

    public function get_parlamentar_by_cargo($cargo, $limit = null, $offset = null) {
        if (!is_null($limit))
            $this->db->limit($limit);

        if (!is_null($offset))
            $this->db->offset($offset);

        $this->db->where('cargo_parlamentar', $cargo);
        $query = $this->db->get('nomes_parlamentar')->result();

        $this->db->flush_cache();

        return $query;
    }

    public function get_parlamentar_by_code($codigo, $nome = "") {
        if ($nome == "") {
            $this->db->where('codigo_parlamentar', $codigo);
            $nome_parlamentar = $this->db->get('nomes_parlamentar')->row(0);

            $this->db->flush_cache();

            if (empty($nome_parlamentar)) {
                return "";
            } else {
                return $nome_parlamentar->nome_parlamentar;
            }
        } else
            return $nome;
    }

    public function get_parlamentar_vinculado_vendedor() {
        $this->db->where('codigo_parlamentar', $this->get_by_id($this->session->userdata('id_usuario'))->vendedor_codigo_parlamentar);
        $nome_parlamentar = $this->db->get('nomes_parlamentar');

        return $nome_parlamentar->row(0)->nome_parlamentar;
    }

    public function verifica_subgestor($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('gestor')->row(0);

        if (!empty($query))
            return $query->tipo_subgestor == "M" || $query->tipo_subgestor == "S";
        else
            return false;
    }

    public function get_users_subgestor_by_gestor($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('gestor')->row(0);

        if (!empty($query)) {
            if ($query->tipo_subgestor == "M" || $query->tipo_subgestor == "S") {
                $this->db->where('id_gestor', $query->id_gestor);
                $query2 = $this->db->get('usuario_subgestor')->result();

                if (!empty($query2))
                    return $query2;
            }
        }

        return null;
    }

}
