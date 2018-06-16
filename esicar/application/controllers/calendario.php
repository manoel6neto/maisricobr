<?php

class calendario extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('calendario_eventos');
        $data['eventos'] = $this->calendario_eventos->get_by_user($this->session->userdata('id_usuario'));
        $data['main'] = "gestor/calendario";
        $data['title'] = "Calendário";

        $this->load->view('in/template', $data);
    }

    public function finalizar() {
        $id = $this->input->post('id');
        $this->load->model('calendario_eventos');
        $pertence = $this->calendario_eventos->pertence($this->session->userdata('id_usuario'), $id);

        if ($pertence == TRUE) {
            $dados['situacao'] = 2;
            $update = $this->calendario_eventos->update($id, $dados);

            if ($update == TRUE)
                echo 'true';
            else
                echo 'false';
        }else {
            echo 'false';
        }
    }

    public function excluir() {
        $id = $this->input->post('id');
        $this->load->model('calendario_eventos');
        $pertence = $this->calendario_eventos->pertence($this->session->userdata('id_usuario'), $id);

        if ($pertence == TRUE) {
            $update = $this->calendario_eventos->excluir($id);

            if ($update == TRUE)
                echo 'true';
            else
                echo 'false';
        }else {
            echo 'false';
        }
    }

    public function nova_tarefa() {
        $dados = Array(
            'titulo' => $this->input->post('titulo'),
            'descricao' => $this->input->post('descricao'),
            'data_inicio' => $this->input->post('inicio'),
            'data_fim' => $this->input->post('fim'),
            'existente' => 0,
            'situacao' => 1,
            'usuario_id' => $this->session->userdata('id_usuario')
        );

        $this->load->model('calendario_eventos');
        $insert = $this->calendario_eventos->insert($dados);

        if ($insert)
            echo "true";
        else
            echo "false";
    }

    public function editar() {
        $dados = Array(
            'titulo' => $this->input->post('titulo'),
            'descricao' => $this->input->post('descricao'),
            'data_inicio' => $this->input->post('inicio'),
            'data_fim' => $this->input->post('fim'),
        );
        $aux = $dados;
        $this->load->model('calendario_eventos');
        $insert = $this->calendario_eventos->update($this->input->post('id'), $dados);

        if ($insert)
            echo "true";
        else
            echo "false";
    }

}
