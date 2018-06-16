<?php

class usuario_cnpj extends CI_Model {

    function get_all() {
        $query = $this->db->get('usuario_cnpj');
        return $query->result();
    }

    function get_all_by_cnpj($id_cnpj) {
        $this->db->where('id_cnpj', $id_cnpj);
        $query = $this->db->get('usuario_cnpj');
        return $query->result();
    }

    function get_all_by_usuario($id_usuario, $direcaoJoin = "") {
        $this->db->distinct();
        $this->db->where('usuario_cnpj.id_usuario', $id_usuario);

        if ($this->session->userdata('nivel') != 4) {
            $this->db->select('usuario_cnpj.id_usuario as id, id_cnpj, gestor.id_gestor, validade, quantidade_cnpj, gestor.id_usuario, inicio_vigencia, tipo_gestor, id_cnpj_siconv, cnpj, id_cidade');
            $this->db->or_where('usuario_gestor.id_usuario', $id_usuario);
            $this->db->join('gestor', 'usuario_cnpj.id_usuario = gestor.id_usuario', $direcaoJoin);
            $this->db->join('cnpj_siconv', 'cnpj_siconv.id_cnpj_siconv = usuario_cnpj.id_cnpj', $direcaoJoin);
            $this->db->join('usuario_gestor', 'usuario_gestor.id_gestor = gestor.id_gestor', 'LEFT');
        }

        $query = $this->db->get('usuario_cnpj');
        //echo $this->db->last_query();
        return $query->result();
    }

    public function get_all_by_subgestor($id_usuario) {
        $this->db->distinct();
        $this->db->where('usuario_cnpj.id_usuario', $id_usuario);

        $this->db->select('usuario_cnpj.id_usuario as id, id_cnpj, gestor.id_gestor, validade, quantidade_cnpj, gestor.id_usuario, inicio_vigencia, tipo_gestor, id_cnpj_siconv, cnpj, id_cidade');
        $this->db->or_where('usuario_subgestor.id_usuario', $id_usuario);
        $this->db->join('gestor', 'usuario_cnpj.id_usuario = gestor.id_usuario');
        $this->db->join('cnpj_siconv', 'cnpj_siconv.id_cnpj_siconv = usuario_cnpj.id_cnpj');
        $this->db->join('usuario_subgestor', 'usuario_subgestor.id_gestor = gestor.id_gestor', 'LEFT');

        $query = $this->db->get('usuario_cnpj');
        //echo $this->db->last_query();
        return $query->result();
    }

    function delete_by_cnpj($id_cnpj) {
        $this->db->where('id_cnpj', $id_cnpj);
        $this->db->delete('usuario_cnpj');
        return $this->db->affected_rows();
    }

