<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>
<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">BANCO De propostas<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader"></h1>
	<div class="bg-white">
		<form method="post" class="form-horizontal">
			<div class="input-group input-lg ">
				<input type="text" class="form-control" placeholder="Pesquisar" name="pesquisar">
				<div class="input-group-btn">
					<button class="btn btn-info" type="submit">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</div>
		</form>
	</div>
	
	<div class="widget borders-none">
		<div class="widget-body ">
		<div class="panel-group accordion" id="accordion">
		<div class="bg-white content-heading" style="color: #428bca; font-weight: bold; font-size: 16px; text-align: center; margin-bottom: 10px;"><?php echo "TOTAL DE PROPOSTAS: ".count($propostas); ?></div>
    <?php if (isset($propostas) AND $propostas != null) { ?>

                <?php
					$i = 1;
					$areanome = "";
					$propostasAux = $propostas;
					foreach ( $propostas as $proposta ) {
						if ($areanome == "" || $areanome !== $proposta->areanome) {
							$qtdArea = 0;
							foreach ( $propostasAux as $p ){
								if ($p->areanome === $proposta->areanome)
									$qtdArea++;
							}
							#echo '<br><h3>'.$proposta->areanome.'</h3><br>';
							?>
							</div></div>
								<div class="panel">
								<div class="panel-heading">
									<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $i;?>"><?php echo $proposta->areanome." <span style='color:red;'>({$qtdArea})</span>";?></a></h4>
							    </div>
							    <div id="collapse-<?php echo $i;?>" class="panel-collapse collapse">
							      	<div class="panel-body">
	      				<?php
							$areanome = $proposta->areanome;
							$i++;
						} else {
							$areanome = $proposta->areanome;
						}
						?>
                
                
                <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading">
				<div class="innerAll border-bottom tickets">
						<div class="row">
                            <div class="col-md-4 pull-right">
							<div class="pull-right">
							<a
								alt="Visualizar Projeto" title="Visualizar Proposta"
								class="btn btn-sm btn-default"
								href="<?php echo base_url(); ?>index.php/in/usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>"><i
								class="fa fa-eye"></i></a>
							
							<?php if($this->session->userdata('nivel') == 1):?>
								<a
									onclick="return confirm('Tem certeza que deseja editar essa proposta?')"
									alt="Editar Projeto" title="Editar Proposta"
									class="btn btn-sm btn-default"
									href="<?php echo base_url(); ?>index.php/in/gestor/informa_valores_programa?edit=1&id=<?php echo $proposta->idProposta; ?>"><i
									class="fa fa-edit"></i></a>
							<?php endif;?>
														
							<?php if($permissoes->utilizar_padrao):?>
								<a
									onclick="return confirm('Tem certeza que deseja utilizar essa proposta?')"
									alt="Utilizar projeto" title="Utilizar Proposta"
									class="btn btn-sm btn-default"
									href="<?php echo base_url(); ?>index.php/in/gestor/escolher_proponente?padrao=1&id=<?php echo $proposta->idProposta; ?>"><i
									class="fa fa-file-text-o"></i></a>
							<?php endif;?>
							
							<?php if($this->session->userdata('nivel') == 1):?> 
								<a
									onclick="return confirm('Tem certeza que deseja excluir essa proposta padrão?')"
									class="btn btn-sm btn-primary"
									href="<?php echo base_url(); ?>index.php/in/gestor/delete_proposta?idProposta=<?php echo $proposta->idProposta; ?>&origem=vbp"><i
									class="fa fa-trash-o"></i></a>
							<?php endif;?>
							</div>
						</div>
						<div class="col-sm-8">
							<ul class="media-list">
								<li class="media">
									<div class="media-body">
										<a
											href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>"
											class="media-heading"><?php echo $proposta->nome; ?></a>
										<div class="clearfix"></div>
										<div class="clearfix pull-left">
											Vigência do Projeto <label class="label label-info" style="font-size: 10px;"><?php
											echo $this->data_model->retornaMesesDiff ( $proposta->data_inicio, $proposta->data_termino );
											?></label>
											<!-- | Data Final <label class="label label-info"><?php echo $proposta->data_termino; ?></label> -->
											| Valor Global <label class="label label-info" style="font-size: 10px;"><?php echo number_format($proposta->valor_global,2,",","."); ?></label>
										</div>
										
										<div class="clearfix"></div>
										<div class="clearfix pull-left">
										<?php if($this->session->userdata('nivel') == 1):?>
										<input type="text" class="atende_a" id="<?php echo $proposta->idProposta; ?>" size="50" value="<?php if(isset($proposta->banco_atende)){echo $proposta->banco_atende;} ?>">
										<?php else:?>
										Atende a: <label style="color: #428bca;"><?php echo $proposta->banco_atende; ?></label>
										<?php endif;?>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</a>
                    <?php
					}
					?>

        <?php
		} // fim if lista
		else {
		?>
        <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
<?php } ?>
            </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#loader").hide();

	$(".atende_a").blur(function(){
		$.ajax({
			url:'<?php echo base_url('index.php/in/gestor/grava_atende_banco_projetos'); ?>',
			type:'post',
			dataType:'html',
			data:{
				banco_atende:$(this).val(),
				idProposta:$(this).attr('id')
			},
			beforeSend:function(){
				$("#loader").slideDown();
			},
			success:function(data){
				$("#loader").slideUp();
			}
		});
	});
});
</script>