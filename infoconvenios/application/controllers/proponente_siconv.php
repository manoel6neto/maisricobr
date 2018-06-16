<?php
include 'application/controllers/BaseController.php';

class proponente_siconv extends BaseController{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function get_lista_cidades(){
		$this->load->model('proponente_siconv_model');
		
		$municipio = $this->input->post('municipio', TRUE);
		
		$listaCidades = $this->proponente_siconv_model->get_municipio($this->input->post('uf', TRUE));
		
		$option = "<option value=''>Escolha</option>";
		foreach ($listaCidades as $cidade){
			if($cidade->codigo_municipio == $municipio)
				$selected = "selected";
			else
				$selected = "";
			$option .= "<option ".$selected." value='".$cidade->codigo_municipio."'>".$cidade->municipio."</option>";
		}
		
		echo $option;
	}
	
	public function get_lista_proponentes(){
		$this->load->model('proponente_siconv_model');
		
		$listaCidades = $this->proponente_siconv_model->get_proponentes($this->input->post('esfera', TRUE), $this->input->post('municipio', TRUE), $this->input->post('uf', TRUE), $this->input->post('tipo', TRUE), $this->input->post('id', TRUE), ($this->session->userdata('nivel') == 2 && $this->session->userdata('usuario_sistema') != "P"));
		
		$option = array();
		foreach ($listaCidades as $cidade){
			$option[] = array("label"=>$cidade->cnpj." - ".$cidade->nome, "value"=>$cidade->cnpj);
		}
		
		echo json_encode($option);
	}
	
	public function relacao_entidades(){
		$this->session->set_userdata('pagAtual', 'relacao_entidades');
		
		$this->load->model('proponente_siconv_model');
		
		$data['lista_proponentes'] = null;
		$data['filtro'] = null;
		
		if($this->input->post('nome_entidade', TRUE) != false){
			$data['filtro'] = $this->input->post('nome_entidade', TRUE);
			$data['lista_proponentes'] = $this->proponente_siconv_model->get_entidades($this->input->post('nome_entidade', TRUE));
		}
		
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['title'] = 'Info Convênios - Relação de Entidades';
		$data['main'] = "proponente_siconv/index";
			
		$this->load->view('in/template', $data);
	}
	
	public function gerar_pdf_rel(){
		if($this->input->post('id_proponente', TRUE) != false){
			$this->load->model('proponente_siconv_model');
			
			$lista_proponentes = $this->proponente_siconv_model->get_entidades_by_id($this->input->post('id_proponente', TRUE));
			
			$this->load->library ('mPDF');
			
			ob_start (); // inicia o buffer
			$tabela = utf8_encode ( $tabela );
			
			$mpdf = new mPDF('', 'A4-L');
			$mpdf->allow_charset_conversion = true;
			$mpdf->charset_in = 'UTF-8';
			#Verificar para aumentar o tamanho da entidade
			$header = array(
				'L'=>array(
					'content'=>'Info Convênios',
					'font-size'=>8
				),
				'C'=>array(
					'content'=>strtoupper($this->session->userdata('entidade')),
					'font-size'=>12
				),
				'R'=>array(
					'content'=>'Info Convênios',
					'font-size'=>8
				),
				'line' => 1
			);
			
			$mpdf->SetHeader($header, 'O');
			$mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');
			$tabela = "<div style='color:red; text-align:center;'>Relação de Entidades Cadastradas no SICONV</div><br>";
			$tabela .= "<table class='table' style=\"font-size: 13px; border-collapse: collapse;\">
					<tr><th colspan='9' style='color: red; text-align:center; font-size: 16px; border: 1px solid;'><span>Total de Registros: " . count($lista_proponentes)."</span></th></tr>
			                    	
			                    	<tr style='color: #428bca; font-size: 16px;'>
			                    		<th style='border: 1px solid;'>Nome Entidade</th>
			                    		<th style='border: 1px solid;'>CNPJ</th>
			                    		<th style='border: 1px solid;'>Natureza Jurídica</th>
										<th style='border: 1px solid;'>Esfera Administrativa</th>
			                    		<th style='border: 1px solid;'>Município</th>
			                    		<th style='border: 1px solid;'>UF</th>
			                    		<th style='border: 1px solid;'>Responsável</th>
										<th style='border: 1px solid;'>Situação</th>
										<th style='border: 1px solid;'>Apta</th>
			                    	</tr>";
			                    	
				foreach ($lista_proponentes as $p):
				$situacao_privada = $this->proponente_siconv_model->busca_situacao_apta($p->cnpj);
                    		$tabela .= "<tr>
	                    		<td style='border: 1px solid;'>". $p->nome ."</td>
	                    		<td style='border: 1px solid;'>". $p->cnpj ."</td>
	                    		<td style='border: 1px solid;'>". $p->natureza_juridica ."</td>
	                    		<td style='border: 1px solid;'>". $p->esfera_administrativa ."</td>
	                    		<td style='border: 1px solid;'>". $p->municipio ."</td>
	                    		<td style='border: 1px solid;'>". $p->municipio_uf_nome ."</td>
	                    		<td style='border: 1px solid;'>". $p->nome_responsavel ."</td>
	                    		<td style='border: 1px solid;'>". $p->situacao ."</td>
	                    		<td style='width: 180px; text-align: center; border: 1px solid;'>". (!empty($situacao_privada) ? str_replace("_", " ", $situacao_privada->situacao)."<br/>".implode("/", array_reverse(explode("-", $situacao_privada->data_inicio)))." - ".implode("/", array_reverse(explode("-", $situacao_privada->data_vencimento))) : "") . "</td>
	                    	</tr>";
			   endforeach;
			   
			   $tabela .= "</table>";
			   
			   $mpdf->WriteHTML ( $tabela );
			   
			   $mpdf->Output();
			   
			   die();
		}
	}
}