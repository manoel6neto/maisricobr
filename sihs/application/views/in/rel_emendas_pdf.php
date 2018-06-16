<?php
$i = 0;
$j = 0;
$numeroEmenda = "";
$codigoSiconv = "";

echo "<div style='text-align:center;'><h2>Relatório de Emendas</h2></div>";

foreach ($emendas as $e){
	$qtd = 0;

	if($numeroEmenda == "" || $numeroEmenda != $e->codigo_emenda){
		echo '<h4 class="panel-title">'.$e->codigo_emenda. '/'. $e->ano . '</h4>';
			
		echo '<table class="table" style="font-size: 14px;">';
		$numeroEmenda = $e->codigo_emenda;
		$j++;
	}else
		$numeroEmenda = $e->codigo_emenda;

	//$propostas = $banco_proposta_model->get_propostas_by_emenda($e->emenda, $e->codigo_programa);

	//if($propostas != null){
	//foreach($propostas as $p){
	if($codigoSiconv == "" || $codigoSiconv != $e->codigo_siconv){
		$codigoSiconv = $e->codigo_siconv;
			
		$programas = $programa_banco_proposta_model->get_programas_by_proposta($e->id_proposta);

		echo "<tr style='background-color:#DCDCDC; border: 1px solid;'><td style='color:red; border: 1px solid;'>Proponente</td><td style='color:#428bca; border: 1px solid;' colspan='7'>".$e->nome_proponente."</td></tr>";
			
		//"<td colspan='2' style='color:red;'>Valor Emenda</td><td colspan='3' style='color:#428bca;'>".$programa_model->get_valor_emenda($e->codigo_emenda, $e->proponente)."</td>";
			
		foreach ($programas as $programa){
			echo "<tr>";

			echo "<td style='color:red; border: 1px solid;'>Programa</td><td style='font-size:12px; border: 1px solid;'>".(substr($programa->nome_programa, 0, 180) . (strlen($programa->nome_programa) > 180 ? "..." : ""))."</td>";
			$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);

			echo "<td style='color:red; border: 1px solid;'>Inicio Vigência</td><td style=' border: 1px solid;'>".implode("/", array_reverse(explode("-", $dadosPrograma->data_inicio_parlam)))."</td>";
			echo "<td style='color:red; border: 1px solid;'>Final Vigência</td><td colspan='3' style=' border: 1px solid;'>".implode("/", array_reverse(explode("-", $dadosPrograma->data_fim_parlam)))."</td>";

			echo "</tr>";
		}
			
		//echo "<tr style='color:red;'><td colspan='7'>Status da Emenda</td></tr>";
			
		echo "<tr><td style='color:red; border: 1px solid;'>Valor da Emenda</td><td colspan='7'  style=' border: 1px solid;'>{$programa_model->get_valor_emenda($e->codigo_emenda, $e->proponente)}</td></tr>";
			
		echo "<tr style='color:#428bca; border: 1px solid;'><td colspan='8'  style=' border: 1px solid;'>Proposta Cadastrada</td></tr>";

		echo "<tr>";
			
		echo "<td style='color:red; border: 1px solid;'>Valor Repasse</td><td style=' border: 1px solid;'>{$e->valor_emenda}</td>";
		echo "<td style='color:red; border: 1px solid;'>Numero</td><td style=' border: 1px solid;'>{$e->codigo_siconv}</td>";
		echo "<td style='color:red; border: 1px solid;'>Data Criação</td><td style=' border: 1px solid;'>".implode("/", array_reverse(explode("-", $e->data_inicio)))."</td>";
		echo "<td style='color:red; border: 1px solid;'>Status</td><td style=' border: 1px solid;'>".$e->situacao."</td>";
		//echo "<td><a href='".base_url("index.php/in/dados_siconv/detalha_propostas_pareceres?id={$e->id_proposta}")."'>Detalhes</a></td>";

		$valor_emenda = $programa_model->get_valor_emenda($e->codigo_emenda, $e->proponente);
		$valor_emenda = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $valor_emenda)));

		$valor_proposta = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $e->valor_emenda)));

		echo "<tr><td style='color:red; border: 1px solid;'>Valor a Utilizar</td><td colspan='7'  style=' border: 1px solid;'>R$ ".number_format(($valor_emenda-$valor_proposta), 2, ",", ".")."</td></tr>";

		echo "</tr>";

		echo "<tr><td colspan='8'></td></tr>";

	}else
		$codigoSiconv = $e->codigo_siconv;
	//}
	//}else
	//echo '<h1 style="text-align: center;">Nenhuma proposta encontrada.</h1>';

	if(isset($emendas[$i+1]->codigo_emenda) && ($numeroEmenda == "" || $numeroEmenda == $emendas[$i+1]->codigo_emenda)){

	}else{
		echo "</table>";
	}

	$i++;
}