<?php

include 'application/controllers/BaseController.php';

class Curl extends BaseController {
	
	function index(){
		$this->load->model('curl_model');
		$params = array(
				"numeroProposta" => "064360/2013",
				
				"camposParaExibirAsArray" => "1"
				
				
		);
		
		echo $this->curl_model->httppost("https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=765735",$params);
		
	}
}