    function delete_by_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->delete('usuario_cnpj');
        return $this->db->affected_rows();
    }

    function add_or_update_usuario_cnpj_for_usuario($id_usuario, $id_cnpj) {

        $data = array(
            'id_usuario' => $id_usuario,
            'id_cnpj' => $id_cnpj
        );

        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('usuario_cnpj');

        if ($query->num_rows > 0) {
            $this->db->where('id_usuario', $id_usuario);
            return $this->db->update('usuario_cnpj', $data);
        }

        return $this->db->insert('usuario_cnpj', $data);
    }

    public function get_all_dados_by_usuario($id_usuario) {
        $this->db->distinct();
        $this->db->select('id_cnpj_siconv, cnpj_siconv.cnpj, cnpj_siconv.id_cidade, municipio AS nome, cnpj_instituicao, sigla');
        $this->db->where('id_usuario', $id_usuario);
        $this->db->join('cnpj_siconv', 'cnpj_siconv.id_cnpj_siconv = usuario_cnpj.id_cnpj');
        $this->db->join('proponente_siconv', 'id_cidade = codigo_municipio');
        $query = $this->db->get('usuario_cnpj');

        return $query->result();
    }

    public function insere_usuario_cnpj($id_usuario, $id_cnpj) {
        $data = array(
            'id_usuario' => $id_usuario,
            'id_cnpj' => $id_cnpj
        );

        return $this->db->insert('usuario_cnpj', $data);
    }

    public function get_cnpjs_atualizar() {
        #select distinct cnpj from cnpj_siconv join usuario_cnpj on id_cnpj_siconv = id_cnpj join usuario on usuario_cnpj.id_usuario = usuario.id_usuario where id_nivel = 2 or id_nivel = 4
        $this->db->distinct();
        $this->db->select('cnpj');
        $this->db->where('id_nivel', 2);
        $this->db->where('status', 'A');
        $this->db->join('usuario_cnpj', 'usuario_cnpj.id_usuario = usuario.id_usuario');
        $this->db->join('cnpj_siconv', 'id_cnpj_siconv = id_cnpj');

        $this->db->where_not_in('usuario.id_usuario', array(25, 34, 37, 38, 41, 58, 124, 117, 99, 98, 63, 59));
        $query = $this->db->get('usuario')->result();

        return $query;
    }

    public function get_login_siconv($cnpj) {

        $resultado_final = array();

        //get gestor
        $this->db->distinct();
        $this->db->select('u.id_usuario, u.login_siconv, u.senha_siconv');
        $this->db->join('usuario_cnpj uc', 'uc.id_usuario = u.id_usuario');
        $this->db->join('cnpj_siconv cs', 'cs.id_cnpj_siconv = uc.id_cnpj');
        $this->db->where('u.id_nivel', 2);
        $this->db->where('u.status', 'A');
        $this->db->where('u.login_siconv <> ""');
        $this->db->where('u.senha_siconv <> ""');
        $this->db->where('u.login_siconv is not null');
        $this->db->where('u.senha_siconv is not null');
        $this->db->where('cs.cnpj', $cnpj);

        $query = $this->db->get('usuario u');

        if ($query->num_rows > 0) {
            $resultado_final = array_merge($resultado_final, $query->result());

            //get tecnicos e outros
            //get id gestor
            $this->db->where('id_usuario', $resultado_final[0]->id_usuario);
            $query = $this->db->get('gestor');

            $id_gestor = null;
            if ($query->num_rows > 0) {
                $id_gestor = $query->row(0)->id_gestor;
                //get usuarios
                $this->db->select('u.id_usuario, u.login_siconv, u.senha_siconv');
                $this->db->join('usuario u', 'u.id_usuario = ug.id_usuario');
                $this->db->where('ug.id_gestor', $id_gestor);
                $query = $this->db->get('usuario_gestor ug');

                if ($query->num_rows > 0) {
                    $resultado_final = array_merge($resultado_final, $query->result());
                }
            }
        }

        return $resultado_final;
    }

    public function get_cnpjs_vinculados_vendedores() {
        $this->db->distinct();
        $this->db->select('cnpj_vinculado');

        return $this->db->get('cnpj_vendedores')->result();
    }

    public function get_dados_envia_email($cnpj) {
        #select distinct nome, email from cnpj_siconv join usuario_cnpj on id_cnpj_siconv = id_cnpj join usuario on usuario_cnpj.id_usuario = usuario.id_usuario where cnpj = '$cnpj'
        $this->db->distinct();
        $this->db->select('nome, email');
        $this->db->where('cnpj', $cnpj);
        $this->db->join('usuario_cnpj', 'usuario_cnpj.id_usuario = usuario.id_usuario');
        $this->db->join('cnpj_siconv', 'id_cnpj_siconv = id_cnpj');

        return $this->db->get('usuario')->row(0);
    }

    public function copia_cnpj_vendedor($options) {
        $this->db->where('id_usuario', $options['id_usuario']);
        $this->db->where('cnpj_vinculado', $options['cnpj_vinculado']);
        $query = $this->db->get('cnpj_vendedores');
        if (count($query->result()) == 0)
            $this->db->insert('cnpj_vendedores', $options);
    }

    public function get_cnpjs_gestor($id_usuario) {
        $this->db->select('cnpj');
        $this->db->join('usuario_cnpj', 'id_cnpj = id_cnpj_siconv');
        $this->db->where('id_usuario', $id_usuario);
        $dados = $this->db->get('cnpj_siconv')->result();

        $lista = array();
        foreach ($dados as $d)
            $lista[] = $d->cnpj;

        return $lista;
    }
    
    public function get_cnpjs_gestor_object($id_usuario) {
        $this->db->join('usuario_cnpj', 'id_cnpj = id_cnpj_siconv');
        $this->db->where('id_usuario', $id_usuario);
        $dados = $this->db->get('cnpj_siconv')->result();

        return $dados;
    }

}
