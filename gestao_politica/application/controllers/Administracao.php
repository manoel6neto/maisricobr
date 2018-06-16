<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administracao extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->session->userdata('sessao') == FALSE) {
            redirect('/login');
        }
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        //Carregando modulos
        $this->load->model('Usuario_Sistema_Model');

        //Carregando os dados dos usuários
        $usuarios_do_sistema = $this->Usuario_Sistema_Model->get_usuarios_sistema();
        $data['usuarios'] = $usuarios_do_sistema;

        $this->load->view('administracao/index', $data);
    }

    public function delete() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        //Carregando modulos
        $this->load->model('Usuario_Sistema_Model');

        if ($this->input->method() == "get") {
            $user_id = $this->input->get('user', TRUE);
            if ($user_id != NULL && $user_id != 0) {
                if ($this->Usuario_Sistema_Model->delete_user_from_id($user_id) == 1) {
                    $this->alert('Usuário apagado com sucesso!');
                    $this->encaminha(base_url('index.php/administracao/index'));
                } else {
                    $this->alert('Falha ao apagar o usuário!');
                    $this->encaminha(base_url('index.php/administracao/index'));
                }
            } else {
                $this->alert('Falha ao apagar o usuário!');
                $this->encaminha(base_url('index.php/administracao/index'));
            }
        }
    }

    public function edit() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        //Carregando modulos
        $this->load->model('Usuario_Sistema_Model');

        //Bibliotecas
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usuario_sistema_nome', 'Nome', 'required');
        $this->form_validation->set_rules('usuario_sistema_cpf', 'Cpf', 'required');
        $this->form_validation->set_rules('usuario_sistema_email', 'Email', 'required');
        $this->form_validation->set_message('required', 'O campo %s é obrigatório');
        $this->form_validation->set_error_delimiters('<div class="error" style="background: #ff9999 !important; background-color: #ff9999 !important; padding: 10px !important; margin-top: 20px !important; border: solid 2px #ff9999 !important; border-radius: 5px !important;">', '</div>');

        //Filtrando o Get se não filtra o Post
        if ($this->input->method() == "get") {
            $user_id = $this->input->get('user', TRUE);
            if ($user_id != NULL && $user_id != 0) {
                $usuario_sistema = $this->Usuario_Sistema_Model->get_usuario_sistema_from_id($user_id);
                if ($usuario_sistema != NULL) {
                    $sistemas_usuario = $this->Usuario_Sistema_Model->get_sistemas_usuario_from_id_usuario_sistema($user_id);
                    $data['usuario'] = $usuario_sistema;
                    if ($this->session->userdata("sessao")['is_admin'] == 1) {
                        $data['usuario_cad_unico'] = $sistemas_usuario['usuario_cad_unico'];
                        $data['usuario_saude'] = $sistemas_usuario['usuario_saude'];
                        $data['usuario_educacao'] = $sistemas_usuario['usuario_educacao'];
                        $data['usuario_ass_social'] = $sistemas_usuario['usuario_assistencia_social'];
                        $data['usuario_cad_imobiliario'] = $sistemas_usuario['usuario_cad_imobiliario'];
                        $data['usuario_esicar'] = $sistemas_usuario['usuario_convenios'];
                        $data['usuario_terceiro_setor'] = $sistemas_usuario['usuario_terceiro_setor'];
                        $data['usuario_comunicacao_social'] = $sistemas_usuario['usuario_comunicacao_social'];
                        $data['usuario_aplicativo_cidadao'] = $sistemas_usuario['usuario_aplicativo_cidadao'];
                        $data['usuario_politicas_publicas'] = $sistemas_usuario['usuario_politica_publica'];
                        $data['usuario_pesquisa'] = $sistemas_usuario['usuario_pesquisa'];
                    }
                    $this->load->view('administracao/edit', $data);
                } else {
                    $data['erro_editar'] = 'Falha ao carregar dados do usuário!';
                    $this->load->view('administracao/index', $data);
                }
            } else {
                $this->load->view('administracao/edit');
            }
        } else {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('administracao/edit');
            } else {
                //Get_all_data
                $post_data = $this->input->post();

                //Inicializando as variaveis
                $options_sistema = NULL;
                $options_aplicativo_cidadao = NULL;
                $options_assistencia_social = NULL;
                $options_cad_imobiliario = NULL;
                $options_cad_unico = NULL;
                $options_comunicacao_social = NULL;
                $options_convenios = NULL;
                $options_educacao = NULL;
                $options_politica_publica = NULL;
                $options_saude = NULL;
                $options_terceiro_setor = NULL;
                $options_pesquisa = NULL;

                //Montando os options para cada sistema
                if (isset($post_data['usuario_sistema_id'])) {
                    $options_sistema = array(
                        'id' => $post_data['usuario_sistema_id'],
                        'nome' => $post_data['usuario_sistema_nome'],
                        'email' => $post_data['usuario_sistema_email'],
                        'senha' => hash('sha1', $post_data['usuario_sistema_senha']),
                        'cpf' => $post_data['usuario_sistema_cpf']
                    );

                    //Verificando a senha
                    if ($post_data['usuario_sistema_senha'] == '') {
                        unset($options_sistema['senha']);
                    }
                } else {
                    if ($post_data['usuario_sistema_senha'] == '') {
                        $data = $post_data;
                        $data['erro_editar'] = 'Senha é obrigatória!';
                        $this->load->view('administracao/edit', $data);
                    }

                    $options_sistema = array(
                        'nome' => $post_data['usuario_sistema_nome'],
                        'email' => $post_data['usuario_sistema_email'],
                        'senha' => hash('sha1', $post_data['usuario_sistema_senha']),
                        'cpf' => $post_data['usuario_sistema_cpf']
                    );
                }

                if ($this->session->userdata("sessao")['is_admin'] == 1) {
                    if ($post_data['aplicativo_cidadao_login'] != '') {
                        $options_aplicativo_cidadao = array(
                            'login' => $post_data['aplicativo_cidadao_login'],
                            'senha' => $post_data['aplicativo_cidadao_senha'],
                            'url_cliente' => $post_data['aplicativo_cidadao_url']
                        );
                    }

                    if ($post_data['assistencia_social_login'] != '') {
                        $options_assistencia_social = array(
                            'login' => $post_data['assistencia_social_login'],
                            'senha' => $post_data['assistencia_social_senha'],
                            'url_cliente' => $post_data['assistencia_social_url']
                        );
                    }

                    if ($post_data['cad_imobiliario_login'] != '') {
                        $options_cad_imobiliario = array(
                            'login' => $post_data['cad_imobiliario_login'],
                            'senha' => $post_data['cad_imobiliario_senha'],
                            'url_cliente' => $post_data['cad_imobiliario_url']
                        );
                    }

                    if ($post_data['cad_unico_login'] != '') {
                        $options_cad_unico = array(
                            'login' => $post_data['cad_unico_login'],
                            'senha' => $post_data['cad_unico_senha'],
                            'url_cliente' => $post_data['cad_unico_url']
                        );
                    }

                    if ($post_data['comunicacao_social_login'] != '') {
                        $options_comunicacao_social = array(
                            'login' => $post_data['comunicacao_social_login'],
                            'senha' => $post_data['comunicacao_social_senha'],
                            'url_cliente' => $post_data['comunicacao_social_url']
                        );
                    }

                    if ($post_data['captacao_recursos_login'] != '') {
                        $options_convenios = array(
                            'login' => $post_data['captacao_recursos_login'],
                            'senha' => $post_data['captacao_recursos_senha'],
                            'url_cliente' => $post_data['captacao_recursos_url']
                        );
                    }

                    if ($post_data['educacao_login'] != '') {
                        $options_educacao = array(
                            'login' => $post_data['educacao_login'],
                            'senha' => $post_data['educacao_senha'],
                            'url_cliente' => $post_data['educacao_url']
                        );
                    }

                    if ($post_data['politicas_publicas_login'] != '') {
                        $options_politica_publica = array(
                            'login' => $post_data['politicas_publicas_login'],
                            'senha' => $post_data['politicas_publicas_senha'],
                            'url_cliente' => $post_data['politicas_publicas_url']
                        );
                    }

                    if ($post_data['saude_login'] != '') {
                        $options_saude = array(
                            'login' => $post_data['saude_login'],
                            'senha' => $post_data['saude_senha'],
                            'url_cliente' => $post_data['saude_url']
                        );
                    }

                    if ($post_data['terceiro_setor_login'] != '') {
                        $options_terceiro_setor = array(
                            'login' => $post_data['terceiro_setor_login'],
                            'senha' => $post_data['terceiro_setor_senha'],
                            'url_cliente' => $post_data['terceiro_setor_url']
                        );
                    }

                    if ($post_data['pesquisa_login'] != '') {
                        $options_pesquisa = array(
                            'login' => $post_data['pesquisa_login'],
                            'senha' => $post_data['pesquisa_senha'],
                            'url_cliente' => $post_data['pesquisa_url']
                        );
                    }
                }

                //Inserindo no banco de dados
                if ($this->session->userdata("sessao")['is_admin'] == 1) {
                    $resultado_banco = $this->Usuario_Sistema_Model->insert_or_update($options_sistema, $options_aplicativo_cidadao, $options_assistencia_social, $options_cad_imobiliario, $options_cad_unico, $options_comunicacao_social, $options_convenios, $options_educacao, $options_politica_publica, $options_saude, $options_terceiro_setor, $options_pesquisa);
                } else {
                    $resultado_banco = $this->Usuario_Sistema_Model->insert_or_update_usuario($options_sistema);
                }

                if ($resultado_banco != NULL && $resultado_banco != 0) {
                    //var_dump($resultado_banco);
                    //die();
                    $this->alert('Dados gravados com sucesso!');
                    $this->encaminha(base_url("index.php/administracao/edit?user={$resultado_banco}"));
                } else {
                    //$data = $post_data;
                    $data['erro_editar'] = 'Falha ao gravar dados do usuário!';
                    $this->load->view('administracao/edit', $data);
                }
            }
        }
    }

}
