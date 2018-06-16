<?php

include 'application/controllers/BaseController.php';

class declaracao extends BaseController{
	
	public function index(){
		$data['title'] = 'Declarações';
		$data['main'] = 'declaracao/index';
		
		$this->session->set_userdata('pagAtual', 'declaracao');
		
		$this->load->view('in/template', $data);
	}
	
	public function lista_rel_capacidade(){
		$this->load->model('dados_rel_capacidade_model');
		$this->load->model('proponente_siconv_model');
		
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['dados_rel'] = $this->dados_rel_capacidade_model->get_all_by_usuario();
		$data['title'] = "Lista de Declarações";
		$data['main'] = 'declaracao/lista_declaracao';
		
		$this->load->view('in/template', $data);
	}
	
	public function nova_capacidade(){
		$this->load->model('dados_rel_capacidade_model');
		
		$data['municipios'] = $this->dados_rel_capacidade_model->get_all_municipios();
		
		if($this->input->post()){
			$this->form_validation->set_rules('descricao_rel', 'Descrição', 'required');
			$this->form_validation->set_rules('nome_prefeito', 'Nome do Prefeito', 'required');
			$this->form_validation->set_rules('codigo_programa', 'Código do Programa', 'required');
			$this->form_validation->set_rules('nome_programa', 'Nome do Programa', 'required');
			$this->form_validation->set_rules('tipo_assinatura', 'Tipo de Assinatura', 'required');
			
			if(empty($_FILES['brasao_prefeitura']['name']))
				$this->form_validation->set_rules('brasao_prefeitura', 'Brasão', 'required');
			
			if($this->input->post('dados_engenharia') == 1){
				$this->form_validation->set_rules('nome_engenheiro', 'Nome do Engenheiro', 'required');
				$this->form_validation->set_rules('crea_engenheiro', 'CREA do Engenheiro', 'required');
			}
			
			$this->form_validation->set_message('required', 'O campo %s é obrigatório');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run()){
				$this->load->model('proponente_siconv_model');
				
				$options = $this->input->post();
				unset($options['dados_engenharia']);
				unset($options['cadastra']);
				unset($options['arquivo_assinatura']);
				unset($options['brasao_prefeitura']);
				$options['estado'] = $this->proponente_siconv_model->get_estado_sigla($options['municipio'])->municipio_uf_nome;
				$options['id_usuario'] = $this->session->userdata('id_usuario');
				
				$this->load->library('upload');
				
				$dir_user = $this->session->userdata('id_usuario').'_files/';
				$dir_files = './upload_assinatura/'.$dir_user;
				if(!file_exists($dir_files))
					mkdir($dir_files);
				
				if(isset($_FILES['brasao_prefeitura']) && $_FILES['brasao_prefeitura']['tmp_name'] != ""){
					$file_name = rand(100, 9999)."_".$this->session->userdata('id_usuario')."_brasao";
					
					$config['upload_path'] = $dir_files;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $file_name;
					$this->upload->initialize($config);
					
					$this->upload->do_upload('brasao_prefeitura');
					
					$options['brasao_prefeitura'] = $dir_user.$this->upload->file_name;
				}
				
				if(isset($_FILES['arquivo_assinatura']) && $_FILES['arquivo_assinatura']['tmp_name'] != ""){
					$file_name = rand(100, 9999)."_".$this->session->userdata('id_usuario')."_ass";
					
					$config['upload_path'] = $dir_files;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $file_name;
					$this->upload->initialize($config);
					
					$this->upload->do_upload('arquivo_assinatura');
						
					$options['arquivo_assinatura'] = $dir_user.$this->upload->file_name;
				}
				
				$this->dados_rel_capacidade_model->insere_dados_rel($options);
					
				$this->alert("Declaração criada com sucesso");
				$this->encaminha(base_url('index.php/declaracao/lista_rel_capacidade'));
			}
		}
		
		$data['title'] = 'Delcaração de Capacidade Técnica';
		$data['main'] = 'declaracao/nova_capacidade';
		
