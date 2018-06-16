<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
    	<div id="content">
<script type="text/javascript">
function selecionar(classe){
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
		if(document.getElementById(classe).checked == 1)
			divs[i].checked = 1;
		else
			divs[i].checked = 0;
	}
}

function ativa(nome){
	var classe = document.getElementById(nome);
	if (classe.style.display == 'none') classe.style.display = '';
	else classe.style.display = 'none';
}
</script>
<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
	</head>

				<?php
				foreach ($listaPropostas as $chave1 => $lista1){
					echo "<a onclick=\"ativa('ext_".$chave1."');\">".$chave1."</a><br>";
					echo "<div id=\"ext_".$chave1."\" style=\"display:none; margin-left: 20px;\">";
					foreach ($lista1 as $chave => $lista){
						$nome_programa = $lista[0]['nome_programa'];
						if (trim($nome_programa) == '' || !isset($nome_programa))
							$nome_programa = $programa_model->obter_por_codigo($chave)->nome;
						else if (strcasecmp(trim($chave), 'NÃO SE APLICA') == 0)
							$nome_programa = $chave;
						$num = count($lista)-1;
						echo "<a onclick=\"ativa('div_".$chave."');\">".$chave." - ".$nome_programa."(".$num.")</a><br>";
						echo "<div id=\"div_".$chave."\" style=\"display:none\">";
						foreach ($lista as $chave1 => $lista1){
							if ($chave1 != 0) //chave do valor do nome do programa
							echo "=>".$lista1['numero'].": ".$lista1['objeto']."<br>";
						}
						echo "</div><br>";
					}
					echo "</div>";
				}/*
				echo "<br>Programas abertos sem propostas cadastradas:<br>";
				foreach ($programas_estado as $chave1 => $lista1){
					echo "<a onclick=\"ativa('ext1_".$chave1."');\">".$chave1."</a><br>";
					echo "<div id=\"ext1_".$chave1."\" style=\"display:none; margin-left: 20px;\">";
					foreach ($lista1 as $chave => $lista){
						$nome_programa = $lista['nome_programa'];
						if (trim($nome_programa) == '' || !isset($nome_programa))
							$nome_programa = $programa_model->obter_por_codigo($chave)->nome;
						else if (strcasecmp(trim($chave), 'NÃO SE APLICA') == 0)
							$nome_programa = $chave;
						if (isset($listaPropostas[$chave1]) !== false){
							if (!array_key_exists($chave, $listaPropostas[$chave1]))
								echo " - ".$chave." - ".$nome_programa."(0)<br>";
						} else {
							echo " - ".$chave." - ".$nome_programa."(0)<br>";
						}
					}
					echo "</div>";
				}
				/*
				$array_aux = array();
				//ordenando
				foreach ($listaPropostas as $chave => $lista){
					$array_aux[$chave] = count($lista)-1;//tira o nome do programa
				}
				arsort($array_aux);
				foreach ($array_aux as $chave => $num){
					$lista = $listaPropostas[$chave];
					//var_dump($lista);
					$nome_programa = $lista[0]['nome_programa'];
					if (trim($nome_programa) == '' || !isset($nome_programa))
						$nome_programa = $programa_model->obter_por_codigo($chave)->nome;
					else if (strcasecmp(trim($chave), 'NÃO SE APLICA') == 0)
						$nome_programa = $chave;
					echo "<a onclick=\"ativa('".$chave."');\">".$nome_programa."(".$num.")</a><br>";
					echo "<div id=\"div_".$chave."\" style=\"display:none\">";
					foreach ($lista as $chave1 => $lista1){
						if ($chave1 != 0) //chave do valor do nome do programa
						echo "=>".$lista1['numero'].": ".$lista1['objeto']."<br>";
					}
					echo "</div><br>";
				}
				foreach ($programas_estado as $programa){
					if (!array_key_exists($programa->codigo, $array_aux)) {
						echo "<a>".$programa->nome."(0)</a><br>";
						echo "<br>";
					}
				}*/
				?>

	</div>
</div>

