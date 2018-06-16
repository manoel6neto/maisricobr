<div class="innerALl col-md-12 col-sm-12">
<?php 
function retorna_mes($nat){
	switch($nat){
		case '01':
			return "Janeiro";
			break;
		case '02':
			return "Fevereiro";
			break;
		case '03':
			return "Março";
			break;
		case '04':
			return "Abril";
			break;
		case '05':
			return "Maio";
			break;
		case '06':
			return "Junho";
			break;
		case '07':
			return "Julho";
			break;
		case '08':
			return "Agosto";
			break;
		case '09':
			return "Setembro";
			break;
		case '10':
			return "Outubro";
			break;
		case '11':
			return "Novembro";
			break;
		case '12':
			return "Dezembro";
			break;

	}
	return " ";
}
if (isset($idTrabalho)) {
if ($idTrabalho->Status_idstatus == 4){ //aceito e esperando alterações
echo "Observações: ".$observacao;
}
}
?>

<?php
$valorRepasse = 0;
$valorContrapartida = 0;
foreach ($cronograma as $lista){
	if($lista->responsavel == "CONVENENTE")
		$valorContrapartida += $lista->parcela;
	else
		$valorRepasse += $lista->parcela;
} 

$corBotao = ((round($proposta->repasse,2)-round($valorRepasse,2) > 0) || (round($proposta->contrapartida_financeira,2)-round($valorContrapartida,2) > 0)) ? "primary" : "success";
?>

<table class="table">
<thead>
<tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr>
</thead>

<tbody id="tbodyrow">
<tr>
<td>CONCEDENTE</td>
<td>Valor Repasse: R$ <?php echo number_format($proposta->repasse, 2, ",", "."); ?></td>
<td style="color: red;">Valor Cadastrado: R$ <?php echo number_format($valorRepasse, 2, ",", "."); ?></td>
<td style="color: green;">Valor a Cadastrar: R$ <?php echo number_format(round($proposta->repasse,2)-round($valorRepasse,2), 2, ",", "."); ?></td>
</tr>

<tr>
<td>CONVENENTE</td>
<td>Valor Contrapartida: R$ <?php echo number_format($proposta->contrapartida_financeira, 2, ",", "."); ?></td>
<td style="color: red;">Valor Cadastrado: R$ <?php echo number_format($valorContrapartida, 2, ",", "."); ?></td>
<td style="color: green;">Valor a Cadastrar: R$ <?php echo number_format(round($proposta->contrapartida_financeira,2)-round($valorContrapartida,2), 2, ",", "."); ?></td>
</tr>


</tbody>
</table>

<h1 class="bg-white content-heading border-bottom" style="color: #428bca; padding-bottom: 10px;">Incluir Desembolso</h1>
<div>
<table class="table">
<thead>
<tr>
<th>Número</th>
<th>Tipo</th>
<th>Mês</th>
<th>Ano</th>
<th>Valor</th>
<th>Status</th>
<th></th>
</tr>
</thead>
<tbody id="tbodyrow">
<?php
$i = 1;
	foreach ($cronograma as $lista){
?>
<tr class="odd">
					<td>
						<div><?php echo $i; ?></div>
					</td>
					<td>
						<div><?php echo $lista->responsavel; ?></div>
					</td>
					<td>
						<div>
            <?php echo retorna_mes($lista->mes); ?>
			</div>
					</td>
					<td>
						<div><?php echo $lista->ano; ?></div>
					</td>
					<td>
						<div>R$ <?php echo number_format($lista->parcela,2,",","."); ?></div>
					</td>
					<td>
						<?php if($trabalho_model->tudo_ok_cronograma($lista->idCronograma, $lista->parcela)): ?>
							<i title="Completo" class="btn-sm btn-success fa fa-check-square"></i>
							<?php $label_botao = "<i class='fa fa-eye'></i> Metas Associadas"; ?>
							<?php $corLabel = 'success';?>
						<?php else: ?>
							<i title="Pendente" class="btn-sm btn-primary fa fa-warning"></i>
							<?php $label_botao = "<i class='fa fa-plus'></i> Associar Meta"; ?>
							<?php $corLabel = 'default';?>
						<?php endif; ?>
					</td>
					<td>
						<div class="pull-right">
							<a class="btn btn-sm btn-default"
								href="<?php echo base_url(); ?>index.php/in/usuario/incluir_parcela_do_cronograma_de_desembolso?cronograma=<?php echo $lista->idCronograma; ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>"><i
								class="fa fa-edit"></i> Editar</a> <a
								class="btn btn-sm btn-<?php echo $corLabel; ?>"
								href="<?php echo base_url(); ?>index.php/in/usuario/incluir_meta_do_cronograma_de_desembolso?cronograma=<?php echo $lista->idCronograma ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>">
								<?php echo $label_botao; ?></a> <a
								class="btn btn-sm btn-primary excluiCrono"
								href="<?php echo base_url(); ?>index.php/in/usuario/excluir_parcela_do_cronograma_de_desembolso?cronograma=<?php echo $lista->idCronograma; ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>"><i
								class="fa fa-trash-o"></i> Excluir</a>
						</div>
					</td>
				</tr>
 <?php
 $i++;
}
?>
</tbody>
		</table>
	</div>
	<a class="btn btn-primary" href="<?php echo base_url().'index.php/in/usuario/listar_metas?id='.$_GET['id'].'&edita_gestor=1';?>">Voltar</a> 
		<a class="btn btn-<?php echo $corBotao; ?>"
		href="<?php echo base_url(); ?>index.php/in/usuario/incluir_parcela_do_cronograma_de_desembolso?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>">Adicionar Desembolso</a> <a
		class="btn btn-primary"
		href="<?php echo base_url(); ?>index.php/in/usuario/listar_obras?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>">Avançar</a>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$(".excluiCrono").click(function(){
		if(confirm("Deseja excluir esse desembolso?"))
			return true;
		return false;
	});
});
</script>