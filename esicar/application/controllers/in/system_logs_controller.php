<?php

include 'application/controllers/BaseController.php';
date_default_timezone_set('America/Sao_Paulo');

class system_logs_controller extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function visualizar_logs($id_usuario = 0, $offset = 0) {
        $this->session->set_userdata('pagAtual', 'logs');

        $this->load->model('system_logs');
        $this->load->model('usuariomodel');
        $this->load->model('relatorio_model');

        if ($this->input->post('usuario', TRUE))
            $id_usuario = $this->input->post('usuario', TRUE);

        $data['logs'] = $this->system_logs->get_all(20, $offset, $id_usuario);

        $config['base_url'] = base_url("index.php/in/system_logs_controller/visualizar_logs/" . $id_usuario . "/");
        $config['total_rows'] = $this->system_logs->get_number_rows($id_usuario);
        $config['per_page'] = 20;
        $config['first_link'] = 'Inicio';
        $config['last_link'] = 'Fim';
        $config['num_links'] = 3;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);

        $data['paginas'] = $this->pagination->create_links();

        $data['usuario_filtro'] = $id_usuario;
        $data['all_users'] = $this->relatorio_model->get_usuarios_rel();
        $data['usuariomodel'] = $this->usuariomodel;
        $data['title'] = "Physis - Logs";
        $data['main'] = 'logs/visualizar_logs';
        $this->load->view('controle_usuarios/temp_controle_usuarios', $data);
    }

}
