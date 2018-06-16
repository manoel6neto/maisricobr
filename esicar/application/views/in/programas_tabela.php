<div id="content">
	<div class="innerAll spacing-x2">
		<!-- Widget -->
		<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">PROGRAMAS ABERTOS</h4>
			</div>
			<div class="widget-body padding-bottom-none">
				<!-- Table -->
				<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
					<?php 
					$cabecalho = array();
					foreach($listaPrograma as $key => $programa){
						foreach($programa as $key1 => $programa1){
							foreach($programa1 as $key2 => $programa2){
								$cabecalho[$key2] = 1;
							}
						}
					}
					ksort($cabecalho);
					
					echo "
					<thead>
					<tr>
					
					<th>Órgão superior</th>";
					foreach($cabecalho as $key => $valor){
						echo "<th>".$key."</th>";
					}
					echo "
					</tr>
					</thead>
					<tbody>";
					$soma = array();
					$somaAno = array();
					foreach($listaPrograma as $key => $programa){
						foreach($programa as $key1 => $programa1){
							foreach($cabecalho as $key2 => $programa2){
								if (isset($soma[$key][$key2]) !== false){
									if (isset($programa1[$key2]) !== false){
										$soma[$key][$key2] += $programa1[$key2];
									}
								}
								else{
									if (isset($programa1[$key2]) !== false){
										$soma[$key][$key2] = $programa1[$key2];
									}
								}
							}
						}
						echo "<tr><td>".$key."</td>";
						//foreach($soma as $key1 => $programa1){
							foreach($cabecalho as $key2 => $programa2){
								if (isset($soma[$key][$key2]) !== false) echo "<td>".$soma[$key][$key2]."</td>";
								else echo "<td></td>";
							}
						//}
						echo "</tr>";
						#foreach($programa as $key1 => $programa1){
						#	echo "<tr class=\"$key\"><td>".$key1."</td>";
						#	foreach($cabecalho as $key2 => $programa2){
						#		if (isset($programa1[$key2]) !== false) echo "<td>".$programa1[$key2]."</td>";
						#		else echo "<td></td>";
						#	}
						#	echo "</tr>";
						#}
						
						foreach($cabecalho as $key2 => $programa2){
							if (isset($somaAno[$key2]) !== false){
								if (isset($soma[$key][$key2]) !== false){
									$somaAno[$key2] += $soma[$key][$key2];
								}
							}
							else{
								if (isset($soma[$key][$key2]) !== false){
									$somaAno[$key2] = $soma[$key][$key2];
								}
							}
							
						}
						//echo "</tr>";
					}
					echo "<tr><td>Total Geral por Ano</td>";
					foreach($cabecalho as $key2 => $programa2){
						if (isset($somaAno[$key2]) !== false) echo "<td>".$somaAno[$key2]."</td>";
								else echo "<td></td>";
					}
					
					echo "
					</tr>
					</tbody>";
				?>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>