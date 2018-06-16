<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
		<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-1.8.2.min.js"></script>
		<script src="<?= base_url();?>configuracoes/js/jquery-css-transform.js" type="text/javascript"></script>
		
    	<div id="content">
<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
		<form name="carrega_dados" method="post" id="carrega_dados" action="lista_programas">
	<a href="propostas"> Voltar </a><br />
	<?php 
	global $login;
	$login            = '43346880559';
	global $senha;
	$senha            = 'Laisa_M2012';
	global $cookie_file_path;
	//$cookie_file_path = tempnam ("/tmp", "CURLCOOKIE");
	$cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".rand());
/** 
* Open an url on https using curl and return content 
* 
* @author hatem <info@phptunisie.net> 
* @param string url            The url to open 
* @param string refer        Referer (optional) 
* @param mixed usecookie    If true, cookie.txt    will be used as default, or the usecookie value. 
* @return string 
*/ 
echo "Cidade: ".$cidade."<br />";
ksort($listaPropostas);
foreach ($listaPropostas as $chave => $lista){
	$aprovados = array();
	$numero_aprovados = 0;
	$soma = array();
	echo "<br />Ano: ".$chave."<br />";
	echo "<table border=\"1\" style=\"font-size: 12px;\"><tr><td>Órgão superior</td><td>Objeto</td><td>Nº. Prop</td>
	<td>Situação</td><td>Fim, vigência</td><td>Valor global</td>
	<td>Valor repasse</td><td>Valor contrapartida</td></tr>";
	foreach ($lista as $chave1 => $lista1){
		echo "<tr><td>".obterOrgaoSuperior($chave1)."</td>";
		foreach ($lista1 as $chave2 => $lista2){
			$situacao = obterNomeSituacao($chave2, $chave1);
			echo "<td style=\"font-size: 10px;\">".$lista2['objeto']."</td>";
			echo "<td>".$lista2['numero']."</td>";
			echo "<td>".$situacao."</td>";
			echo "<td>".implode("/",array_reverse(explode("-",$lista2['fim'])))."</td>";
			echo "<td>".$lista2['valor_global']."</td>";
			echo "<td>".$lista2['valor_repasse']."</td>";
			echo "<td>".$lista2['valor_contra_partida']."</td>";
			if (isset($soma['valor_global']) !== false)
				$soma['valor_global'] += (float)$lista2['valor_global'];
			else
				$soma['valor_global'] = (float)$lista2['valor_global'];
			
			if (isset($soma['valor_repasse']) !== false)
				$soma['valor_repasse'] += (float)$lista2['valor_repasse'];
			else
				$soma['valor_repasse'] = (float)$lista2['valor_repasse'];
			
			if (isset($soma['valor_contra_partida']) !== false)
				$soma['valor_contra_partida'] += (float)$lista2['valor_contra_partida'];
			else
				$soma['valor_contra_partida'] = (float)$lista2['valor_contra_partida'];
			if (strcasecmp($situacao, 'Proposta/Plano de Trabalho Aprovados') == 0 ||
			strcasecmp($situacao, 'Assinado') == 0 ||
			strcasecmp($situacao, 'Em execução') == 0){
				$numero_aprovados++;
				if (isset($aprovados['valor_global']) !== false)
				$aprovados['valor_global'] += (float)$lista2['valor_global'];
			else
				$aprovados['valor_global'] = (float)$lista2['valor_global'];
			
			if (isset($aprovados['valor_repasse']) !== false)
				$aprovados['valor_repasse'] += (float)$lista2['valor_repasse'];
			else
				$aprovados['valor_repasse'] = (float)$lista2['valor_repasse'];
			
			if (isset($aprovados['valor_contra_partida']) !== false)
				$aprovados['valor_contra_partida'] += (float)$lista2['valor_contra_partida'];
			else
				$aprovados['valor_contra_partida'] = (float)$lista2['valor_contra_partida'];
			}
			
			/*	['objeto'] = $orgaos->objeto_resumido;
			$listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['numero'] = $this->obterNumeroProposta($orgaos->id);
			$listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['fim'] = $orgaos->fim_execucao;
			$listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['valor_global'] = $orgaos->valor_global;
			$listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['valor_repasse'] = $orgaos->valor_repasse;
			$listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['valor_contra_partida']*/
		}
		echo "</tr>";
	}
	echo "<tr><td>Propostas cadastradas:</td>";
	echo "<td>".count($listaPropostas[$chave])."</td>";
	echo "<td></td>";
	echo "<td colspan=2 style=\"color: red;\">Total cadastrado</td>";
	echo "<td style=\"color: red;\">".$soma['valor_global']."</td>";
	echo "<td style=\"color: red;\">".$soma['valor_repasse']."</td>";
	echo "<td style=\"color: red;\">".$soma['valor_contra_partida']."</td><tr>";
	
	echo "<tr><td>Propostas aprovadas:</td>";
	echo "<td>".$numero_aprovados."</td>";
	echo "<td></td>";
	echo "<td colspan=2>Total aprovado</td>";
	if (isset($aprovados['valor_global']) !== false) echo "<td>".$aprovados['valor_global']."</td>";
	else echo "<td></td>";
	if (isset($aprovados['valor_repasse']) !== false) echo "<td>".$aprovados['valor_repasse']."</td>";
	else echo "<td></td>";
	if (isset($aprovados['valor_contra_partida']) !== false) echo "<td>".$aprovados['valor_contra_partida']."</td><tr>";
	else echo "<td></td><tr>";
	echo "</table>";
}
/*
<option value="PLANO_TRABALHO_APROVADO">Proposta/Plano de Trabalho Aprovados</option>
<option value="ASSINADA">Assinado</option>
<option value="EM_EXECUCAO">Em execução</option>


{"metadados": {}, "programas": 
[{"cod_programa_siconv": "5600020080678", "data_fim_emenda_parlamentar": null, "href": "http://api.convenios.gov.br/siconv/id/programa/74622", "data_inicio_recebimento_propostas": "2008-12-01", "acao_orcamentaria": "0B160101", "id": 74622, "data_inicio_emenda_parlamentar": null, "nome": "GEST\u00c3O DA POL\u00cdTICA DE DESENVOLVIMENTO URBANO", 
"convenios": {"href": "http://api.convenios.gov.br/siconv/v1/consulta/convenios?id_programa=74622"}, 
"data_inicio_beneficiario_especifico": null, "data_publicacao_dou": null, "descricao": "GEST\u00c3O DA POL\u00cdTICA DE DESENVOLVIMENTO URBANO", 
"atende_a": [{"NaturezaJuridica": {"href": "http://api.convenios.gov.br/siconv/id/natureza_juridica/32", "id": 32}}], 
"ufs_habilitadas": ["MA"], "data_fim_beneficiario_especifico": null, "situacao": "DISPONIBILIZADO", "data_disponibilizacao": "2008-12-01", "propostas": 
{"href": "http://api.convenios.gov.br/siconv/v1/consulta/propostas?id_programa=74622"}, 
"aceita_emenda_parlamentar": false, 
"orgao_mandatario": {"Orgao": {"href": "http://api.convenios.gov.br/siconv/id/orgao/25220", "id": 25220}},
 "orgao_executor": {"Orgao": {"href": "http://api.convenios.gov.br/siconv/id/orgao/56000", "id": 56000}}, 
 "emendas": {"href": "http://api.convenios.gov.br/siconv/v1/consulta/emendas?id_programa=74622"}, 
 "obriga_plano_trabalho": true, "data_fim_recebimento_propostas": "2008-12-31", 
 "orgao_vinculado": {"Orgao": {"href": "http://api.convenios.gov.br/siconv/id/orgao/56000", "id": 56000}}, 
 "orgao_superior": {"Orgao": {"href": "http://api.convenios.gov.br/siconv/id/orgao/56000", "id": 56000}}}
]}*/

