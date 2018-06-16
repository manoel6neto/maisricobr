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

.ui-dialog { z-index: 10000 !important ;}

#form-search {
	z-index: 100;
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
	<h1 class="bg-white content-heading border-bottom">Busca de Programas&nbsp;<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader"></h1>
	<div id="form-search">

		<div class="bg-white">
			<div class="panel-group accordion" id="tabAccountAccordion">

				<!-- Opções da pesquisa - Accordion Item -->

				<div id="collapse-1-1" class="panel-collapse collapse">
					<div class="panel-body">

						<form class="form-horizontal" role="form" name="carrega_dados"
							method="post" id="carrega_dados" action="busca_programas">
							<div class="row">
								<div class="col-md-4">

									<div class="form-group">
										<div class="col-sm-8">
                                                                                    <div class="checkbox">
                                                                                        <label class="checkbox-custom"> <i
                                                                                                class="fa fa-fw fa-square-o"></i> <input type="checkbox"
                                                                                                onclick='ativa1()' <?php if($filtro == null || isset($filtro['vigencia'])){?>checked="checked"<?php }?> name="vigencia"
                                                                                                value="vigencia" id="vigencia" /> Programas em vigência
                                                                                        </label>

                                                                                        
                                                                                    </div>
										</div>
                                                                            