		$this->load->view('in/template', $data);
	}
	
	public function busca_programa_nome(){
		$this->db->where('codigo', $this->input->post('codigo_programa', TRUE));
		$programa = $this->db->get('siconv_programa')->row(0);
		
		if(isset($programa->nome))
			echo $programa->nome;
		else
			echo "ERRO";
	}
	
	function alert($text) {
		echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
	}
	
	function encaminha($url) {
		echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
		exit();
	}
	
	public function gera_rel_capacidade(){
		$this->load->library ( 'mPDF' );
		ob_start (); // inicia o buffer
		$tabela = utf8_encode ( $tabela );
	
		$mpdf = new mPDF ();
		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'UTF-8';
		
		$this->load->model('dados_rel_capacidade_model');
		$this->load->model('proponente_siconv_model');
		
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['dados_rel_capacidade_model'] = $this->dados_rel_capacidade_model;
		$data['dados_rel'] = $this->dados_rel_capacidade_model->get_by_id($this->input->get('id', TRUE));
		
	
		$tabela = $this->load->view('declaracao/rel_capacidade_tecnica', $data, TRUE);
	
		$mpdf->WriteHTML($tabela);
	
		$mpdf->Output('Declaração Capacidade Técnica', 'D');
	}
	
	public function gera_rel_capacidade_word(){
		header("Content-type: application/vnd.ms-word");
		header("Content-type: application/force-download");
		header("Content-Disposition: attachment; filename=\"Declaração Capacidade Técnica.doc\"");
		header("Pragma: no-cache");
		
		$this->load->model('dados_rel_capacidade_model');
		$this->load->model('proponente_siconv_model');
		
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['dados_rel_capacidade_model'] = $this->dados_rel_capacidade_model;
		$data['dados_rel'] = $this->dados_rel_capacidade_model->get_by_id($this->input->get('id', TRUE));
		
		$tabela = $this->load->view('declaracao/rel_capacidade_tecnica', $data, TRUE);
		
		echo $tabela;
		
		exit();
	}
	
	public function deleta_declaracao(){
		$this->load->model('system_logs');
		
		$this->db->where('id_rel', $this->input->get('id', TRUE));
		$dados = $this->db->get('dados_rel_capacidade')->row(0);
		
		if(isset($dados->brasao_prefeitura) && file_exists('./upload_assinatura/'.$dados->brasao_prefeitura))
			unlink('./upload_assinatura/'.$dados->brasao_prefeitura);
	
		if(isset($dados->arquivo_assinatura) && file_exists('./upload_assinatura/'.$dados->arquivo_assinatura))
			unlink('./upload_assinatura/'.$dados->arquivo_assinatura);
		
		$this->db->where('id_rel', $this->input->get('id', TRUE));
		$this->db->delete('dados_rel_capacidade');
		
		$this->system_logs->add_log(EXCLUI_ARQ_CAPACIDADE." - Descrição: ".$dados->descricao_rel);
		
		$this->alert("Arquivo deletado com sucesso.");
		$this->encaminha(base_url('index.php/declaracao/lista_rel_capacidade'));
	}
	
	public function nova_contrapartida(){
		$this->load->model('dados_rel_contrapartida_model');
		
		if($this->input->post()){
			$this->form_validation->set_rules('descricao_rel', 'Descrição', 'required');
			$this->form_validation->set_rules('nome_prefeito', 'Nome do Prefeito', 'required');
			$this->form_validation->set_rules('codigo_programa', 'Código do Programa', 'required');
			$this->form_validation->set_rules('nome_programa', 'Nome do Programa', 'required');
			$this->form_validation->set_rules('tipo_assinatura', 'Tipo de Assinatura', 'required');
			$this->form_validation->set_rules('valor_contrapartida', 'Valor de Contrapartida', 'required');
			$this->form_validation->set_rules('vlr_extenso_contrapartida', 'Valor por extenso contrapartida', 'required');
			$this->form_validation->set_rules('num_lei', 'Nº da Lei', 'required');
			$this->form_validation->set_rules('data_pub_lei', 'Data de Publicação da Lei', 'required');
			$this->form_validation->set_rules('ano_loa', 'Ano da LOA', 'required');
			$this->form_validation->set_rules('orgao', 'Orgão', 'required');
			$this->form_validation->set_rules('unidade', 'Unidade', 'required');
			$this->form_validation->set_rules('proj_atividade', 'Projeto/Atividade', 'required');
			$this->form_validation->set_rules('nat_despesa', 'Natureza da Despesa', 'required');
				
			if(empty($_FILES['brasao_prefeitura']['name']))
				$this->form_validation->set_rules('brasao_prefeitura', 'Brasão', 'required');
				
			$this->form_validation->set_message('required', 'O campo %s é obrigatório');
				
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run()){
				$this->load->model('proponente_siconv_model');
				
				$options = $this->input->post();
				unset($options['dados_engenharia']);
				unset($options['cadastra']);
				unset($options['arquivo_assinatura']);
				unset($options['brasao_prefeitura']);
				$options['estado'] = $this->proponente_siconv_model->get_estado_sigla($options['municipio'])->municipio_uf_nome;
				$options['id_usuario'] = $this->session->userdata('id_usuario');
				$options['valor_contrapartida'] = str_replace(",", ".", str_replace(".", "", $options['valor_contrapartida']));
				$options['data_pub_lei'] = implode("-", array_reverse(explode("/", $options['data_pub_lei'])));
				
				$this->load->library('upload');
				
				$dir_user = $this->session->userdata('id_usuario').'_files/';
				$dir_files = './upload_assinatura/'.$dir_user;
				if(!file_exists($dir_files))
					mkdir($dir_files);
				
				if(isset($_FILES['brasao_prefeitura']) && $_FILES['brasao_prefeitura']['tmp_name'] != ""){
					$file_name = rand(100, 9999)."_".$this->session->userdata('id_usuario')."_brasao";
						
					$config['upload_path'] = $dir_files;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $file_name;
					$this->upload->initialize($config);
						
					$this->upload->do_upload('brasao_prefeitura');
						
					$options['brasao_prefeitura'] = $dir_user.$this->upload->file_name;
				}
				
				if(isset($_FILES['arquivo_assinatura']) && $_FILES['arquivo_assinatura']['tmp_name'] != ""){
					$file_name = rand(100, 9999)."_".$this->session->userdata('id_usuario')."_ass";
						
					$config['upload_path'] = $dir_files;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $file_name;
					$this->upload->initialize($config);
						
					$this->upload->do_upload('arquivo_assinatura');
				
					$options['arquivo_assinatura'] = $dir_user.$this->upload->file_name;
				}
				
				$this->dados_rel_contrapartida_model->insere_dados_rel($options);
					
				$this->alert("Declaração criada com sucesso");
				$this->encaminha(base_url('index.php/declaracao/lista_dec_contrapartida'));
			}
		}
		
		$data['municipios'] = $this->dados_rel_contrapartida_model->get_all_municipios();
		
		$data['title'] = 'Delcaração de Contrapartida Financeira';
		$data['main'] = 'declaracao/nova_contrapartida';
		
		$this->load->view('in/template', $data);
	}
	
	public function lista_dec_contrapartida(){
		$this->load->model('dados_rel_contrapartida_model');
		$this->load->model('proponente_siconv_model');
	
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['dados_rel'] = $this->dados_rel_contrapartida_model->get_all_by_usuario();
		$data['title'] = "Lista de Declarações";
		$data['main'] = 'declaracao/lista_dec_contrapartida';
	
		$this->load->view('in/template', $data);
	}
	
	public function gera_rel_contrapartida(){
		$this->load->library ( 'mPDF' );
		ob_start (); // inicia o buffer
		$tabela = utf8_encode ( $tabela );
	
		$mpdf = new mPDF ();
		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'UTF-8';
	
		$this->load->model('dados_rel_contrapartida_model');
		$this->load->model('proponente_siconv_model');
	
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['dados_rel_capacidade_model'] = $this->dados_rel_contrapartida_model;
		$data['dados_rel'] = $this->dados_rel_contrapartida_model->get_by_id($this->input->get('id', TRUE));
	
	
		$tabela = $this->load->view('declaracao/rel_contrapartida_financeira', $data, TRUE);
	
		$mpdf->WriteHTML($tabela);
	
		$mpdf->Output('Declaração Contrapartida Financeira', 'D');
	}
	
	public function gera_rel_contrapartida_word(){
		header("Content-type: application/vnd.ms-word");
		header("Content-type: application/force-download");
		header("Content-Disposition: attachment; filename=\"Declaração Contrapartida Financeira.doc\"");
		header("Pragma: no-cache");
	
		$this->load->model('dados_rel_contrapartida_model');
		$this->load->model('proponente_siconv_model');
	
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['dados_rel_capacidade_model'] = $this->dados_rel_contrapartida_model;
		$data['dados_rel'] = $this->dados_rel_contrapartida_model->get_by_id($this->input->get('id', TRUE));
	
		$tabela = $this->load->view('declaracao/rel_contrapartida_financeira', $data, TRUE);
	
		echo $tabela;
	
		exit();
	}
	
	public function deleta_declaracao_contrapartida(){
		$this->load->model('system_logs');
	
		$this->db->where('id_rel', $this->input->get('id', TRUE));
		$dados = $this->db->get('dados_rel_contrapartida')->row(0);
	
		if(isset($dados->brasao_prefeitura) && file_exists('./upload_assinatura/'.$dados->brasao_prefeitura))
			unlink('./upload_assinatura/'.$dados->brasao_prefeitura);
	
		if(isset($dados->arquivo_assinatura) && file_exists('./upload_assinatura/'.$dados->arquivo_assinatura))
			unlink('./upload_assinatura/'.$dados->arquivo_assinatura);
	
		$this->db->where('id_rel', $this->input->get('id', TRUE));
		$this->db->delete('dados_rel_contrapartida');
	
		$this->system_logs->add_log(EXCLUI_ARQ_CONTRAPARTIDA." - Descrição: ".$dados->descricao_rel);
	
		$this->alert("Arquivo deletado com sucesso.");
		$this->encaminha(base_url('index.php/declaracao/lista_dec_contrapartida'));
	}
}