function obterNomeOrgao($id, $numero){
	//return 'teste';
	//echo ".".$id.".<br>";
	$numero = trim($numero);
	$remotePageUrl = 'https://www.convenios.gov.br/siconv/proposta/CopiarDadosProposta/CopiarDadosProposta.do';
	$remotePageUrl1 = 'https://www.convenios.gov.br/siconv/CopiarDadosProposta/ConsultarPropostasEConveniosConsultar.do?numeroProposta='.$numero;
	
	$nome = '';
	if ((int)$id > 0){
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/programa/$id.json";
		$id_orgao = null;
		if ($json = file_get_contents($jsonurl))
		{
			$json_output = json_decode($json);
			$id_orgao = $json_output->programas[0]->orgao_superior->Orgao->id;
		}
		
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id_orgao.json";
		
		if ($json = file_get_contents($jsonurl))
		{
			if (trim($json) == '' || !isset($json)){
				$jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id_orgao.html";
				$json = file_get_contents($jsonurl);
				$nome = getTextBetweenTags($json, "<dt>Nome<\/dt>
				  <dd>", "<\/dd>");
				$nome = $nome[0];
			}
			else{
				$json_output = json_decode($json);
				$nome = $json_output->orgaos[0]->nome;
			}
		}
	} 
	
	//caso não encontre em local nenhum, varre direto na página do siconv
	if (trim($nome) == '' || trim($nome)=='NÃO SE APLICA'){
		
		obter_paginaLogin();
		//obter_pagina('https://www.convenios.gov.br/portal/acessoLivre.html');
		$documento = obter_pagina($remotePageUrl);
		$documento1 = obter_pagina($remotePageUrl1);
		
		$idProposta = '0';
		preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
		foreach($matches1[1] as $pag1){
			if (strstr($pag1, 'ResultadoConsultaPropostaSelecionar') !== false){
				$tok = explode("idProposta=", $pag1);
				$idProposta = $tok[1];
			}
		}
	
		$remotePageUrl2 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta='.$idProposta;
		$documento2 = obter_pagina($remotePageUrl2);
		$url_sem_espaco = removeSpaceSurplus($documento2);
		$sup = getTextBetweenTags($url_sem_espaco, "<td class=\"label\">Órgão<\/td> <td class=\"field\" colspan=\"4\">", "<\/td>");
		$superior = strtok($sup[0],"-");
		$superior = trim(strtok("-"));
				
		return $superior;
	}
	
    return $nome;
}

function obterNomeSituacao($id, $numero){
	//return 'teste';
	//echo $numero.".".$id.".<br>";
	$jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$numero.json";
	$nome = '';
	$remotePageUrl = 'https://www.convenios.gov.br/siconv/proposta/CopiarDadosProposta/CopiarDadosProposta.do';
	$remotePageUrl1 = 'https://www.convenios.gov.br/siconv/CopiarDadosProposta/ConsultarPropostasEConveniosConsultar.do?numeroProposta='.$numero;
    
    if ($json = file_get_contents($jsonurl))
    {
		$json_output = json_decode($json);
		$programa = $json_output->propostas[0]->programas[0]->associacao[0]->Programa->id;
		$numero = $json_output->propostas[0]->numero_proposta;
		
		$remotePageUrl1 = 'https://www.convenios.gov.br/siconv/CopiarDadosProposta/ConsultarPropostasEConveniosConsultar.do?numeroProposta='.$numero;
		if ($id != '0'){
			$id = trim($id);
			
			$jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.json";
			//echo $jsonurl."<br>";
			$json = file_get_contents($jsonurl);
			if (trim($json) == '' || !isset($json)){
				$jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.html";
				$json = file_get_contents($jsonurl);
				$nome = getTextBetweenTags($json, "<dt>Nome<\/dt>
					  <dd>", "<\/dd>");
				$nome = $nome[0];
			}
			else{
				$json_output = json_decode($json);
				$nome = $json_output->situacaopropostas[0]->nome;
			}
		}
	}
	
	//caso não encontre em local nenhum, varre direto na página do siconv
	if (trim($nome) == '' || trim($nome)=='NÃO SE APLICA'){
		//echo trim($nome)."...<br />";
		obter_paginaLogin();
		//obter_pagina('https://www.convenios.gov.br/portal/acessoLivre.html');
		$documento = obter_pagina($remotePageUrl);
		$documento1 = obter_pagina($remotePageUrl1);
		
		$idProposta = '0';
		preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
		foreach($matches1[1] as $pag1){
			if (strstr($pag1, 'ResultadoConsultaPropostaSelecionar') !== false){
				$tok = explode("idProposta=", $pag1);
				$idProposta = $tok[1];
			}
		}
		$remotePageUrl2 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta='.$idProposta;
		$documento2 = obter_pagina($remotePageUrl2);
		$url_sem_espaco = removeSpaceSurplus($documento2);
		$sup = getTextBetweenTags($url_sem_espaco, "Situação<\/td> <td colspan=\"4\"> <table cellpadding=\"0\" cellspacing=\"0\"> <td class=\"field\" width=\"40\%\">", "<\/td>");
		return strtoupper($sup[0]);

	}
    return $nome;
}

function obterOrgaoSuperior($id){
	
	$nome = '';
   	$id = trim($id);
	$jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.json";
	
    $json = file_get_contents($jsonurl);
    //echo $jsonurl;
    if (trim($json) == '' || !isset($json)){
		$jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.html";
		$json = file_get_contents($jsonurl);
		//echo $jsonurl."123<br />";
		$nome = getTextBetweenTags($json, "\">Programa ", ": ");
		$numero = getTextBetweenTags($json, "Numero Proposta<\/dt>
          <dd>", "<\/dd>");
		//if (trim($nome[0]) == '' || !isset($nome[0])) $nome[0] = obterOrgaoSuperior($id);
		//echo "!!".$numero[0]."--".$id."__";
		$nome = obterNomeOrgao(trim($nome[0]), $numero[0]);
	}
    if (trim($nome) == ''){
		
		$json_output = json_decode($json);
		$programa = $json_output->propostas[0]->programas[0]->associacao[0]->Programa->id;
		$numero = $json_output->propostas[0]->numero_proposta;
		//echo "&&".$numero."**";
		$nome = obterNomeOrgao(trim($programa), $numero);
	}
	
    return $nome;
}

function obter_paginaLogin(){
	global $login , $senha, $cookie_file_path;
	$url         	  = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha";
	//$cookie_file_path = "application/views/configuracoes/cookie_proposta1.txt";
	//$cookie_file_path = tempnam ("/tmp", "CURLCOOKIE");
	//$fp = fopen($cookie_file_path, "w+");
	//fclose($fp);
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
	$ch = curl_init();
	// extra headers 
	$headers[] = "Accept: */*";
	$headers[] = "Connection: Keep-Alive";

	// basic curl options for all requests
	curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
	curl_setopt($ch, CURLOPT_HEADER,  0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
	curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); 

	// set first URL
	curl_setopt($ch, CURLOPT_URL, $url);

	// execute session to get cookies and required form inputs
	$content = curl_exec($ch);
	
	curl_close($ch);
	if (strstr($content, 'ELIUMAR CARLOS DE SOUSA SILVA') === false){
		$senha = 'Laisa_M2012';
		$this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE1".rand());
		return $this->obter_paginaLogin();
	}
	return $content;
}

function obter_pagina($url){
	global $cookie_file_path;
	//$cookie_file_path = "application/views/configuracoes/cookie_proposta1.txt";
	//$cookie_file_path = tempnam ("/tmp", "CURLCOOKIE");
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
	$ch = curl_init(); 
	// extra headers
	$headers[] = "Accept: */*";
	$headers[] = "Connection: Keep-Alive";

	// basic curl options for all requests
	curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
	curl_setopt($ch, CURLOPT_HEADER,  0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
	curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); 

	// set first URL
	curl_setopt($ch, CURLOPT_URL, $url);

	// execute session to get cookies and required form inputs
	$content = curl_exec($ch); 
	
	curl_close($ch);
	
	return $content;
}
function removeSpaceSurplus($str)
{
	return preg_replace("/\s+/", ' ', trim($str));
}

function getTextBetweenTags($string, $tag1, $tag2) {
    $pattern = "/$tag1([\w\W]*?)$tag2/";
    preg_match_all($pattern, $string, $matches);
    return $matches[1];
}
	?>
	</div>
</div>

