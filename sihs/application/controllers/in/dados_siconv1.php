<?php

include 'application/controllers/BaseController.php';

class Dados_siconv extends BaseController {
	function __construct() {
		parent::__construct ();
		$this->login = '43346880559';
		$this->senha = 'Laisa_M2012';
		// $this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE");
		$this->cookie_file_path = tempnam ( "/tmp", "CURLCOOKIE" . rand () );
	}
	function cidades_ajax() {
		$cod_estados = mysql_real_escape_string ( $_REQUEST ['cod_estados'] );
		
		$this->load->model ( 'cidades_model' );
		$cidades = $this->cidades_model->obter_cidades ( $cod_estados );
		foreach ( $cidades as $cidade ) {
			$cidades [] = array (
					'cod_cidades' => $cidade ['cod_cidades'],
					'nome' => $cidade ['nome'] 
			);
		}
		
		echo (json_encode ( $cidades ));
	}
	function carrega_programas() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		
		$this->load->model ( 'programa_model' );
		$id = $this->input->get_post ( 'id', TRUE );
		$count_pag = - 1;
		if ($this->input->get_post ( 'count_pag', TRUE ) !== false)
			$count_pag = intval ( $this->input->get_post ( 'count_pag', TRUE ) );
		
		if ($id == '10')
			$this->programa_model->apagar_dados ();
		
