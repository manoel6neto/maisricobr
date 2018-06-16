<?php
include 'application/controllers/BaseController.php';

class proposta_comercial extends BaseController{
	
	public function index(){
		$this->session->set_userdata('pagAtual', 'area_vendedor');
		
		$this->load->model('proposta_comercial_model');
		
		$data['proposta_cadastradas'] = $this->proposta_comercial_model->get_all_propostas();
		
		$data['title'] = 'Info Convênios - Proposta Comercial';
		$data['main'] = "proposta_comercial/index";
			
		$this->load->view('in/template', $data);
	}
	
	public function gera_proposta(){
		$this->load->model('proposta_comercial_model');
		
		if($this->input->post(null, TRUE)){
			$this->form_validation->set_rules('tipo_proposta', 'Tipo Proposta', 'required');
			$this->form_validation->set_rules('periodo', 'Período', 'required');
			$this->form_validation->set_rules('parcelas', 'Parcelas', 'required');
			$this->form_validation->set_rules('descricao_proposta_comercial', 'Descrição Proposta', 'required|max_length[150]');
			$this->form_validation->set_rules('nome_entidade', 'Tipo da Entidade', 'required|max_length[255]');
			
			if($this->input->post('tipo_proposta', TRUE) == "Governos Municipais"){
				$this->form_validation->set_rules('num_cnpj', 'Nº CNPJ Extra Eco Mista', 'integer');
				$this->form_validation->set_rules('num_cnpj_autarquias', 'Nº CNPJ Extra Autarquias', 'integer');
				$this->form_validation->set_rules('num_cnpj_sem_fim', 'Nº CNPJ Extra P.S/FINS LUCRAT.', 'integer');
			}else if($this->input->post('tipo_proposta', TRUE) == "Empresas Interesse Público")
				$this->form_validation->set_rules('entidade', 'Entidade', 'required');
			else if($this->input->post('tipo_proposta', TRUE) == "Consórcios Públicos")
				$this->form_validation->set_rules('num_associado', 'Nº Entes Consorciados', 'required|integer|greater_than[1]');
			else if($this->input->post('tipo_proposta', TRUE) == "Parlamentar")
				$this->form_validation->set_rules('num_parlamentar', 'Nº CNPJs', 'required|integer|greater_than[1]');
			else if($this->input->post('tipo_proposta', TRUE) == "Governos Municipais")
				$this->form_validation->set_rules('valo_proposta_comercial', 'Valor da Proposta', 'required');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_message('required', 'O campo %s é obrigatório.');
			$this->form_validation->set_message('integer', 'O campo %s deve conter somente números.');
			$this->form_validation->set_message('max_length', 'O campo %s deve conter no máximo %s caracteres.');
			$this->form_validation->set_message('greater_than', 'O campo %s deve conter valor maior que %s.');
			
			$options = $this->input->post(null, TRUE);
			
			if($this->form_validation->run() === TRUE){
				$this->proposta_comercial_model->calcula_proposta($options);
			
				$this->alert('Proposta Cadastrada com sucesso!');
				
				$this->encaminha(base_url('index.php/proposta_comercial'));
			}
		}
		
		$data['tipo_proposta'] = $this->proposta_comercial_model->get_tipo_proposta_array();
		$data['entidade_interesse'] = $this->proposta_comercial_model->get_tipo_empresa_interesse_array();
		
		$data['title'] = 'Info Convênios - Proposta Comercial';
		$data['main'] = "proposta_comercial/gera_proposta";
			
		$this->load->view('in/template', $data);
	}
	
