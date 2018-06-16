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
	var classe = document.getElementById("div_"+nome);
	if (classe.style.display == 'none') classe.style.display = '';
	else classe.style.display = 'none';
}
</script>
<div id="ConteudoDiv">
	<div id="salvar" class="action">
		<div class="trigger">
		<form name="carrega_dados" method="post" id="carrega_dados" action="<?= base_url();?>index.php/in/dados_siconv/programas?usuario=<?=$usuario?>">
	</head>

			<?php
				echo "por cidade:<br />";
				?>
				<input type="checkbox" id="cidade" name="todos_municipio" value="todos_municipio" onclick="selecionar('cidade')"> <b>TODOS</b><br />
				<?php
				foreach($listaCidade as $key => $cidade){

					if (in_array(mb_strtolower($cidade->qualificacao, 'UTF-8'), array_map('strtolower', $dados_post)) &&
					in_array(mb_strtolower($cidade->atende, 'UTF-8'), array_map('strtolower', $dados_post))){
						
					echo "<input type=\"checkbox\" class=\"cidade\" id=\"".$cidade->codigo."\" name=\"radio".$cidade->codigo."\" value=\"".$cidade->codigo."\"><a onclick=\"ativa('".$cidade->codigo."');\">".$cidade->nome."</a><br />";
					echo "<div id=\"div_".$cidade->codigo."\" style=\"display:none\"><table>";
							echo "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>".$cidade->codigo."</td></tr>";
							echo "<tr><td><b>ÓRGÃO SUPERIOR:</b></td><td colspan=3 bgcolor=\"grey\">".$cidade->orgao."</td></tr>";
							echo "<tr><td><b>ÓRGÃO PROVENENTE:</b></td><td colspan=3>".$cidade->orgao_vinculado."</td></tr>";	
							echo "<tr><td><b>INÍCIO VIGÊNCIA:</b></td><td>".implode("/",array_reverse(explode("-",$cidade->data_inicio)))."</td>";	
							echo "<td><b>FIM VIGÊNCIA:</b> ".implode("/",array_reverse(explode("-",$cidade->data_fim)))."</td>";	
							echo "<td><b>QUALIFICAÇÃO:</b> ".$cidade->qualificacao."</td></tr>";	
							echo "<tr><td><b>PROGRAMA:</b></td><td colspan=3>".$cidade->nome."</td></tr>";	
						echo "</table>";
							echo $cidade->descricao."<br>";
							echo "<b>Obs.:</b> ".$cidade->observacao."<br>";
							echo "<b>INDICADO PARA:</b> ".$cidade->atende."<br><br></div>";	
					}
				}

				echo "por estado:<br />";
				?>
				<input type="checkbox" id="estado" name="todos_estado" value="todos_estado" onclick="selecionar('estado')"> <b>TODOS</b><br />
				<?php
				foreach($listaEstado as $key => $estado){
					if (in_array(mb_strtolower($estado->qualificacao, 'UTF-8'), array_map('strtolower', $dados_post)) &&
					in_array(mb_strtolower($estado->atende, 'UTF-8'), array_map('strtolower', $dados_post))){
					echo "<input type=\"checkbox\" class=\"estado\" id=\"".$estado->codigo."\" name=\"radio".$estado->codigo."\" value=\"".$estado->codigo."\"><a onclick=\"ativa('".$estado->codigo."');\">".$estado->nome."</a><br />";
					echo "<div id=\"div_".$estado->codigo."\" style=\"display:none\"><table>";
							echo "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>".$estado->codigo."</td></tr>";
							echo "<tr><td><b>ÓRGÃO SUPERIOR:</b></td><td colspan=3 bgcolor=\"grey\">".$estado->orgao."</td></tr>";
							echo "<tr><td><b>ÓRGÃO PROVENENTE:</b></td><td colspan=3>".$estado->orgao_vinculado."</td></tr>";	
							echo "<tr><td><b>INÍCIO VIGÊNCIA:</b></td><td>".implode("/",array_reverse(explode("-",$estado->data_inicio)))."</td>";	
							echo "<td><b>FIM VIGÊNCIA:</b> ".implode("/",array_reverse(explode("-",$estado->data_fim)))."</td>";	
							echo "<td><b>QUALIFICAÇÃO:</b> ".$estado->qualificacao."</td></tr>";	
							echo "<tr><td><b>PROGRAMA:</b></td><td colspan=3>".$estado->nome."</td></tr>";	
						echo "</table>";
							echo $estado->descricao."<br>";
							echo "<b>Obs.:</b> ".$estado->observacao."<br>";
							echo "<b>INDICADO PARA:</b> ".$estado->atende."<br><br></div>";
					}
				}
			?>
			<input type="submit" name="operation" value="Selecionar Programas" />
		</form>
	</div>
</div>

