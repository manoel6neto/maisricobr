<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>

<?php

define("LATIN1_UC_CHARS", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ");
define("LATIN1_LC_CHARS", "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý");

function uc_latin1 ($str) {
    $str = strtoupper(strtr($str, LATIN1_LC_CHARS, LATIN1_UC_CHARS));
    return strtr($str, array("ß" => "SS"));
}


$permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));
?>
<style>
.panel-heading {
	margin: 10px;
}

.panel-heading:hover {
	background-color: #DDD !important;
}

#form-search {
	z-index: 1000;
	display: block;
	position: relative;
}
.codigo{font-size:small; float:right;}
.media{width: 100%;}
</style>

<script type="text/javascript">
function mascaraData(campoData){
    var data = campoData.value;
    if (data.length == 2){
        data = data + '/';
        campoData.value = data;
		return true;              
    }
    if (data.length == 5){
        data = data + '/';
        campoData.value = data;
        return true;
    }
}
</script>

<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">Controle de Emendas - Ano <?php echo date("Y"); ?></h1>
	<div id="form-search">

		<div class="bg-white">

			<!-- <div class="panel-group accordion" id="tabAccountAccordion">


				<div id="collapse-1-1" class="panel-collapse collapse">
					<div class="panel-body">

						<form class="form-horizontal" role="form" name="carrega_dados"
							method="post" id="carrega_dados" action="busca_emendas_geral">
							<div class="row">

								
								
								<div class="col-md-8">
									<div class="form-group col-md-10">
										<label class="col-sm-10 control-label" style="text-align: left;">Nº Emenda (Sem o ano)</label></br>
										<div class="col-sm-4">
											<input type="text" name="num_emenda" class="form-control campos ie" id="num_emenda" value="<?php if(isset($filtro['num_emenda'])){ echo $filtro['num_emenda']; }?>">
										</div>
									
										<label class="col-sm-10 control-label" style="text-align: left;">Nome Proponente</label></br>
										<div class="col-sm-8">
											
											<?php echo form_dropdown('proponente[]', $proponentes, '', "id='proponente' class='form-control'  multiple='multiple' style='display: none;'"); ?>
										</div>
										
										<label class="col-sm-10 control-label" style="text-align: left;">Esfera Administrativa</label></br>
										<div class="col-sm-8">
											
											<?php echo form_dropdown('esfera[]', $programa_model->get_esferas_by_emendas(), '', "id='esfera' class='form-control'  multiple='multiple' style='display: none;'"); ?>
										</div>
                                                                                
                                        <label class="col-sm-12 control-label" style="text-align: left;">Selecionar Anos</label></br>
                                        <div class="col-sm-12">
                                        	<input class="selecionarTodos" type="checkbox" name="anos[]" value="TODOS" />&nbsp;<span style="color: #428bca; font-size: 14px; margin-right: 10px;">Todos os anos</span>
	                                        <?php foreach ($anos as $ano): ?>
	                                        
	                                        <?php 
	                                        if(($ano == date("Y") && $filtro == null) || ($filtro != null && in_array($ano, $filtro['anos'])))
	                                        	$selected = "checked";
	                                        else
	                                        	$selected = "";
	                                        ?>
	                                        
	                                        <input class="anos" type="checkbox" <?php echo $selected;?> name="anos[]" value="<?php echo $ano; ?>" />&nbsp;<span style="color: #428bca; font-size: 14px; margin-right: 10px;"><?php echo $ano; ?></span>
	                                        <?php endforeach; ?>
                                        </div>
									</div>
								</div>
							</div>

							<div class="" align="center">
								<button class="btn btn-primary btnPesquisa" id="busca_dados" 
                                                                        type="submit" form="carrega_dados">
									<i class="fa fa-search"></i>&nbsp;&nbsp;Buscar
								</button>
							</div>
							
						</form>
					</div>
				</div>
				
				<a class="accordion-toggle" data-toggle="collapse"
					style="text-decoration: none;" data-parent="#tabAccountAccordion"
					href="#collapse-1-1" id="btnPesqAvancada">
					<div class="panel-heading">
						<h4 class="panel-title" style="text-align: center;">
							<button class="btn btn-circle btn-info">
								<i class="fa fa-arrow-down" id="icon-pesq-avancada"> </i>
							</button>
							<br/><span id="info-pesq-avancada" style="color: red;">Abrir Pesquisa Avançada</span>
						</h4>
					</div>
				</a>
			</div> -->
			<!-- INICIA A LEITURA DAS EMENDAS -->
			<?php if($proponentes_emendas != null && $emendas != null){ ?>
			<form action="rel_emendas_pdf" target="_blank" name="gera_pdf" id="gera_pdf" method="post">
                                <input type="submit" value="Gerar PDF" id="gerarPdf"  style="margin-left: 25px;" class="btn btn-primary">
                            </form>

                            <br>
			<div class="widget borders-none">
                                    <div class="widget-body ">
                                            <div class="panel-group accordion" id="accordion">

                                            </div>
                                    </div>

                                    <div class="panel">
                                    <?php //var_dump($emendas);?>
				<?php
				$z = 0; 
				$i = 0;
				$j = 0;
				$numeroEmenda = "";
				$codigoSiconv = "";
				$achouProposta = false;
				foreach ($proponentes_emendas as $cod=>$p_emendas){
					echo '<div class="panel-heading">
							<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$p_emendas[0].'"><span style="color: black;">'.$cod. '</a></h4>
                          </div>
                          <div id="collapse-'.$p_emendas[0].'" class="panel-collapse collapse">
                          	<div class="panel-body">
                            	<div class="innerAll border-bottom tickets">
                                	<div class="row">';
				?>
			<!-- INICIO DAS EMENDAS -->
                       
                       <?php if($emendas != null){ ?>
                        
							<div class="widget borders-none">
									<div class="widget-body ">
											<div class="panel-group accordion" id="accordion-1">
						
											</div>
									</div>
						
									<div class="panel">
							<?php 
							
							$numeroEmenda = "";
							foreach ($emendas as $em) {
								if($em->cnpj == $p_emendas[1]){
									if(($numeroEmenda == "" || $numeroEmenda != $em->emenda)) {
										$anoEmenda = explode("-", $em->data_inicio_parlam);
										echo '<div class="panel-heading">
													<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-1" href="#collapse-'.$p_emendas[0].'-'.$j.'"><span style="color: red;">'.$em->emenda. '/'. $anoEmenda[0] . ' - '. $usuariomodel->get_parlamentar_by_code(substr($em->emenda, 0, 4), $em->parlamentar). '</a></h4>
												</div>
												<div id="collapse-'.$p_emendas[0].'-'.$j.'" class="panel-collapse collapse">
													<div class="panel-body">
														<div class="innerAll border-bottom tickets">
															<div class="row">
										';
										$abriuTable = true;
						
										echo '<table class="table">';
										$numeroEmenda = $em->emenda;
										$j++;
									}else {
										$abriuTable = false;
										$numeroEmenda = $em->emenda;
									}
									
									/**********INICIO DAS PROPOSTAS************/
									if ($abriuTable)
										echo "<tr style='background-color:#DCDCDC;'><td style='color:red;'>Proponente</td><td style='color:#428bca;' colspan='8'>".$em->nome."</td></tr>";
									$achouProposta = false;
									foreach ($emendas_propostas as $e){
										$qtd = 0;
										if ($em->emenda == $e->codigo_emenda && $em->codigo_programa == $e->codigo_programa && str_replace("-", "", str_replace("/", "", str_replace(".", "", $em->cnpj))) == $e->proponente) {
											$achouProposta = true;
											//if($codigoSiconv == "" || $codigoSiconv != $e->codigo_siconv){
												//$codigoSiconv = $e->codigo_siconv;
						
												//$programas = $programa_banco_proposta_model->get_programas_by_proposta($e->id_proposta);
												//foreach ($programas as $programa){
											echo "<tr>";
											echo "<td style='color:red;'>Programa</td><td colspan='7'><a style='color:black;' target='_blank' href='{$programa_model->get_programa_by_codigo($e->codigo_programa, true)->link}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest'><b>". $e->codigo_programa . "</b> - ".(substr($e->nome_programa, 0, 180) . (strlen($e->nome_programa) > 180 ? "..." : "")). "</a></td>";
														//$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);
											echo "</tr>";
												//}
						
											echo "<tr><td style='color:red;'>Valor da Emenda</td><td colspan='8'>{$em->valor}</td></tr>";
											echo "<tr style='color:#428bca;'><td colspan='9'>Proposta Cadastrada</td></tr>";
											echo "<tr>";
											echo "<td style='color:red;'>Valor Repasse</td><td>{$e->valor_repasse}</td>";
											echo "<td style='color:red;'>Número</td><td><a style='font-size: 12px;' class='label label-info' target='_blank' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$e->id_siconv}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest'>{$e->codigo_siconv}</a></td>";
											echo "<td style='color:red;'>Situação</td><td>".$e->situacao_proposta."</td>";
											echo "<td style='color:blue; font-size: 14px;'><a href='".base_url("index.php/in/dados_siconv/detalha_propostas_pareceres?id={$e->id_proposta}")."' target='_blank'>Detalhar Proposta</a></td>";
											echo "</tr>";
											
											//Get valor a utilizar
											$valor_emenda = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $em->valor)));
											$valor_repasse = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $e->valor_repasse)));
						
											//Valor a utilizar é pegar todas os programas que usam a proposta e pegar o repasse
											echo "<tr><td style='color:red;'>Valor a Utilizar</td><td colspan='8'>R$ ".number_format(($valor_emenda-$valor_repasse), 2, ",", ".")."</td></tr>";
										}
									}
									if (!$achouProposta) {
										if($abriuTable){
											$programas = $programa_model->get_programa_by_codigo($em->codigo_programa);
											foreach ($programas as $programa){
												echo "<tr>";
												echo "<td style='color:red;'>Programa</td><td colspan='7'><a style='color:black;' target='_blank' href='{$programa->link}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest'><b>". $programa->codigo . "</b> - ".(substr($programa->nome, 0, 180) . (strlen($programa->nome) > 180 ? "..." : ""))."</a></td>";
												//$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);
												echo "</tr>";
											}
										}
										if ($abriuTable)
											echo "<tr><td style='color:red;'>Valor da Emenda</td><td colspan='8'>{$em->valor}</td></tr>";
									}
									echo "<tr><td colspan='9'></td></tr>";
									
									/**********INICIO DAS PROPOSTAS************/
									
									if(isset($emendas[$i+1]->emenda) && ($numeroEmenda == "" || $numeroEmenda == $emendas[$i+1]->emenda)) {
						
									} else {
										if ($abriuTable) {
											echo "</table>
													</div>
												</div>
											</div>
										  </div>";
										}
									}
									
								}
								$i++;
							}
							?>
						
									</div>
							</div>
						
						<?php }else{?>
							<h1 style="text-align: center;">Nenhum dado encontrado.</h1>
							
						<?php }?>
                        
            <!-- FIM DAS EMENDAS -->
                        <?php echo "</div>
                                 </div>
                              </div>
                           </div>";
                            ?>
                        
                       <?php }?>
			
			 			</div>
                 </div>
			
			<?php }else{?>
			<h1 style="text-align: center;">Nenhum dado encontrado.</h1>
			<?php }?>
			<!-- FINALIZA A LEITURA DAS EMENDAS -->
		</div>

	</div>
