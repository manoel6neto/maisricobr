<div id="content" class="innerAll bg-white">

<?php if($this->session->userdata('nivel') == 1):?>
<h1 class="bg-white content-heading border-bottom">Relatório de visitas Por representante</h1>
<?php else:?>
<h1 class="bg-white content-heading border-bottom">Relatório de visitas Realizadas</h1>
<?php endif;?>
	
<br>

<?php if($dados_rel != null):?>
<form action="rel_visita_representante_pdf" target="_blank" method="post">
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
		if($d->nome == $dados->nome)
			$qtd++;
	}
	
	if($titulo == "" || $titulo != $dados->nome){
		echo '<div class="panel-heading">
				<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$j.'"><span style="color: red;">'.$dados->nome. ' <span style="color: #428bca;">('.$qtd.')</span></span></a></h4>
			</div>
		<div id="collapse-'.$j.'" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="innerAll border-bottom tickets">
					<div class="row">
		';
		
		echo '<table class="table">';
		$titulo = $dados->nome;
		$j++;
	}else
		$titulo = $dados->nome;
	
	echo "<tr style='background-color:#DCDCDC;'>
				<td colspan='2'><b>Município:</b> {$dados->municipio} / {$dados->municipio_uf_sigla}</td>
				<td><b>Contato:</b> {$dados->nome_contato}</td>
				<td><b>Email:</b> {$dados->email_contato}</td>
				<td><b>Telefones:</b> ".$contato_municipio_model->formataCelular($dados->telefone_contato)." / ".$contato_municipio_model->formataCelular($dados->celular_contato)." / ".$contato_municipio_model->formataCelular($dados->comercial_contato)."</td>
		</tr>";
	
	$historico_contato = $historico_contato_municipio_model->get_all_historico($dados->id_contato_municipio);
	
	echo "<tr><td colspan='5'><b>Histórico da Visita</b></td></tr>";
	
	foreach ($historico_contato as $historico){
		echo "<tr>
				<td><b>Status:</b> {$contato_municipio_model->getStatusContato($historico->status_contato)}</td>
				<td><b>Data da Visita:</b> ".implode("/", array_reverse(explode("-", $historico->data_visita)))."</td>
				<td><b>Data do Retorno:</b> ".implode("/", array_reverse(explode("-", $historico->data_retorno)))."</td>
				<td><b>Classificação:</b> {$historico_contato_municipio_model->getClassVisita($historico->class_contato)}</td>
				<td style='width:50%;'><b>Obs Gerais:</b> {$historico->obs_gerais}</td>
			</tr>";
	}
	
	echo "<tr><td colspan='5'></td></tr>";
	
	if(isset($dados_rel[$i+1]->nome) && ($titulo == "" || $titulo == $dados_rel[$i+1]->nome)){
	
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

<?php if($this->session->userdata('nivel') == 4):?>
<a class="btn btn-primary" href="<?php echo base_url('index.php/controle_usuarios/area_vendedor');?>">Voltar</a>
<?php else:?>
<a class="btn btn-primary" href="<?php echo base_url('index.php/relatorio');?>">Voltar</a>
<?php endif;?>
</div>