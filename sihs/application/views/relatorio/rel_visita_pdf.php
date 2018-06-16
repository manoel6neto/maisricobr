<div style="text-align: center; border: solid 1px black;"><h3>Relatório de Visitas Por Representante</h3></div>

<div class="widget borders-none">
	<div class="panel">
<?php 
$titulo = "";
$i = 0;
$j = 0;
foreach ($dados_rel as $dados){
	$qtd = 0;
	
	foreach ($dados_rel as $d){
		if($d->nome == $dados->nome)
			$qtd++;
	}
	
	if($titulo == "" || $titulo != $dados->nome){
		echo '<div class="panel-heading">
				<h4 class="panel-title"><span style="color: red;">'.$dados->nome. ' <span style="color: #428bca;">('.$qtd.')</span></span></h4>
			</div>';
		
		echo '<table class="table" style="font-size:10px;">';
		$titulo = $dados->nome;
		$j++;
	}else
		$titulo = $dados->nome;
	
	echo "<tr style='background-color:#DCDCDC;'>
				<td style='border: 1px solid;' colspan='2'><b>Município:</b> {$dados->municipio} / {$dados->municipio_uf_sigla}</td>
				<td style='border: 1px solid;'><b>Contato:</b> {$dados->nome_contato}</td>
				<td style='border: 1px solid;'><b>Email:</b> {$dados->email_contato}</td>
				<td style='border: 1px solid;'><b>Telefones:</b> ".$contato_municipio_model->formataCelular($dados->telefone_contato)." / ".$contato_municipio_model->formataCelular($dados->celular_contato)." / ".$contato_municipio_model->formataCelular($dados->comercial_contato)."</td>
		</tr>";
	
	$historico_contato = $historico_contato_municipio_model->get_all_historico($dados->id_contato_municipio);
	
	echo "<tr><td style='border: 1px solid;' colspan='5'><b>Histórico da Visita</b></td></tr>";
	
	foreach ($historico_contato as $historico){
		echo "<tr>
				<td style='border: 1px solid;'><b>Status:</b> {$contato_municipio_model->getStatusContato($historico->status_contato)}</td>
				<td style='border: 1px solid;'><b>Data da Visita:</b> ".implode("/", array_reverse(explode("-", $historico->data_visita)))."</td>
				<td style='border: 1px solid;'><b>Data do Retorno:</b> ".implode("/", array_reverse(explode("-", $historico->data_retorno)))."</td>
				<td style='border: 1px solid;'><b>Classificação:</b> {$historico_contato_municipio_model->getClassVisita($historico->class_contato)}</td>
				<td style='width:400px; border: 1px solid;'><b>Obs Gerais:</b> {$historico->obs_gerais}</td>
			</tr>";
	}
	
	echo "<tr><td colspan='5'></td></tr>";
	
	if(isset($dados_rel[$i+1]->nome) && ($titulo == "" || $titulo == $dados_rel[$i+1]->nome)){
	
	}else{
		echo "</table>";
	}
	
	$i++;
}
?>

	</div>
</div>