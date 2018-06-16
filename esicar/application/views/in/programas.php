<div id="content">
<h1 class="bg-white content-heading border-bottom">Relatório de programas com emenda</h1>
	<div class="widget">
		<div class="widget-body padding-none">

<form class="form-horizontal" role="form" name="carrega_dados" method="post" id="carrega_dados" action="lista_programas?usuario=<?php echo $usuario ?>">
<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<div class="checkbox">
				<label class="checkbox-custom">
					<i class="fa fa-fw fa-square-o"></i>
					<input type="checkbox" onclick='ativa1()' checked=1 name="vigencia" value="vigencia"/> Programas em vigência (Para inserir um intervalo de datas, desmarque esta opção)
			</label>
		</div>
	</div>
</div>
<div class="form-group">
    <label for="Dt_Inicio" class="col-sm-3 control-label">Data de Início:</label>
    <div class="col-sm-3">
        <input disabled=1 class="form-control ie" type="text" size="11" name="Dt_Inicio" title="Entre com a data no formato: DD/MM/AAAA" id="Data1" maxlength="10" onkeyup="formatar(this,'##/##/####',event)">
    </div>
</div>
<div class="form-group">
	<label for="Dt_Fim" class="col-sm-3 control-label">Data Final:</label>
	<div class="col-sm-3">
		<input class="form-control ie" disabled=1 type="text" size="11" name="Dt_Fim" title="Entre com a data no formato: DD/MM/AAAA" id="Data2" maxlength="10" onkeyup="formatar(this,'##/##/####',event)">
	</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Estado:</label>
	<div class="col-sm-3">
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
	
<div class="form-group" >
<label class="col-sm-3 control-label">Cidade:</label>
	<div class="col-sm-3">
		<span id="carregando" >Aguarde, carregando...</span>
		<select style="width: 100%;" name="cod_estados" class="cod_cidades" id="select2_1">
		</select>		
	</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label" for="cod_estados">Órgão Concedente:</label>
	<div class="col-sm-3">
		<select name="orgao" id="orgao" style="width: 100%;">
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
	<label class="col-sm-3 control-label"  for="cod_estados">Qualificação do Proponente:</label>
	<div class="col-sm-4">
	<input type="checkbox" id="prop" name="todos" checked=1 value="todos" onclick="selecionar('prop')"/> <label for="prop" class="control-label">Todos</label><br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao1" value="Proposta de Proponente Específico do Concedente"/>Proposta de Proponente Específico do Concedente<br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao2" value="Proposta Voluntária"/>Proposta Voluntária<br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao3" value="Proposta Voluntária, Proposta de Proponente de Emenda Parlamentar"/>Proposta Voluntária, Proposta de Proponente de Emenda Parlamentar<br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao4" value="Proposta de Proponente de Emenda Parlamentar"/>Proposta de Proponente de Emenda Parlamentar<br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao5" value="Proposta Voluntária, Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar"/>Proposta Voluntária, Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar<br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao6" value="Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar"/>Proposta de Proponente Específico do Concedente, Proposta de Proponente de Emenda Parlamentar<br>
	<input type="checkbox" class='prop' checked=1 name="qualificacao7" value="Proposta Voluntária, Proposta de Proponente Específico do Concedente"/>Proposta Voluntária, Proposta de Proponente Específico do Concedente<br>
	</div>	
</div>	

