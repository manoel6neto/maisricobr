<?php

$var = 1;

class Parlamentar extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->login = "";
    }

    function gera_senha() {
        $this->load->model('usuario_model');
        $this->load->model('usuariomodel');
        if ($this->input->get_post('codigo', TRUE) !== false) {
            $codigo = $this->input->get_post('codigo', TRUE);
            $decoded_codigo = base64_decode($codigo);
            $array_exploded = explode("#", $decoded_codigo);
            $email_codigo = $array_exploded[1];
            $id_codigo = $array_exploded[0];
            $sistema = $array_exploded[2];
            if ($this->usuario_model->verifica_codigo($codigo) == true) {
                if ($this->input->get_post('acao', TRUE) == 'mudar') {
                    $nova_senha = $this->input->get_post('novasenha', TRUE);
                    if ($this->usuariomodel->altera_senha($nova_senha, $codigo, trim($id_codigo), trim($email_codigo)) > 0) {
                        $this->alert('A senha foi modificada com sucesso!');
                        $this->encaminha('../parlamentar');
                    }
                }
            } else {
                $this->alert('Desculpe mais este link já expirou!');
                $this->encaminha('parlamentar');
            }
        }

        $data['sistema'] = $sistema;
        $data['login'] = '';
        $data['title'] = '';
        $data['main'] = 'login/gera_senha';
        $this->load->view('in/template_login', $data);
    }

    function recupera() {
        $this->load->model('usuariomodel');
        $this->load->model('nivel_usuario');
        if ($this->input->get_post('email', TRUE) !== false) {
            $email = $this->input->get_post('email', TRUE);
            $nivel = $this->input->get_post('nivel', TRUE);
            if ($this->usuariomodel->recupera_email($email, $nivel, 'parlamentar') != null) {
                $this->alert('Enviamos um e-mail com um link para recuperação de senha, para o endereço de e-mail informado!');
                $this->encaminha('../parlamentar');
            } else {
                $this->alert('O email informado não foi encontrado no sistema.');
                $this->encaminha('../parlamentar');
            }
        }

        $data['niveis_user'] = $this->nivel_usuario->get_all();
        $data['login'] = '';
        $data['title'] = '';
        $data['main'] = 'login/recupera';
        $this->load->view('in/template_login', $data);
    }

    function index() {
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = "G&P - Login";
        } else {
            $data['title'] = "Physis - Login";
        }
        // VALIDATION RULES
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nivel', 'Nível', 'required');
        $this->form_validation->set_rules('login', 'Login', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        $this->form_validation->set_message('required', 'O campo %s é obrigatório');

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->load->model('usuariomodel');
        $this->load->model('nivel_usuario');
        $this->load->model('gestor');

        $data['niveis_user'] = $this->nivel_usuario->get_all();
        unset($data['niveis_user'][count($data['niveis_user']) - 1]);

        if ($this->form_validation->run() == FALSE) {
            $data['login'] = $this->login;
            $data['main'] = 'login/parlamentar';
            $this->load->view('in/template_login', $data);
        } else {
            $query = $this->usuariomodel->validar('P');

            if ($query !== null && $query !== "ERRO_LOGIN" && $query != "ERRO_SISTEMA_USUARIO") { // VERIFICA LOGIN E SENHA
                if ($query->status === "A") {
                    $data = array(
                        'login' => $this->input->post('login'),
                        'id_usuario' => $query->id_usuario,
                        'nome_usuario' => $query->nome,
                        'entidade' => $query->entidade,
                        'nivel' => $query->id_nivel,
                        'usuario_sistema' => $query->usuario_sistema,
                        'sistema' => 'P',
                        'logged' => true
                    );

                    if ($query->id_nivel === "2") {
                        $gestor = $this->gestor->get_all_by_usuario($query->id_usuario);
                        $data['nivel_gestor'] = $gestor->nivel_gestor;
                    }
                    $this->session->set_userdata($data);

                    $ehParlamentar = false;
                    if ($query->id_nivel === "2")
                        $ehParlamentar = $this->usuariomodel->verifica_eh_parlamentar();

                    if ($query->id_nivel === "4" || $query->id_nivel === "1" || $ehParlamentar) {
                        $options = array(
                            'login_siconv' => '',
                            'senha_siconv' => '',
                            'vendedor_codigo_parlamentar' => ''
                        );

                        $this->limpa_dados_sinconv($query->id_usuario, $options);

                        if ($query->id_nivel == "4")
                            $this->usuariomodel->update_modo_vendedor($query->id_usuario);
                    }

                    if ($query->primeiro_acesso === "S")
                        redirect('licenca_uso');

                    if (($query->id_nivel === "3" || $query->id_nivel === "2") && $this->usuariomodel->verifica_possui_usuario_siconv())
                        redirect('controle_usuarios/vincula_usuario_siconv');

                    if ($query->id_nivel === "4") {
                        $this->session->set_userdata('rel_visualizado', '');
                        redirect('controle_usuarios/vincular_codigo_parlamentar_vendedor');
                    }
                    ##Thomas: Depois verificar como filtras os niveis (melhor seria ter todas as funcoes em um controle e so modificar os metodos e visões [menus e etc])
                    if (($query->id_nivel >= "1" && $query->id_nivel <= "3") || $query->id_nivel == "5") {
                        redirect('in/gestor');
                    }
                } else {
                    $data['erro_login'] = 'Usuário bloqueado. Entre em contato com adm@physisbrasil.com';
                    $data['login'] = $this->login;
                    $data['main'] = 'login/parlamentar';
                    $this->load->view('in/template_login', $data);
                }
            } else if ($query == "ERRO_LOGIN") {
                $data['erro_login'] = 'Login ou Senha incorretos!';
                $data['login'] = $this->login;
                $data['main'] = 'login/parlamentar';
                $this->load->view('in/template_login', $data);
            } else if ($query == "ERRO_SISTEMA_USUARIO") {
                $data['erro_login'] = 'Usuário não possui permissão para acessar este sistema!';
                $data['login'] = $this->login;
                $data['main'] = 'login/parlamentar';
                $this->load->view('in/template_login', $data);
            } else {
                $data['erro_login'] = 'Usuário com prazo de utilização expirado. Entre em contato com suporte@physisbrasil.com';
                $data['login'] = $this->login;
                $data['main'] = 'login/parlamentar';
                $this->load->view('in/template_login', $data);
            }
        }
    }

    function limpa_dados_sinconv($id_usuario, $options) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('usuario', $options);
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>
	window.location='" . $url . "';
	</script>";
    }

    function sair() {
        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            $this->load->model('system_logs');
            $this->system_logs->add_log(SESSAO_FINALIZADA_VENDEDOR);
        }
        $this->session->sess_destroy();
        redirect('in/parlamentar');
    }

    public function validaCampos() {
        if ($this->input->post('campoSenha', TRUE) != "")
            $this->db->where('senha', sha1($this->input->post('campoSenha', TRUE)));

        $this->db->where('id_nivel', $this->input->post('nivel', TRUE));
        $this->db->where('login', $this->input->post('valorCampo', TRUE));
        $query = $this->db->get('usuario')->result();

        echo count($query);
    }

}
