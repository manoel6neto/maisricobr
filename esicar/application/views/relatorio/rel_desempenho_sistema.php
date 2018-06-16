<div id="content" class="innerAll bg-white">

<h1 class="bg-white content-heading border-bottom">Relatório Desempenho Sistema</h1>
	
<br>

<?php if($dados_rel != null):?>
<form action="rel_atividade_usuario_pef" target="_blank" method="post">
<input type="submit" value="Gerar PDF" class="btn btn-primary">
</form>

<br>

<div class="widget borders-none">
	<div class="widget-body ">
		<div class="panel-group accordion" id="accordion">
		
		</div>
	</div>
	
	<div class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Quantidade de Usuários - <?php echo $dados_rel['num_usuarios']; ?></span></h4>
		</div> 
		
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">CNPJs Vinculados - <?php echo $dados_rel['cnpjs_vinculados']; ?></span></h4>
		</div>
		
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Ministérios Utilizados - <?php echo $dados_rel['ministerios_utilizados']; ?></span></h4>
		</div>
		
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Propostas Cadastradas Pelo Sistema - <?php echo $dados_rel['envi_sistema']; ?></span></h4>
		</div>
		
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Propostas Elaboradas do Zero - <?php echo $dados_rel['elab_zero']; ?></span></h4>
		</div>
		
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Propostas Utilizadas do Banco - <?php echo $dados_rel['util_banco']; ?></span></h4>
		</div>
		
		<?php if($this->session->userdata('nivel') == 1):?>
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Bancos Utilizados - <?php echo $dados_rel['bancos_utilizados']; ?></span></h4>
		</div>
		
		<div class="panel-heading">
			<h4 class="panel-title"><span style="color: #428bca;">Acesso ao Sistema - <?php echo $dados_rel['logaram_sistema']; ?></span></h4>
		</div>
		
		<div class="panel-heading">
			<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-0"><span style="color: #428bca;">Inadimplência - <?php echo count($dados_rel['usuario_bloqueado']); ?></span></a></h4>
		</div>
		<div id="collapse-0" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="innerAll border-bottom tickets">
					<div class="row">
					<?php foreach ($dados_rel['usuario_bloqueado'] as $user):?>
					
						<p style="color: red; font-size:15px;"><?php echo "- ".$user->nome; ?></p>

					<?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>

<?php else:?>
<h1 style="text-align: center;">Nenhum dado encontrado.</h1>
<?php endif;?>

<a class="btn btn-primary" href="<?php echo base_url('index.php/relatorio');?>">Voltar</a>

</div>