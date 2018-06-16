<div id="content">
<h1 class="bg-white content-heading border-bottom">Relatório de programas com emenda</h1>
	<div class="widget">
		<div class="widget-body padding-none">
<div id="ConteudoDiv">
<style type="text/css">
	.carregando{
		color:#666;
		display:none;
	}
</style>
<form class="form-horizontal" role="form" name="carrega_dados" method="post" id="carrega_dados" action="gerapdf_programas_emenda">
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
	<label for="Dt_Fim" class="col-sm-3 control-label">Data de Término:</label>
	<div class="col-sm-3">
		<input class="form-control ie" disabled=1 type="text" size="11" name="Dt_Fim" title="Entre com a data no formato: DD/MM/AAAA" id="Data2" maxlength="10" onkeyup="formatar(this,'##/##/####',event)">
	</div>
</div>
<div class="form-group">
	<label for="emenda" class="col-sm-3 control-label">Número da emenda:</label>
	<div class="col-sm-3">
		<input class="form-control ie" type="text"  name="emenda" id="emenda">
	</div>
</div>
<div class="form-group">
	<label for="emenda" class="col-sm-3 control-label">CNPJ:</label>
	<div class="col-sm-3">
		<input class="form-control ie" type="text"  name="cnpj" id="cnpj">
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-6">
		<button type="submit" name="operation" onclick="" value="Gerar" class="btn btn-primary">Gerar</button>
	</div>
</div>
		<div class='loadinggif' style="display: none;"><center><img src='<?= base_url();?>configuracoes/loading.gif'/><br/>
		<p>CARREGANDO DADOS DO SICONV. ESSA TAREFA PODE DEMORAR ALGUNS MINUTOS...</p></center></div>
		</form>
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
					$('#cod_cidades').hide();
					$('.carregando').show();
					$.getJSON('cidades_ajax?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
						var options = '<option value=""></option>';	
						for (var i = 0; i < j.length; i++) {
							options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
						}	
						$('#cod_cidades').html(options).show();
						$('.carregando').hide();
					});
				} else {
					$('#cod_cidades').html('<option value="">– Escolha um estado –</option>');
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