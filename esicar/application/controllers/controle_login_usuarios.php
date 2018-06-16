<?php

class controle_login_usuarios extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function seleciona_sistema() {
        $this->load->model('usuariomodel');
        $this->load->model('nivel_usuario');
        $this->load->model('gestor');

        $usuario = $this->session->userdata('user_from_login');

        if ($this->input->post()) {
            $this->form_validation->set_rules('sistema', 'Sistema', 'required');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === TRUE) {

                $sistema = $this->input->post('sistema');

                if ($usuario->status === "A") {
                    $data = array(
                        'login' => $this->input->post('login'),
                        'id_usuario' => $usuario->id_usuario,
                        'nome_usuario' => $usuario->nome,
                        'entidade' => $usuario->entidade,
                        'nivel' => $usuario->id_nivel,
                        'usuario_sistema' => $usuario->usuario_sistema,
                        'sistema' => $sistema,
                        'logged' => true
                    );

                    if ($usuario->id_nivel === "2") {
                        $gestor = $this->gestor->get_all_by_usuario($usuario->id_usuario);
                        $data['nivel_gestor'] = $gestor->nivel_gestor;
                    }
                    $this->session->set_userdata($data);

                    $ehParlamentar = false;
                    if ($usuario->id_nivel === "2") {
                        $ehParlamentar = $this->usuariomodel->verifica_eh_parlamentar();
                    }

                    if ($usuario->id_nivel === "4" || $usuario->id_nivel === "1" || $ehParlamentar || $usuario->id_nivel === "15") {
                        $options = array(
                            'login_siconv' => '',
                            'senha_siconv' => '',
                            'vendedor_codigo_parlamentar' => ''
                        );

                        $this->limpa_dados_sinconv($usuario->id_usuario, $options);

                        if ($usuario->id_nivel == "4" || $usuario->id_nivel == "15") {
                            $this->usuariomodel->update_modo_vendedor($usuario->id_usuario);
                        }
                    }

                    if ($usuario->primeiro_acesso === "S") {
                        redirect('licenca_uso');
                    }

                    if (($usuario->id_nivel === "3" || $usuario->id_nivel === "2") && $this->usuariomodel->verifica_possui_usuario_siconv()) {
                        redirect('controle_usuarios/vincula_usuario_siconv');
                    }

                    if ($usuario->id_nivel === "4" || $usuario->id_nivel == "15") {
                        $this->session->set_userdata('rel_visualizado', '');
                        if ($sistema == 'M' || $sistema == 'E') {
                            redirect('controle_usuarios/vincular_cnpj_vendedor');
                        } else {
                            redirect('controle_usuarios/vincular_codigo_parlamentar_vendedor');
                        }
                    }
                    ##Thomas: Depois verificar como filtras os niveis (melhor seria ter todas as funcoes em um controle e so modificar os metodos e visões [menus e etc])
                    if (($usuario->id_nivel >= "1" && $usuario->id_nivel <= "3") || $usuario->id_nivel == "5") {
                        redirect('in/gestor');
                    }
                } else {
                    $data['erro_login'] = 'Usuário bloqueado. Entre em contato com adm@physisbrasil.com';
                    $data['login'] = $this->input->post('login');
                    $data['main'] = 'login/login';
                    $this->load->view('in/template_login', $data);
                }
            }
        }

        $pagina = "controle_usuarios/seleciona_sistema";

        $data['sistemas'] = array(
            'M' => 'Municipal',
            'P' => 'Parlamentar',
            'E' => 'Estadual'
        );

        $data['usuario'] = $usuario;
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Selecionar Sistema';
        } else {
            $data['title'] = 'Physis - Selecionar Sistema';
        }
        $data['main'] = $pagina;

        $this->load->view('in/template_login', $data);
    }

    public function seleciona_sistema_nivel() {
        $this->load->model('usuariomodel');
        $this->load->model('nivel_usuario');
        $this->load->model('gestor');

        $usuario = null;
        $users = $this->session->userdata('users_from_login');

        if ($this->input->post()) {
            $this->form_validation->set_rules('sistema', 'Sistema', 'required');
            $this->form_validation->set_rules('perfil', 'Perfil', 'required');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === TRUE) {

                $sistema = $this->input->post('sistema');
                $perfil = $this->input->post('perfil');

                foreach ($users as $user) {
                    if ($user->id_nivel == $perfil && ($user->usuario_sistema == $sistema || $user->usuario_sistema == 'T')) {
                        $usuario = $user;
                        break;
                    }
                }

                if ($usuario != null) {
                    if ($usuario->status === "A") {
                        $data = array(
                            'login' => $this->input->post('login'),
                            'id_usuario' => $usuario->id_usuario,
                            'nome_usuario' => $usuario->nome,
                            'entidade' => $usuario->entidade,
                            'nivel' => $usuario->id_nivel,
                            'usuario_sistema' => $usuario->usuario_sistema,
                            'sistema' => $sistema,
                            'logged' => true
                        );

                        if ($usuario->id_nivel === "2") {
                            $gestor = $this->gestor->get_all_by_usuario($usuario->id_usuario);
                            $data['nivel_gestor'] = $gestor->nivel_gestor;
                        }
                        $this->session->set_userdata($data);

                        $ehParlamentar = false;
                        if ($usuario->id_nivel === "2") {
                            $ehParlamentar = $this->usuariomodel->verifica_eh_parlamentar();
                        }

                        if ($usuario->id_nivel === "4" || $usuario->id_nivel === "1" || $ehParlamentar) {
                            $options = array(
                                'login_siconv' => '',
                                'senha_siconv' => '',
                                'vendedor_codigo_parlamentar' => ''
                            );

                            $this->limpa_dados_sinconv($usuario->id_usuario, $options);

                            if ($usuario->id_nivel == "4") {
                                $this->usuariomodel->update_modo_vendedor($usuario->id_usuario);
                            }
                        }

                        if ($usuario->primeiro_acesso === "S") {
                            redirect('licenca_uso');
                        }

                        if (($usuario->id_nivel === "3" || $usuario->id_nivel === "2") && $this->usuariomodel->verifica_possui_usuario_siconv()) {
                            redirect('controle_usuarios/vincula_usuario_siconv');
                        }

                        if ($usuario->id_nivel === "4") {
                            $this->session->set_userdata('rel_visualizado', '');
                            if ($sistema == 'M' || $sistema == 'E') {
                                redirect('controle_usuarios/vincular_cnpj_vendedor');
                            } else {
                                redirect('controle_usuarios/vincular_codigo_parlamentar_vendedor');
                            }
                        }
                        ##Thomas: Depois verificar como filtras os niveis (melhor seria ter todas as funcoes em um controle e so modificar os metodos e visões [menus e etc])
                        if (($usuario->id_nivel >= "1" && $usuario->id_nivel <= "3") || ($query->id_nivel >= "5" && $query->id_nivel <= "8")) {
                            redirect('in/gestor');
                        }
                    } else {
                        $data['erro_login'] = 'Usuário bloqueado. Entre em contato com adm@physisbrasil.com';
                        $data['login'] = $this->login;
                        $data['main'] = 'login/login';
                        $this->load->view('in/template_login', $data);
                    }
                }
            }
        }

        $pagina = "controle_usuarios/seleciona_sistema_nivel";

        $array_sistemas = array();
        $array_perfis = array();
        foreach ($users as $user) {
            if (count($array_sistemas) != 2) {
                if ($user->usuario_sistema == 'T') {
                    $array_sistemas = array(
                        'M' => 'Municipal',
                        'P' => 'Parlamentar',
                        'E' => 'Estadual'
                    );
                } else {
                    if (!array_key_exists($user->usuario_sistema, $array_sistemas)) {
                        if ($user->usuario_sistema == 'P') {
                            $array_sistemas['P'] = 'Parlamentar';
                        } else if ($user->usuario_sistema == 'M') {
                            $array_sistemas['M'] = 'Municipal';
                        } else if ($user->usuario_sistema == 'E') {
                            $array_sistemas['E'] = 'Estadual';
                        }
                    }
                }
            }


            $nome_nivel = "";
            switch ($user->id_nivel) {
                case 1:
                    $nome_nivel = "Administrador";
                    break;
                case 2:
                    $nome_nivel = "Gestor";
                    break;
                case 3:
                    $nome_nivel = "Técnico";
                    break;
                case 4:
                    $nome_nivel = "Representante Comercial";
                    break;
                case 5:
                    $nome_nivel = "Prefeito";
                    break;
            }

            $array_perfis[$user->id_nivel] = $nome_nivel;
        }

        $data['sistemas'] = $array_sistemas;
        $data['perfis'] = $array_perfis;
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Selecionar Sistema';
        } else {
            $data['title'] = 'Physis - Selecionar Sistema';
        }
        $data['main'] = $pagina;

        $this->load->view('in/template_login', $data);
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
        redirect('in/login');
    }

    public
            function validaCampos() {
        if ($this->input->post('campoSenha', TRUE) != "")
            $this->db->where('senha', sha1($this->input->post('campoSenha', TRUE)));

        //$this->db->where('id_nivel', $this->input->post('nivel', TRUE));
        $this->db->where('login', $this->input->post('valorCampo', TRUE));
        $query = $this->db->get('usuario')->result();

        echo count($query);
    }

}
