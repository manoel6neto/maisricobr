<div style="text-align: center;">
	<?php $nome_exibir = (!$ehEstadual) ? $cidade." - ".$estado : $nome_governo;?>

	<h3>Dados Quantitativos <?php echo $nome_exibir; ?></h3>
</div>

<br/>

<div style="text-align: center;">
	<h4>Dados Relacionados ao SICONV (Propostas)</h4>
</div>
<table align="center">
	<thead>
		<tr>
			<td colspan="5" style="text-align: center;"><b>Propostas</b></td>		
		</tr>
		<tr style="color: red; font-size: 15px;">
			<td colspan="3"><b>Cadastradas</b> -- <span style="color: black;"><?php echo $num_propostas_cadastradas;?></span></td>
			<td colspan="2"><b>Não Enviadas</b> -- <span style="color: black;"><?php echo $num_propostas_cadastradas-$num_propostas;?></span></td>
		</tr>
		<tr style="color: red; font-size: 15px;">
			<td><b>Enviadas -- <span style="color: black;"><?php echo $num_propostas;?></span></b></td>
			<td style="width: 40px;"></td>
			<td><b>Aprovadas -- <span style="color: black;"><?php echo $num_total_aprovadas;?></span></b></td>
			<td style="width: 40px;"></td>
			<td><b>% -- <span style="color: black;"><?php echo number_format(($num_total_aprovadas*100)/($num_propostas > 0 ? $num_propostas : 1), 2);?></span></b></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista_anos as $ano):?>
		<tr style="color: #428bca; font-size: 14px;">
			<td><?php echo "Ano ".$ano." --- ".(isset($num_cadastradas[$ano]) ? $num_cadastradas[$ano] : 0);?></td>
			<td style="width: 40px;"></td>
			<td><?php echo "Ano ".$ano." --- ".(isset($num_aprovadas[$ano]) ? $num_aprovadas[$ano] : 0);?></td>
			<td style="width: 40px;"></td>
			<td><?php echo "Ano ".$ano." --- ".number_format((((isset($num_aprovadas[$ano]) ? $num_aprovadas[$ano] : 0)*100)/(isset($num_cadastradas[$ano]) ? $num_cadastradas[$ano] : 1)), 2);?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>

<br/><br/>

<div style="text-align: center;">
	<h4>Dados Gerais Relacionados ao SICONV ano <?php echo $ano_busca;?></h4>
</div>

<table align="center">
	<thead>
		<tr style="color: #428bca; font-size: 16px;">
			<td colspan="2"><b>Dados Município <?php echo (!$ehEstadual) ? "" : " + Estado";?></b></td>
			<td></td>
			<td colspan="2"><b>Dados Estado</b></td>
		</tr>
	</thead>
	<tbody style="color: red;">
		<tr style="color: red; font-size: 15px;">
			<td>Total Programas Abertos ----------- </td>
			<td><?php echo $programas_municipio;?></td>
			<td></td>
			<td>Total Programas Abertos ----------- </td>
			<td><?php echo $programas_estado;?></td>
		</tr>
		
		<tr style="color: red; font-size: 15px;">
			<td>Voluntários ------------------------------- </td>
			<td><?php echo $programas_municipio_voluntario;?></td>
			<td></td>
			<td>Voluntários ------------------------------- </td>
			<td><?php echo $programas_estado_voluntario;?></td>
		</tr>
		
		<tr style="color: red; font-size: 15px;">
			<td>Emendas Parlamentares ------------ </td>
			<td><?php echo $programas_municipio_emenda;?></td>
			<td></td>
			<td>Emendas Parlamentares ------------ </td>
			<td><?php echo $programas_estado_emenda;?></td>
		</tr>
		
		<tr style="color: red; font-size: 15px;">
			<td>Emendas Proponente Específico - </td>
			<td><?php echo $programas_municipio_especifico;?></td>
			<td></td>
			<td>Emendas Proponente Específico - </td>
			<td><?php echo $programas_estado_especifico;?></td>
		</tr>
		
		<?php if(!$ehEstadual):?>
		<tr style="color: red; font-size: 15px;">
			<td>Propostas Enviadas ------------------- </td>
			<td><?php echo (isset($num_cadastradas[$ano_busca]) ? $num_cadastradas[$ano_busca] : 0);?></td>
			<td></td>
			<td>Propostas Enviadas ------------------- </td>
			<td><?php echo $num_cadastradas_estado;?></td>
		</tr>
		<?php else:?>
		<tr style="color: red; font-size: 15px;">
			<td>Propostas Aprovadas ----------------- </td>
			<td><?php echo $num_cadastradas_estado+(isset($num_cadastradas[$ano_busca]) ? $num_cadastradas[$ano_busca] : 0);?></td>
			<td></td>
			<td>Propostas Aprovadas ----------------- </td>
			<td><?php echo (isset($num_cadastradas[$ano_busca]) ? $num_cadastradas[$ano_busca] : 0);?></td>
		</tr>
		<?php endif;?>
		
		<?php if(!$ehEstadual):?>
		<tr style="color: red; font-size: 15px;">
			<td>Propostas Aprovadas ----------------- </td>
			<td><?php echo (isset($num_aprovadas[$ano_busca]) ? $num_aprovadas[$ano_busca] : 0);?></td>
			<td></td>
			<td>Propostas Aprovadas ----------------- </td>
			<td><?php echo $num_aprovadas_estado;?></td>
		</tr>
		<?php else:?>
		<tr style="color: red; font-size: 15px;">
			<td>Propostas Aprovadas ----------------- </td>
			<td><?php echo $num_aprovadas_estado+(isset($num_aprovadas[$ano_busca]) ? $num_aprovadas[$ano_busca] : 0);?></td>
			<td></td>
			<td>Propostas Aprovadas ----------------- </td>
			<td><?php echo (isset($num_aprovadas[$ano_busca]) ? $num_aprovadas[$ano_busca] : 0);?></td>
		</tr>
		<?php endif;?>
	</tbody>
</table>

<br/><br/>

<div style="text-align: center;">
	<h4>Programas Abertos e Emendas disponibilizadas (Atualizado dia <?php echo date('d/m/Y', mktime(0, 0, 0, date('m'), date('d'), date('Y'))); ?>)</h4>
</div>

<table align="center">
	<thead>
		<tr style="color: #428bca; font-size: 16px;">
			<td>Programas Abertos --------------- </td>
			<td><?php echo $programas_vigencia;?></td>
		</tr>
		
		<tr style="color: #428bca; font-size: 16px;">
			<td>Emenda Parlamentar ------------- </td>
			<td><?php echo $programas_vigencia_emenda;?></td>
		</tr>
		
		<tr style="color: #428bca; font-size: 16px;">
			<td>Proponente Específico ------------ </td>
			<td><?php echo $programas_vigencia_especifico;?></td>
		</tr>
		<tr style="color: #428bca; font-size: 16px;">
			<td></td>
			<td></td>
		</tr>
	</thead>
</table>