		$anterior = array ();
		$remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
		$this->obter_paginaLogin ();
		// $this->obter_pagina($remotePageUrl);
		$documento = $this->obter_pagina ( $remotePageUrl );
		// echo $remotePageUrl."<br />";
		preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches );
		$orgaos = $this->getTextBetweenTags ( $documento, "<\/span> ", "<\/a><\/li>" );
		if ($count_pag >= 0) { // MINISTERIO DAS CIDADES muito longo
			$documento1 = $this->obter_pagina ( "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480" );
			$prox = null;
			if ($count_pag == 0) {
				$anterior [0] = $remotePageUrl;
				$anterior [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480";
				$this->imprimeDetalhePrograma_bd ( "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480", $orgaos [120], $anterior );
				$this->obter_pagina ( $remotePageUrl );
				$documento1 = $this->obter_pagina ( "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480" );
				preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1 );
				foreach ( $matches1 [1] as $pag1 ) {
					if (strstr ( $pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=' ) !== false) {
						$this->imprimeDetalhePrograma_bd ( str_replace ( "&amp;", "&", $pag1 ), $orgaos [120], $anterior );
					}
				}
			}
			$anterior1 = array ();
			$anterior2 = array ();
			for($i = ($count_pag * 10) + 1; $i <= ($count_pag * 10) + 10; $i ++) {
				if ($i == 1)
					continue; // primeira página ja foi percorrida
				$prox [0] = str_replace ( "&amp;", "&", $prox [0] );
				$anterior1 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480";
				$anterior2 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480";
				$this->imprimeDetalhePrograma_bd ( "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480&d-16544-t=listaProgramas&d-16544-g=" . $i, $orgaos [120], $anterior1 );
				$this->obter_pagina ( "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480" );
				$documento1 = $this->obter_pagina ( "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480&d-16544-t=listaProgramas&d-16544-g=" . $i );
				$anterior2 [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480&d-16544-t=listaProgramas&d-16544-g=" . $i;
				preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1 );
				foreach ( $matches1 [1] as $pag1 ) {
					if (strstr ( $pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=' ) !== false) {
						$this->imprimeDetalhePrograma_bd ( str_replace ( "&amp;", "&", $pag1 ), $orgaos [120], $anterior2 );
					}
				}
			}
			die ();
		}
		foreach ( $matches [1] as $key => $pag ) {
			if (($key > $id - 10 && $key <= $id && $key != 89 && $key != 121 && $id != '89') || ($id == '89' && $key == 89)) {
				
				$pag = str_replace ( "&amp;", "&", $pag );
				$anterior [0] = $remotePageUrl;
				$anterior [1] = "https://www.convenios.gov.br" . $pag;
				$this->imprimeDetalhePrograma_bd ( "https://www.convenios.gov.br" . $pag, $orgaos [$key - 1], $anterior );
				$this->obter_pagina ( $remotePageUrl );
				// echo $remotePageUrl."<br />";
				$documento1 = $this->obter_pagina ( "https://www.convenios.gov.br" . $pag );
				// echo "https://www.convenios.gov.br".$pag."<br />";
				preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1 );
				foreach ( $matches1 [1] as $pag1 ) {
					if (strstr ( $pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=' ) !== false) {
						$this->imprimeDetalhePrograma_bd ( str_replace ( "&amp;", "&", $pag1 ), $orgaos [$key - 1], $anterior );
					}
				}
				$url_sem_espaco = $this->removeSpaceSurplus ( $documento1 );
				$prox = $this->getTextBetweenTags ( $url_sem_espaco, " \[<a href=\"", "\">Pr" );
				$anterior1 = array ();
				$anterior2 = array ();
				while ( count ( $prox ) > 0 ) {
					// echo "https://www.convenios.gov.br".$prox[0]."<br />";
					$prox [0] = str_replace ( "&amp;", "&", $prox [0] );
					$anterior1 [0] = "https://www.convenios.gov.br" . $pag;
					$anterior2 [0] = "https://www.convenios.gov.br" . $pag;
					$this->imprimeDetalhePrograma_bd ( "https://www.convenios.gov.br" . $prox [0], $orgaos [$key - 1], $anterior1 );
					$this->obter_pagina ( "https://www.convenios.gov.br" . $pag );
					// echo "https://www.convenios.gov.br".$pag."<br />";
					$documento1 = $this->obter_pagina ( "https://www.convenios.gov.br" . $prox [0] );
					// echo "https://www.convenios.gov.br".$prox[0]."<br />";
					$anterior2 [1] = "https://www.convenios.gov.br" . $prox [0];
					preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1 );
					foreach ( $matches1 [1] as $pag1 ) {
						if (strstr ( $pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=' ) !== false) {
							$this->imprimeDetalhePrograma_bd ( str_replace ( "&amp;", "&", $pag1 ), $orgaos [$key - 1], $anterior2 );
						}
					}
					$this->obter_pagina ( "https://www.convenios.gov.br" . $pag );
					// echo "https://www.convenios.gov.br".$pag."<br />";
					$documento2 = $this->obter_pagina ( "https://www.convenios.gov.br" . $prox [0] );
					// echo "https://www.convenios.gov.br".$prox[0]."<br />";
					$prox = $this->getTextBetweenTags ( $documento2, " \[<a href=\"", "\">Pr" );
				}
			}
		}
		
		die ();
	}
	function programas_tabela() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		$data ['main'] = 'in/programas_tabela';
		$this->load->model ( 'programa_model' );
		$programas = $this->programa_model->get_all ();
		$listaPrograma = array ();
		
		foreach ( $programas as $programa ) {
			if (isset ( $listaPrograma [$programa->orgao] [$programa->orgao_vinculado] [$programa->ano] ) === false)
				$listaPrograma [$programa->orgao] [$programa->orgao_vinculado] [$programa->ano] = 1;
			else
				$listaPrograma [$programa->orgao] [$programa->orgao_vinculado] [$programa->ano] ++;
		}
		$data ['login'] = '';
		$data ['listaPrograma'] = $listaPrograma;
		$this->load->view ( 'in/template', $data );
	}
	function programas() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		$data ['main'] = 'in/programas';
		$this->load->model ( 'cidades_model' );
		
		$data ['cidades'] = $this->cidades_model->obter_estados ();
		$data ['login'] = '';
		$this->load->view ( 'in/template', $data );
	}
	function propostas() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		$data ['main'] = 'in/propostas';
		$this->load->model ( 'cidades_model' );
		
		$data ['cidades'] = $this->cidades_model->obter_estados ();
		$data ['login'] = '';
		$this->load->view ( 'in/template', $data );
	}
	function proposta_tabela() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		$data ['main'] = 'in/proposta_tabela';
		$listaPropostas = array ();
		$ano = $this->input->get_post ( 'ano', TRUE );
		$this->load->model ( 'cidades_model' );
		
		$cidade = $this->input->get_post ( 'cod_cidades', TRUE );
		$cnpj = $this->cidades_model->obter_cnpj ( $cidade );
		
		foreach ( $cnpj as $cidade ) {
			$cidade ['cnpj'] = preg_replace ( "/[^0-9\s]/", "", $cidade ['cnpj'] );
			$situacao = "PROPOSTA_EM_ANALISE";
			$jsonurl = "http://api.convenios.gov.br/siconv/v1/consulta/propostas.json?id_proponente=" . $cidade ['cnpj'];
			
			$listaPropostas = $this->imprimeProposta ( $jsonurl, $ano, $listaPropostas );
		}
		$data ['listaPropostas'] = $listaPropostas;
		$data ['login'] = '';
		$this->load->view ( 'in/template', $data );
	}
	function lista_programas() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		$data ['main'] = 'in/listar_programas';
		$this->load->model ( 'programa_model' );
		
		$dataInicio = implode ( "-", array_reverse ( explode ( "/", $this->input->get_post ( 'Dt_Inicio', TRUE ) ) ) );
		$dataFim = implode ( "-", array_reverse ( explode ( "/", $this->input->get_post ( 'Dt_Fim', TRUE ) ) ) );
		// if (strtotime(str_replace("/","-",$dataFim_Programa))>=strtotime($dataInicio) && strtotime(str_replace("/","-",$dataFim_Programa))<=strtotime($dataFim)){
		$this->load->model ( 'cidades_model' );
		$estado = $this->input->get_post ( 'cod_estados', TRUE );
		$estado = $this->obterEstadoNome ( $estado );
		$cidade = $this->input->get_post ( 'cod_cidades', TRUE );
		$cnpj = $this->cidades_model->obter_cnpj ( $cidade );
		
		$data ['listaCidade'] = $this->programa_model->obter_por_cidade ( $dataInicio, $dataFim, $cidade );
		$data ['listaEstado'] = $this->programa_model->obter_por_estado ( $dataInicio, $dataFim, $estado );
		$data ['login'] = '';
		
		$this->load->view ( 'in/template', $data );
	}
	function relatorio() {
		$data ['title'] = "SIHS - Gestão de Usuários e Propostas";
		$data ['main'] = 'in/relatorio';
		$campos = $this->input->post ( NULL, TRUE );
		$this->load->model ( 'programa_model' );
		$listaPrograma = array ();
		$i = 0;
		foreach ( $campos as $key => $programas ) {
			if ($key != 'operation' && $key != 'todos_municipio' && $key != 'todos_estado') {
				$programa = $this->programa_model->obter_por_codigo ( $programas );
				$listaPrograma [$i] ['superior'] = $programa->orgao;
				$listaPrograma [$i] ['provenente'] = $programa->orgao_vinculado;
				$listaPrograma [$i] ['dataInicio'] = implode ( "/", array_reverse ( explode ( "-", $programa->data_inicio ) ) );
				$listaPrograma [$i] ['dataFim'] = implode ( "/", array_reverse ( explode ( "-", $programa->data_fim ) ) );
				;
				$listaPrograma [$i] ['qualificacao'] = $programa->qualificacao;
				$listaPrograma [$i] ['nome'] = $programa->nome;
				$listaPrograma [$i] ['descricao'] = $programa->descricao;
				$listaPrograma [$i] ['obs'] = $programa->observacao;
				$listaPrograma [$i] ['atende'] = $programa->atende;
			}
			$i ++;
		}
		$data ['login'] = '';
		$data ['listaPrograma'] = $listaPrograma;
		$this->load->view ( 'in/template', $data );
	}
	function encaminha($url) {
		echo "<script type='text/javascript'>
	window.location='" . $url . "';
	</script>";
	}
	function alert($text) {
		echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
	}
	function voltaPagina() {
		echo "<script type='text/javascript'>history.back();</script>";
	}
	function getTextBetweenTags($string, $tag1, $tag2) {
		$pattern = "/$tag1([\w\W]*?)$tag2/";
		preg_match_all ( $pattern, $string, $matches );
		return $matches [1];
	}
	function imprimeDetalhePrograma_bd($pag, $orgao, $anterior) {
		$superior = '';
		$validacao = array ();
		
		foreach ( $anterior as $key => $aux ) {
			$this->obter_pagina ( $aux );
			// echo $aux." ant<br />";
		}
		// echo $pag.".1111<br />"; return;
		$url1 = $this->obter_pagina ( $pag );
		
		// echo $url1;
		$url1 = $this->removeSpaceSurplus ( $url1 );
		preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $url1, $matches1 );
		
		foreach ( $matches1 [1] as $key => $pag1 ) {
			if (strstr ( $pag1, 'ResultadoDaConsultaDeProgramasDeConvenioDetalhar' ) !== false) {
				$this->obter_pagina ( $pag );
				// echo $pag.".22222<br />";
				$url1_ = $this->obter_pagina ( "https://www.convenios.gov.br" . $pag1 );
				// echo "https://www.convenios.gov.br".$pag1."<br />";
				$url_sem_espaco = $this->removeSpaceSurplus ( $url1_ );
				
				$orgao_vinculado = '';
				$orgao = '';
				$codigo = $this->getTextBetweenTags ( $url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>" );
				// testando erros na página antes de processar
				if (isset ( $codigo [0] ) === false) {
					// echo $url1_;
					$this->obter_paginaLogin ();
					foreach ( $anterior as $key => $aux )
						$this->obter_pagina ( $aux );
					$this->obter_pagina ( $pag );
					$url1_ = $this->obter_pagina ( "https://www.convenios.gov.br" . $pag1 );
					// echo $url1_;
					$url_sem_espaco = $this->removeSpaceSurplus ( $url1_ );
					$codigo = $this->getTextBetweenTags ( $url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>" );
				}
				
				$sup = $this->getTextBetweenTags ( $url_sem_espaco, "Órgão<\/td> <td class=\"field\">", "<\/td>" );
				$exec = $this->getTextBetweenTags ( $url_sem_espaco, "Órgão Vinculado<\/td> <td class=\"field\">", "<\/td>" );
				$qualificacao = $this->getTextBetweenTags ( $url_sem_espaco, "Qualificação da proposta<\/td> <td class=\"field\">", "<\/td>" );
				$atende = $this->getTextBetweenTags ( $url_sem_espaco, "Programa Atende a<\/td> <td class=\"field\">", "<\/td>" );
				$nome = $this->getTextBetweenTags ( $url_sem_espaco, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>" );
				$descricao = $this->getTextBetweenTags ( $url_sem_espaco, "Descrição <\/td> <tr> <td class=fieldCaixa colspan=2>", "<\/td>" );
				$observacao = $this->getTextBetweenTags ( $url_sem_espaco, "Observação <\/tr> <tr> <td class=fieldCaixa colspan=2>", "<\/td>" );
				
				$data_inicio = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataInicioVigencia\" value=\"", "\" onmouseup" );
				if (count ( $data_inicio ) == 0)
					$data_inicio = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataInicioBeneficiarioEspecifico\" value=\"", "\" onmouseup" );
				else if (trim ( $data_inicio [0] ) == '')
					$data_inicio = array ();
				if (count ( $data_inicio ) == 0)
					$data_inicio = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataInicioEmendaParlamentar\" value=\"", "\" onmouseup" );
				else if (trim ( $data_inicio [0] ) == '')
					$data_inicio = array ();
				if (count ( $data_inicio ) == 0)
					$data_inicio = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup" );
				else if (trim ( $data_inicio [0] ) == '')
					$data_inicio = array ();
				
				$data_fim = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataFimdeVigencia\" value=\"", "\" onmouseup" );
				if (count ( $data_fim ) == 0)
					$data_fim = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataFimBeneficiarioEspecifico\" value=\"", "\" onmouseup" );
				else if (trim ( $data_fim [0] ) == '')
					$data_fim = array ();
				if (count ( $data_fim ) == 0)
					$data_fim = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataFimEmendaParlamentar\" value=\"", "\" onmouseup" );
				else if (trim ( $data_fim [0] ) == '')
					$data_fim = array ();
				if (count ( $data_fim ) == 0)
					$data_fim = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup" );
				else if (trim ( $data_fim [0] ) == '')
					$data_fim = array ();
				
				$ano_disponibilizacao = $this->getTextBetweenTags ( $url_sem_espaco, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup" );
				if (trim ( $ano_disponibilizacao [0] ) == '')
					$ano_disponibilizacao = $data_inicio;
				$estados = $this->getTextBetweenTags ( $url_sem_espaco, "Estados Habilitados<\/td> <td class=\"field\">", "<\/td>" );
				
				if (isset ( $sup [0] ) !== false) {
					$orgao = strtok ( $sup [0], "-" );
					$orgao = trim ( strtok ( "-" ) );
				}
				
				if (isset ( $exec [0] ) !== false) {
					$orgao_vinculado = strtok ( $exec [0], "-" );
					$orgao_vinculado = trim ( strtok ( "-" ) );
				} else {
					$orgao_vinculado = $orgao;
				}
				
				$data = array (
						'link' => "https://www.convenios.gov.br" . $pag1,
						'codigo' => trim ( $codigo [0] ),
						'nome' => trim ( $nome [0] ),
						'orgao' => trim ( $orgao ),
						'orgao_vinculado' => trim ( $orgao_vinculado ),
						'qualificacao' => trim ( $qualificacao [0] ),
						'atende' => trim ( $atende [0] ),
						'descricao' => trim ( $descricao [0] ),
						'observacao' => trim ( $observacao [0] ),
						'data_inicio' => implode ( "-", array_reverse ( explode ( "/", trim ( $data_inicio [0] ) ) ) ),
						'data_fim' => implode ( "-", array_reverse ( explode ( "/", trim ( $data_fim [0] ) ) ) ),
						'ano' => substr ( trim ( $ano_disponibilizacao [0] ), - 4 ),
						'estados' => trim ( $estados [0] ) 
				);
				
				$inserido = $this->programa_model->add_records ( $data );
				
				$cidades_habilitadas_beneficiario = $this->getTextBetweenTags ( $url_sem_espaco, "<div class=\"cnpjBeneficiario\">", "<\/div>" );
				$nome_cidades = $this->getTextBetweenTags ( $url_sem_espaco, "<\/td> <td> <div class=\"nome\">", "<\/div>" );
				$cidades_habilitadas_especifico = $this->getTextBetweenTags ( $url_sem_espaco, "<div class=\"cnpj\">", "<\/div>" );
				$laco_inicial = 0; // para pegar os valores dos dois laços, caso precise
				foreach ( $cidades_habilitadas_beneficiario as $chave => $cidade_habilitada ) {
					$data = array (
							'codigo_programa' => trim ( $codigo [0] ),
							'cnpj' => trim ( $cidade_habilitada ),
							'nome' => trim ( $nome_cidades [$chave] ) 
					);
					
					$inserido = $this->programa_model->insere_beneficiario ( $data );
					$laco_inicial ++;
				}
				
				foreach ( $cidades_habilitadas_especifico as $chave => $cidade_habilitada ) {
					$data = array (
							'codigo_programa' => trim ( $codigo [0] ),
							'cnpj' => trim ( $cidade_habilitada ),
							'nome' => trim ( $nome_cidades [$laco_inicial] ) 
					);
					
					$inserido = $this->programa_model->insere_beneficiario ( $data );
					$laco_inicial ++;
				}
				$paginas_beneficiario = $this->getTextBetweenTags ( $url_sem_espaco, "id=\"cnpjsBeneficiarioEspecifico\" class=\"table\"> <span class=\"pagelinks\">Página ", "<\/span>" );
				$paginas_especifico = $this->getTextBetweenTags ( $url_sem_espaco, "id=\"cnpjsEmendaParlamentar\" class=\"table\"> <span class=\"pagelinks\">Página ", "<\/span>" );
				// echo "https://www.convenios.gov.br".$pag1." - codigo: ".$codigo[0]."<br />";
				$complemento_url = 'cnpjsBeneficiarioEspecifico';
				$tok = 0;
				if (isset ( $paginas_beneficiario [0] ) || isset ( $paginas_especifico [0] )) {
					if (isset ( $paginas_beneficiario [0] )) {
						$tok = strtok ( $paginas_beneficiario [0], "(" );
						$tok = strtok ( $tok, "de" );
						$tok = ( int ) trim ( strtok ( "de" ) );
					}
					$maior = $tok;
					if (isset ( $paginas_especifico [0] )) {
						$tok = strtok ( $paginas_especifico [0], "(" );
						$tok = strtok ( $tok, "de" );
						$tok = ( int ) trim ( strtok ( "de" ) );
					}
					// para formar a string que percorrerá os cnpjs que contem duas categorias, a maior percorre tudo
					if ($maior < $tok) {
						$maior = $tok;
						$complemento_url = 'cnpjsEmendaParlamentar';
					}
					// pegando o id para as subpaginas
					$pos = strripos ( $pag1, "id=" );
					$id = '';
					if ($pos !== false) {
						$id = substr ( $pag1, $pos + 3 );
					}
					$flag1 = true;
					
					for($i = 2; $i <= $maior && $flag1 == true; $i ++) {
						
						$this->obter_pagina ( "https://www.convenios.gov.br" . $pag1 );
						$url1_aux = $this->obter_pagina ( "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=$complemento_url&d-16544-p=$i&d-16544-g=$i" );
						
						// echo "https://www.convenios.gov.br".$pag1."<br />";
						// echo "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=$complemento_url&d-16544-p=$i&d-16544-g=$i"."<br />";
						$url_sem_espaco = $this->removeSpaceSurplus ( $url1_aux );
						$cidades_habilitadas_beneficiario = $this->getTextBetweenTags ( $url_sem_espaco, "<div class=\"cnpjBeneficiario\">", "<\/div>" );
						$nome_cidades = $this->getTextBetweenTags ( $url_sem_espaco, "<\/td> <td> <div class=\"nome\">", "<\/div>" );
						$cidades_habilitadas_especifico = $this->getTextBetweenTags ( $url_sem_espaco, "<div class=\"cnpj\">", "<\/div>" );
						$laco_inicial = 0; // para pegar os valores dos dois laços, caso precise
						foreach ( $cidades_habilitadas_beneficiario as $chave => $cidade_habilitada ) {
							$data = array (
									'codigo_programa' => trim ( $codigo [0] ),
									'cnpj' => trim ( $cidade_habilitada ),
									'nome' => trim ( $nome_cidades [$chave] ) 
							);
							
							$inserido = $this->programa_model->insere_beneficiario ( $data );
							$laco_inicial ++;
						}
						
						foreach ( $cidades_habilitadas_especifico as $chave => $cidade_habilitada ) {
							$data = array (
									'codigo_programa' => trim ( $codigo [0] ),
									'cnpj' => trim ( $cidade_habilitada ),
									'nome' => trim ( $nome_cidades [$laco_inicial] ) 
							);
							
							$inserido = $this->programa_model->insere_beneficiario ( $data );
							$laco_inicial ++;
						}
					}
				}
			} else if (strstr ( $pag1, 'error-page' ) !== false) {
				echo "Siconv encaminhando para página com erro: " . $pag . "<br />";
			}
		}
		/*
		 * $anos = $this->getTextBetweenTags($url1, "<div class=\"anoPrograma\">", "<\/div>"); foreach($anos as $key => $ano){ if (isset($listaPrograma[$superior][$orgao][$ano]) === false) $listaPrograma[$superior][$orgao][$ano] = 1; else $listaPrograma[$superior][$orgao][$ano]++; } return $listaPrograma;
		 */
	}
	function imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior) {
		$superior = '';
		$flag = false; // verificar se pagina ja pegou o superior
		$validacao = array ();
		
		// $this->obter_paginaLogin();
		foreach ( $anterior as $key => $aux ) {
			$this->obter_pagina ( $aux );
			// echo $aux." ant<br />";
		}
		
		// $this->obter_pagina($remotePageUrl);
		$url1 = $this->obter_pagina ( $pag );
		// echo $anterior."<br>";
		// echo $pag." p1<br>";
		$url1 = $this->removeSpaceSurplus ( $url1 );
		// echo "1<br>";
		// echo $url1;
		preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $url1, $matches1 );
		// preg_match_all("/href\=\"(*)\"/i", $url1, $matches1);
		// echo "2<br>";
		foreach ( $matches1 [1] as $key => $pag1 ) {
			if ($flag == true) {
				// echo "flag";
				break;
			}
			// echo $pag1."..".$key." - 3<br>";
			// if ($pag1 == 'siconv/principal/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=') //erro intermitente de causa desconhecida
			// $listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma);
			if (strstr ( $pag1, 'ResultadoDaConsultaDeProgramasDeConvenioDetalhar' ) !== false) {
				// $this->obter_paginaLogin();
				$this->obter_pagina ( $pag );
				$url1_ = $this->obter_pagina ( "https://www.convenios.gov.br" . $pag1 );
				$url_sem_espaco = $this->removeSpaceSurplus ( $url1_ );
				// echo $pag1." 4<br>";
				$sup = $this->getTextBetweenTags ( $url_sem_espaco, "Órgão<\/td> <td class=\"field\">", "<\/td>" );
				// $superior = $sup[0];
				// echo"-1-";
				if (isset ( $sup [0] ) !== false) {
					$superior = strtok ( $sup [0], "-" );
					$superior = trim ( strtok ( "-" ) );
					$flag = true;
					break;
				} else {
					echo "Página com erro: " . $pag . "<br />";
					echo "Página com erro: https://www.convenios.gov.br" . $pag1 . "<br />";
					echo $url1_;
					/*
					 * $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do'; //$this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".); $this->obter_paginaLogin(); $this->obter_pagina($remotePageUrl); return $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior);
					 */
					// $listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior);
					// echo "Siconv deu um erro fatal!<br />Por favor atualize a página."; die();
				}
				// echo $superior." -5<br>";
			} 			// resolvendo problemas de somas erraas
			else if (strstr ( $pag1, 'error-page' ) !== false) {
				/*
				 * unlink("application/views/configuracoes/cookie.txt"); $cria = fopen("application/views/configuracoes/cookie.txt","w+"); fclose($cria); $login = '43346880559'; $senha = 'Laisa_M2012'; $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha"; $this->obter_pagina($url);
				 */
				echo "Siconv encaminhando para página com erro: " . $pag . "<br />";
				// $listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma);
			}
		}
		// echo "6<br>";
		$anos = $this->getTextBetweenTags ( $url1, "<div class=\"anoPrograma\">", "<\/div>" );
		foreach ( $anos as $key => $ano ) {
			if (isset ( $listaPrograma [$superior] [$orgao] [$ano] ) === false)
				$listaPrograma [$superior] [$orgao] [$ano] = 1;
			else
				$listaPrograma [$superior] [$orgao] [$ano] ++;
			// echo $superior." - ".$orgao." - ".$key ." - ".$ano." codnao<br>";
		}
		// echo "7<br>";
		return $listaPrograma;
	}
	function imprimeDetalhe($pag, $dataInicio, $dataFim, $estado, $cnpj, $anterior) {
		foreach ( $anterior as $key => $aux ) {
			$this->obter_pagina ( $aux );
			// echo $aux." ant<br />";
		}
		// echo $pag." ant<br />";
		
		$url1 = $this->obter_pagina ( $pag );
		// echo $url1;
		$url_sem_espaco = $this->removeSpaceSurplus ( $url1 );
		$dataFim_Programa1 = $this->getTextBetweenTags ( $url_sem_espaco, $this->removeSpaceSurplus ( "\"dataFimdeVigencia\" value=\"" ), "\"" );
		if (count ( $dataFim_Programa1 ) > 0 && trim ( $dataFim_Programa1 [0] ) != '') {
			$dataFim_Programa = $dataFim_Programa1 [0];
		} else {
			$dataFim_Programa2 = $this->getTextBetweenTags ( $url_sem_espaco, $this->removeSpaceSurplus ( "\"dataFimBeneficiarioEspecifico\" value=\"" ), "\"" );
			if (count ( $dataFim_Programa2 ) > 0 && trim ( $dataFim_Programa2 [0] ) != '') {
				$dataFim_Programa = $dataFim_Programa2 [0]; // echo ".1.";
			} else {
				$dataFim_Programa2 = $this->getTextBetweenTags ( $url_sem_espaco, $this->removeSpaceSurplus ( "\"dataFimEmendaParlamentar\" value=\"" ), "\"" );
				if (count ( $dataFim_Programa2 ) > 0 && trim ( $dataFim_Programa2 [0] ) != '') {
					$dataFim_Programa = $dataFim_Programa2 [0]; // echo ".2.";
				} else {
					$dataFim_Programa2 = $this->getTextBetweenTags ( $url_sem_espaco, $this->removeSpaceSurplus ( "\"dataDeDisponibilizacao\" value=\"" ), "\"" );
					if (count ( $dataFim_Programa2 ) > 0 && trim ( $dataFim_Programa2 [0] ) != '') {
						$dataFim_Programa = $dataFim_Programa2 [0]; // echo ".3.";
					} else {
						echo "Página com erro: " . $pag . "<br />";
						$remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
						// $this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".rand());
						$this->obter_paginaLogin ();
						$this->obter_pagina ( $remotePageUrl );
						$this->imprimeDetalhe ( $pag, $orgao, $listaPrograma, $anterior );
					}
				}
			}
		}
		// echo $dataFim_Programa.">=".$dataInicio." && ".$dataFim_Programa."<=".$dataFim."<br>";
		if (strtotime ( str_replace ( "/", "-", $dataFim_Programa ) ) >= strtotime ( str_replace ( "/", "-", $dataInicio ) ) && strtotime ( str_replace ( "/", "-", $dataFim_Programa ) ) <= strtotime ( str_replace ( "/", "-", $dataFim ) )) {
			// echo ".1.";
			$nome = $this->getTextBetweenTags ( $url_sem_espaco, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>" );
			$codigo = $this->getTextBetweenTags ( $url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>" );
			$this->codigoEstado [] = trim ( $codigo [0] );
			$this->pagEstado [] = $pag;
			$this->listaEstado [] = "<a href='" . $pag . "'>" . $codigo [0] . "</a> - " . $nome [0];
			// echo strtotime(str_replace("/","-",$dataFim_Programa)).">=".strtotime(str_replace("/","-",$dataInicio))." && ".strtotime(str_replace("/","-",$dataFim_Programa))."<=".strtotime(str_replace("/","-",$dataFim))."<br>";
			// echo $dataFim_Programa." -dt- ".$dataInicio.",".$dataFim."<br>";
			$estados_habilitados = $this->getTextBetweenTags ( $url_sem_espaco, "Estados Habilitados<\/td> <td class=\"field\">", "<\/td>" );
			// echo $estado." -Habilitado ".count($estados_habilitados).".".$estados_habilitados[0]."<br />";
			// echo $indiciePagina."https://www.convenios.gov.br".$pag1."<br>";
			/*
			 * if (strstr($estados_habilitados[0], $estado) !== false || strstr($estados_habilitados[0], 'Todos os Estados estão Aptos') !== false ){ //echo $indiciePagina."https://www.convenios.gov.br".$pag1." estado<br>"; $this->codigoEstado[] = $codigo[0]; $this->listaEstado[] = "<a href='https://www.convenios.gov.br".$pag."'>".$codigo[0]."</a> - ".$nome[0]; }
			 */
			// echo ".2.";
			$cidades_habilitadas = $this->getTextBetweenTags ( $url_sem_espaco, "class=\"cnpjBeneficiario\">", "<\/div>" );
			if (count ( $cidades_habilitadas ) == 0)
				$cidades_habilitadas = $this->getTextBetweenTags ( $url_sem_espaco, "class=\"cnpj\">", "<\/div>" );
			$flag = true;
			// echo count($cidades_habilitadas)."--cidade--<br>";
			foreach ( $cidades_habilitadas as $cidade_habilitada ) {
				foreach ( $cnpj as $cidade ) {
					if (strstr ( $cidade_habilitada, $cidade ['cnpj'] ) !== false) {
						// echo $indiciePagina."https://www.convenios.gov.br".$pag1." cidade<br>";
						$this->codigoCidade [] = trim ( $codigo [0] );
						$this->pagCidade [] = $pag;
						$this->listaCidade [] = "<a href='" . $pag . "'>" . $codigo [0] . "</a> - " . $nome [0];
						// echo $codigo[0]; die();
						$flag = false;
						break;
					}
				}
				if ($flag == false)
					break; // saindo do foreach do meio
			}
			// echo ".3.";
			$paginas = $this->getTextBetweenTags ( $url1, "span class=\"pagelinks\">Página ", "<\/span>" );
			if (isset ( $paginas [0] )) {
				$tok = strtok ( $paginas [0], "(" );
				$tok = strtok ( $tok, "de" );
				$tok = ( int ) trim ( strtok ( "de" ) );
				// pegando o id para as subpaginas
				$pos = strripos ( $pag, "id" );
				$id = '';
				if ($pos !== false) {
					$id = substr ( $dataInicio, $pos + 2 );
				}
				$flag1 = true;
				// echo $id." - id<br>";
				for($i = 2; $i <= $tok && $flag1 == true; $i ++) {
					// $this->obter_paginaLogin();
					$this->obter_pagina ( "https://www.convenios.gov.br" . $pag );
					// echo "https://www.convenios.gov.br".$pag."<br />";
					// echo "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=$i&d-16544-g=$i"."<br />";
					$url1_aux = $this->obter_pagina ( "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=$i&d-16544-g=$i" );
					$cidades_habilitadas = $this->getTextBetweenTags ( $url1_aux, "class=\"cnpjBeneficiario\">", "<\/div>" );
					if (count ( $cidades_habilitadas ) == 0)
						$cidades_habilitadas = $this->getTextBetweenTags ( $url1_aux, "class=\"cnpj\">", "<\/div>" );
					foreach ( $cidades_habilitadas as $cidade_habilitada ) {
						foreach ( $cnpj as $cidade ) {
							if (strstr ( $cidade_habilitada, $cidade ['cnpj'] ) !== false) {
								// echo $indiciePagina."https://www.convenios.gov.br".$pag1." cidade1<br>";
								$this->codigoCidade [] = trim ( $codigo [0] );
								$this->pagCidade [] = $pag;
								$this->listaCidade [] = "<a href='" . $pag . "'>" . $codigo [0] . "</a> - " . $nome [0];
								// echo $codigo[0]; die();
								$flag1 = false;
								break;
							}
						}
						if ($flag1 == false)
							break; // saindo do foreach do meio
					}
				}
			}
			// echo ".4.";
		}
		// echo ".5.";
	}
	function obter_paginaLogin() {
		$login = $this->login;
		$senha = $this->senha;
		$url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha";
		// $cookie_file_path = "application/views/configuracoes/cookie.txt";
		$cookie_file_path = $this->cookie_file_path;
		$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
		$ch = curl_init ();
		// extra headers
		$headers [] = "Accept: */*";
		$headers [] = "Connection: Keep-Alive";
		
		// basic curl options for all requests
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_USERAGENT, $agent );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_COOKIEFILE, $cookie_file_path );
		curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file_path );
		
		// set first URL
		curl_setopt ( $ch, CURLOPT_URL, $url );
		
		// execute session to get cookies and required form inputs
		$content = curl_exec ( $ch );
		
		curl_close ( $ch );
		if (strstr ( $content, 'ELIUMAR CARLOS DE SOUSA SILVA' ) === false) {
			$this->senha = 'Laisa_M2012';
			$this->cookie_file_path = tempnam ( "/tmp", "CURLCOOKIE1" . rand () );
			return $this->obter_paginaLogin ();
		}
		
		return $content;
	}
	function obter_pagina($url) {
		// $cookie_file_path = "application/views/configuracoes/cookie.txt";
		$cookie_file_path = $this->cookie_file_path;
		$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
		$ch = curl_init ();
		// extra headers
		$headers [] = "Accept: */*";
		$headers [] = "Connection: Keep-Alive";
		
		// basic curl options for all requests
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_USERAGENT, $agent );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_COOKIEFILE, $cookie_file_path );
		curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookie_file_path );
		
		// set first URL
		curl_setopt ( $ch, CURLOPT_URL, $url );
		
		// execute session to get cookies and required form inputs
		$content = curl_exec ( $ch );
		curl_close ( $ch );
		
		return $content;
	}
	function removeSpaceSurplus($str) {
		return preg_replace ( "/\s+/", ' ', trim ( $str ) );
	}
	function obterNomeOrgao($id, $numero) {
		$numero = trim ( $numero );
		$remotePageUrl = 'https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do';
		$remotePageUrl1 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do?tipo_consulta=CONSULTA_COMPLETA&numeroProposta=' . $numero;
		$nome = '';
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id.json";
		
		$json = file_get_contents ( $jsonurl );
		if (trim ( $json ) == '' || ! isset ( $json )) {
			$jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id.html";
			$json = file_get_contents ( $jsonurl );
			$nome = $this->getTextBetweenTags ( $json, "<dt>Nome<\/dt>
          <dd>", "<\/dd>" );
			$nome = $nome [0];
		} else {
			$json_output = json_decode ( $json );
			$nome = $json_output->orgaos [0]->nome;
		}
		// caso não encontre em local nenhum, varre direto na página do siconv
		if (trim ( $nome ) == '' || trim ( $nome ) == 'NÃO SE APLICA') {
			$this->obter_paginaLogin ();
			echo $remotePageUrl1;
			$this->obter_pagina ( $remotePageUrl );
			$documento = $this->obter_pagina ( $remotePageUrl1 );
			preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches1 );
			foreach ( $matches1 [1] as $pag1 ) {
				if (strstr ( $pag1, 'ResultadoDaConsultaDePropostaDetalharProposta' ) !== false) {
					$pag1 = str_replace ( "&amp;", "&", $pag1 );
					// $listaPrograma = $this->imprimeDetalhePrograma(str_replace("&amp;","&",$pag1), $orgaos[$key-1], $listaPrograma, $anterior);
					$url1_ = $this->obter_pagina ( "https://www.convenios.gov.br" . $pag1 );
					$url_sem_espaco = $this->removeSpaceSurplus ( $url1_ );
					$sup = $this->getTextBetweenTags ( $url_sem_espaco, "<td class=\"label\">Órgão<\/td> <td class=\"field\" colspan=\"4\">", "<\/td>" );
					$superior = strtok ( $sup [0], "-" );
					$superior = trim ( strtok ( "-" ) );
					return $superior;
				}
			}
		}
		return $nome;
	}
	function obterNomeSituacao($id, $numero) {
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$numero.json";
		$json = file_get_contents ( $jsonurl );
		$json_output = json_decode ( $json );
		$programa = $json_output->propostas [0]->programas [0]->associacao [0]->Programa->id;
		$numero = $json_output->propostas [0]->numero_proposta;
		
		$remotePageUrl = 'https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do';
		$remotePageUrl1 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do?tipo_consulta=CONSULTA_COMPLETA&numeroProposta=' . $numero;
		
		$id = trim ( $id );
		$nome = '';
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.json";
		// echo $jsonurl."<br>";
		$json = file_get_contents ( $jsonurl );
		if (trim ( $json ) == '' || ! isset ( $json )) {
			$jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.html";
			$json = file_get_contents ( $jsonurl );
			$nome = $this->getTextBetweenTags ( $json, "<dt>Nome<\/dt>
			  <dd>", "<\/dd>" );
			$nome = $nome [0];
		} else {
			$json_output = json_decode ( $json );
			$nome = $json_output->situacaopropostas [0]->nome;
		}
		
		// caso não encontre em local nenhum, varre direto na página do siconv
		if (trim ( $nome ) == '' || trim ( $nome ) == 'NÃO SE APLICA') {
			$this->obter_paginaLogin ();
			$this->obter_pagina ( $remotePageUrl );
			// echo $remotePageUrl1;
			$documento = $this->obter_pagina ( $remotePageUrl1 );
			preg_match_all ( "/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches1 );
			foreach ( $matches1 [1] as $pag1 ) {
				if (strstr ( $pag1, 'ResultadoDaConsultaDePropostaDetalharProposta' ) !== false) {
					$pag1 = str_replace ( "&amp;", "&", $pag1 );
					// $listaPrograma = $this->imprimeDetalhePrograma(str_replace("&amp;","&",$pag1), $orgaos[$key-1], $listaPrograma, $anterior);
					$url1_ = $this->obter_pagina ( "https://www.convenios.gov.br" . $pag1 );
					$url_sem_espaco = $this->removeSpaceSurplus ( $url1_ );
					$sup = $this->getTextBetweenTags ( $url_sem_espaco, "Situação<\/td> <td colspan=\"4\"> <table cellpadding=\"0\" cellspacing=\"0\"> <td class=\"field\" width=\"40\%\">", "<\/td>" );
					return strtoupper ( $sup [0] );
				}
			}
		}
		return $nome;
	}
	function obterOrgaoSuperior($id) {
		$id = trim ( $id );
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.json";
		$json = file_get_contents ( $jsonurl );
		// echo $jsonurl;
		if (trim ( $json ) == '' || ! isset ( $json )) {
			$jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.html";
			$json = file_get_contents ( $jsonurl );
			// echo $jsonurl;
			$nome = $this->getTextBetweenTags ( $json, "\">Programa ", ": " );
			$numero = $this->getTextBetweenTags ( $json, "Numero Proposta<\/dt>
          <dd>", "<\/dd>" );
			// if (trim($nome[0]) == '' || !isset($nome[0])) $nome[0] = obterOrgaoSuperior($id);
			// echo "!!".$numero[0]."--".$id."__";
			$nome = $this->obterNomeOrgao ( substr ( $nome [0], 0, 5 ), $numero [0] );
		}
		if (trim ( $nome ) == '') {
			$json_output = json_decode ( $json );
			$programa = $json_output->propostas [0]->programas [0]->associacao [0]->Programa->id;
			$numero = $json_output->propostas [0]->numero_proposta;
			// echo "&&".$numero."**";
			$nome = obterNomeOrgao ( substr ( $programa, 0, 5 ), $numero );
		}
		
		return $nome;
	}
	function imprimeProposta($jsonurl, $ano, $listaPropostas) {
		$json = file_get_contents ( $jsonurl, 0, null, null );
		$json_output = json_decode ( $json );
		
		if ($json == '') {
			echo "Siconv com problemas internos, por favor tente novamente mais tarde.";
		}
		foreach ( $json_output->propostas as $orgaos ) {
			// echo $orgaos->valor_global."-".$orgaos->situacao->SituacaoProposta->id."<br />";
			$situacao = null;
			if ($orgaos->situacao == null)
				$situacao = 0;
			else
				$situacao = $orgaos->situacao->SituacaoProposta->id;
				// echo trim(substr($orgaos->data_cadastramento_proposta, 0, 4))."-".trim($ano)."<br />";
			if (trim ( substr ( $orgaos->data_cadastramento_proposta, 0, 4 ) ) == trim ( $ano ) || trim ( $ano ) == '') {
				
				$listaPropostas [trim ( substr ( $orgaos->data_cadastramento_proposta, 0, 4 ) )] [$orgaos->id] [$situacao] ['valor_global'] = $orgaos->valor_global;
				$listaPropostas [trim ( substr ( $orgaos->data_cadastramento_proposta, 0, 4 ) )] [$orgaos->id] [$situacao] ['valor_repasse'] = $orgaos->valor_repasse;
				$listaPropostas [trim ( substr ( $orgaos->data_cadastramento_proposta, 0, 4 ) )] [$orgaos->id] [$situacao] ['valor_contra_partida'] = $orgaos->valor_contra_partida;
				// $listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['id'] = $orgaos->id;
			}
		}
		if (isset ( $json_output->metadados->proximos ))
			$listaPropostas = $this->imprimeProposta ( $json_output->metadados->proximos, $ano, $listaPropostas );
		return $listaPropostas;
	}
	function obterEstado($estado) {
		switch ($estado) {
			case 7 :
				return 27;
			case 8 :
				return 7;
			case 10 :
				return 8;
			case 11 :
				return 9;
			case 14 :
				return 12;
			case 13 :
				return 11;
			case 12 :
				return 10;
			case 15 :
				return 13;
			case 16 :
				return 14;
			case 18 :
				return 16;
			case 19 :
				return 17;
			case 17 :
				return 15;
			case 20 :
				return 18;
			case 21 :
				return 19;
			case 23 :
				return 21;
			case 9 :
				return 22;
			case 22 :
				return 20;
			case 25 :
				return 23;
			case 27 :
				return 25;
			case 26 :
				return 24;
			case 24 :
				return 26;
		}
		return $estado;
	}
	function obterEstadoNome($estado) {
		switch ($estado) {
			
			case "1" :
				return "AC";
			case "2" :
				return "AL";
			case "4" :
				return "AM";
			case "3" :
				return "AP";
			case "5" :
				return "BA";
			case "6" :
				return "CE";
			case "7" :
				return "DF";
			case "8" :
				return "ES";
			case "10" :
				return "GO";
			case "11" :
				return "MA";
			case "14" :
				return "MG";
			case "13" :
				return "MS";
			case "12" :
				return "MT";
			case "15" :
				return "PA";
			case "16" :
				return "PB";
			case "18" :
				return "PE";
			case "19" :
				return "PI";
			case "17" :
				return "PR";
			case "20" :
				return "RJ";
			case "21" :
				return "RN";
			case "23" :
				return "RO";
			case "9" :
				return "RR";
			case "22" :
				return "RS";
			case "25" :
				return "SC";
			case "27" :
				return "SE";
			case "26" :
				return "SP";
			case "24" :
				return "TO";
		}
		return $estado;
	}
}
/* End of file student.php */
/* Location: ./system/application/controllers/student.php */
