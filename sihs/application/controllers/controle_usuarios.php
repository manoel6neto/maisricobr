<?php

include 'application/controllers/BaseController.php';

class controle_usuarios extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->model('usuariomodel');
        $this->usuario_logado = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
        $this->session->set_userdata('nivel', $this->usuario_logado->id_nivel);
    }

    public function index() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('usuariomodel');
        $this->load->model('confirma_cadastro');
        $this->load->model('proposta_model');
        $this->load->model('usuario_subgestor_model');

        if ($this->input->post())
            $data['pesquisa'] = $this->input->post('pesquisa', TRUE);

        $data['confirma_cadastro'] = $this->confirma_cadastro;
        $data['usuariosubgestormodel'] = $this->usuario_subgestor_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['proposta_model'] = $this->proposta_model;
        $data['lista_usuarios'] = $this->usuariomodel->get_all_usuarios_permissao($this->input->post('pesquisa', TRUE), $this->input->get_post('avulso', TRUE));
        $data['title'] = 'SIHS - Controle de Usuários';
        $data['main'] = "controle_usuarios/lista_usuarios";

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function alterar_gestor() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('usuariomodel');
        $this->load->model('usuario_subgestor_model');

        $usuario = NULL;
        $subGestor = NULL;

        if ($this->input->get('id', TRUE) != FALSE) {
            $usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));
        }

        if ($usuario != NULL) {
            $usuario_subgestor = $this->usuario_subgestor_model->get_by_usuario($usuario->id_usuario);
            if ($usuario_subgestor != NULL) {
                $subGestor = $this->usuariomodel->get_by_id($usuario_subgestor->id_gestor);
            }
        }

        if ($this->input->get_post('id_subgestor', TRUE) != FALSE && $this->input->get_post('id_usuario', TRUE) != FALSE) {
            $this->usuario_subgestor_model->add_or_update_usuario_gestor_for_usuario($this->input->get_post('id_usuario', TRUE), $this->input->get_post('id_subgestor', TRUE));

            $this->alert("Vinculo alterado com sucesso.");
            $this->encaminha(base_url('index.php/controle_usuarios'));
        }

        $array_subgestores = array();
        $subGestores = $this->usuariomodel->get_users_by_nivel_id(6);
        foreach ($subGestores as $sub) {
            $array_subgestores[$sub->id_usuario] = $sub->nome;
        }

        $data['subGestores'] = $array_subgestores;
        $data['subGestor'] = $subGestor;
        $data['usuario'] = $usuario;
        $data['title'] = 'SIHS - Alteração de gestor';
        $data['main'] = "controle_usuarios/altera_gestor";

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function promover_a_gestor() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('usuariomodel');
        $this->load->model('usuario_subgestor_model');
        $this->load->model('usuario_gestor');
        $this->load->model('gestor');

        $usuario = NULL;

        if ($this->input->get('id', TRUE) != FALSE) {
            $usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));

            if ($usuario != NULL) {
                if ($usuario->id_nivel == 3) {
                    $usuario_gestor_obj = $this->usuario_gestor->get_by_usuario($usuario->id_usuario);
                    if ($usuario_gestor_obj != NULL) {
                        $gestor = $this->gestor->get_by_id($usuario_gestor_obj->id_gestor);
                        if ($gestor != NULL) {
                            $gestor_for_user = array(
                                'validade' => $gestor->validade,
                                'quantidade_cnpj' => $gestor->quantidade_cnpj,
                                'id_usuario' => $usuario->id_usuario,
                                'inicio_vigencia' => $gestor->inicio_vigencia,
                                'tipo_gestor' => $gestor->tipo_gestor,
                                'nivel_gestor' => $gestor->nivel_gestor,
                                'tipo_subgestor' => 'S'
                            );

                            $usuario_update_array = array(
                                'id_nivel' => 6
                            );

                            $this->usuariomodel->update_by_array($usuario->id_usuario, $usuario_update_array);
                            $this->gestor->add_gestor_from_array($gestor_for_user);
                        }
                    }
                } else {
                    $usuario_gestor_obj = $this->usuario_subgestor_model->get_by_usuario($usuario->id_usuario);
                    if ($usuario_gestor_obj != NULL) {
                        $gestor = $this->gestor->get_by_user_id($usuario_gestor_obj->id_gestor);
                        if ($gestor != NULL) {

                            $gestor_of_gestor = $this->gestor->get_by_id($this->usuario_gestor->get_by_usuario($gestor->id_usuario)->id_gestor);

                            $gestor_for_user = array(
                                'validade' => $gestor_of_gestor->validade,
                                'quantidade_cnpj' => $gestor_of_gestor->quantidade_cnpj,
                                'id_usuario' => $usuario->id_usuario,
                                'inicio_vigencia' => $gestor_of_gestor->inicio_vigencia,
                                'tipo_gestor' => $gestor_of_gestor->tipo_gestor,
                                'nivel_gestor' => $gestor_of_gestor->nivel_gestor,
                                'tipo_subgestor' => 'S'
                            );

                            $usuario_update_array = array(
                                'id_nivel' => 6
                            );

                            $usuario_gestor = array(
                                'id_usuario' => $usuario->id_usuario,
                                'id_gestor' => $gestor_of_gestor->id_gestor
                            );

                            $this->usuariomodel->update_by_array($usuario->id_usuario, $usuario_update_array);
                            $this->gestor->add_gestor_from_array($gestor_for_user);
                            $this->usuario_gestor->add_from_array($usuario_gestor);
                            $this->usuario_subgestor_model->delete_by_usuario($usuario->id_usuario);
                        }
                    }
                }

                $this->alert("Usuário promovido com sucesso!!");
                $this->encaminha(base_url('index.php/controle_usuarios'));
            }
        }
    }

    public function change_type() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('usuariomodel');
        $this->load->model('usuario_subgestor_model');
        $this->load->model('usuario_gestor');
        $this->load->model('gestor');

        $usuario = NULL;

        if ($this->input->get('id', TRUE) != FALSE) {
            $usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));

            if ($usuario != NULL) {
                if ($usuario->id_nivel == 3) {
                    $usuario_update_array = array(
                        'id_nivel' => 7
                    );

                    $this->usuariomodel->update_by_array($usuario->id_usuario, $usuario_update_array);
                    $this->usuario_gestor->delete_by_usuario($usuario->id_usuario);
                } else {
                    $usuario_update_array = array(
                        'id_nivel' => 3
                    );

                    $usuario_gestor = array(
                        'id_usuario' => $usuario->id_usuario,
                        'id_gestor' => $this->gestor->get_by_user_id($this->session->userdata('id_usuario'))->id_gestor
                    );

                    $this->usuariomodel->update_by_array($usuario->id_usuario, $usuario_update_array);
                    $this->usuario_subgestor_model->delete_by_usuario($usuario->id_usuario);
                    $this->usuario_gestor->add_from_array($usuario_gestor);
                }

                $this->alert("Nível alterado com sucesso!!");
                $this->encaminha(base_url('index.php/controle_usuarios'));
            }
        }
    }

    public function get_csv_usuarios_gestor() {
        if ($this->input->get('id', TRUE) != FALSE) {
            $lista_usuario = array();
            $lista_id_usuarios = null;
            $usuario = null;
            $gestor = null;

            $this->db->where('id_usuario', $this->input->get('id', TRUE));
            $query_usuario = $this->db->get('usuario');
            if ($query_usuario->num_rows > 0) {
                $usuario = $query_usuario->row(0);
            }

            if ($usuario != null) {
                $this->db->where('id_usuario', $usuario->id_usuario);
                $query_gestor = $this->db->get('gestor');
                if ($query_gestor->num_rows > 0) {
                    $gestor = $query_gestor->row(0);
                }

                if ($gestor != null) {
                    $this->db->where('id_gestor', $gestor->id_gestor);
                    $query_lista_usuarios_gestor = $this->db->get('usuario_gestor');
                    if ($query_lista_usuarios_gestor->num_rows > 0) {
                        $lista_id_usuarios = $query_lista_usuarios_gestor->result();
                    }

                    if ($lista_id_usuarios != null) {
                        foreach ($lista_id_usuarios as $user) {
                            $temp_user = null;
                            $this->db->where('id_usuario', $user->id_usuario);
                            $query_temp_user = $this->db->get('usuario');
                            if ($query_temp_user->num_rows > 0) {
                                $temp_user = $query_temp_user->row(0);
                            }

                            if ($temp_user != null) {
                                array_push($lista_usuario, $temp_user);
                            }
                        }
                    }

                    array_push($lista_usuario, $usuario);
                }

                if ($lista_usuario != null) {
                    if (count($lista_usuario) > 0) {
                        // output headers so that the file is downloaded rather than displayed
                        header('Content-Type: text/csv; charset=ISO-8859-1');
                        header('Content-Disposition: attachment; filename=dados_gestor.csv');

                        // create a file pointer connected to the output stream
                        $output = fopen('php://output', 'w');

                        // output the column headings
                        fputcsv($output, array('nome', 'email', 'celular', 'tipo'));
                        foreach ($lista_usuario as $user_to_csv) {
                            // loop over the rows, outputting them
                            $row = array(
                                'nome' => utf8_decode($user_to_csv->nome),
                                'email' => utf8_decode($user_to_csv->email),
                                'celular' => $user_to_csv->celular,
                                'tipo' => $user_to_csv->id_nivel == 2 ? "gestor" : "subordinado"
                            );

                            fputcsv($output, $row);
                        }

//                        $output->close();
//                        
//                        return $output;
                    }
                }
            }
        }
    }

    public function reenvia_email() {
        if ($this->input->get('id', TRUE) != FALSE) {
            $this->load->model('usuariomodel');
            $this->load->model('confirma_cadastro');
            $this->load->model('system_logs');

            $usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));

            $temUsuarioSiconv = true;

            $dados_cadastro = $this->confirma_cadastro->busca_dados_validar($usuario->email, $usuario->login);

            $this->usuariomodel->envia_email_confirma_cadastro($usuario->email, $usuario->nome, $usuario->login, $temUsuarioSiconv, base64_decode($dados_cadastro->senha_usuario), true);

            $this->alert('Email reenviado com sucesso!');
            $this->encaminha(base_url('index.php/controle_usuarios'));
        } else {
            $this->alert("Erro ao reenviar email de confirmação");
            $this->encaminha(base_url('index.php/controle_usuarios'));
        }
    }

    public function atualiza_dados_confirmacao($id, $options) {
        $this->load->model('confirma_cadastro');

        $usuario = $this->usuariomodel->get_by_id($id);

        if ($usuario->primeiro_acesso == 'S') {
            $temUsuarioSiconv = true;

            $dados_cadastro = $this->confirma_cadastro->busca_dados_validar($usuario->email, $usuario->login);

            $options = array(
                'nome_usuario' => $options['nome'],
                'cpf_usuario' => $options['login'],
                'email_usuario' => $options['email'],
                'tem_login_siconv' => $temUsuarioSiconv,
                'senha_usuario' => $dados_cadastro->senha_usuario
            );

            $this->confirma_cadastro->atualiza($options, $dados_cadastro->confirma_cadastro_id);
        }
    }

    // Método para atualizar e adicionar os encarregados para um determinado usuário do sistema
    public function atualiza_encarregados($id_gestor_default = null) {
        $this->session->set_userdata('pagAtual', '');
        $this->load->model('encarregado_model');
        $this->load->model('gestor');

        $data['title'] = 'SIHS - Atualização de Gestores';
        $data['main'] = "controle_usuarios/lista_encarregados";
        if ($id_gestor_default == null) {
            if ($this->input->get_post('id_gestor', TRUE) !== false) {
                $data['id_gestor'] = $this->input->get_post('id_gestor', TRUE);
            } else {
                $data['id_gestor'] = $this->gestor->get_id_by_usuario($this->input->get('id_usuario', TRUE));
            }
        } else {
            $data['id_gestor'] = $id_gestor_default;
        }
        $data['encarregados'] = $this->encarregado_model->get_by_gestor($data['id_gestor']);

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function excluir_encarregado() {
        $this->session->set_userdata('pagAtual', '');
        $this->load->model('encarregado_model');
        $this->load->model('gestor');

        $id_encarregado = $this->input->get('id_encarregado', TRUE);
        $id_gestor = $this->input->get('id_gestor', TRUE);
        $this->encarregado_model->del_by_id($id_encarregado);

        $this->atualiza_encarregados($id_gestor);
    }

    public function novo_encarregado() {
        $this->session->set_userdata('pagAtual', '');
        $this->load->model('encarregado_model');

        $id_gestor = $this->input->get_post('id_gestor', TRUE);

        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('funcao', 'Função', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[encarregado.email]|is_unique[usuario.email]|valid_email');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('valid_email', 'O campo %s deve conter um endereço de email válido.');
            $this->form_validation->set_message('is_unique', '%s já cadastrado.');

            if ($this->form_validation->run() === TRUE) {
                $encarregado = $this->input->post();
                $linhas_afetadas = $this->encarregado_model->adicionar_by_gestor_id($id_gestor, $encarregado);

                $this->load->model('system_logs');
                $this->load->model('gestor');
                $this->load->model('usuariomodel');
                $this->system_logs->add_log(ENCARREGADOS_ADICIONADOS . " - ID GESTOR: " . $id_gestor . ", Nome: " . $this->usuariomodel->get_by_id($this->gestor->get_by_id($id_gestor)->id_usuario)->nome);

                $this->alert("Gestor adicionado com sucesso.");
                $this->encaminha(base_url() . 'index.php/controle_usuarios/atualiza_encarregados?id_gestor=' . $id_gestor);
            }
        }

        $data['title'] = 'SIHS - Novo Responsável';
        $data['main'] = "controle_usuarios/novo_encarregado";
        $data['id_gestor'] = $id_gestor;

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function editar_encarregado() {
        $this->session->set_userdata('pagAtual', '');
        $this->load->model('encarregado_model');

        $id_gestor = $this->input->get_post('id_gestor', TRUE);

        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('funcao', 'Função', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|callback_valida_email_atualizar|valid_email');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('valid_email', 'O campo %s deve conter um endereço de email válido.');
            $this->form_validation->set_message('is_unique', '%s já cadastrado.');

            if ($this->form_validation->run() === TRUE) {
                $id = $this->input->get('id', TRUE);

                $encarregado = $this->input->post();
                $linhas_afetadas = $this->encarregado_model->atualizar_by_gestor_id($id_gestor, $id, $encarregado);

                $this->load->model('system_logs');
                $this->load->model('gestor');
                $this->load->model('usuariomodel');
                $this->system_logs->add_log(ENCARREGADOS_ADICIONADOS . " - ID GESTOR: " . $id_gestor . ", Nome: " . $this->usuariomodel->get_by_id($this->gestor->get_by_id($id_gestor)->id_usuario)->nome);

                $this->alert("Gestor editado com sucesso.");
                $this->encaminha(base_url() . 'index.php/controle_usuarios/atualiza_encarregados?id_gestor=' . $id_gestor);
            }
        }

        if ($this->input->get_post('id', TRUE) !== false) {
            $encarregado = $this->encarregado_model->get_by_id($this->input->get_post('id', TRUE));
            $data['encarregado'] = $encarregado;
        }

        $data['title'] = 'SIHS - Atualizar Responsável';
        $data['main'] = "controle_usuarios/novo_encarregado";
        $data['id_gestor'] = $id_gestor;

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function editar_permissoes() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('usuariomodel');
        $this->load->model('permissoes_usuario');

        if ($this->input->post(null, TRUE)) {
            $id = $this->input->get('id', TRUE);

            $optionsPermissoes = array(
                'criar_usuario' => $this->input->post('criar_usuario'),
                'editar_usuario' => $this->input->post('editar_usuario'),
                'ativar_usuario' => $this->input->post('ativar_usuario'),
                'desativar_usuario' => $this->input->post('desativar_usuario'),
                'tornar_proj_padrao' => $this->input->post('tornar_proj_padrao'),
                'apagar_projeto_padrao' => $this->input->post('apagar_projeto_padrao'),
                'vincular_cnpj_usuario' => $this->input->post('vincular_cnpj_usuario'),
                'editar_cnpj_usuario' => $this->input->post('editar_cnpj_usuario'),
                'consultar_programa' => $this->input->post('consultar_programa'),
                'relatorio_programa' => $this->input->post('relatorio_programa'),
                'criar_projeto' => $this->input->post('criar_projeto'),
                'editar_projeto' => $this->input->post('editar_projeto'),
                'apagar_projeto' => $this->input->post('apagar_projeto'),
                'alterar_end_projeto' => $this->input->post('alterar_end_projeto'),
                'duplicar_projeto' => $this->input->post('duplicar_projeto'),
                'utilizar_padrao' => $this->input->post('utilizar_padrao'),
                'exportar_siconv' => $this->input->post('exportar_siconv'),
                'consultar_proposta' => $this->input->post('consultar_proposta'),
                'relatorio_proposta' => $this->input->post('relatorio_proposta'),
                'status_proposta' => $this->input->post('status_proposta'),
                'parecer_proposta' => $this->input->post('parecer_proposta'),
                'visualiza_emendas' => $this->input->post('visualiza_emendas'),
                'visualiza_prop_parecer' => $this->input->post('visualiza_prop_parecer')
            );

            $retorno = $this->permissoes_usuario->update_by_usuario_id($id, $optionsPermissoes);

            $dados_usuario = $this->usuariomodel->get_by_id($id);

            if ($retorno != null && $retorno > 0) {
                $this->load->model('system_logs');
                $this->system_logs->add_log(PERMISSAO_USUARIO_EDITADA . " - ID Responsável: " . $this->usuario_logado->id_usuario . ", Nome usuário: " . $this->input->post('nome', TRUE));

                $this->alert("Permissões editadas com sucesso.");

                if ($dados_usuario->id_nivel != 9)
                    $this->encaminha(base_url() . 'index.php/controle_usuarios');
                else
                    $this->encaminha(base_url() . 'index.php/controle_usuarios/lista_usuario_avulso');
            }
        }

        if ($this->input->get_post('id', TRUE) !== false) {

            $data['usuario'] = $this->usuariomodel->get_by_id($this->input->get_post('id', TRUE));
            //$data['permissoes'] = $this->permissoes_usuario->get_by_usuario_id($this->input->get_post('id', TRUE));
            $data['permissoes'] = $this->permissoes_usuario->get_by_usuario_id($this->input->get_post('id', TRUE));
        }

        $data['title'] = 'SIHS - Controle de Usuários';
        $data['main'] = "controle_usuarios/permissao_usuario";

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function novo_usuario() {
        $this->session->set_userdata('pagAtual', 'novo_usuario');
        $this->load->model('nivel_usuario');
        $this->load->model('usuariomodel');
        $this->load->model('programa_model');
        $this->load->model('proponente_siconv_model');
        $this->load->model('system_logs');

        $nivelUsuarios = $this->nivel_usuario->get_all();
        $usuariosGestor = $this->usuariomodel->get_all_gestor();

        $dados_post = $this->input->post();
        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('login', 'CPF', 'required|min_length[11]|callback_valid_cpf');
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[usuario.email]|matches[confirmar_email]');
            $this->form_validation->set_rules('confirmar_email', 'Confirmar Email', '');
            $this->form_validation->set_rules('telefone', 'Telefone', '');
            $this->form_validation->set_rules('celular', 'Celular', '');
            $this->form_validation->set_rules('login_siconv', 'Login SICONV', '');
            $this->form_validation->set_rules('senha_siconv', 'Senha SICONV', '');
            $this->form_validation->set_rules('id_nivel', 'Nível', 'required');
            $this->form_validation->set_rules('entidade', 'Nome da Entidade', '');
            $this->form_validation->set_rules('cnpj_instituicao', 'required|max_length[100]');

            if ($this->session->userdata('nivel') == 1)
                $this->form_validation->set_rules('usuario_sistema', 'Acesso ao Sistema', 'required');

            if ($this->input->post('id_nivel') === "2") {
                $this->form_validation->set_rules('validade', 'Período do contrato', 'required');
                if ($this->input->post('tipo_gestor', TRUE) != "2")
                    $this->form_validation->set_rules('quantidade_cnpj', 'Quantidade de CNPJs', 'required');
                $this->form_validation->set_rules('tipo_gestor', 'Tipo Gestor', 'required');

                if ($this->input->post('tipo_gestor', TRUE) == "0") {
                    $this->form_validation->set_rules('estado', 'Estado', 'required');
                    $this->form_validation->set_rules('municipio', 'Municipio', 'required');
                    $this->form_validation->set_rules('proponente', 'Proponente', 'required|callback_valida_quantidade');
                }
            } else if ($this->input->post('id_nivel', TRUE) == "3" || $this->input->post('id_nivel') == "7")
                $this->form_validation->set_rules('id_gestor', 'Gestor do Sistema', '');
            else if ($this->input->post('id_nivel', TRUE) == "4") {
                $this->form_validation->set_rules('estado_restrito', 'Acesso Restrito - Estado', 'required');
                $this->form_validation->set_rules('esfera_restrita', 'Acesso Restrito - Esfera Administrativa', 'required');
            }

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('valid_email', 'O campo %s deve conter um endereço de email válido.');
            $this->form_validation->set_message('is_unique', '%s já cadastrado.');
            $this->form_validation->set_message('min_length', '%s deve possuir %s digitos.');
            $this->form_validation->set_message('matches', '%s e %s devem ser iguais');
            $this->form_validation->set_message('max_length', 'O campo %s deve conter %s dígitos.');

            if ($this->form_validation->run() === TRUE) {
                $optionsUsuario = $this->input->post();
                $optionsUsuario['senha'] = rand(1000, 9999999);
                unset($optionsUsuario['confirmar_email']);

                if ($this->input->post('id_nivel') === "2")
                    $optionsUsuario['status'] = 'I';

                if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 6) {
                    if ($this->input->post('id_nivel', TRUE) == 3 || $this->input->post('id_nivel', TRUE) == 5 || $this->input->post('id_nivel') == 6 || $this->input->post('id_nivel') == 7 || $this->input->post('id_nivel') == 8)
                        $optionsUsuario['usuario_sistema'] = $this->session->userdata('usuario_sistema');
                }

                $ultimo_usuario = $this->usuariomodel->insere_usuario($optionsUsuario);

                if ($ultimo_usuario != null) {
                    $this->load->model('gestor');

                    if ($this->input->post('id_nivel') === "2") {
                        $this->load->model('data_model');
                        $optionsGestor = array(
                            'validade' => $this->data_model->retornaNovaData(date("Y-m-d"), $this->input->post('validade', TRUE), true),
                            'quantidade_cnpj' => $this->input->post('quantidade_cnpj', TRUE),
                            'id_usuario' => $ultimo_usuario,
                            'inicio_vigencia' => date("Y-m-d"),
                            'tipo_gestor' => $this->input->post('tipo_gestor', TRUE)
                        );

                        $ultimo_gestor = $this->gestor->insere_gestor($optionsGestor);

                        if ($this->input->post('tipo_gestor', TRUE) == "0") {
                            $this->load->model('cnpj_siconv');
                            $this->load->model('usuario_cnpj');

                            $proponentes = $this->input->post('proponente');

                            foreach ($proponentes as $proponente) {
                                $options_cnpj = array(
                                    'cnpj' => preg_replace('/[^0-9]/', '', (string) $proponente),
                                    'id_cidade' => $this->input->post('municipio'),
                                    'cnpj_instituicao' => $this->proponente_siconv_model->get_instituicao_nome($proponente),
                                    'sigla' => $this->input->post('estado'),
                                    'esfera_administrativa' => $this->proponente_siconv_model->get_instituicao_esfera($proponente)
                                );

                                $id_cnpj = $this->cnpj_siconv->insere_cnpj($options_cnpj);
                                if ($id_cnpj > 0)
                                    $this->usuario_cnpj->insere_usuario_cnpj($ultimo_usuario, $id_cnpj);
                            }

                            $this->usuariomodel->notifica_admin_usuario_cadastrado($ultimo_usuario);

                            $this->system_logs->add_log(VINCULA_CNPJ . " - ID: " . $ultimo_usuario . ", Nome: " . $this->usuariomodel->get_by_id($ultimo_usuario)->nome);
                        } else {
                            if ($this->input->post('tipo_gestor', TRUE) == "2") {
                                //Atrelando todos os cnpjs do estado
                                //Get Proponentes
                                $this->db->select("cnpj, nome, codigo_municipio, municipio_uf_sigla, esfera_administrativa");
                                $this->db->where("municipio_uf_sigla = '{$this->input->post('estado')}' and (esfera_administrativa = 'ESTADUAL' or esfera_administrativa = 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA')");
                                $query = $this->db->get('proponente_siconv');

                                if ($query->num_rows > 0) {
                                    foreach ($query->result() as $prop) {
                                        //Inserindo o cnpj_siconv
                                        $ultimo_cnpj_siconv = null;
                                        $array_cnpj_siconv = array(
                                            'cnpj' => preg_replace('/[^0-9]/', '', (string) $prop->cnpj),
                                            'id_cidade' => $prop->codigo_municipio,
                                            'cnpj_instituicao' => $prop->nome,
                                            'sigla' => $prop->municipio_uf_sigla,
                                            'esfera_administrativa' => $prop->esfera_administrativa
                                        );

                                        $this->db->insert('cnpj_siconv', $array_cnpj_siconv);
                                        $ultimo_cnpj_siconv = $this->db->insert_id();

                                        if ($ultimo_cnpj_siconv != null) {
                                            if ($ultimo_cnpj_siconv != 0) {
                                                //inserindo usuario_cnpj
                                                $array_usuario_cnpj = array(
                                                    'id_usuario' => $ultimo_usuario,
                                                    'id_cnpj' => $ultimo_cnpj_siconv
                                                );

                                                $this->db->insert('usuario_cnpj', $array_usuario_cnpj);
                                            }
                                        }
                                    }

                                    $array_update_gestor = array(
                                        'quantidade_cnpj' => count($query->result())
                                    );

                                    $this->db->where('id_gestor', $ultimo_gestor);
                                    $this->db->update('gestor', $array_update_gestor);
                                }
                            }
                        }

                        $this->envia_sms($this->usuariomodel->get_by_id($ultimo_usuario)->nome);
                    } else if ($this->input->post('id_nivel') === "3" || $this->input->post('id_nivel') === "5") {
                        $this->load->model('usuario_gestor');

                        if ($this->session->userdata('nivel') == 1)
                            $idGestor = $this->input->post('id_gestor', TRUE);
                        else
                            $idGestor = $this->session->userdata('id_usuario');
                        $this->usuario_gestor->add_or_update_usuario_gestor_for_usuario($ultimo_usuario, $this->gestor->get_id_by_usuario($idGestor));
                    } else if ($this->input->post('id_nivel') == "4") {
                        $this->load->model('estados_direito_vendedor_model');
                        $this->load->model('esfadm_direito_vendedor_model');

                        $this->estados_direito_vendedor_model->limpa_restricao($ultimo_usuario);
                        $this->esfadm_direito_vendedor_model->limpa_restricao($ultimo_usuario);

                        $estados_restritos = $this->input->post('estado_restrito', TRUE);
                        if (!empty($estados_restritos)) {
                            $lista_estados_vendedor = array();
                            foreach ($estados_restritos as $restrito)
                                $lista_estados_vendedor[] = array('id_vendedor' => $ultimo_usuario, 'estado_sigla' => $restrito);

                            if (!empty($lista_estados_vendedor))
                                $this->estados_direito_vendedor_model->insere($lista_estados_vendedor);
                        }

                        $esferas_restritas = $this->input->post('esfera_restrita', TRUE);
                        if (!empty($esferas_restritas)) {
                            $lista_esferas_restritas = array();
                            foreach ($esferas_restritas as $restrita)
                                $lista_esferas_restritas[] = array('id_vendedor' => $ultimo_usuario, 'esfera_administrativa' => $restrita);

                            if (!empty($lista_esferas_restritas))
                                $this->esfadm_direito_vendedor_model->insere($lista_esferas_restritas);
                        }
                    } else if ($this->input->post('id_nivel') == 6) {
                        $dadosGestor = $this->gestor->get_all_by_usuario($this->session->userdata('id_usuario'));

                        $optionsGestor = array(
                            'validade' => $dadosGestor->validade,
                            'quantidade_cnpj' => $dadosGestor->quantidade_cnpj,
                            'id_usuario' => $ultimo_usuario,
                            'inicio_vigencia' => $dadosGestor->inicio_vigencia,
                            'tipo_gestor' => $dadosGestor->tipo_gestor,
                            'tipo_subgestor' => $this->input->post('tipo_subgestor')
                        );

                        $this->gestor->insere_gestor($optionsGestor);

                        $this->load->model('usuario_gestor');

                        $this->usuario_gestor->add_or_update_usuario_gestor_for_usuario($ultimo_usuario, $dadosGestor->id_gestor);
                    } else if ($this->input->post('id_nivel') == 7 || $this->input->post('id_nivel') == 8) {
                        $this->load->model('usuario_subgestor_model');

                        $this->usuario_subgestor_model->add_or_update_usuario_gestor_for_usuario($ultimo_usuario, $this->session->userdata('id_usuario'));
                    }
                }

                $this->usuariomodel->realaciona_usuarios_cadastro(array('id_usuario_cadastrado' => $ultimo_usuario, 'id_usuario_cadastrou' => $this->session->userdata('id_usuario')));

                $this->system_logs->add_log(USUARIO_INCLUIDO . " - ID: " . $ultimo_usuario . ", Nome: " . $this->input->post('nome', TRUE));

                $temUsuarioSiconv = true;

                $this->usuariomodel->envia_email_confirma_cadastro($optionsUsuario['email'], $optionsUsuario['nome'], $optionsUsuario['login'], $temUsuarioSiconv, $optionsUsuario['senha']);

                $this->alert("Usuário cadastrado com sucesso. Uma mensagem foi enviada para o email cadastrado para a confirmação.");
                $this->encaminha(base_url('index.php/controle_usuarios'));
            } else
                $data['dados_post'] = $dados_post;
        }

        $data['entidadeDefault'] = "";
        if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 6)
            $data['entidadeDefault'] = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'))->entidade;
        $data['title'] = 'SIHS - Novo Usuário';
        $data['main'] = "controle_usuarios/novo_usuario";
        $data['nivelUsuarios'] = $nivelUsuarios;
        $data['usuariosGestor'] = $usuariosGestor;
        $data['programa_model'] = $this->programa_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['estados_block'] = array();
        $data['esferas_block'] = array();

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function envia_sms($nome_usuario) {
        //  		Eliumar	 EU
        $telefones = array('5573988462781', '5573991192425');

        for ($i = 0; $i <= 1; $i++) {
            $credencial = URLEncode("218565391A8CE4A44253ABF179EBC1505B7A0A3F"); //**Credencial da Conta 40 caracteres
            $principal = URLEncode("ESICAR");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $auxuser = URLEncode("USER_ATIVACAO"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $mobile = URLEncode($telefones[$i]); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
            $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
            $msg = "Caro administrador,\r\n O usuário {$nome_usuario} foi cadastrado no sistema e-SICAR - SIHS."; // Mensagem
            $msg = mb_convert_encoding($msg, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
            $msg = URLEncode($msg);
            $response = fopen("http://www.mpgateway.com/v_2_00/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&PRINCIPAL_USER=" . $principal . "&AUX_USER=" . $auxuser . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
            $status_code = fgets($response, 4);
            echo "Codigo retornando do fopen=";
            echo $status_code;
        }
    }

    public function atualiza_usuario() {
        $this->session->set_userdata('pagAtual', '');
        $this->load->model('nivel_usuario');
        $this->load->model('usuariomodel');
        $this->load->model('data_model');
        $this->load->model('programa_model');
        $this->load->model('proponente_siconv_model');
        $this->load->model('estados_direito_vendedor_model');
        $this->load->model('esfadm_direito_vendedor_model');

        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('telefone', 'Telefone', '');
            $this->form_validation->set_rules('senha', 'Senha Login e-SICAR', '');
            $this->form_validation->set_rules('celular', 'Celular', '');
            $this->form_validation->set_rules('login_siconv', 'Login SICONV', '');
            $this->form_validation->set_rules('senha_siconv', 'Senha SICONV', '');
            $this->form_validation->set_rules('id_nivel', 'Nível', 'required');

            if ($this->session->userdata('nivel') != 1) {
                $this->form_validation->set_rules('id_nivel', 'Nível', '');
            } else if ($this->session->userdata('nivel') == 1) {
                if ($this->input->post('id_nivel', TRUE) == "4") {
                    $this->form_validation->set_rules('estado_restrito', 'Acesso Restrito - Estado', 'required');
                    $this->form_validation->set_rules('esfera_restrita', 'Acesso Restrito - Esfera Administrativa', 'required');
                }
            }

            if ($this->input->post('id_nivel') === "2") {
                $this->form_validation->set_rules('validade', 'Período do contrato', 'required');
                $this->form_validation->set_rules('quantidade_cnpj', 'Quantidade de CNPJs', 'required');
            }

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('valid_email', 'O campo %s deve conter um endereço de email válido.');
            $this->form_validation->set_message('is_unique', '%s já cadastrado.');

            if ($this->form_validation->run() === TRUE) {
                $id = $this->input->get('id', TRUE);

                $optionsUsuario = $this->input->post();

                $this->atualiza_dados_confirmacao($id, $optionsUsuario);

                $linhas_afetadas = $this->usuariomodel->atualiza_usuario($id, $optionsUsuario);

                if ($this->session->userdata('id_usuario') == $id) {
                    $this->session->set_userdata('nome_usuario', $optionsUsuario['nome']);
                    $this->session->set_userdata('entidade', $optionsUsuario['entidade']);
                }
                $this->load->model('gestor');

                if ($this->input->post('id_nivel') === "2") {
                    $dados_gestor = $this->gestor->get_all_by_usuario($id);
                    if ($dados_gestor != null) {
                        if ($this->data_model->get_meses_to_time($this->data_model->retornaDiffDatas($dados_gestor->inicio_vigencia, $dados_gestor->validade, false)) != $this->input->post('validade', TRUE)) {
                            $optionsGestor = array(
                                'validade' => $this->data_model->retornaNovaData(date("Y-m-d"), $this->input->post('validade', TRUE), true),
                                'quantidade_cnpj' => $this->input->post('quantidade_cnpj', TRUE),
                                'id_usuario' => $id,
                                'inicio_vigencia' => date("Y-m-d"),
                                'tipo_gestor' => $this->input->post('tipo_gestor', TRUE)
                            );
                        } else {
                            $optionsGestor = array(
                                'quantidade_cnpj' => $this->input->post('quantidade_cnpj', TRUE),
                                'id_usuario' => $id,
                                'tipo_gestor' => $this->input->post('tipo_gestor', TRUE)
                            );
                        }

                        $this->gestor->atualiza_gestor($id, $optionsGestor);
                    } else {
                        $optionsGestor = array(
                            'validade' => $this->data_model->retornaNovaData(date("Y-m-d"), $this->input->post('validade', TRUE), true),
                            'quantidade_cnpj' => $this->input->post('quantidade_cnpj', TRUE),
                            'id_usuario' => $id,
                            'inicio_vigencia' => date("Y-m-d")
                        );

                        $this->gestor->insere_gestor($optionsGestor);
                    }
                } else if ($this->input->post('id_nivel') === "3" || $this->input->post('id_nivel') === "5") {
                    $this->load->model('usuario_gestor');

                    if ($this->session->userdata('nivel') == 1)
                        $idGestor = $this->input->post('id_gestor', TRUE);
                    else
                        $idGestor = $this->session->userdata('id_usuario');
                    $this->usuario_gestor->add_or_update_usuario_gestor_for_usuario($id, $this->gestor->get_id_by_usuario($idGestor));
                } else if ($this->input->post('id_nivel') == "4") {
                    if ($this->session->userdata('nivel') == "1") {
                        $this->load->model('estados_direito_vendedor_model');
                        $this->load->model('esfadm_direito_vendedor_model');

                        $this->estados_direito_vendedor_model->limpa_restricao($id);
                        $this->esfadm_direito_vendedor_model->limpa_restricao($id);

                        $estados_restritos = $this->input->post('estado_restrito', TRUE);
                        if (!empty($estados_restritos)) {
                            $lista_estados_vendedor = array();
                            foreach ($estados_restritos as $restrito)
                                $lista_estados_vendedor[] = array('id_vendedor' => $id, 'estado_sigla' => $restrito);

                            if (!empty($lista_estados_vendedor))
                                $this->estados_direito_vendedor_model->insere($lista_estados_vendedor);
                        }

                        $esferas_restritas = $this->input->post('esfera_restrita', TRUE);
                        if (!empty($esferas_restritas)) {
                            $lista_esferas_restritas = array();
                            foreach ($esferas_restritas as $restrita)
                                $lista_esferas_restritas[] = array('id_vendedor' => $id, 'esfera_administrativa' => $restrita);

                            if (!empty($lista_esferas_restritas))
                                $this->esfadm_direito_vendedor_model->insere($lista_esferas_restritas);
                        }
                    }
                }else if ($this->input->post('id_nivel') == "6" && $this->session->userdata('nivel') == 2) {
                    $dadosGestor = $this->gestor->get_all_by_usuario($this->session->userdata('id_usuario'));

                    $optionsGestor = array(
                        'validade' => $dadosGestor->validade,
                        'quantidade_cnpj' => $dadosGestor->quantidade_cnpj,
                        'id_usuario' => $id,
                        'inicio_vigencia' => $dadosGestor->inicio_vigencia,
                        'tipo_gestor' => $dadosGestor->tipo_gestor
                    );

                    $this->gestor->atualiza_gestor($optionsGestor);

                    $this->load->model('usuario_gestor');

                    $this->usuario_gestor->add_or_update_usuario_gestor_for_usuario($id, $dadosGestor->id_gestor);
                }

                $this->load->model('system_logs');
                $this->system_logs->add_log(USUARIO_EDITADO . " - ID: " . $id . ", Nome: " . $this->input->post('nome', TRUE));

                $this->alert("Usuário editado com sucesso.");
                if ($this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5)
                    $this->encaminha(base_url() . 'index.php/in/gestor');
                else
                    $this->encaminha(base_url() . 'index.php/controle_usuarios');
            }
        }

        if ($this->input->get_post('id', TRUE) !== false) {
            $this->load->model('usuario_gestor');
            $this->load->model('gestor');

            $usuario = $this->usuariomodel->get_by_id($this->input->get_post('id', TRUE));
            if ($usuario->id_nivel == "3" || $usuario->id_nivel == "5") {
                $gestor = $this->usuario_gestor->get_by_usuario($this->input->get_post('id', TRUE));
                $gestor_usuario = $this->gestor->get_by_id($gestor->id_gestor);
            } else if ($usuario->id_nivel === "2")
                $gestor_usuario = $this->gestor->get_all_by_usuario($usuario->id_usuario);
            else if ($usuario->id_nivel == "6")
                $gestor_usuario = $this->gestor->get_by_usuario($usuario->id_usuario);

            $data['usuario'] = $usuario;
            if (isset($gestor_usuario))
                $data['gestor_usuario'] = $gestor_usuario;
        }

        $nivelUsuarios = $this->nivel_usuario->get_all();
        $usuariosGestor = $this->usuariomodel->get_all_gestor(true, false);

        $data['title'] = 'SIHS - Atualização de Usuário';
        $data['main'] = "controle_usuarios/novo_usuario";
        $data['nivelUsuarios'] = $nivelUsuarios;
        $data['usuariosGestor'] = $usuariosGestor;
        $data['data_model'] = $this->data_model;
        $data['programa_model'] = $this->programa_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['estados_block'] = $this->estados_direito_vendedor_model->get_lista_estados_bloqueados($_GET['id']);
        $data['esferas_block'] = $this->esfadm_direito_vendedor_model->get_lista_esferas_bloqueadas($_GET['id']);

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function vincular_cnpj() {
        $this->session->set_userdata('pagAtual', 'vincular_cnpj');

        $this->load->model('usuariomodel');
        $this->load->model('proponente_siconv_model');

        if ($this->session->userdata('nivel') === "1" && $this->input->get('id', TRUE) === FALSE) {
            $data['gestores'] = $this->usuariomodel->get_all_gestor();
            $pagina = "controle_usuarios/lista_gestores";
        } else if ($this->input->get('id', TRUE) !== FALSE) {
            $id = $this->input->get('id', TRUE);

            $dados_post = $this->input->post();
            if ($this->input->post()) {
                $this->form_validation->set_rules('proponente', 'Proponente', 'required|callback_valida_quantidade');
                $this->form_validation->set_rules('estado', 'Estado', 'required');
                $this->form_validation->set_rules('municipio', 'Municipio', 'required');
                $this->form_validation->set_rules('cnpj_instituicao', 'required|max_length[100]');

                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

                $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
                //$this->form_validation->set_message('is_unique', 'O campo %s deve conter um valor único.');
                $this->form_validation->set_message('min_length', 'O campo %s deve conter 14 dígitos.');
                $this->form_validation->set_message('max_length', 'O campo %s deve conter %s dígitos.');

                if ($this->form_validation->run() === TRUE) {
                    $this->load->model('cnpj_siconv');
                    $this->load->model('usuario_cnpj');

                    $proponentes = $this->input->post('proponente');

                    foreach ($proponentes as $proponente) {
                        $options_cnpj = array(
                            'cnpj' => preg_replace('/[^0-9]/', '', (string) $proponente),
                            'id_cidade' => $this->input->post('municipio'),
                            'cnpj_instituicao' => $this->proponente_siconv_model->get_instituicao_nome($proponente),
                            'sigla' => $this->input->post('estado'),
                            'esfera_administrativa' => $this->proponente_siconv_model->get_instituicao_esfera($proponente)
                        );

                        $id_cnpj = $this->cnpj_siconv->insere_cnpj($options_cnpj);
                        if ($id_cnpj > 0)
                            $this->usuario_cnpj->insere_usuario_cnpj($id, $id_cnpj);
                    }

                    $this->load->model('system_logs');
                    $this->system_logs->add_log(VINCULA_CNPJ . " - ID: " . $id . ", Nome: " . $this->usuariomodel->get_by_id($id)->nome);

                    $this->alert("CNPJ vinculado com sucesso.");

                    $this->encaminha(base_url() . 'index.php/controle_usuarios/vincular_cnpj?id=' . $id);
                } else
                    $data['dados_post'] = $dados_post;
            }

            $pagina = "controle_usuarios/vincular_cnpj";

            $this->load->model('usuario_cnpj');
            $this->load->model('programa_model');
            $this->load->model('proposta_model');
            $this->load->model('gestor');

            $dados = $this->usuariomodel->get_by_id($id);
            $dados_cnpj = $this->usuario_cnpj->get_all_dados_by_usuario($id);
            $dados_gestor = $this->gestor->get_all_by_usuario($dados->id_usuario);

            if ($dados->id_nivel == 6 && $this->session->userdata('nivel') == 2)
                $this->session->set_userdata('pagAtual', '');

            $data['dados_usuario'] = $dados;
            $data['dados_cnpj'] = $dados_cnpj;
            $data['programa_model'] = $this->programa_model;
            $data['proposta_model'] = $this->proposta_model;
            $data['dados_gestor'] = $dados_gestor;
            $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        }

        $data['usuariomodel'] = $this->usuariomodel;
        $data['title'] = 'SIHS - Vincular CNPJ';
        $data['main'] = $pagina;

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function valida_quantidade($proponente) {
        $this->load->model('gestor');
        $this->load->model('usuario_cnpj');

        if ($this->input->get('id', TRUE) != FALSE) {
            $id_usuario = $this->input->get('id', TRUE);
            $dados_gestor = $this->gestor->get_all_by_usuario($id_usuario);

            $this->form_validation->set_message('valida_quantidade', 'Quantidade máxima de CNPJs é ' . $dados_gestor->quantidade_cnpj);

            $cnpjs = $this->usuario_cnpj->get_cnpjs_gestor($id_usuario);

            if (count(array_merge($proponente, $cnpjs)) > $dados_gestor->quantidade_cnpj)
                return false;
        }else {
            $this->form_validation->set_message('valida_quantidade', 'Quantidade máxima de CNPJs é ' . $this->input->post('quantidade_cnpj', TRUE));
            if (count($proponente) > $this->input->post('quantidade_cnpj', TRUE))
                return false;
        }

        return true;
    }

    public function vincular_codigo_parlamentar_vendedor() {
        $this->load->model('usuariomodel');
        $this->load->model('cnpj_siconv');
        $this->load->model('proponente_siconv_model');

        $id = $this->session->userdata('id_usuario');
        if ($this->input->post()) {
            $this->form_validation->set_rules('codigo_parlamentar', 'Código Parlamentar', 'required');
            $this->form_validation->set_rules('nivel_parlamentar', 'Nível Parlamentar', 'required');
            $this->form_validation->set_rules('estado_parlamentar', 'Estado do Parlamentar', 'required');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === TRUE) {
                $this->cnpj_siconv->delete_by_usuario($this->session->userdata('id_usuario'));

                //Salvando o código de parlamentar para o vendedor
                $options = array(
                    'vendedor_codigo_parlamentar' => $this->input->post('codigo_parlamentar'),
                    'vendedor_visita' => 'S'
                );
                $this->usuariomodel->set_codigo_parlamentar_vendedor($id, $options);
                $vendedor = $this->usuariomodel->get_by_id($id);

                //Definindo o nível do parlamentar para o vendedor
                $this->session->set_userdata('nivel_gestor', $this->input->post('nivel_parlamentar'));
                $this->session->set_userdata('estado_parlamentar', $this->input->post('estado_parlamentar'));

                $this->load->model('system_logs');
                $this->system_logs->add_log(VINCULA_CODIGO_PARLAMENTAR_VENDEDOR, " - VENDEDOR: " . $vendedor->nome . " CÓDIGO DE PARLAMENTAR: " . $this->input->post('codigo_parlamentar') . ".");

                $this->alert("Código e estado do parlamentar vinculados com sucesso.");
                $this->encaminha(base_url() . 'index.php/in/gestor');
            }
        }

        $data['usuariomodel'] = $this->usuariomodel;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;

        $pagina = "controle_usuarios/vincular_codigo_parlamentar_vendedor";

        $dados = $this->usuariomodel->get_by_id($id);
        $data['dados_usuario'] = $dados;

        $data['title'] = 'SIHS - Vincular Código Parlamentar';
        $data['main'] = $pagina;

        $this->load->view('in/template_login', $data);
    }

    public function get_lista_parlamentar() {
        $this->load->model('usuariomodel');

        $retorno = $this->usuariomodel->get_all_parlamentar($this->input->post('estado', TRUE));

        echo json_encode($retorno);
    }

    public function vincular_cnpj_vendedor() {
        $this->load->model('usuariomodel');
        $this->load->model('proponente_siconv_model');
        $this->load->model('contato_municipio_model');

        $id = $this->session->userdata('id_usuario');

        if ($this->input->post()) {
            $this->form_validation->set_rules('proponente', 'Proponente', 'required');
            if ($this->session->userdata('sistema') != 'E')
                $this->form_validation->set_rules('municipio', 'Municipio', 'required');
            $this->form_validation->set_rules('esfera', 'Esfera Administrativa', 'required');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('min_length', 'O campo %s deve conter %s dígitos.');

            if ($this->input->post('visita', TRUE) != FALSE && $this->input->post('visita', TRUE) == 1) {
                $this->form_validation->set_rules('visita', 'Cadastro de Visita', '');
                $this->form_validation->set_rules('nome_contato', 'Nome do Responsável', 'required');
                $this->form_validation->set_rules('status_contato', 'Status', 'required');

                $this->form_validation->set_message('valid_email', 'O campo %s deve conter um email válido');
                $this->form_validation->set_message('max_length', 'O campo %s deve conter %s digitos');
                $this->form_validation->set_message('integer', 'O campo %s deve conter valores numéricos');
            }

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === TRUE) {
                $this->load->model('cnpj_siconv');
                $this->load->model('usuario_cnpj');

                $proponentes = $this->input->post('proponente');

                $this->cnpj_siconv->delete_by_usuario($this->session->userdata('id_usuario'));

                $lista_dados = array();
                foreach ($proponentes as $proponente) {
                    $options_cnpj = array(
                        'cnpj' => preg_replace('/[^0-9]/', '', (string) $proponente),
                        'id_cidade' => $this->proponente_siconv_model->get_municipio_by_cnpj($proponente, false)->codigo_municipio,
                        'cnpj_instituicao' => $this->proponente_siconv_model->get_instituicao_nome($proponente),
                        'sigla' => $this->input->post('estado'),
                        'esfera_administrativa' => $this->proponente_siconv_model->get_instituicao_esfera($proponente)
                    );

                    if (empty($lista_dados[0]) || !in_array($this->proponente_siconv_model->get_instituicao_esfera($proponente), $lista_dados[0]))
                        $lista_dados[0][] = $this->proponente_siconv_model->get_instituicao_esfera($proponente);
                    $lista_dados[1][] = $proponente;

                    $id_cnpj = $this->cnpj_siconv->insere_cnpj($options_cnpj);
                    if ($id_cnpj > 0)
                        $this->usuario_cnpj->insere_usuario_cnpj($id, $id_cnpj);

                    $this->usuario_cnpj->copia_cnpj_vendedor(array('cnpj_vinculado' => preg_replace('/[^0-9]/', '', (string) $proponente), 'id_usuario' => $this->session->userdata('id_usuario')));
                }

                if ($this->input->post('visita', TRUE) != FALSE && $this->input->post('visita', TRUE) == 1) {
                    $this->load->model('historico_contato_municipio_model');

                    $ultimoId = $this->contato_municipio_model->insert_or_update(array(
                        'id_contato_municipio' => $this->input->post('id_contato_municipio', TRUE),
                        'nome_contato' => $this->input->post('nome_contato', TRUE),
                        'id_municipio' => $this->proponente_siconv_model->get_municipio_by_cnpj($proponentes[0], false)->codigo_municipio,
                        'sigla_uf' => $this->input->post('estado', TRUE),
                        'id_usuario_cadastrou' => $this->session->userdata('id_usuario')
                    ));

                    $this->historico_contato_municipio_model->insert(array('id_contato_municipio' => $ultimoId, 'status_contato' => $this->input->post('status_contato', TRUE), 'data_visita' => date('Y-m-d')));

                    foreach ($proponentes as $proponente)
                        $this->historico_contato_municipio_model->insere_cnpj_contato(array('id_contato_municipio' => $ultimoId, 'cnpj_contato' => $proponente));

                    $this->usuariomodel->update_modo_vendedor($this->session->userdata('id_usuario'), 'S');
                }

                $this->load->model('system_logs');

                $municipio = $this->input->post('municipio') != false ? $this->proponente_siconv_model->get_municipio_nome($this->input->post('municipio'))->municipio : "";

                $this->system_logs->add_log(VINCULA_CNPJ_VENDEDOR . " - Município: " . $municipio . "/" . $this->input->post('estado') . " - Entidade: " . implode(", ", $lista_dados[0]) . " - CNPJs: " . implode(", ", $lista_dados[1]));

                $this->alert("CNPJ vinculado com sucesso.");

                $this->encaminha(base_url() . 'index.php/in/gestor');
            }
        }

        $pagina = "controle_usuarios/vincular_cnpj_vendedor";

        $this->load->model('usuario_cnpj');
        $this->load->model('programa_model');
        $this->load->model('proposta_model');
        $this->load->model('gestor');

        $dados = $this->usuariomodel->get_by_id($id);
        $dados_cnpj = $this->usuario_cnpj->get_all_dados_by_usuario($id);

        $data['dados_usuario'] = $dados;
        $data['dados_cnpj'] = $dados_cnpj;
        $data['programa_model'] = $this->programa_model;
        $data['proposta_model'] = $this->proposta_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['contato_municipio_model'] = $this->contato_municipio_model;

        $data['title'] = 'SIHS - Vincular CNPJ';
        $data['main'] = $pagina;

        $this->load->view('in/template_login', $data);
    }

    public function busca_ultimo_contato_municipio() {
        $this->load->model('contato_municipio_model');

        $dados_contato = $this->contato_municipio_model->get_ultimo_contato($this->input->post('municipio', TRUE), $this->input->post('estado', TRUE), $this->input->post('proponente', TRUE), $this->input->post('esfera', TRUE));

        echo json_encode($dados_contato);
    }

    public function remove_cnpj() {
        $this->db->where('id_cnpj_siconv', $this->input->get('id_cnpj', TRUE));
        $this->db->delete('cnpj_siconv');

        $this->db->flush_cache();

        $this->db->where('id_cnpj', $this->input->get('id_cnpj', TRUE));
        $this->db->delete('usuario_cnpj');

        $this->encaminha(base_url() . 'index.php/controle_usuarios/vincular_cnpj?id=' . $this->input->get('id', TRUE));
    }

    public function permissao_usuario() {
        $this->session->set_userdata('pagAtual', 'permissao_usuario');

        $data['title'] = 'SIHS - Permissões Usuário';
        $data['main'] = "controle_usuarios/permissao_usuario";

        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function desativa_usuario() {
        if ($this->input->get('id', TRUE) !== FALSE) {
            $this->load->model('usuariomodel');
            $num_rows = $this->usuariomodel->muda_status_usuario($this->input->get('id', TRUE), 'I');

            if ($num_rows > 0) {
                $this->load->model('system_logs');
                $this->system_logs->add_log(USUARIO_DESATIVADO . " - ID: " . $this->input->get('id', TRUE) . ", Nome: " . $this->usuariomodel->get_by_id($this->input->get('id', TRUE))->nome);

                $this->alert("Usuário desativado com sucesso.");
            } else
                $this->alert("Erro ao desativar usuário.");

            $this->encaminha(base_url() . 'index.php/controle_usuarios');
        }
    }

    public function ativa_usuario() {
        if ($this->input->get('id', TRUE) !== FALSE) {
            $this->load->model('usuariomodel');
            $num_rows = $this->usuariomodel->muda_status_usuario($this->input->get('id', TRUE), 'A');

            if ($num_rows > 0) {
                $dados_usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));
                if ($dados_usuario->id_nivel == 2) {
                    if ($dados_usuario->usuario_novo == 'S') {
                        $this->load->model('confirma_cadastro');
                        $this->load->model('programa_model');

                        $dados_cadastro = $this->confirma_cadastro->busca_dados_validar($dados_usuario->email, $dados_usuario->login);

                        $senha_gerada = rand(1000, 9999999);
                        $array_senha_id = array(
                            'id_usuario' => $this->input->get('id', TRUE),
                            'senha' => $senha_gerada
                        );

                        $this->usuariomodel->atualiza_senha($array_senha_id);

                        $this->usuariomodel->envia_email_usuario_cadastrado($dados_cadastro->email_usuario, $dados_cadastro->nome_usuario, $dados_cadastro->cpf_usuario, $dados_cadastro->tem_login_siconv, $senha_gerada);

                        $this->usuariomodel->notifica_vendedor_user_ativado($this->input->get('id', TRUE));

                        $this->usuariomodel->marca_usuario_nao_novo($this->input->get('id', TRUE));

                        $this->programa_model->programas_abertos(true, $this->input->get('id', TRUE));
                    }
                }

                $this->load->model('system_logs');
                $this->system_logs->add_log(USUARIO_ATIVADO . " - ID: " . $this->input->get('id', TRUE) . ", Nome: " . $this->usuariomodel->get_by_id($this->input->get('id', TRUE))->nome);

                $this->alert("Usuário ativado com sucesso.");
            } else if ($num_rows === "SEM_PERMISSAO")
                $this->alert("Somente o gestor desse usuário pode ativá-lo.");
            else
                $this->alert("Erro ao ativar usuário.");

            //$this->encaminha(base_url() . 'index.php/controle_usuarios');
            redirect('controle_usuarios');
        }
    }

    public function ativa_usuario_avulso() {
        if ($this->input->get('id', TRUE) !== FALSE) {
            $this->load->model('usuariomodel');
            $num_rows = $this->usuariomodel->muda_status_usuario($this->input->get('id', TRUE), 'A');

            if ($num_rows > 0) {
                $dados_usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));
                if ($dados_usuario->id_nivel == 2) {
                    if ($dados_usuario->usuario_novo == 'S') {
                        $this->load->model('pagseguro_usuario_model', 'pgu');
                        $this->load->model('confirma_cadastro');
                        $this->load->model('programa_model');

                        $this->pgu->set_permissao_plano($this->input->get('id', TRUE), $this->input->get('cod_ref', TRUE));

                        $dados_cadastro = $this->confirma_cadastro->busca_dados_validar($dados_usuario->email, $dados_usuario->login);

                        $senha_gerada = rand(1000, 9999999);
                        $array_senha_id = array(
                            'id_usuario' => $this->input->get('id', TRUE),
                            'senha' => $senha_gerada
                        );

                        $this->usuariomodel->atualiza_senha($array_senha_id);

                        $this->usuariomodel->envia_email_usuario_cadastrado($dados_cadastro->email_usuario, $dados_cadastro->nome_usuario, $dados_cadastro->cpf_usuario, $dados_cadastro->tem_login_siconv, $senha_gerada);

                        $this->usuariomodel->marca_usuario_nao_novo($this->input->get('id', TRUE));

                        $this->programa_model->programas_abertos(true, $this->input->get('id', TRUE));
                    }
                }

                $this->load->model('system_logs');
                $this->system_logs->add_log(USUARIO_ATIVADO . " - ID: " . $this->input->get('id', TRUE) . ", Nome: " . $this->usuariomodel->get_by_id($this->input->get('id', TRUE))->nome);

                $this->alert("Usuário ativado com sucesso.");
            } else if ($num_rows === "SEM_PERMISSAO")
                $this->alert("Somente o gestor desse usuário pode ativá-lo.");
            else
                $this->alert("Erro ao ativar usuário.");

            $this->encaminha(base_url() . 'index.php/controle_usuarios/lista_usuario_avulso');
        }
    }

    public function lista_usuario_avulso() {
        $this->session->set_userdata('pagAtual', 'lista_usuario_avulso');

        $this->load->model('usuariomodel');
        $this->load->model('confirma_cadastro');
        $this->load->model('pagseguro_usuario_model', 'pgu');

        if ($this->input->post()) {
            $data['pesquisa'] = $this->input->post('pesquisa', TRUE);
        }

        $data['lista_usuarios'] = $this->pgu->get_all_usuario($this->input->post('pesquisa', TRUE));

        $data['usuariomodel'] = $this->usuariomodel;
        $data['confirma_cadastro'] = $this->confirma_cadastro;
        $data['pagseguro_usuario_model'] = $this->pgu;

        $data['title'] = "SIHS - Controle de Usuários";
        $data['main'] = 'controle_usuarios/lista_usuario_avulso';
        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function historico_compra() {
        $this->session->set_userdata('pagAtual', 'lista_usuario_avulso');

        $this->load->model('usuariomodel');
        $this->load->model('confirma_cadastro');
        $this->load->model('pagseguro_usuario_model', 'pgu');

        $data['usuario'] = $this->pgu->get_user_by_id($this->input->get('id', TRUE));

        $data['usuariomodel'] = $this->usuariomodel;
        $data['pagseguro_usuario_model'] = $this->pgu;

        $data['title'] = "SIHS - Controle de Usuários";
        $data['main'] = 'controle_usuarios/historico_compra';
        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

    public function confirma_pagamento() {
        $this->load->model('pagseguro_usuario_model', 'pgu');

        $this->pgu->set_permissao_plano($this->input->get('id', TRUE), $this->input->get('cod_ref', TRUE));

        $this->alert("Compra confirmada com sucesso.");
        $this->encaminha(base_url('index.php/controle_usuarios/lista_usuario_avulso'));
    }

    public function apagar_usuario() {
        try {
            if ($this->input->get('id', TRUE) !== FALSE) {
                $this->load->model('usuariomodel');

                $nome = $this->usuariomodel->get_nome_by_id($this->input->get('id', TRUE));
                $num_rows = $this->usuariomodel->apagar_usuario($this->input->get('id', TRUE));

                if ($num_rows > 0) {
                    $this->load->model('system_logs');
                    $this->system_logs->add_log("Usuário apagado" . " - ID: " . $this->input->get('id', TRUE) . ", Nome: " . $nome);

                    $this->alert("Usuário apagado com sucesso.");
                } else {
                    $this->alert("Falha ao apagar o usuário. Consulte o suporte.");
                }
            }
        } catch (Exception $ex) {
            
        }

        $this->encaminha(base_url() . 'index.php/controle_usuarios');
    }

    public function primeiro_acesso() {
        $this->session->set_userdata('pagAtual', 'permissao_usuario');

        if ($this->input->post()) {
            $this->form_validation->set_rules('senha_atual', 'Senha Atual', 'callback_valida_senha_atual');
            $this->form_validation->set_rules('nova_senha', 'Nova Senha', 'required|matches[repetir_senha]');
            $this->form_validation->set_rules('repetir_senha', 'Repetir Senha', '');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('matches', 'Os campos %s e %s não conferem.');

            if ($this->form_validation->run() === TRUE) {
                $this->load->model('usuariomodel');
                $this->usuariomodel->atualiza_senha_usuario($this->session->userdata('id_usuario'), array('senha' => sha1($this->input->post('nova_senha')), 'primeiro_acesso' => 'N'));

                $this->load->model('system_logs');
                $this->system_logs->add_log(ALTERACAO_SENHA_PRIMEIRO_ACESSO);

                $this->alert('Senha alterada com sucesso');

                if ($this->session->userdata('nivel') === "4") {
                    if ($this->session->userdata('sistema') == 'M') {
                        $this->encaminha(base_url() . 'index.php/controle_usuarios/vincular_cnpj_vendedor');
                    } else {
                        redirect('controle_usuarios/vincular_codigo_parlamentar_vendedor');
                    }
                } else {
                    if (($this->session->userdata('nivel') === "3" || $this->session->userdata('nivel') === "2") && $this->usuariomodel->verifica_possui_usuario_siconv()) {
                        $this->encaminha(base_url() . 'index.php/controle_usuarios/vincula_usuario_siconv');
                    } else {
                        $this->encaminha(base_url() . 'index.php/in/gestor');
                    }
                }
            }
        }

        $data['title'] = 'SIHS - Alterar Senha';
        $data['main'] = "controle_usuarios/primeiro_acesso";

        $this->load->view('in/template_login', $data);
    }

    public function vincula_usuario_siconv() {
        if ($this->input->post()) {
            $this->load->model('usuariomodel');
            $this->usuariomodel->add_login_senha_siconv_vendedor_admin($this->usuario_logado->id_usuario, $this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE));

            $this->encaminha(base_url() . 'index.php/in/gestor');
        }
        $data['title'] = 'SIHS - Novo projeto';
        $data['main'] = 'gestor/login_usuario';
        $this->load->vars($data);
        $this->load->view('in/template_login');
    }

    public function valida_senha_atual($senha) {
        $str_senha = sha1($senha);
        $this->db->select('COUNT(*) AS count');
        $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
        $this->db->where('senha', $str_senha);
        $retorno = $this->db->get('usuario')->row(0)->count;

        if ($retorno === "0") {
            $this->form_validation->set_message('valida_senha_atual', 'O campo %s não confere.');
            return false;
        } else
            return true;
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

    function cidades_ajax() {
        $cod_estados = mysql_real_escape_string($_REQUEST ['cod_estados']);

        $this->load->model('cidades_model');
        $this->load->model('cidades_siconv');
        $cidades_aux = $this->cidades_model->obter_cidades($this->obterEstadoInverso($cod_estados));

        foreach ($cidades_aux as $cidade) {
            $id_cidade = $this->cidades_siconv->get_by_name($cidade ['nome']);
            if ($id_cidade != null) {
                $cidades [] = array(
                    'cod_cidades' => $id_cidade->id_cidade,
                    'Codigo' => $id_cidade->id_cidade,
                    'nome' => $cidade ['nome']
                );
            }
        }

//         $this->load->model('cidades_model');
//         $cidades_aux = $this->cidades_model->obter_cidades_inverso($this->obterEstadoInverso($cod_estados));
//         foreach ($cidades_aux as $cidade) {
//         	$cidades[] = array(
//         		'cod_cidades' => $cidade['Codigo'],
//         		'Codigo' => $cidade['Codigo'],
//         		'nome' => $cidade['nome'],
//         	);
//         }

        echo (json_encode($cidades));
    }

    function obterEstadoInverso($estado) {

        switch ($estado) {
            case 27: return 7;
            case 7: return 8;
            case 8: return 10;
            case 9: return 11;
            case 12: return 14;
            case 11: return 13;
            case 10: return 12;
            case 13: return 15;
            case 14: return 16;
            case 16: return 18;
            case 17: return 19;
            case 15: return 17;
            case 18: return 20;
            case 19: return 21;
            case 21: return 23;
            case 22: return 9;
            case 20: return 22;
            case 23: return 25;
            case 25: return 27;
            case 24: return 26;
            case 26: return 24;
        }
        return $estado;
    }

    public function busca_id_estado() {
        $this->load->model('cnpj_siconv');
        $id_estado = $this->cnpj_siconv->get_estado_by_cidade($this->input->get('id_cidade', TRUE));

        echo json_encode(array('id_estado' => $id_estado));
    }

    public function lista_permissoes() {
        $permissoes = array(
            "consultar_programa", "relatorio_programa", "criar_usuario", "editar_usuario", "apagar_usuario",
            "criar_projeto", "editar_projeto", "apagar_projeto", "duplicar_projeto", "exportar_siconv",
            "consultar_proposta", "relatorio_proposta", "status_proposta", "parecer_proposta"
        );

        return $permissoes;
    }

    public function validar_cnpj($cnpj) {
        $this->form_validation->set_message('validar_cnpj', 'O campo %s é inválido.');

        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        $passou = $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);

        if ($passou == false)
            return false;
        else
            return true;
    }

    function valid_cpf($cpf) {
        $this->form_validation->set_message('valid_cpf', 'O %s informado não é válido.');

        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf)) {
            return false;
        }

        // 9 primeiros digitos do cpf
        $digit = substr($cpf, 0, 9);

        // calculo dos 2 digitos verificadores
        for ($j = 10; $j <= 11; $j++) {
            $sum = 0;
            for ($i = 0; $i < $j - 1; $i++) {
                $sum += ($j - $i) * ((int) $digit[$i]);
            }

            $summod11 = $sum % 11;
            $digit[$j - 1] = $summod11 < 2 ? 0 : 11 - $summod11;
        }
        return $digit[9] == ((int) $cpf[9]) && $digit[10] == ((int) $cpf[10]);
    }

    public function valida_cnpj_cadastrado($cnpj) {
        $this->form_validation->set_message('valida_cnpj_cadastrado', '%s já cadastrado');

        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        $this->db->where('cnpj', $cnpj);
        $this->db->where('id_cidade', $this->input->post('municipio', TRUE));
        $dadosCnpj = $this->db->get('cnpj_siconv')->row(0);

        $this->db->cache_delete_all();

        if (isset($dadosCnpj->id_cnpj_siconv) && ($this->input->post('id_cnpj', TRUE) != FALSE && $this->input->post('id_cnpj', TRUE) != $dadosCnpj->id_cnpj_siconv)) {
            if ($this->session->userdata('nivel') != 4) {
                $this->db->where('id_cnpj', $dadosCnpj->id_cnpj_siconv);
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));

                if (count($this->db->get('usuario_cnpj')->result()) > 0)
                    return false;
                else
                    return true;
            } else
                return true;
        } else
            return true;
    }

    public function valida_email_atualizar($email) {
        $this->form_validation->set_message('valida_email_atualizar', '%s já cadastrado');

        $this->db->where('id_encarregado <> ', $this->input->get('id'));
        $this->db->where('email', $email);
        $query = $this->db->get('encarregado');

        return $query->num_rows <= 0;
    }

    public function area_vendedor() {
        $this->session->set_userdata('pagAtual', 'area_vendedor');

        $data['title'] = 'SIHS - Área do Vendedor';
        $data['main'] = 'controle_usuarios/area_vendedor';
        $this->load->vars($data);

        $this->load->view('in/template');
    }

    public function cadastro_visitas() {
        $this->session->set_userdata('pagAtual', 'area_vendedor');

        $this->load->model('contato_municipio_model');
        $this->load->model('proponente_siconv_model');
        $this->load->model('historico_contato_municipio_model');

        $data['title'] = 'SIHS - Cadastro de Visitas';
        $data['main'] = 'controle_usuarios/cadastro_visitas';
        $data['contato_municipio_model'] = $this->contato_municipio_model;
        $data['historico_contato_municipio_model'] = $this->historico_contato_municipio_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['lista_contatos'] = $this->contato_municipio_model->get_dados_contato();

        $this->load->view('in/template', $data);
    }

    public function completa_cadastro_contato() {
        $this->session->set_userdata('pagAtual', 'area_vendedor');

        $this->load->model('proponente_siconv_model');
        $this->load->model('contato_municipio_model');
        $this->load->model('historico_contato_municipio_model');

        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('nome_contato', 'Nome do Responsável', 'required');
            $this->form_validation->set_rules('email_contato', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('telefone_contato', 'Telefone', 'max_length[11]|min_length[10]');

            $this->form_validation->set_rules('data_retorno[]', 'Data Retorno', 'callback_checkDateFormat');
            $this->form_validation->set_rules('data_visita[]', 'Data Visita', 'callback_checkDateFormat');

            $this->form_validation->set_message('min_length', 'O campo %s deve conter %s digitos');
            $this->form_validation->set_message('required', 'O campo %s é obrigatório');
            $this->form_validation->set_message('valid_email', 'O campo %s deve conter um email válido');
            $this->form_validation->set_message('max_length', 'O campo %s deve conter %s digitos');
            $this->form_validation->set_message('integer', 'O campo %s deve conter valores numéricos');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $dados = $this->input->post(null, TRUE);

            if ($this->form_validation->run()) {
                $this->contato_municipio_model->atualiza($this->input->get('id', TRUE), array(
                    'telefone_contato' => preg_replace('/[^0-9]/', '', (string) $this->input->post('telefone_contato', TRUE)),
                    'celular_contato' => preg_replace('/[^0-9]/', '', (string) $this->input->post('celular_contato', TRUE)),
                    'comercial_contato' => preg_replace('/[^0-9]/', '', (string) $this->input->post('comercial_contato', TRUE)),
                    'nome_contato' => $this->input->post('nome_contato', TRUE),
                    'email_contato' => $this->input->post('email_contato', TRUE)
                ));

                $idHistorico = $this->input->post('id_historico', TRUE);
                $dataRetorno = $this->input->post('data_retorno', TRUE);
                $obsGerais = $this->input->post('obs_gerais', TRUE);
                $classContato = $this->input->post('class_contato', TRUE);
                $dataVisita = $this->input->post('data_visita', TRUE);

                for ($i = 0; $i < count($idHistorico); $i++) {
                    $this->historico_contato_municipio_model->atualiza_historico($idHistorico[$i], array(
                        'data_retorno' => ($dataRetorno[$i] != "" ? implode("-", array_reverse(explode("/", $dataRetorno[$i]))) : null),
                        'class_contato' => (isset($classContato[$i]) ? $classContato[$i] : null),
                        'obs_gerais' => $obsGerais[$i],
                        'data_visita' => ($dataVisita[$i] != "" ? implode("-", array_reverse(explode("/", $dataVisita[$i]))) : null),
                    ));
                }
            }
        }

        $data['contato_municipio_model'] = $this->contato_municipio_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['title'] = 'SIHS - Cadastro de Visitas';
        $data['main'] = 'controle_usuarios/completa_cadastro_contato';
        $data['dados_usuario'] = $this->contato_municipio_model->get_by_id($this->input->get('id', TRUE));
        $data['dados_historico'] = $this->historico_contato_municipio_model->get_all_historico($this->input->get('id', TRUE));

        $this->load->view('in/template', $data);
    }

    function checkDateFormat($date) {
        $this->form_validation->set_message('checkDateFormat', 'O campo %s não é uma data válida.');
        if ($date != "") {
            if (preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $date)) {
                if (substr($date, 6, 4) >= date("Y")) {
                    if (checkdate(substr($date, 3, 2), substr($date, 0, 2), substr($date, 6, 4)))
                        return true;
                    else
                        return false;
                } else
                    return false;
            } else {
                return false;
            }
        }
    }

    function checkTemContatoAtivo() {
        $this->load->model('contato_municipio_model', 'cm');
        echo $this->cm->checkTemContatoCadastrado();
    }

    function cria_cadastro_visita() {
        $this->load->model('cnpj_siconv');
        $this->load->model('usuario_cnpj');
        $this->load->model('programa_model');
        $this->load->model('contato_municipio_model');
        $this->load->model('historico_contato_municipio_model');

        $usuario_cnpj = $this->usuario_cnpj->get_all_by_usuario($this->session->userdata('id_usuario'), "LEFT");

        $cnpjs = array();
        foreach ($usuario_cnpj as $uc)
            array_push($cnpjs, $this->cnpj_siconv->get_by_id($uc->id_cnpj));

        $ultimoId = $this->contato_municipio_model->insert_or_update(array(
            'id_contato_municipio' => false,
            'id_municipio' => $cnpjs[0]->id_cidade,
            'sigla_uf' => $cnpjs[0]->sigla,
            'id_usuario_cadastrou' => $this->session->userdata('id_usuario'),
            'data_cadastro' => date('Y-m-d')
        ));

        $this->historico_contato_municipio_model->insert(array('id_contato_municipio' => $ultimoId, 'status_contato' => 'VISITA_1', 'data_visita' => date("Y-m-d")));

        foreach ($cnpjs as $proponente)
            $this->historico_contato_municipio_model->insere_cnpj_contato(array('id_contato_municipio' => $ultimoId, 'cnpj_contato' => $this->programa_model->formatCPFCNPJ($proponente->cnpj)));

        echo $ultimoId;
    }

}
