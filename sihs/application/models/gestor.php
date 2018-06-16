<?php

class gestor extends CI_Model {

    function get_all() {
        $query = $this->db->get('gestor');
        return $query->result();
    }

    function get_by_id($id_gestor) {
        $this->db->where('id_gestor', $id_gestor);
        $query = $this->db->get('gestor');
        return $query->row(0);
    }

    function get_by_user_id($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('gestor');
        return $query->row(0);
    }

    public function get_all_by_usuario($id_usuario) {
        $this->db->select('gestor.*, usuario_gestor.id_usuario AS idUsuario, usuario_gestor.id_gestor AS idGestor');
        $this->db->where('gestor.id_usuario', $id_usuario);
        $this->db->or_where('usuario_gestor.id_usuario', $id_usuario);
        $this->db->join('usuario_gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
        $query = $this->db->get('gestor')->row(0);

        return $query;
    }

    public function add_gestor_from_array($options) {
        $this->db->insert('gestor', $options);
    }

    public function get_tecnicos($id_gestor) {
        $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario');
        $this->db->where('usuario_gestor.id_gestor', $id_gestor);
        $query = $this->db->get('usuario');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return null;
    }

    public function get_by_usuario_only_gestor($id_usuario) {
        $this->db->where('gestor.id_usuario', $id_usuario);
        return $this->db->get('gestor')->row(0);
    }

    public function get_id_by_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('gestor');

        if ($query->num_rows > 0) {
            return $query->row(0)->id_gestor;
        } else {
            return null;
        }
    }

    public function get_gestores_info() {
        $this->db->select('u.id_usuario, u.nome, u.email, cnpjs.cnpj');
        $this->db->join('gestor g', 'u.id_usuario = g.id_usuario');
        $this->db->join('usuario_cnpj uc', 'u.id_usuario = uc.id_usuario');
        $this->db->join('cnpj_siconv cnpjs', 'uc.id_cnpj = cnpjs.id_cnpj_siconv');
        $this->db->where('g.tipo_gestor', 10);
        $query = $this->db->get('usuario u');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_by_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('gestor');

        return $query->row(0);
    }

    function get_by_login($login) {
        $this->db->where('login', $login);
        $this->db->where('id_nivel', 2);
        $query_user = $this->db->get('usuario');
        $usuario = $query_user->row(0);

        $this->db->where('id_usuario', $usuario->id_usuario);
        $query = $this->db->get('gestor');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    function get_validade($id_gestor) {

        $this->db->where('id_gestor', $id_gestor);
        $query = $this->db->get('gestor');

        return $query->row(0)->validade;
    }

    function delete_gestor($id) {
        $this->db->where('id_gestor', $id);
        $this->db->delete('gestor');
        return $this->db->affected_rows();
    }

    function update_gestor($id) {
        $this->load->helper('url');

        $data = array(
            'validade' => $this->input->post('validade'),
            'quantidade_cnpj' => $this->input->post('quantidade_cnpj'),
            'quantidade_subcontas' => $this->input->post('quantidade_subcontas')
        );

        $this->db->where('id_gestor', $id);
        return $this->db->update('gestor', $data);
    }

    function add_gestor() {
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
            'id_nivel' => $this->input->post('id_nivel'),
        );

        $this->db->where('login', $data['login']);
        $query_usuario = $this->db->get('usuario');
        $populacao = $query_usuario->result_array();

        if (count($populacao) > 0) {
            return NULL;
        }

        $id_usario = $this->db->insert('usuario', $data);

        $data_gestor = array(
            'id_usuario' => $id_usario,
            'validade' => $this->input->post('validade'),
            'quantidade_cnpj' => $this->input->post('quantidade_cnpj'),
            'quantidade_subcontas' => $this->input->post('quantidade_subcontas')
        );

        $this->db->where('id_usuario', $data_gestor['id_usuario']);
        $query_gestor = $this->db->get('gestor');

        if ($query_gestor->num_rows > 0) {
            return null;
        }

        return $this->db->insert('gestor', $data_gestor);
    }

    public function insere_gestor($options) {
        $this->db->insert('gestor', $options);

        return $this->db->insert_id();
    }

    public function atualiza_gestor($id, $options) {
        $this->db->where('id_usuario', $id);
        $this->db->update('gestor', $options);
    }

}
