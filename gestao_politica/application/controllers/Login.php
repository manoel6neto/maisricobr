<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        //Bibliotecas
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username_login_cpf', 'Cpf', 'required');
        $this->form_validation->set_rules('pass_senha_geral', 'Senha', 'required');
        $this->form_validation->set_message('required', 'O campo %s é obrigatório');
        $this->form_validation->set_error_delimiters('<div class="error" style="background: #ff9999 !important; background-color: #ff9999 !important; margin-top: 2px !important; margin-bottom: 10px !important; padding: 5px !important; border: solid 2px #ff9999 !important; border-radius: 5px !important;">', '</div>');

        //Modulos
        $this->load->model('Usuario_Sistema_Model');

        //Verificando se foi chamada POST (Login) ou GET (Carregando a página)
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login/index');
        } else {
            $cpf = $this->input->post('username_login_cpf', TRUE);
            $senha = $this->input->post('pass_senha_geral', TRUE);
            $usuario_sistema = $this->Usuario_Sistema_Model->get_usuario_from_cpf_senha($cpf, hash("sha1", $senha));

            if ($usuario_sistema != NULL) {
                //Dados da sessão
                $session = array(
                    'id_usuario_sistema' => intval($usuario_sistema->id),
                    'nome_usuario' => $usuario_sistema->nome,
                    'is_admin' => $usuario_sistema->is_admin,
                    'logged' => TRUE
                );

                //Criando sessão do usuário
                $this->session->set_userdata('sessao', $session);
                redirect('Modulos');
            } else {
                $data['erro_login'] = 'Cpf ou Senha incorretos!';
                $this->load->view('login/index', $data);
            }
        }
    }

    public function sair() {
        $this->session->sess_destroy();
        redirect('/login');
    }

}