<!--                                                                                <div class="col-sm-8">
                                                                                    <div class="checkbox">
                                                                                        <label class="checkbox-custom"> <i class="fa fa-fw fa-square-o"></i> 
                                                                                            <input type="checkbox" id="disponibilidade" name="disponibilidade" 
                                                                                            <?php if($filtro == null || isset($filtro['disponibilidade'])){?>checked="checked"<?php }?>
                                                                                            value="disponibilidade" /> Considerar Disponibilidade
                                                                                        </label>
                                                                                    </div>
                                                                                </div>-->

										<label class="col-sm-8 control-label"
											style="text-align: left;">Data de Início</label></br>
										<div class="col-sm-10">
											<input name="data_inicio" id="Data1" maxlength="10" <?php if($filtro == null || isset($filtro['vigencia'])){?>disabled="disabled"<?php }?> class="form-control ie" OnKeyUp="mascaraData(this);" value="<?php if(isset($filtro['data_inicio'])){ echo $filtro['data_inicio'];} ?>" />
												<!-- type="text"
												size="11" name="data_inicio"
												title="Entre com a data no formato: DD/MM/AAAA" id="Data1"
												maxlength="10" onkeyup="formatar(this,'##/##/####',event)" -->
										</div>

										<label class="col-sm-10 control-label"
											style="text-align: left;">Data Final</label></br>
										<div class="col-sm-10">
											<input name="data_fim" class="form-control ie" maxlength="10" <?php if($filtro == null || isset($filtro['vigencia'])){?>disabled="disabled"<?php }?> id="Data2" OnKeyUp="mascaraData(this);" value="<?php if(isset($filtro['data_fim'])){ echo $filtro['data_fim'];} ?>"/>
												<!-- type="text"
												size="11" name="data_fim"
												title="Entre com a data no formato: DD/MM/AAAA" id="Data2"
												maxlength="10" onkeyup="formatar(this,'##/##/####',event)" -->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group col-md-10">
										<label for="cod_estados" style="color: red;">Qualificação da Proposta:</label> 
										<label class="checkbox"> 
										<input type="checkbox"
											id="prop1voluntaria" disabled="true" class="obrigaQualificacao" name="qualificacao[]" <?php if(($filtro == null || in_array("Proposta Voluntária", $filtro['qualificacao'])) || ($usuario_parlamentar)){?>checked="checked"<?php }?>
											value="Proposta	Voluntária" 
											onclick="selecionarCima('prop')"> 
											<b>Proposta	Voluntária</b>
										</label> 
											<!-- 
											<label for="emenda" style="color: red;">Buscar Emendas:</label>
											<div class="">
												<?php
												if(!empty($cnpjs))
													echo "<input type='checkbox' class='cnpj_todos'> <span style='color:#428bca;'><b>Selecionar Todos</b></span>"; 
												?>
												<?php $lista_cnpjs = array();?>
												<?php foreach ($cnpjs as $cnpj){?>
												
													<label> 
													<input type="checkbox" class="cnpj"
														name="cnpj[]" <?php if(isset($filtro['cnpj']) && in_array($cnpj->cnpj, $filtro['cnpj'])){?>checked="checked"<?php }?>
														value="<?php echo $cnpj->cnpj;?>"> 
														<?php $lista_cnpjs[] = $cnpj->cnpj;?>
														<b><?php echo $programa_model->formatCPFCNPJ($cnpj->cnpj) ." - ". (isset($cnpj_siconv->get_cidade_by_cnpj_siconv($cnpj->cnpj)->Nome) ? $cnpj_siconv->get_cidade_by_cnpj_siconv($cnpj->cnpj)->Nome." - " : "").$cnpj->cnpj_instituicao; ?></b>
													</label> 
												<?php } ?>
												
												<?php if($this->session->userdata('nivel') == 1): ?>
													<label class="checkbox">
													<input type="text" class="cnpj" name="cnpj[]" id="num_cnpj" value="<?php if(isset($filtro['cnpj'])){ echo $filtro['cnpj'][0]; } ?>">
													</label>
												<?php endif; ?>
											</div>
											 -->
									</div>
										
									
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="cod_estados" style="color: red;">Qualificação do Proponente:</label>
										
                                                                                <?php if($lista_entidades['municipal']): ?>
                                                                                    <label class="checkbox"> <input type="checkbox"
                                                                                            class="obrigaAtende" disabled="true" id="prop1municipal" name="atende[]" <?php if(($usuario_parlamentar && !isset($filtro['atende'])) || (isset($lista_entidades) && $lista_entidades['municipal'] && !isset($filtro['atende'])) || (isset($filtro['atende']) && in_array("Administração Pública Municipal", $filtro['atende']))){?>checked="checked"<?php }?>
                                                                                             value="Administração Pública Municipal" > 
                                                                                            <b>Municipal</b>
                                                                                    </label> 
                                                                                <?php endif; ?>
										
                                                                                <?php if($lista_entidades['estadual']): ?>
                                                                                    <label class="checkbox"> <input type="checkbox"
                                                                                            class="obrigaAtende" disabled="true" id="prop1estadual" name="atende[]" <?php if(($usuario_parlamentar && !isset($filtro['atende'])) || (isset($lista_entidades) && $lista_entidades['estadual'] && !isset($filtro['atende'])) || (isset($filtro['atende']) && in_array("Administração Pública Estadual ou do Distrito Federal", $filtro['atende']))){?>checked="checked"<?php }?>
                                                                                            value="Administração Pública Estadual ou do Distrito Federal" > 
                                                                                            <b>Estadual</b>
                                                                                    </label> 
                                                                                <?php endif; ?>
                                                                                
                                                                                <?php if($lista_entidades['privada']): ?> 
                                                                                    <label class="checkbox"> <input type="checkbox"
                                                                                            class="obrigaAtende" disabled="true" id="prop1entidade" name="atende[]" <?php if(($usuario_parlamentar && !isset($filtro['atende'])) || (isset($lista_entidades) && $lista_entidades['privada'] && !isset($filtro['atende'])) || (isset($filtro['atende']) && in_array("Entidade Privada sem fins lucrativos", $filtro['atende']))){?>checked="checked"<?php }?>
                                                                                            value="Entidade Privada sem fins lucrativos" > 
                                                                                            <b>Entidade Privada</b>
                                                                                    </label> 
                                                                                <?php endif; ?>
										
                                                                                <?php if($lista_entidades['eco_mista']): ?>
                                                                                    <label class="checkbox"> <input type="checkbox"
                                                                                            class="obrigaAtende" disabled="true" id="prop1entidade" name="atende[]" <?php if(($usuario_parlamentar && !isset($filtro['atende'])) || (isset($lista_entidades) && $lista_entidades['eco_mista'] && !isset($filtro['atende'])) || (isset($filtro['atende']) && in_array("Empresa pública/Sociedade de economia mista", $filtro['atende']))){?>checked="checked"<?php }?>
                                                                                            value="Empresa pública/Sociedade de economia mista" > 
                                                                                            <b>Empresa pública/Sociedade de economia mista</b>
                                                                                    </label>
                                                                                <?php endif; ?>
									
                                                                                <?php if($lista_entidades['consorcio']): ?>
                                                                                    <label class="checkbox"> <input type="checkbox"
                                                                                            class="obrigaAtende" disabled="true" id="prop1consorcio" name="atende[]" <?php if(($usuario_parlamentar && !isset($filtro['atende'])) || (isset($lista_entidades) && $lista_entidades['consorcio'] && !isset($filtro['atende'])) || (isset($filtro['atende']) && in_array("Consórcio Público", $filtro['atende']))){?>checked="checked"<?php }?>
                                                                                             value="Consórcio Público"> 
                                                                                            <b>Consórcio</b>
                                                                                    </label>
                                                                                <?php endif; ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-12">
									
										<input type="checkbox" name="mostra_ocultos" value="1" <?php if(isset($filtro['mostra_ocultos']) && $filtro['mostra_ocultos'] == 1){ echo "checked='checked'";}?>> 
											<b>Exibir Programas Ocultos</b>
									
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
									<label for="cod_estados">Orgão Concedente:</label> <select id="select2_1"
										style="width: 100%;" name="orgao">
										<option value="">Escolha</option>
											<?php
											foreach ( $orgaos as $orgao ) {
												if(isset($filtro['orgao']) && $filtro['orgao'] == $orgao ['orgao'])
													$selected = "selected";
												else
													$selected = "";
												echo '<option value="' . $orgao ['orgao'] . '" '.$selected.'>' . uc_latin1 ( $orgao ['orgao'] ) . '</option>';
											}
											?>
										</select>
								</div>
							</div>
							
							<div class="" align="center">
								<button class="btn btn-primary btnPesquisa" type="submit" form="carrega_dados">
									<i class="fa fa-search"></i>&nbsp;&nbsp;Buscar
								</button>
							</div>
							
						</form>
					</div>
				</div>

				<div class="input-group input-lg ">
					<input name="pesquisa" type="text" class="form-control"
						placeholder="Pesquisar" form="carrega_dados"
						<?php if(isset($pesquisa)){echo "value=\"{$pesquisa}\"";}?> />
					<div class="input-group-btn">
						<button class="btn btn-info btnPesquisa" type="submit" form="carrega_dados">
							<i class="fa fa-search"></i>
						</button>
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

		</div>

	</div>

	<input type="hidden" id="oculta_prog_pdf" name="oculta_prog_pdf" value="0">
	
	<?php if(isset($lista) AND $lista!=null){ ?>
	<h1 class="bg-white" style="text-align: right;">
		
		<form method="post" id="gera_pdf" action="<?php echo base_url();?>index.php/in/dados_siconv/gerapdf_lista"  target="_blank">
		<?php if($permissoes->relatorio_programa):?>
		<input type="submit" class="btn btn-primary" id="gerarPdf" style="float: left;" value="Gerar PDF"/>
		
		<input type="submit" class="btn btn-success" id="enviaPdf" style="float: left; margin-left: 8px;" value="Enviar Por Email"/>
		
		<!-- <input type="submit" class="btn btn-info" id="bloqProg" style="float: left; margin-left: 8px;" value="Bloquear Programas"/> -->
		<?php endif;?>
		</form>
		<span>EXIBIDOS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_rows; ?></span> TOTAL: <?php echo "<span style='font-size: 22px;' class='num_prog'>".$total_rows."</span>"; if(isset($pesquisa)&& $pesquisa!=""){echo "Pesquisa por: {$pesquisa}";}?></span>
		<input type="hidden" id="num_prog" value="<?php echo $num_rows; ?>">
	</h1>
	
	<h1 class="bg-white" style="text-align: left;">
		<p><input type="checkbox" class="selecionarTodos">&nbsp;<span style="color: #428bca; font-size: 14px;">Selecionar Todos</span></p>
	</h1>
	
	<div class="widget borders-none">
		<div class="widget-body ">
		
		<?php 
	
		$i=1; 
		$xyz = 0;
		foreach($lista as $programa){  
			?>
			
			<div class="innerAll border-bottom tickets" id="<?php echo $programa->codigo."_ocultar";?>">
					<div class="row">
						<div class="col-sm-12">
							<div class="pull-left">
								<label class="label label-primary codigo"><?php echo $programa->codigo;?></label>
							</div>
							<br><br>
							<ul class="media-list">
								<li class="media">
									<div class="pull-left">
										<div class="center">
											<div class="checkbox">
																
												<input form="gera_pdf" class="checkboxInput" type="checkbox" name="ides[]" value="<?php echo $programa->codigo?>"/>
											
											</div>
										</div>
									</div>
	
									<div class="media-body">
										<?php 
										if(isset($programa->codigo_beneficiario)){
											$dados_emenda = $programa_model->get_dados_beneficiario($programa->codigo_beneficiario, $filtro['cnpj'], true);
											foreach ($dados_emenda as $d){
												echo "<br><label class=\"label label-info\">Emenda:</label> ".$d->emenda_cnpj ."|". $d->emenda_nome ."|". $d->emenda_valor;
												
												if(isset($d->emenda) && $d->emenda != "")
													echo "<br><label class=\"label label-info\">Parlementar:</label> ".$programa_model->get_parlamentar_by_emenda($d->emenda) ."|". $d->emenda;
												
												echo "<br>";
											}
											
											echo  "<hr>";
										}
										?>
	
										<a title="Abrir os detalhes do programa no SICONV" href="<?php echo $programa->link."&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest";?>" target="_blank" class="media-heading"> <?php echo $programa->nome;?></a>
										<div class="clearfix"></div>
										<?php echo $programa->descricao;?>
										<br><br>
										Orgão <label class="label label-info"><?php echo $programa->orgao;?></label> <?php if($this->session->userdata('nivel') != 1){ ?><label class="label label-success oculta_programa" title="Clique para ocultar este programa da relação. &#13;Para exibí-lo novamente, utilize a busca avançada." id="<?php echo $programa->codigo?>" style="float: right; cursor: pointer;">Ocultar Programa da Lista</label><?php } ?>
										<br>
										<b>Qualificação do Proposta:</b> <?php echo $programa->qualificacao;?>
										<br>
										<b>Qualificação da Proponente:</b> <?php echo $programa->atende;?>
										<br>
										<b>Estados Atendidos:</b> <?php echo $programa->estados;?>
										<br><br>
										
										<?php if(isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0):?>
											<span style="color: red;">Proposta Voluntária</span><br>
                                                                                        <?php if(isset($programa->data_disp) && strtotime($programa->data_disp) > 0):?>
                                                                                        Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp)));?></label> | 
                                                                                        <?php endif;?>
											Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio)));?></label>
											| Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim)));?></label>
											<br>
										<?php endif;?>
										
										<?php 
										if(!empty($lista_cnpjs)){
											$propostas = $banco_proposta_model->get_propostas_proponente_programa($lista_cnpjs, $programa->codigo);
											if(!empty($propostas)){
												echo "<br><b>Utilizado nas Propostas:</b><br>";
												foreach ($propostas as $p) {
                                                                                                        if (isset($p->tipo)) {
                                                                                                            echo "<a target='_blank' href='".base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id='.$p->id_proposta)."'>".$p->codigo_siconv."</a> - " . $p->tipo . " - " . $p->situacao . " - Valor: " . $p->valor_global  . " <br>";
                                                                                                        } else {
                                                                                                            echo "<a target='_blank' href='".base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id='.$p->id_proposta)."'>".$p->codigo_siconv."</a> - " . "Não verificado" . " - " . $p->situacao . " - Valor: " . $p->valor_global  . " <br>";
                                                                                                        }
                                                                                                }
											}
										}
										?>
                                                                                        
                                                                                <br />        
                                                                                <div class="panel">
                                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $programa->codigo."-".$xyz;?>"><label class="label label-info" style="background-color: red">Detalhes</label></a>
                                                                                    <div id="collapse-<?php echo $programa->codigo."-".$xyz;?>" class="panel-collapse collapse">
                                                                                        <div class="panel-body">
                                                                                            <?php if(isset($programa->tem_chamamento) && $programa->tem_chamamento == 1):?>
                                                                                            <label class="label label-info">Chamamento:</label> Sim <br>
                                                                                            <?php else: ?>
                                                                                            <label class="label label-info">Chamamento:</label> Não <br>
                                                                                            <?php endif;?>
                                                                                                
                                                                                            <?php if(isset($programa->anexos)):?>
                                                                                                <?php if ($programa->anexos == 1): ?>
                                                                                                    <label class="label label-info">Anexos:</label> Sim <br>
                                                                                                <?php else: ?>    
                                                                                                    <label class="label label-info">Anexos:</label> Não <br>
                                                                                                <?php endif; ?>
                                                                                            <?php else: ?>
                                                                                                <label class="label label-info">Anexos:</label> Não verificado <br>
                                                                                            <?php endif;?> 
                                                                                                
                                                                                            <?php if(isset($programa->observacao)):?>
                                                                                            <label class="label label-info">Observação:</label> <?php echo $programa->observacao;?><br>
                                                                                            <?php endif;?>
                                                                                                
                                                                                            <?php if(isset($programa->objeto)):?>
                                                                                            <label class="label label-info">Objeto:</label> <?php echo $programa->objeto;?><br>
                                                                                            <?php endif;?> 
                                                                                                
                                                                                            <?php if(isset($programa->regra_contrapartida)):?>
                                                                                            <label class="label label-info">Regras de contrapartida:</label> <br>
                                                                                                <?php echo preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"", preg_replace("/td style=\"text-align:center\"/", "td class=\"thomas\" style=\"text-align:center\"", preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"", preg_replace("/td style=\"text-align:center\"/", "td class=\"thomas\" style=\"text-align:center\"", preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"", preg_replace("/td style=\"text-align:center\"/", "td class=\"thomas\" style=\"text-align:center\"", preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"", preg_replace("/td style=\"text-align:center\"/", "td class=\"thomas\" style=\"text-align:center\"", preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"", preg_replace("/td style=\"text-align:center\"/", "td class=\"thomas\" style=\"text-align:center\"", preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"", preg_replace("/td style=\"text-align:center\"/", "td class=\"thomas\" style=\"text-align:center\"", preg_replace("/td style=\"text-align:center\"/", "td style=\"text-align:left\"",  str_replace('tr class="even"', 'tr class="even" style="text-align:center"', str_replace("th ", "th style=\"text-align:center\"", str_replace("<td", "<td style=\"text-align:center\"", str_replace('tr class="odd"', 'tr class="odd" style="text-align:center"', $programa->regra_contrapartida)))), 1), 5), 1), 5), 1), 5), 1), 5), 1), 5), 1), 5), 1);?><br>
                                                                                            <?php endif;?>     
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        <?php $xyz++;?>
								</li>
							</ul>
						</div>
					</div>
				</div>
			<?php 
			}
		 ?>
	
	
		</div>
	</div>
	
	<h1 class="bg-white" style="text-align: right;">
		
		<form method="post" id="gera_pdf2" action="<?php echo base_url();?>index.php/in/dados_siconv/gerapdf_lista"  target="_blank">
		<?php if($permissoes->relatorio_programa):?>
		<input type="submit" class="btn btn-primary" id="gerarPdf2" style="float: left;" value="Gerar PDF"/>
		
		<input type="submit" class="btn btn-success" id="enviaPdf2" style="float: left; margin-left: 8px;" value="Enviar Por Email"/>
		
		<!-- <input type="submit" class="btn btn-info" id="bloqProg2" style="float: left; margin-left: 8px;" value="Bloquear Programas"/> -->
		<?php endif;?>
		</form>
		<span>EXIBIDOS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_rows; ?></span> TOTAL: <?php echo "<span style='font-size: 22px;' class='num_prog'>".$total_rows."</span>"; if(isset($pesquisa)&& $pesquisa!=""){echo "Pesquisa por: {$pesquisa}";}?></span>

	</h1>
	<?php } //fim if lista
	else { ?>
	<h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
	<?php }?>
