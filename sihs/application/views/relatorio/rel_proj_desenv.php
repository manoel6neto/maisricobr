<div id="content" class="innerAll bg-white">

<h1 class="bg-white content-heading border-bottom">Relatório de Propostas Desenvolvidas</h1>
	
<br>

<?php if($dados_rel != null):?>
<form action="rel_proj_desenv_pdf" target="_blank" method="post">
<input type="submit" value="Gerar PDF" class="btn btn-primary">
</form>

<br>

<div class="widget borders-none">
	<div class="widget-body ">
		<div class="panel-group accordion" id="accordion">
		
		</div>
	</div>
	
	<div class="panel">
<?php 
$titulo = "";
$i = 0;
$j = 0;
foreach ($dados_rel as $dados){
	$qtd = 0;
	
	foreach ($dados_rel as $d){
		if($d->TITULO == $dados->TITULO)
			$qtd++;
	}
	
	if($titulo == "" || $titulo != $dados->TITULO){
		echo '<div class="panel-heading">
				<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$j.'"><span style="color: red;">'.$dados->TITULO. ' <span style="color: #428bca;">('.$qtd.')</span></span></a></h4>
			</div>
		<div id="collapse-'.$j.'" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="innerAll border-bottom tickets">
					<div class="row">
		';
		
		echo '<table class="table">';
		$titulo = $dados->TITULO;
		$j++;
	}else
		$titulo = $dados->TITULO;
	
	$listaProgramas = "";
		
	$programas = $programa_proposta_model->get_programas_by_proposta($dados->idProposta);
	foreach ($programas as $p)
		$listaProgramas .= "- ".substr($p->nome_programa, 0, 180) . (strlen($p->nome_programa) > 180 ? "..." : ""). "<br>";
	
	$labelDataEnvio = "";
	if($dados->TITULO == "Projetos Enviados Pelo Sistema")
		$labelDataEnvio = "Data Envio";
	
	echo "<tr style='background-color:#DCDCDC;'><td></td><td style='color:red;'>Responsável</td><td style='color:#428bca;'>".$usuariomodel->get_by_id($dados->idGestor)->nome."</td><td>Data Criação</td><td>".implode("/", array_reverse(explode("-", $dados->data)))."</td><td>{$labelDataEnvio}</td><td>".implode("/", array_reverse(explode("-", $dados->data_envio)))."</td></tr>";
	echo "<tr><td></td><td>Município</td><td colspan='6'>ITABUNA</td></tr>";
	echo "<tr><td></td><td>Área</td><td colspan='6'>{$dados->areanome}</td></tr>";
	echo "<tr><td></td><td>Nome do Projeto</td><td colspan='6'>{$dados->nome}</td></tr>";
	echo "<tr><td></td><td>Programa</td><td colspan='6'>{$listaProgramas}</td></tr>";
	echo "<tr><td></td><td>Valor</td><td>".number_format($dados->valor_global, 2, ",", ".")."</td><td>Data Inicio</td><td>".implode("/", array_reverse(explode("-", $dados->data_inicio)))."</td><td>Data Fim</td><td>".implode("/", array_reverse(explode("-", $dados->data_termino)))."</td></tr>";
	
	echo "<tr><td colspan='7'></td></tr>";
	
	if(isset($dados_rel[$i+1]->TITULO) && ($titulo == "" || $titulo == $dados_rel[$i+1]->TITULO)){
	
	}else{
		echo "</table>
					</div>
				</div>
			</div>
		</div>";
	}
	
	$i++;
}
?>

	</div>
</div>

<?php else:?>
<h1 style="text-align: center;">Nenhum dado encontrado.</h1>
<?php endif;?>

<a class="btn btn-primary" href="<?php echo base_url('index.php/relatorio');?>">Voltar</a>

</div>