	public function gera_pdf(){
		$this->load->model('proposta_comercial_model');
		$this->load->model('trabalho_model');
		$this->load->model('cnpj_siconv');
		$this->load->model('contato_municipio_model', 'cm');
		$this->load->model('proponente_siconv_model');
		$this->load->model('programa_model');
		$this->load->model('usuariomodel');
		
		$proposta = $this->proposta_comercial_model->get_proposta_by_id($this->input->get('id', TRUE));
		
		$dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($proposta->cnpj_proposta_comercial);
		
		$cod_cidade_proposta = $dados_cidade_proposta->Codigo;
		$cidade_proposta = $dados_cidade_proposta->Nome;
		
		$estado = $dados_cidade_proposta->Sigla;
		
		$proponente = $this->proponente_siconv_model->get_municipio_by_cnpj($proposta->cnpj_proposta_comercial);

                $array_esferas = array();
                array_push($array_esferas, $proponente->esfera_administrativa);
                
		$dados_contato_municipio = $this->cm->get_ultimo_contato($proponente->codigo_municipio, $estado, $this->programa_model->formatCPFCNPJ($proposta->cnpj_proposta_comercial), $array_esferas);
		
		$data['estado'] = $estado;
		$data['municipio'] = $cidade_proposta;
		$data['proposta'] = $proposta;
		$data['proposta_comercial_model'] = $this->proposta_comercial_model;
		$data['usuario'] = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
		
		$data['nome_contato'] = $dados_contato_municipio['nome_contato'];
		$data['email_contato'] = $dados_contato_municipio['email_contato'];
		
		$this->load->library ( 'mPDF' );
		
		
		ob_start (); // inicia o buffer
		
		$mpdf = new mPDF ();
		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'UTF-8';

		$header = '<img src="'.base_url('layout/assets/images/logo_physis_rel.png').'" width="150">
					<img src="'.base_url('layout/assets/images/logo_bem_comum_rel.png').'" width="120" height="40" style="float: right;">';
		
		//$mpdf->SetHTMLHeader("<div></div>");
		$mpdf->SetHTMLFooter("<div></div>");
		
		$mpdf->WriteHTML ( $this->load->view('proposta_comercial/gera_pdf_capa_proposta', $data, TRUE) );
		
		$mpdf->AddPage();
		
		$mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px;'>Av. Almirante de Tamandaré, nº 502, 2º andar, Centro – Itabuna – Ba, CEP 45.600-130  -  Fone: 73 3613-0622</div><brr><div align='center' style='font-size: 10px;'>Site: www.physisbrasil.com.br - Email:adm@physisbrasil.com.br</div>");
		
		$mpdf->WriteHTML ( $this->load->view('proposta_comercial/gera_pdf_descritivo_proposta', $data, TRUE) );
		
		$mpdf->AddPage();
		
		$mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px;'>Av. Almirante de Tamandaré, nº 502, 2º andar, Centro – Itabuna – Ba, CEP 45.600-130  -  Fone: 73 3613-0622</div><brr><div align='center' style='font-size: 10px;'>Site: www.physisbrasil.com.br - Email:adm@physisbrasil.com.br</div>");
		
		$mpdf->WriteHTML ( $this->load->view('proposta_comercial/gera_pdf_nova_proposta', $data, TRUE) );
		
		$mpdf->Output ();
		
		exit();
	}
	
