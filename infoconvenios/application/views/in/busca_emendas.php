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
	<h1 class="bg-white content-heading border-bottom">Controle de Emendas</h1>
	<div id="form-search">

		<div class="bg-white">

			<div class="panel-group accordion" id="tabAccountAccordion">

				<!-- Opções da pesquisa - Accordion Item -->

				<div id="collapse-1-1" class="panel-collapse collapse">
					<div class="panel-body">

						<form class="form-horizontal" role="form" name="carrega_dados"
							method="post" id="carrega_dados" action="busca_emendas">
							<div class="row">
<!--								<div class="col-md-4">

									<div class="form-group">
										<div class="col-sm-8">
											<div class="checkbox">
												<label class="checkbox-custom"> <i
													class="fa fa-fw fa-square-o"></i> <input type="checkbox"
													onclick='ativa1()' <?php if($filtro == null || isset($filtro['vigencia'])){?>checked="checked"<?php }?> name="vigencia"
													value="vigencia" /> Informar Período
												</label>
											</div>
										</div>

										<label class="col-sm-8 control-label"
											style="text-align: left;">Data de Início</label></br>
										<div class="col-sm-10">
											<input name="data_inicio" id="Data1" maxlength="10" <?php if($filtro == null || isset($filtro['vigencia'])){?>disabled="disabled"<?php }?> class="form-control campos ie" OnKeyUp="mascaraData(this);" value="<?php if(isset($filtro['data_inicio'])){ echo $filtro['data_inicio'];} ?>" />
												 type="text"
												size="11" name="data_inicio"
												title="Entre com a data no formato: DD/MM/AAAA" id="Data1"
												maxlength="10" onkeyup="formatar(this,'##/##/####',event)" 
										</div>

										<label class="col-sm-10 control-label"
											style="text-align: left;">Data Final</label></br>
										<div class="col-sm-10">
											<input name="data_fim" class="form-control ie campos" maxlength="10" <?php if($filtro == null || isset($filtro['vigencia'])){?>disabled="disabled"<?php }?> id="Data2" OnKeyUp="mascaraData(this);" value="<?php if(isset($filtro['data_fim'])){ echo $filtro['data_fim'];} ?>"/>
												 type="text"
												size="11" name="data_fim"
												title="Entre com a data no formato: DD/MM/AAAA" id="Data2"
												maxlength="10" onkeyup="formatar(this,'##/##/####',event)" 
										</div>
									</div>
								</div>-->
								
								
								<div class="col-md-8">
									<div class="form-group col-md-10">
										<label class="col-sm-10 control-label" style="text-align: left;">Nº Emenda (Sem o ano)</label></br>
										<div class="col-sm-4">
											<input type="text" name="num_emenda" class="form-control campos ie" id="num_emenda" value="<?php if(isset($filtro['num_emenda'])){ echo $filtro['num_emenda']; }?>">
										</div>
									
										<label class="col-sm-10 control-label" style="text-align: left;">Nome Proponente</label></br>
										<div class="col-sm-8">
											<input type="text" name="proponente_nome" class="form-control campos ie" id="proponente_nome" value="<?php if(isset($filtro['proponente_nome'])){ echo $filtro['proponente_nome']; }?>">
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
	                                        
	                                        <input class="anos" <?php echo $selected; ?> type="checkbox" name="anos[]" value="<?php echo $ano; ?>" />&nbsp;<span style="color: #428bca; font-size: 14px; margin-right: 10px;"><?php echo $ano; ?></span>
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
				<!-- botão de configuração da pesquisa -->
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
			</div>
			
			<!-- INICIA A LEITURA DAS EMENDAS -->
			
                        <?php if($emendas != null): ?>
                            <?php //if($emendas_propostas != null):?>
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
                            <?php 
                            $i = 0;
                            $j = 0;
                            $numeroEmenda = "";
                            $codigoSiconv = "";
                            $achouProposta = false;
                            $dataEmenda = "";
                            foreach ($emendas as $em) {
                                if($numeroEmenda == "" || $numeroEmenda != $em->emenda) {
                                	$anoEmenda = explode("-", $em->data_inicio_parlam);
                                    echo '<div class="panel-heading">
                                                <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$j.'"><span style="color: red;">'.$em->emenda. '/'. $anoEmenda[0] . '</a></h4>
                                            </div>
                                            <div id="collapse-'.$j.'" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="innerAll border-bottom tickets">
                                                        <div class="row">
                                    ';

                                    echo '<table class="table">';
                                    $numeroEmenda = $em->emenda;
                                    $dataEmenda = substr($em->data_inicio_parlam,0,4);
                                    $j++;
                                }else {
                                	if($dataEmenda == "" || substr($dataEmenda,0,4) != substr($em->data_inicio_parlam,0,4)){
                                		$anoEmenda = explode("-", $em->data_inicio_parlam);
                                		echo '<div class="panel-heading">
                                                <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.($j).'"><span style="color: red;">'.$em->emenda. '/'. $anoEmenda[0] . '</a></h4>
                                            </div>
                                            <div id="collapse-'.($j).'" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="innerAll border-bottom tickets">
                                                        <div class="row">
                                    ';
                                		
                                		echo '<table class="table">';
                                		$j++;
                                	}
                                    $numeroEmenda = $em->emenda;
                                    $dataEmenda = substr($em->data_inicio_parlam,0,4);
                                }
                                
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
                                        echo "<td style='color:red;'>Programa</td><td colspan='7'> <b>". $e->codigo_programa . "</b> - ".(substr($e->nome_programa, 0, 180) . (strlen($e->nome_programa) > 180 ? "..." : ""))."</td>";
                                                    //$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);
                                        echo "</tr>";
                                            //}

                                        echo "<tr><td style='color:red;'>Valor da Emenda</td><td colspan='8'>{$em->valor}</td></tr>";
                                        echo "<tr style='color:#428bca;'><td colspan='9'>Proposta Cadastrada</td></tr>";
                                        echo "<tr>";
                                        echo "<td style='color:red;'>Valor Repasse</td><td>{$e->valor_repasse}</td>";
                                        echo "<td style='color:red;'>Número</td><td><a style='font-size: 12px;' class='label label-info' target='_blank' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$e->id_siconv}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest'>{$e->codigo_siconv}</a></td>";
                                        echo "<td style='color:red;'>Situação</td><td>".$e->situacao."</td>";
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
                                	if($emendas_propostas != null){
	                                    $programas = $programa_model->get_programa_by_codigo($em->codigo_programa);
	                                    foreach ($programas as $programa){
	                                        echo "<tr>";
	                                        echo "<td style='color:red;'>Programa</td><td colspan='7'><b>". $programa->codigo . "</b> - ".(substr($programa->descricao, 0, 180) . (strlen($programa->descricao) > 180 ? "..." : ""))." </td>";
	                                        //$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);
	                                        echo "</tr>";
	                                    }
                                	}
                                    echo "<tr><td style='color:red;'>Valor da Emenda</td><td colspan='8'>{$em->valor}</td></tr>";
                                }
                                echo "<tr><td colspan='9'></td></tr>";
                                
                                if((isset($emendas[$i+1]->emenda) && ($numeroEmenda == "" || $numeroEmenda == $emendas[$i+1]->emenda)) && (isset($emendas[$i+1]->data_inicio_parlam) && ($dataEmenda == "" || substr($dataEmenda,0,4) == substr($emendas[$i+1]->data_inicio_parlam,0,4)))) {

                                } else {
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
                            <?php //endif;?>
                        <?php endif;?>
			
			
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

	$("#gerarPdf").click(function(){
		$(".campos").each(function(){
			$(this).attr('form', 'gera_pdf');
		});
	});

	$("#busca_dados").click(function(){
		$(".campos").each(function(){
			$(this).attr('form', 'carrega_dados');
		});
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