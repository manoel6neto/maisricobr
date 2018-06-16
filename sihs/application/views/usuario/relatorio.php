<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
<div id="content">
    <h1 class="bg-white content-heading border-bottom">RELATÓRIO DE PROGRAMAS</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
				<?php
					foreach($lista as $programa){
						echo "<br /><br /><table>";
							echo "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>".$programa->codigo."</td></tr>";
							echo "<tr><td><b>ÓRGÃO SUPERIOR:</b></td><td colspan=3 bgcolor=\"grey\">".$programa->orgao."</td></tr>";
							echo "<tr><td><b>ÓRGÃO PROVENENTE:</b></td><td colspan=3>".$programa->orgao_vinculado."</td></tr>";	
							echo "<tr><td><b>INÍCIO VIGÊNCIA:</b></td><td>".implode("/",array_reverse(explode("-",$programa->data_inicio)))."</td>";	
							echo "<td><b>FIM VIGÊNCIA:</b> ".implode("/",array_reverse(explode("-",$programa->data_fim)))."</td>";	
							echo "<td><b>QUALIFICAÇÃO:</b> ".$programa->qualificacao."</td></tr>";	
							echo "<tr><td><b>PROGRAMA:</b></td><td colspan=3>".$programa->nome."</td></tr>";	
						echo "</table>";
							echo $programa->descricao."<br>";
							echo "<b>Obs.:</b> ".$programa->observacao."<br>";
							echo "<b>INDICADO PARA:</b> ".$programa->atende."<br>";	
					}
					?>
					<input name="geraPdf" type="button" value="Gerar PDF" onclick="window.location='gerapdf'" style="margin:25px 0px 5px 0px;">
	</div>
</div>
</div>
</div>

