<?php

class system_logs extends CI_Model {

    function get_all($limit, $offset, $id_usuario = 0) {
        $this->load->model('relatorio_model');
        $usuarios = $this->relatorio_model->get_usuarios_rel();

        $lista_ids_user = array();
        foreach ($usuarios as $k => $u)
            $lista_ids_user[] = $k;

        $this->db->where_in('id_usuario', $lista_ids_user);

        if ($id_usuario > 0)
            $this->db->where('id_usuario', $id_usuario);

        $this->db->order_by("system_log_id", "desc");
        $query = $this->db->get('system_logs', $limit, $offset);
        return $query->result();
    }

    public function get_number_rows($id_usuario = 0) {
        if ($this->session->userdata('nivel') == 2) {
            $this->load->model('relatorio_model');
            $usuarios = $this->relatorio_model->get_usuarios_rel();

            $lista_ids_user = array();
            foreach ($usuarios as $k => $u)
                $lista_ids_user[] = $k;

            $this->db->where_in('id_usuario', $lista_ids_user);
        }

        if ($id_usuario > 0)
            $this->db->where('id_usuario', $id_usuario);

        return $this->db->get('system_logs')->num_rows();
    }

    public function get_log_ultimo_acesso($lista_ids) {
        $this->db->where_in('id_usuario', $lista_ids);
        $this->db->order_by("system_log_id", "desc");
        $query = $this->db->get('system_logs');

        return $query->result();
    }

    function get_by_id($id_system_log) {
        $this->db->where('system_log_id', $id_system_log);
        $query = $this->db->get('system_logs');
        return $query->row(0);
    }

    function get_all_by_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('system_logs');

        return $query->result();
    }

    function get_all_by_acao($acao) {
        $this->db->where('acao', $acao);
        $query = $this->db->get('system_logs');

        return $query->result();
    }

    function add_log($acao, $cadastroAdmin = false) {
        if ($this->session->userdata('id_usuario') > 0) {
            $options = array(
                'acao' => $acao,
                'id_usuario' => $this->session->userdata('id_usuario'),
                'data' => date("Y-m-d H:i:s")
            );
        } else {
            $options = array(
                'acao' => $acao,
                'id_usuario' => 1,
                'data' => date("Y-m-d H:i:s")
            );
        }

        $this->db->insert('system_logs', $options);
    }

    function add_log_importa($acao) {
        $options = array(
            'acao' => $acao,
            'id_usuario' => 1,
            'data' => date("Y-m-d H:i:s")
        );

        $this->db->insert('system_logs', $options);
    }

    public function add_log_cron($acao) {
        $options = array(
            'acao' => $acao,
            'id_usuario' => 1,
            'data' => date("Y-m-d H:i:s")
        );

        $this->db->insert('system_logs', $options);
    }

}
