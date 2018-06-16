<?php

class usuario_subgestor_model extends CI_Model{
	
	function get_all() {
		$query = $this->db->get('usuario_subgestor');
		return $query->result();
	}
	
	function get_all_by_gestor($id_gestor) {
		$this->db->where('id_gestor', $id_gestor);
		$query = $this->db->get('usuario_subgestor');
		return $query->result();
	}
	
	function get_by_usuario($id_usuario) {
		$this->db->where('id_usuario', $id_usuario);
		$query = $this->db->get('usuario_subgestor');
		return $query->row(0);
	}
	
	function delete_by_gestor($id_gestor) {
		$this->db->where('id_gestor', $id_gestor);
		$this->db->delete('usuario_subgestor');
		return $this->db->affected_rows();
	}
	
	function delete_by_usuario($id_usuario) {
		$this->db->where('id_usuario', $id_usuario);
		$this->db->delete('usuario_subgestor');
		return $this->db->affected_rows();
	}
	
	function add_or_update_usuario_gestor_for_usuario($id_usuario, $id_gestor) {
	
		$data = array(
				'id_usuario' => $id_usuario,
				'id_gestor' => $id_gestor
		);
	
		$this->db->where('id_usuario', $id_usuario);
		$query = $this->db->get('usuario_subgestor');
	
		if ($query->num_rows > 0) {
			$this->db->where('id_usuario', $id_usuario);
			return $this->db->update('usuario_subgestor', $data);
		}
	
		return $this->db->insert('usuario_subgestor', $data);
	}
	
	public function get_tipo_gestor($id){
		$this->db->where('id_usuario', $id);
		return $this->db->get('gestor')->row(0)->tipo_gestor;
	}
}