	public function send_proposta_usuario(){
		$this->load->model('proposta_comercial_model');
		$this->load->model('trabalho_model');
		$this->load->model('cnpj_siconv');
		$this->load->model('contato_municipio_model', 'cm');
		$this->load->model('proponente_siconv_model');
		$this->load->model('programa_model');
		$this->load->model('usuariomodel');
		
		$dadosRepresentante = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
		
		$this->db->flush_cache();
		
		$proposta = $this->proposta_comercial_model->get_proposta_by_id($this->input->post('id', TRUE));
		
		$dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($proposta->cnpj_proposta_comercial);
		
		$cod_cidade_proposta = $dados_cidade_proposta->Codigo;
		$cidade_proposta = $dados_cidade_proposta->Nome;
		
		$estado = $dados_cidade_proposta->Sigla;
		
		$proponente = $this->proponente_siconv_model->get_municipio_by_cnpj($proposta->cnpj_proposta_comercial);
		
		$dados_contato_municipio = $this->cm->get_ultimo_contato($proponente->codigo_municipio, $estado, $this->programa_model->formatCPFCNPJ($proposta->cnpj_proposta_comercial), array($proponente->esfera_administrativa));
		
		$data['estado'] = $estado;
		$data['municipio'] = $cidade_proposta;
		$data['proposta'] = $proposta;
		$data['proposta_comercial_model'] = $this->proposta_comercial_model;
		$data['usuario'] = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
		
		$data['nome_contato'] = $dados_contato_municipio['nome_contato'];
		$data['email_contato'] = $dados_contato_municipio['email_contato'];
		
		define("_MPDF_TEMP_PATH", BASEPATH.'../arquivos_proposta/tmp');
		
		$this->load->library ( 'mPDF' );
		
		ob_start (); // inicia o buffer
		
		$mpdf = new mPDF ();
		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'UTF-8';
		
		$header = '<img src="'.base_url('layout/assets/images/logo_physis_rel.png').'" width="150">
					<img src="'.base_url('layout/assets/images/logo_bem_comum_rel.png').'" width="120" height="40" style="float: right;">';
		
		//$mpdf->SetHTMLHeader("<div></div>");
		$mpdf->SetHTMLFooter("<div></div>");
		
		$mpdf->WriteHTML ( $this->load->view('proposta_comercial/gera_pdf_capa_proposta', $data, TRUE) );
		
		$mpdf->AddPage();
		
		$mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px;'>Av. Almirante de Tamandaré, nº 502, 2º andar, Centro – Itabuna – Ba, CEP 45.600-130  -  Fone: 73 3613-0622</div><brr><div align='center' style='font-size: 10px;'>Site: www.physisbrasil.com.br - Email:adm@physisbrasil.com.br</div>");
		
		$mpdf->WriteHTML ( $this->load->view('proposta_comercial/gera_pdf_descritivo_proposta', $data, TRUE) );
		
		$mpdf->AddPage();
		
		$mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px;'>Av. Almirante de Tamandaré, nº 502, 2º andar, Centro – Itabuna – Ba, CEP 45.600-130  -  Fone: 73 3613-0622</div><brr><div align='center' style='font-size: 10px;'>Site: www.physisbrasil.com.br - Email:adm@physisbrasil.com.br</div>");
		
		$mpdf->WriteHTML ( $this->load->view('proposta_comercial/gera_pdf_nova_proposta', $data, TRUE) );
		
		$nomeArquivo = $this->session->userdata('id_usuario')."_".rand(1111, 99999)."_proposta_comercial_".$cidade_proposta.".pdf";

		$mpdf->Output(BASEPATH.'../arquivos_proposta/'.$nomeArquivo, 'F');

		$mailto = $dados_contato_municipio['email_contato'];
		$from_name = 'Info Convênios';
		$from_mail = 'propostacomercial@physisbrasil.com.br';
		$subject = $this->input->post('assunto', TRUE);
		$message = $this->input->post('mensagem', TRUE);
		$filename = $nomeArquivo;
		
		$message .= "<br><br>".$dadosRepresentante->nome."<br>".$dadosRepresentante->entidade;
		
		$this->load->library('email');
		
		$this->email->initialize($this->usuariomodel->inicializa_config_email($from_mail));
		
		$this->email->set_mailtype('html');
		$this->email->reply_to($dadosRepresentante->email, $dadosRepresentante->nome);
		$this->email->from($from_mail, $dadosRepresentante->nome);
		$this->email->to($mailto);
		
		if($this->input->post('email', TRUE))
			$this->email->cc(trim($this->input->post('email', TRUE), ","));
		
		$this->email->bcc('propostacomercial@physisbrasil.com.br');
		
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->attach(BASEPATH.'../arquivos_proposta/'.$nomeArquivo);
		echo $this->email->send();
		
		exit();
	}
	
	public function get_dados_visita(){
		$this->load->model('proposta_comercial_model');
		$this->load->model('cnpj_siconv');
		$this->load->model('contato_municipio_model', 'cm');
		$this->load->model('proponente_siconv_model');
		$this->load->model('programa_model');
		
		$proposta = $this->proposta_comercial_model->get_proposta_by_id($this->input->get('id', TRUE));
		
		$dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($proposta->cnpj_proposta_comercial);
		
		$estado = $dados_cidade_proposta->Sigla;
		
		$proponente = $this->proponente_siconv_model->get_municipio_by_cnpj($proposta->cnpj_proposta_comercial);
		
		$dados_contato_municipio = $this->cm->get_ultimo_contato($proponente->codigo_municipio, $estado, $this->programa_model->formatCPFCNPJ($proposta->cnpj_proposta_comercial), array($proponente->esfera_administrativa));
		
		$data['nome_contato'] = $dados_contato_municipio['nome_contato'];
		$data['email_contato'] = $dados_contato_municipio['email_contato'];
		
		echo json_encode($data);
	}
	
	public function deleta_proposta(){
		$this->db->where('id_proposta_comercial', $this->input->get('id', TRUE));
		$this->db->delete('proposta_comercial');
		
		$this->alert('Proposta deletada com sucesso!');
		$this->encaminha(base_url('index.php/proposta_comercial'));
	}
	
	function alert($text) {
		echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
	}
	
	function encaminha($url) {
		echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
		exit();
	}
}