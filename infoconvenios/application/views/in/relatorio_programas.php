<div id="content">
	<h1 class="bg-white content-heading border-bottom">Relatório de Programas</h1>
		<div class="widget innerAll">
			<div class="widget-body">

				<form class="form-horizontal" role="form" name="carrega_dados" method="post" id="carrega_dados" action="gerapdf_programas">
					
				
						<div class="form-group">
							<div class="col-sm-10">
								<div class="checkbox">
									<label class="checkbox-custom">
										<i class="fa fa-fw fa-square-o"></i>
										<input type="checkbox" onclick='ativa1()' checked=1 name="vigencia" value="vigencia"/> Programas em vigência (Para inserir um intervalo de datas, desmarque esta opção)
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" style="text-align: left;">Data de Início</label>
							<div class="col-sm-2">
								<input disabled=1 class="form-control ie" type="text" size="11" name="Dt_Inicio" title="Entre com a data no formato: DD/MM/AAAA" id="Data1" maxlength="10" onkeyup="formatar(this,'##/##/####',event)"/>
							</div>
						</div></br>
						<div class="form-group">
							<label class="col-sm-2 control-label" style="text-align: left;">Data Final</label>
							<div class="col-sm-2">
								<input class="form-control ie" disabled=1 type="text" size="11" name="Dt_Fim" title="Entre com a data no formato: DD/MM/AAAA" id="Data2" maxlength="10" onkeyup="formatar(this,'##/##/####',event)"/>
							</div>
						</div>
		
				
					
						<div class="form-group">
							<div class="col-md-6">
								<label class="col-sm-2 control-label">Estado:</label>
								<select style="width: 100%;" name="cod_estados" id="cod_estados">
									<option value=""></option>
									<?php
										foreach ($cidades as $estado) {
											echo '<option value="'.$estado['cod_estados'].'">'.$estado['sigla'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
							
						<div class="form-group">
							<div class="col-md-6">
								<label class="col-sm-2 control-label">Cidade:</label>
								<span id="carregando" >Aguarde, carregando...</span>
								<select style="width: 100%;" name="cod_estados" class="cod_cidades" id="select2_1">
								</select>		
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-6">
								<label for="cod_estados">Órgão Concedente:</label>
								<select style="width: 100%;" name="orgao" id="select2_2" >
									<option value=""></option>
									<?php
									
										foreach ($orgaos as $orgao) {
											echo '<option value="'.$orgao['orgao'].'">'.$orgao['orgao'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
				
					<div class="form-group">
						<label for="cod_estados">Qualificação da Proposta:</label>
						<label class="checkbox">
							<input type="checkbox" class="checkbox" id="prop" name="todos" checked=1 value="todos" onclick="selecionarCima('prop')"/> TODOS
						</label>
						
						<label class="checkbox">
							<input type="checkbox" id="prop1voluntaria" name="voluntaria" checked=1 value="voluntaria" onclick="selecionarCima('prop')"> <b>Proposta Voluntária</b>
						</label>
						<label class="checkbox">
							<input type="checkbox" id="prop1especifico" name="especifico" checked=1 value="especifico" onclick="selecionarCima('prop')"> <b>Proposta de Proponente Específico do Concedente</b>
						</label>
						<label class="checkbox">
							<input type="checkbox" id="prop1emenda" name="emenda" checked=1 value="emenda" onclick="selecionarCima('prop')"> <b>Proposta de Proponente de Emenda Parlamentar</b>
						</label>
						
						<label for="cod_estados">Atende a:</label> (<a onclick="ativa('atende_div')">mostra/esconde</a>)
						
						<div id="div_atende_div" style="display:none;">
						<table>
							<tr>
								<td><input type="checkbox" class='prop especifico' checked=1 name="qualificacao1" value="Proposta de Proponente Específico do Concedente">Proposta de Proponente Específico do Concedente</td>
								<td><input type="checkbox" class='prop voluntaria' checked=1 name="qualificacao2" value="Proposta Voluntária">Proposta Voluntária</td>
								<td><input type="checkbox" class='prop voluntaria emenda' checked=1 name="qualificacao3" value="Proposta Voluntária, Proposta de Proponente de Emenda Parlamentar">Proposta Voluntária, Proposta de Proponente de Emenda Parlamentar</td>
							</tr>
							<tr>
								<td><input type="checkbox" class='prop emenda' checked=1 name="qualificacao4" value="Proposta de Proponente de Emenda Parlamentar">Proposta de Proponente de Emenda Parlamentar</td>
								<td><input type="checkbox" class='prop especifico voluntaria emenda' checked=1 name="qualificacao5" value="Proposta Voluntária, Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar">Proposta Voluntária, Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar</td>
								<td><input type="checkbox" class='prop especifico emenda' checked=1 name="qualificacao6" value="Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar">Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar</td>
							</tr>
							<tr>
								<td  colspan=3><input type="checkbox" class='prop especifico voluntaria' checked=1 name="qualificacao7" value="Proposta Voluntária, Proposta de Proponente Específico do Concedente">Proposta Voluntária, Proposta de Proponente Específico do Concedente</td>
							</tr>
						</table>
						</div>
					</div>
					
					<div class="form-group">						
						<label for="cod_estados">Qualificação do Proponente:</label>
						<label class="checkbox">
							<input type="checkbox" class="checkbox" id="prop1" name="todos" checked=1 value="todos" onclick="selecionar('prop1')"> <b>TODOS</b>
						</label>
						<label class="checkbox">
							<input type="checkbox" class="checkbox" id="prop1municipal" name="municipal" checked=1 value="municipal" onclick="selecionar('prop1')"> <b>Municipal</b>
						</label>
						<label class="checkbox">
							<input type="checkbox" class="checkbox" id="prop1estadual" name="estadual" checked=1 value="estadual" onclick="selecionar('prop1')"> <b>Estadual</b>
						</label>
						<label class="checkbox">
							<input type="checkbox" class="checkbox" id="prop1entidade" name="entidade" checked=1 value="entidade" onclick="selecionar('prop1')"> <b>Entidade Privada</b>
						</label>
						<label class="checkbox">
							<input type="checkbox" class="checkbox" id="prop1consorcio" name="consorcio" checked=1 value="consorcio" onclick="selecionar('prop1')"> <b>Consórcio</b>
						</label>
						
						<div style="display:none;">
							<table>
							<?php
							foreach ($atende as $key => $at) {
								$class = "prop1";
								if (strripos(strtolower($at['atende']), 'municip') !== false) $class .= " municipal";
								if (strripos(strtolower($at['atende']), 'estad') !== false) $class .= " estadual";
								if (strripos(strtolower($at['atende']), 'entidade privada') !== false) $class .= " entidade";
								if (strripos(strtolower($at['atende']), 'consórcio') !== false) $class .= " consorcio";
								echo '<tr> <td><input type="checkbox" class="'.$class.'" checked=1 name="atende'.$key.'" value="'.$at['atende'].'">'.$at['atende'].'</td> </tr>';
							}
							?>
							</table>
						</div>
					</div>

					<br />
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-10">
							<button type="submit" name="operation" class="btn btn-primary">Gerar</button>
						</div>
					</div>
					
						<br />
						<br />
						Obs. O siconv pode não funcionar corretamente ao clicar no link do programa e não é de nossa responsabilidade quaisquer inconstâncias encontradas lá.
						<br /> Se tiver a sessão expirada tente novamente (mais 2 vezes) para que o siconv abra uma nova sessão e você possa continuar navegando na plataforma online.
						<div class='loadinggif'><center><img src='<?= base_url();?>configuracoes/loading.gif'/><br/>
						<p>CARREGANDO DADOS DO SICONV. ESSA TAREFA PODE DEMORAR ALGUNS MINUTOS...</p></center></div>
						
				</form>
				
			</div>
		</div>
	</div>

<script language="javascript">
		$(document).ready(function() {
		$('#carregando').hide();
		$(".loadinggif").hide();
			$("#carrega_dados").submit(function() {
				$(".loadinggif").show();
			})
		});
function selecionar(classe){
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
			divs[i].checked = 0;
	}
	
	for(var i=0; i<divs.length; i++) {
		if(document.getElementById(classe).checked == 1)
			divs[i].checked = 1;
		}
		
		if(document.getElementById("prop1municipal").checked == 1)
			selecionar_parte('municipal');
		if(document.getElementById("prop1estadual").checked == 1)
			selecionar_parte('estadual');
		if(document.getElementById("prop1entidade").checked == 1)
			selecionar_parte('entidade');
		if(document.getElementById("prop1consorcio").checked == 1)
			selecionar_parte('consorcio');

}

function selecionarCima(classe){
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
			divs[i].checked = 0;
	}
	
	for(var i=0; i<divs.length; i++) {
		if(document.getElementById(classe).checked == 1)
			divs[i].checked = 1;
		}
		
		if(document.getElementById("prop1emenda").checked == 1)
			selecionar_parte('emenda');
		if(document.getElementById("prop1especifico").checked == 1)
			selecionar_parte('especifico');
		if(document.getElementById("prop1voluntaria").checked == 1)
			selecionar_parte('voluntaria');

}

function selecionar_parte(classe){
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
		divs[i].checked = 1;
	}
}

function selecionar_parte1(classe){
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
</script>


<script type="text/javascript">
	$(function(){
		$('#cod_estados').change(function(){
			if( $(this).val() ) {
				
				$('#carregando').show();
				$.getJSON('cidades_ajax?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
					var options = '<option value=""></option>';	
					for (var i = 0; i < j.length; i++) {
						options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
					}	
					$('#select2_1').html(options).show();
					$('#carregando').hide();
				});
			} else {
				
				$('#select2_1').html('<option value="">– Escolha um estado –</option>');
			}
		});
	});
	function formatar(src, mask,e){
		var tecla ="";
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
			if (texto.substring(0,1) != saida){
			src.value += texto.substring(0,1);
			}
		}
	}
</script>