<div class="form-group">
	<label class="col-sm-3 control-label">Atende a:</label>(<a onclick="ativa('atende_div')">mostra/esconde</a>)
	<div class="col-sm-6 ">
	<input type="checkbox" id="prop1" name="todos" checked=1 value="todos" onclick="selecionar('prop1')"> <label for="prop1" class="control-label">Todos</label><br>
	[ <a href="javascript:undefined;" onclick="selecionar_parte('municipal')"> Municipal </a> ]
	[ <a href="javascript:undefined;" onclick="selecionar_parte('estadual')"> Estadual </a> ]
	[ <a href="javascript:undefined;" onclick="selecionar_parte('entidade')"> Entidade Privada </a> ]
	[ <a href="javascript:undefined;" onclick="selecionar_parte('consorcio')"> Consórcio </a> ]
	</div>
	
		
	<div class="col-sm-6 col-sm-offset-3">
	<div id="div_atende_div" style="display:none;">
	<?php
	foreach ($atende as $key => $at) {
		$class = "prop1";
		if (strripos(strtolower($at['atende']), 'municip') !== false) $class .= " municipal";
		if (strripos(strtolower($at['atende']), 'estad') !== false) $class .= " estadual";
		if (strripos(strtolower($at['atende']), 'entidade privada') !== false) $class .= " entidade";
		if (strripos(strtolower($at['atende']), 'consórcio') !== false) $class .= " consorcio";
		echo '<input type="checkbox" class="'.$class.'" checked=1 name="atende'.$key.'" value="'.$at['atende'].'">'.$at['atende'].'<br>';
	}
	?>
	</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<button type="submit" name="operation" onclick="" value="Verificar" class="btn btn-primary">Verificar</button>
	</div>
</div>
</form>
	<?php if($lista){	?>
	<div class="widget borders-none">
		<div class="widget-body ">		
		<h2>Programas selecionados pelo gestor para a cidade:</h2>
		<p>(ao realizar uma nova seleção, haverá a subistituição da lista vigente e permissão para que o município altere seus programas)</p>
		<?php foreach($lista as $programa){	?>
			<a href="<?php echo $programa->link;?>" target="_blank" class="list-group-item">
				<h4 class="list-group-item-heading"><?php echo $programa->codigo;?></h4>
				<p class="margin-none"><?php echo $programa->nome;?></p>
			</a>
		<?php } ?>
		</br>
		<a class="btn btn-primary" href="<?= base_url();?>index.php/in/dados_siconv/relatorio?todas=1&usuario=<?= $usuario;?>">Gerar Relatório</a>
		</div>
	</div>
	<?php } ?>
	<?php if($listaAceito){?>
	<div class="widget  borders-none">
		<div class="widget-body">
		<h2>Programas escolhidos pela cidade:</h2>
		<div class="list-group email-item-list">
					
		<?php foreach($listaAceito as $programa){?>
			<a href="<?php echo $programa->link;?>" target="_blank" class="list-group-item">
				<h4 class="list-group-item-heading"><?php echo $programa->codigo;?></h4>
				<p class="margin-none"><?php echo $programa->nome;?></p>
			</a>
		<?php } ?>
		<br />
		<a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/dados_siconv/relatorio?usuario=<?php echo $usuario; ?>">Gerar Relatório</a>
		<a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/dados_siconv/permitir_mudancas?usuario=<?php echo $usuario; ?>">Permitir alterações</a>
		</div>
		</div>
	</div>
		<?php } ?>
	<div class="widget  borders-none">
		<div class="widget-body">
		<p>Obs. O siconv pode não funcionar corretamente ao clicar no link do programa e não é de nossa responsabilidade quaisquer inconstâncias encontradas lá.
		<br /> Se tiver a sessão expirada tente novamente (mais 2 vezes) para que o siconv abra uma nova sessão e você possa continuar navegando na plataforma online.
		</p>
		<div class='loadinggif' style="display: none;"><center><img src='<?php echo base_url(); ?>configuracoes/loading.gif'/><br/>
		<p>CARREGANDO DADOS DO SICONV. ESSA TAREFA PODE DEMORAR ALGUNS MINUTOS...</p></center></div>
		</div>
	</div>
</div>
</div>

</div>

<script language="javascript">
		$(document).ready(function() {
		$(".loadinggif").hide();
			$("#carrega_dados").submit(function() {
				$(".loadinggif").show();
			})
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
	$(function(){
		$('#carregando').hide();
		$('#select2_1').hide();
			$('#cod_estados').change(function(){
				if( $(this).val() ) {
					$('#select2_1').hide();
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
					$('#carregando').hide();
					$('#select2_1').html('<option value="">– Escolha um estado –</option>');
				}
			});
		});
		
function formatar(src, mask,e) 
{
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
  if (texto.substring(0,1) != saida) 
  {
	src.value += texto.substring(0,1);
  }
}
}
</script>
	
