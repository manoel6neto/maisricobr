<?php

class usuario_model extends CI_Model{
	
	## Thomas: Alterando para poder filtrar ou não pelo tipo de usuario ao inves de criar vários metodos para a mesma coisa
	function get_all($filter = NULL){
                if ($filter != NULL) { $this->db->where('tipoPessoa !=', $filter); }
		$query = $this->db->get('pessoa');
		return $query->result();
	}

	function get_all_trabalho($id){
		$this->db->where('idGestor', $id);
		$query = $this->db->get('pessoa');
		return $query->result();
	}
	
	function get_all_municipio(){
		$this->db->where('tipoPessoa', 3);
		$query = $this->db->get('pessoa');
		return $query->result();
	}
	
	function get_all_gestores(){
		$this->db->where('tipoPessoa', 2);
		$query = $this->db->get('pessoa');
		return $query->result();
	}
	
	function get_all_tipos_usuarios(){
		$query = $this->db->get('tipo_pessoa');
		return $query->result();
	}
	
	function obter_situacao($id){
		
		$this->db->where('idPessoa', $id);
		$query = $this->db->get('pessoa');
		return $query->row(0)->ativo;
	}
        
        public function get_proponentes_by_gestor($id_usuario) {
            $this->db->distinct();
            $this->db->select('cs.*');
            $this->db->join('usuario_cnpj uc', 'uc.id_cnpj = cs.id_cnpj_siconv');
            $this->db->where('uc.id_usuario', $id_usuario);
            
            $query = $this->db->get('cnpj_siconv cs');
            
            if ($query->num_rows > 0) {
                return $query->result();
            } else {
                return null;
            }
        }
	
	function verifica_dono_proposta($usuario, $id){
		
		//se for o dono
		$this->db->where('idProposta', $id);
		$this->db->where('idGestor', $usuario);
		$query = $this->db->get('proposta');
		if (count($query->result()) > 0)
			return true;
		
		//se for padrão
		$this->db->where('idProposta', $id);
		$this->db->where('padrao', true);
		$query = $this->db->get('proposta');
		if (count($query->result()) > 0)
			return true;

		//se existir um trabalho atribuido a ela nesse projeto
		$this->db->where('id_correspondente', $id);
		$this->db->where('Pessoa_idPessoa', $usuario);
		$query = $this->db->get('trabalho');
		if (count($query->result()) > 0)
			return true;

		return false;
	}

	function ativa_usuario($options, $id){
		$this->db->where('idPessoa', $id);
		$this->db->update('pessoa', $options);
		return $this->db->affected_rows();
	}
	
	function adiciona_relatorio($id, $nome){

		$options['idPessoa'] = $id;
		$options['nome'] = $nome;
		$options['status'] = false;
		 
		$this->db->insert('documento', $options);
		return $this->db->insert_id();
	}
	
	function verifica_relatorio($id, $usuario){

		$this->db->where('id', $id);
		$this->db->where('idPessoa', $usuario);
		$this->db->where('status', true);
		$query = $this->db->get('documento');
		
		if (count($query->result_array()) > 0)
			return true;
		return false;
	}
	
	function aceita_relatorio($id, $nome){
		
		$this->db->where('id', $id);
		$query = $this->db->get('documento');
		
		$this->db->where('idPessoa', $query->row(0)->idPessoa);
		$query1 = $this->db->get('pessoa');
		$email = $query1->row(0)->email;
		
		$options['status'] = true;
		$this->db->where('id', $id);
		$this->db->update('documento', $options);
		
		$this->load->library('email');

		$this->email->from('adm@physisbrasil.com.br', 'Physis Brasil');
		$this->email->to($email);
		
		$texto = "Atenção! Acesse seu relatório no link <a href=\"".base_url()."index.php/in/usuario/ler_pdf?id=$id&nome=$nome\">Relatório</a>!
			Obrigado,
				Physis Brasil";
		
		$this->email->subject('Liberação de arquivo de relatório');
		$this->email->message($texto);
		$this->email->send();
		
		return $this->db->affected_rows();
	}
	
	function altera_senha($nova_senha, $codigo, $email_codigo){

		$options['senha'] = hash('sha1', $nova_senha);
		
		$this->db->where('email', $email_codigo);
		$this->db->update('pessoa', $options);
		
		$this->db->where('codigo', $codigo);
		$this->db->delete('codigos'); 
		
		return $this->db->affected_rows();
	}
	function verifica_codigo($codigo){
		
		$this->db->where('codigo', $codigo);
		$this->db->where('data > NOW()', null);
		$query = $this->db->get('codigos');

		if (count($query->result()) > 0)
			return true;
		return false;
	}
	