</div>

<script>
function ativa1(){
	var data2 = document.getElementById("Data2");
	var data1 = document.getElementById("Data1");
	if (data2.disabled == true){ data2.disabled = false; data1.disabled = false; }
	else { data2.disabled = true; data1.disabled = true; }
}

$(document).ready(function() {
	var pesqAvancadaAberta = false;
	
	$("#btnPesqAvancada").click(function(){
		if(!pesqAvancadaAberta){
			$("#info-pesq-avancada").html("Fechar Pesquisa Avançada");
			$("#icon-pesq-avancada").attr("class", "fa fa-arrow-up");
		}else{
			$("#info-pesq-avancada").html("Abrir Pesquisa Avançada");
			$("#icon-pesq-avancada").attr("class", "fa fa-arrow-down");
		}

		pesqAvancadaAberta = !pesqAvancadaAberta;
	});

	$("#proponente").multiselect({
		nonSelectedText:"Escolha um Proponente",
		numberDisplayed: 0,
		nSelectedText: "Selecionados",
		buttonClass: "form-control",
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		allSelectedText: "Todos Selecionados",
		includeSelectAllOption: true,
		selectAllText: "Selecionar Todos"
	});

	$("#esfera").multiselect({
		nonSelectedText:"Escolha uma Esfera",
		numberDisplayed: 0,
		nSelectedText: "Selecionados",
		buttonClass: "form-control",
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		allSelectedText: "Todos Selecionados",
		includeSelectAllOption: true,
		selectAllText: "Selecionar Todos"
	});

	$("#gerarPdf").click(function(){
		$(".campos").each(function(){
			$(this).attr('form', 'gera_pdf');
		});
	});

	$("#busca_dados").click(function(){
		var podePesquisar = false;
		
		$(".campos").each(function(){
			$(this).attr('form', 'carrega_dados');
		});

		$(".anos").each(function(){
			if($(this).is(":checked"))
				podePesquisar = true;
		});

		if(!podePesquisar){
			alert("Informe ao menos um ano para a pesquisa.");
			return false;
		}
	});

	$(".campos").keydown(function(e){
		var tecla = (e.keyCode?e.keyCode:e.which);

		if(tecla == 13){
			$(".campos").each(function(){
				$(this).attr('form', 'carrega_dados');
			});
		}
	});

	$(".selecionarTodos").click(function(){
		$(".anos").each(function(){
			if($(".selecionarTodos").is(":checked"))
				$(this).attr("checked", $(".selecionarTodos").is(":checked"));
			else{
				if($(this).val() != "<?php echo date("Y"); ?>")
					$(this).attr("checked", $(".selecionarTodos").is(":checked"));
			}
		});
	});

	$(".anos").click(function(){
		if(!$(this).is(":checked")){
			if($(".selecionarTodos").is(":checked"))
				$(".selecionarTodos").attr("checked", false);
		}
	});
});
</script>