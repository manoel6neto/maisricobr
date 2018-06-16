<?php

class calendario_eventos extends CI_Model {

    public function get_by_user($usuario_id) {
        $this->db->where('usuario_id', $usuario_id);
        $query = $this->db->get('calendario');

        return $query->result();
    }

    public function insert($dados) {
        $this->db->insert('calendario', $dados);

        if ($this->db->affected_rows() == 1) {
            return TRUE;
        }

        return FALSE;
    }

    public function pertence($usuario_id, $id) {
        $this->db->where('usuario_id', $usuario_id);
        $this->db->where('id', $id);
        $query = $this->db->get('calendario');

        if ($query->num_rows > 0) {
            return true;
        }
        return false;
    }

    public function update($id, $dados) {
        $this->db->where('id', $id);
        $this->db->update('calendario', $dados);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function excluir($id) {
        $this->db->where('id', $id);
        $this->db->delete('calendario');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

}