</div>

<script type="text/javascript">
$(function(){
	
});

$(document).ready(function() {
	var pesqAvancadaAberta = false;
	$("#loader").hide();

	var dialog;
	
	dialog = $("#dialog-form").dialog({
		height: 420,
		width: 450,
		modal: true,
		buttons: {
			"Enviar": function() {
				$("#pop").css('height', $(document).height());
				$('#pop').css('display', 'block');

				var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

				if(checkRegexp( $("#email").val(), emailRegex)){
					if(!confirm("Confirma o envio do PDF para o email: "+$("#email").val())){
						return false;
					}
	
					$(this).dialog( "close" );
					
					$("#gera_pdf").attr('action', '<?php echo base_url('index.php/in/dados_siconv/envia_programas_email'); ?>');
					$("#gera_pdf").submit();
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		}
	}).position({
	    my: "center",
	    at: "center",
	    of: window
	});

	function checkRegexp( o, regexp ) {
		emails = o.split(",");
		for(i = 0; i < emails.length; i++){
			if($.trim(emails[i]) != ""){
				if ( !( regexp.test( $.trim(emails[i]) ) ) ) {
			        alert("Informe um email válido.");
			        return false;
			    }
			}
		}

	    return true;
	}
    
	dialog.dialog( "close" );

	$("#gerarPdf2").click(function(){
		var validaCheck = false;
		var qtdOcultar = 0;
		$(".checkboxInput").each(function(){
			$(this).attr('form', 'gera_pdf2');
			
			if($(this).is(":checked")){
				qtdOcultar++;
				validaCheck = true;
			}

			if(!$("#"+$(this).val()+"_ocultar").is(':visible')){
				$(this).attr('checked', false);
				qtdOcultar--;
			}
		});

		$(".cnpj").each(function(){
			$(this).attr('form', 'gera_pdf2');
		});

		$("#gera_pdf").attr('action', '<?php echo base_url('index.php/in/dados_siconv/gerapdf_lista'); ?>');

		if(!validaCheck){
			alert("Selecione ao menos um programa para gerar o relatório.");
			return false;
		}

		$("#oculta_prog_pdf").val("0");
		$("#oculta_prog_pdf").attr('form', 'gera_pdf2');
		$("#gera_pdf2").submit();

		return false;
	});

	$("#enviaPdf2").click(function(){
		var validaCheck = 0;
		$(".checkboxInput").each(function(){
			$(this).attr('form', 'gera_pdf');
			if($(this).is(":checked")){
				validaCheck++;
			}
			
			if(!$("#"+$(this).val()+"_ocultar").is(':visible')){
				$(this).attr('checked', false);
				validaCheck--;
			}
		});

		$(".cnpj").each(function(){
			$(this).attr('form', 'gera_pdf');
		});

		if(validaCheck <= 0){
			alert("Selecione ao menos um programa para gerar o relatório.");
			return false;
		}

		$("#oculta_prog_pdf").val("0");
		$("#oculta_prog_pdf").attr('form', 'gera_pdf');

		$("#assunto").attr('form', 'gera_pdf');
		$("#email").attr('form', 'gera_pdf');
		$("#mensagem").attr('form', 'gera_pdf');
		
		dialog.dialog("open");

		return false;
	});

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
		var validaCheck = false;
		var qtdOcultar = 0;
		$(".checkboxInput").each(function(){
			$(this).attr('form', 'gera_pdf');
			
			if($(this).is(":checked")){
				qtdOcultar++;
				validaCheck = true;
			}

			if(!$("#"+$(this).val()+"_ocultar").is(':visible')){
				$(this).attr('checked', false);
				qtdOcultar--;
			}
		});

		$(".cnpj").each(function(){
			$(this).attr('form', 'gera_pdf');
		});

		$("#gera_pdf").attr('action', '<?php echo base_url('index.php/in/dados_siconv/gerapdf_lista'); ?>');

		if(!validaCheck){
			alert("Selecione ao menos um programa para gerar o relatório.");
			return false;
		}
		
		$("#oculta_prog_pdf").val("0");
		$("#oculta_prog_pdf").attr('form', 'gera_pdf');
		$("#gera_pdf").submit();

		return false;
	});

	$("#enviaPdf").click(function(){
		var validaCheck = 0;
		$(".checkboxInput").each(function(){
			$(this).attr('form', 'gera_pdf');
			if($(this).is(":checked")){
				validaCheck++;
			}
			
			if(!$("#"+$(this).val()+"_ocultar").is(':visible')){
				$(this).attr('checked', false);
				validaCheck--;
			}
		});

		$(".cnpj").each(function(){
			$(this).attr('form', 'gera_pdf');
		});
		
		if(validaCheck <= 0){
			alert("Selecione ao menos um programa para gerar o relatório.");
			return false;
		}

		$("#oculta_prog_pdf").val("0");
		$("#oculta_prog_pdf").attr('form', 'gera_pdf');
		
		$("#assunto").attr('form', 'gera_pdf');
		$("#email").attr('form', 'gera_pdf');
		$("#mensagem").attr('form', 'gera_pdf');
		
		dialog.dialog("open");

		return false;
	});

	$(".oculta_programa").click(function(){
		var id = $(this).attr('id');
		$.ajax({
			url:'<?php echo base_url('index.php/in/dados_siconv/oculta_programas'); ?>',
			dataType:'html',
			type:'post',
			data:{
				idsPrograma:id
			},
			beforeSend:function(){
				$("#loader").show();
			},
			success:function(){
				$("#loader").hide();
				$("#"+id+"_ocultar").remove();
				var novoValor = $("#num_prog").val()-1;
				
				$(".num_prog").html(novoValor);
				$("#num_prog").val(novoValor);

				if(novoValor <= 0)
					$("#carrega_dados").submit();
			}
		});
	});

	$(".btnPesquisa").click(function(){
		var validaAtende = false;
		var validaQualificacao = false;

		$(".obrigaAtende").each(function(){
			if($(this).is(":checked"))
				validaAtende = true;
		});

		$(".obrigaQualificacao").each(function(){
			if($(this).is(":checked"))
				validaQualificacao = true;
		});

		$(".cnpj").each(function(){
			$(this).attr('form', 'carrega_dados');
		});
		
		if(!$("#vigencia").is(":checked")){
			if($("#Data1").val() == "" && $("#Data2").val() == ""){
				alert("Informe ao menos uma data de vigência.");
				return false;
			}
		}

		if(validaAtende && validaQualificacao){
			$("#loader").show();
			return true;
		}else{
			alert("Informe ao menos uma Qualificação da Proposta e uma Qualificação do Proponente");
			return false;
		}
	});

	$("#num_cnpj").keyup(function(){
		if ($(this).val().length == 2) {
			$(this).val($(this).val()+ '.');
            return true;
        }
        if ($(this).val().length == 6) {
        	$(this).val($(this).val()+ '.');
            return true;
        }
        if ($(this).val().length == 10) {
        	$(this).val($(this).val()+ '/');
            return true;
        }
        if ($(this).val().length == 15) {
        	$(this).val($(this).val()+ '-');
            return true;
        }
	});

	$("#num_cnpj").focusout(function(){
		formataCNPJ($(this).val());
	});

	function formataCNPJ(value){
		var cnpjAUX = "";
		if(value.length <= 14){
			for(var i = 0; i < value.length; i++){
				cnpjAUX += value[i];
				if(cnpjAUX.length == 2)
					cnpjAUX += ".";
				else if(cnpjAUX.length == 6)
					cnpjAUX += ".";
				else if(cnpjAUX.length == 10)
					cnpjAUX += "/";
				else if(cnpjAUX.length == 15)
					cnpjAUX += "-";
			}

			$("#num_cnpj").val(cnpjAUX);
		}
	}
	
	$(".loadinggif").hide();
		$("#carrega_dados").submit(function() {
			$(".loadinggif").show();
		});
	});

	$(".selecionarTodos").click(function(){
		$(".checkboxInput").each(function(){
			if($("#"+$(this).val()+"_ocultar").is(':visible'))
				$(this).attr("checked", $(".selecionarTodos").is(":checked"));
			else
				$(this).attr("checked", false);
		});
	});

	$(".checkboxInput").click(function(){
		$(".selecionarTodos").attr("checked", false);
	});

	$(".cnpj_todos").click(function(){
		$(".cnpj").each(function(){
			$(this).attr("checked", $(".cnpj_todos").is(":checked"));
		});
	});

	$(".cnpj").click(function(){
		$(".cnpj_todos").attr("checked", false);
	});
	