	function recupera_email($email){

		$this->db->where('email', $email);
		$query = $this->db->get('pessoa');
		$codigo = base64_encode($email);
		if (count($query->result_array()) > 0) {
			//$email = $query->row(0)->email;
			
			$data_expirar = date('Y-m-d H:i:s', strtotime('+1 day'));

			$texto = 'Recebemos uma tentativa de recuperação de senha para este e-mail, caso não tenha sido você,
desconsidere este e-mail, caso contrário clique no link abaixo 
				<a href="'.base_url().'index.php/in/login/gera_senha?codigo='.$codigo.'">Recuperar Senha</a></p>';
			
			$this->load->library('email');

			$this->email->from('adm@physisbrasil.com.br', 'Physis Brasil');
			$this->email->to($email);
			
			$this->email->subject('Recuparação de senha');
			$this->email->message($texto);
			$this->email->send();
			
		}
		$options['codigo'] = $codigo;
		$options['data'] = $data_expirar;
		$this->db->insert('codigos', $options);
		return $this->db->insert_id();
		
	}
	
	function obter_tipo_por_id($id){
		
		$this->db->where('idPessoa', $id);
		$query = $this->db->get('tipo_pessoa');
		return $query->row(0)->nome;
	}
	
	function decrementa_quantidade($id){

		$this->db->where('idPessoa', $id);
		$query = $this->db->get('pessoa');
		$quant = intval($query->row(0)->quantidade);
		if ($quant < 1){
			return false;
		}
		
		$options['quantidade'] = $quant - 1;
		$this->db->where('idPessoa', $id);
		$this->db->update('pessoa', $options);
		
		return true;	
		}	
	
	function add_records($options = array()){
		$this->db->where('login', $options['login']);
		$query = $this->db->get('pessoa');
		$populacao = $query->result_array();
		
		if (count($populacao) > 0) return false;
		
		$this->db->insert('pessoa', $options);
		return $this->db->insert_id();
	}

	## Thomas: Adicionado metodo no model para inserir usuários utilizando form helpers
	function add_pessoas() {
		$this->load->helper('url');

		$data = array(
			'login' => $this->input->post('login'),
			'senha' => $this->input->post('senha'),
			##TODO: Criar form para cadastro evitando duplicar o nome com o login
			'nome' => $this->input->post('login')
		);

		$this->db->where('login', $data['login']);
		$query = $this->db->get('pessoa');
		$populacao = $query->result_array();
		
		if (count($populacao) > 0) return NULL;

		return $this->db->insert('pessoa', $data);
	}
	
	function delete_record(){
		$this->db->where('idPessoa', $this->uri->segment(4));
		$this->db->delete('pessoa');
		return $this->db->affected_rows();
	}
	
	function update_record($options = array()){
		if (isset($options['nome']))
			$this->db->set('nome', $options['nome']);
		if (isset($options['senha']))
			$this->db->set('senha', $options['senha']);
		$this->db->where('idPessoa', $options['idPessoa']);
		$this->db->update('pessoa', $options);
		return $this->db->affected_rows();
	}
	
	function get_by_login($id){
		$this->db->where('login', $id);
		$query = $this->db->get('pessoa');
		return $query->row(0);
	}
	
	function get_by_id($id){
		
		$this->db->where('idPessoa', $id);
		$query = $this->db->get('pessoa');
		return $query->row(0);
	}
	
	# VALIDA USUÁRIO
    function validar() {
		$senha = hash('sha1', $this->input->post('senha'));

        $this->db->where('login', $this->input->post('login')); 
        $this->db->where('senha', $senha);
        $this->db->where('ativo', 1); // Verifica o status do usuário

        $query = $this->db->get('pessoa'); 

        if ($query->num_rows > 0) {
            return $query->row(0); // RETORNA as restricoes de usuario
        }
        
    }

    # VERIFICA SE O USUÁRIO ESTÁ LOGADO
    function logged($tipo) {
        $logged = $this->session->userdata('logged');
        //if ($tipo != 6 && $tipo !=7) echo "teste";
        if ((!isset($logged) || $logged != true) || ($tipo != $this->session->userdata('tipo') && $tipo !=1 )) {
            echo 'Sem acesso para entrar nesta zona do site. <a href="'.base_url().'index.php/in/login">Efetuar Login</a>';
            die();
        }
        return true;
    }
}

?>