function selecionar(classe){
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
		if(document.getElementById(classe).checked == 1)
			divs[i].checked = 1;
		else
			divs[i].checked = 0;
	}
}

function selecionar_parte(classe){
	var divs = document.getElementsByClassName("prop1");
	for(var i=0; i<divs.length; i++) {
		divs[i].checked = 0;
	}
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
		divs[i].checked = 1;
	}
}

function ativa1(){
	var data2 = document.getElementById("Data2");
	var data1 = document.getElementById("Data1");
	if (data2.disabled == true){ data2.disabled = false; data1.disabled = false; }
	else { data2.disabled = true; data1.disabled = true; }
}

function ativa(nome){
	var classe = document.getElementById("div_"+nome);
	if (classe.style.display == 'none') classe.style.display = '';
	else classe.style.display = 'none';
}
function formatar(src, mask,e) 
{
	var tecla = "";
	if (document.all) // Internet Explorer
		tecla = event.keyCode;
	else
		tecla = e.which;
	//code = evente.keyCode;
	if(tecla != 8){


	if (src.value.length == src.maxlength){
	return;
	}
  var i = src.value.length;
  var saida = mask.substring(0,1);
  var texto = mask.substring(i);
  if (texto.substring(0,1) != saida) 
  {
	src.value += texto.substring(0,1);
  }
}
}
</script>

<div id="dialog-form" title="Enviar Relatório de Programas">
  <form>
    <fieldset>
      <label for="assunto">Assunto</label><br>
      <input type="text" name="assunto" size="40" id="assunto" value="Lista de Programas SICONV" class="text ui-widget-content ui-corner-all" style="color: black;"><br>
      <label for="mensagem">Mensagem</label><br>
      <textarea name="mensagem" id="mensagem" rows="8" cols="50" class="text ui-widget-content ui-corner-all"></textarea><br>
      <label for="email">Email - Separe os emails por vírgula (,)</label><br>
      <input type="text" name="email" id="email" size="40" value="" class="text ui-widget-content ui-corner-all" style="color:black;"><br>
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>
