<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>configuracoes/js/thumbnailviewer.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url();?>configuracoes/js/form-validation.js"></script>

<script language="JavaScript" type="text/javascript">
    function enviardadosProposta(form) {
		var campos = form.getElementsByTagName('input');
        var podeEnviar = true;
        var valorPermitido = true;

        if (parseFloat(replaceAll($("#salvarValorRepasseespecifico").val(), '.','').replace(",", ".")) > parseFloat(replaceAll($('#valorDisponivel').html(), '.','').replace(",", "."))) {
            
            valorPermitido = false;
        }

        for (i = 0; i < campos.length; i++) {
            var classe = campos[i].className;
            var valor = campos[i].value;
            if(valor != "Avançar" && valor != "Confirmar" && valor != "Voltar"){
	            if (classe.indexOf('obrigatorio') != -1 && valor == ''){
	                podeEnviar = false;
	                campos[i].style.color = "#fff";
	                campos[i].style.backgroundColor = "#FF7777";
	            }else if(classe.indexOf('numericoRequired') != -1 && parseFloat(replaceAll(valor, '.','').replace(",", ".")) == 0.00){
	            	podeEnviar = false;
	                campos[i].style.color = "#fff";
	                campos[i].style.backgroundColor = "#FF7777";
	            }else{
	            	campos[i].style.color = "#a7a7a7";
	            	campos[i].style.backgroundColor = "#fff";
	            }
            }
        }

        var campos = form.getElementsByTagName('select');
        for (i = 0; i < campos.length; i++) {
            var classe = campos[i].className;
            var valor = campos[i].value;
            if (classe.indexOf('obrigatorio') != -1 && valor == ''){
                podeEnviar = false;
                campos[i].style.color = "#fff";
                campos[i].style.backgroundColor = "#FF7777";
            }else{
            	campos[i].style.color = "#a7a7a7";
            	campos[i].style.backgroundColor = "#fff";
            }
        }

        if (valorPermitido != true) {
            
            alert("Valor do repasse específico acima do disponível!");
            return false;
        }
        
        if (podeEnviar != true) {

            alert('existem campos obrigatórios em branco!');
            return false;
        }

        

//         if (parseFloat(replaceAll(document.getElementById('salvarValorRepasse').value, '.','').replace(",", ".")) < parseFloat(100000.00)) {
//             alert('Valor de repasse não pode ser inferior a R$ 100.000,00 (cem mil reais)');
//             return false;
//         }
        //replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", ".")
        
        var valor_percent = parseFloat(replaceAll(document.getElementById('percentual').value, '.','').replace(",", "."))*0.01*
        parseFloat(replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", "."));
        
		if(valor_percent > 0){
			if (valor_percent.toFixed(2) > parseFloat(replaceAll(document.getElementById('salvarValorContrapartida').value, '.','').replace(",", ".")).toFixed(2)) {
	            alert('Valor abaixo do percentual de contrapartida');
	            return false;
	        }
		}

        var valida_global = parseFloat(replaceAll(document.getElementById('salvarValorContrapartida').value, '.','').replace(",", ".")) + parseFloat(replaceAll(document.getElementById('salvarValorRepasse').value, '.','').replace(",", "."));
        if ( parseFloat(replaceAll(document.getElementById('salvarValorGlobal').value, '.','').replace(",", ".")).toFixed(2) != valida_global.toFixed(2)) {
            alert('Valor global deve ser igual ao repasse + contrapartida');
            return false;
        }

        var soma = parseFloat(document.getElementById('percentual').value.replace(".", "").replace(",", ".")) * parseFloat(document.getElementById('salvarValorGlobal').value.replace(".", "").replace(",", "."));
        if (soma < parseFloat(document.getElementById('salvarValorContrapartida').value.replace(",", ".")))
            alert('Contrapartida maior do que o previsto. Cadastro efetuado mesmo assim.');

        return true;
	}
    
function replaceAll(str, s1, s2){
    var exists = false;
	if (str.indexOf(s1) !== -1)
		exists = true; // does 'abc' exist in my string?

	while (exists) // replace 'abc' as long as it exists
	{
		str = str.replace(s1, s2);

		if (str.indexOf(s1) !== -1)
			exists = true;
		else
			exists = false;
	}
	return str;
}
</script>

<script type="text/javascript">
$(function() {
	$("#formprop").validate({
		rules: {
			"idRowSelectionAsArray[]": {
            	required: true
            },
		}
	}); 
});
</script>
<div class="innerALl">
	<div class="col-md-12 col-sm-12">
	<h3 style="color: #428bca;">Selecionar Objetos / Preencher Valores</h3>
	
			<form name="" id="formprop" method="post" action="incluir_proposta" enctype="multipart/form-data">
				<input type="hidden" name="cnpjProponente" value="<?php echo $cnpjProponente;?>">
				<input type="hidden" name="orgao" value="<?php echo $orgao;?>">
				<input type="hidden" name="id" value="<?php if (isset($id)) { echo $id; } ?>">
                                <input type="hidden" name="offline" value="<?php if (isset($offline)) { echo $offline;} ?>">
				
				<table class="table"><?php echo $tabela;?></table>
				<label for="idRowSelectionAsArray[]" class="error" style="display:none; color: red;">Selecione um programa<br></label>
				<br>                  
				
				<?php 
				if(isset($_GET['padrao']))
					$action = "?padrao=1&id=".$_GET['id'];
				else if(isset($_GET['id']))
					$action = "?id=".$_GET['id'];
				else
					$action = "";
				?>
				
				<?php $ehProjPadrao = (isset($_GET['padrao']) && $_GET['padrao'] != "") ? "padrao=1&" : "" ; ?>
				
				<?php if ($id != "") { ?>
                <?php $edit = (isset($_GET['padrao']) && $_GET['padrao'] != "") ? "" : "&edit=1"; ?>
                
                <?php if(isset($_GET['padrao']) && $_GET['padrao'] != ""){?>
                
                <input  class="btn btn-primary" type="submit" formaction="selecionar_programas?padrao=1&id=<?php echo $id; ?><?php echo $edit; ?>" id="form_submit" value="Voltar">
                <input  class="btn btn-primary" type="submit" formaction="incluir_proposta?<?php echo $ehProjPadrao; ?>id=<?php echo $id; ?><?php echo $edit; ?>" id="avanca_form" value="Avançar">
                
                <?php }else{?>
                
                <?php //if($this->session->userdata('nivel') == 1):?>
                <input  class="btn btn-primary" type="submit" formaction="escolher_proponente?id=<?php echo $id; ?><?php echo $edit; ?>" id="form_submit" value="Alterar Orgão">
                <?php //endif;?>
                
                <input  class="btn btn-primary" type="submit" formaction="selecionar_programas?id=<?php echo $id; ?><?php echo $edit; ?>" id="form_submit" value="Incluir Programas">
                <input  class="btn btn-primary" type="submit" formaction="incluir_proposta?<?php echo $ehProjPadrao; ?>id=<?php echo $id; ?><?php echo $edit; ?>&only_save=1" id="avanca_form" value="Salvar">
                <input  class="btn btn-primary" type="submit" name="avanca_form" formaction="incluir_proposta?<?php echo $ehProjPadrao; ?>id=<?php echo $id; ?><?php echo $edit; ?>" id="avanca_form" value="Avançar">
                <?php }?>
                
                <?php }else{ ?>
                <input  class="btn btn-primary" type="submit" formaction="selecionar_programas?<?php echo $ehProjPadrao; ?>" id="form_submit" value="Voltar">
                
                <input  class="btn btn-primary" type="submit" formaction="incluir_proposta?<?php echo $ehProjPadrao; ?>" id="avanca_form" value="Avançar">
                <?php }?>
                <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
                                        
                <div id="valores_programa">
                <?php 
                if(isset($valores_programa)){
                	foreach ($valores_programa as $valor){
                		if(is_array($valor))
                			$valor = (object)$valor;
                		?>
                		<div id="<?php echo $valor->id_programa; ?>_modal">
                		<input type="hidden" name="id_programa_proposta[]" value="<?php if(isset($valor->id_programa_proposta)){echo $valor->id_programa_proposta;} ?>">
                		<input type="hidden" name="id_programa[]" value="<?php echo $valor->id_programa; ?>">
						<input type="hidden" name="codigo_programa[]" value="<?php echo $valor->codigo_programa; ?>">
						<input type="hidden" name="nome_programa_modal[]" value="<?php echo $valor->nome_programa; ?>">
						<input type="hidden" name="objeto[]" value="<?php echo $valor->objeto; ?>">
						<input type="hidden" name="qualificacao[]" value="<?php echo $valor->qualificacao; ?>">
						<input type="hidden" name="percentual[]" value="">
						<input type="hidden" name="valorGlobal[]" value="<?php echo number_format($valor->valor_global, 2, ",", "."); ?>">
						<input type="hidden" name="valorContrapartida[]" value="<?php echo number_format($valor->total_contrapartida, 2, ",", "."); ?>">
						<input type="hidden" name="valorContrapartidaFinanceira[]" value="<?php echo number_format($valor->contrapartida_financeira, 2, ",", "."); ?>">
						<input type="hidden" name="valorContrapartidaBensServicos[]" value="<?php if(isset($valor->contrapartida_bens) && $valor->contrapartida_bens > 0){echo number_format($valor->contrapartida_bens, 2, ",", ".");} ?>">
						<input type="hidden" name="valorRepasse[]" value="<?php echo number_format($valor->repasse, 2, ",", "."); ?>">
						<input type="hidden" name="valorRepasseespecifico[]" value="<?php echo number_format($valor->repasse_especifico, 2, ",", "."); ?>">
						</div>
                		<?php 
                	}
                }
                ?>
                </div>
			</form>

	</div>
	
	<div id="" style="visibility: collapse;">
	<?php foreach ($objetos_tabela as $idx=>$o):?>
	<div id="<?php echo $idx; ?>">
		<div class="col-md-12 bg-white">
			<div class="form-group">			
				<h3 style="color: #428bca;">Objetos</h3>
				<hr>
				<table class="table">
					<tbody><?php echo $o; ?></tbody>
				</table>
				
				
				<h3 style="color: #428bca;">Regra de Contrapartida</h3>
				<hr>
				<table class="table">
					<tbody><?php echo $qualificacao_tabela[$idx]; ?></tbody>		
				</table>
			</div>
		</div>
	</div>
	<?php endforeach;?>
	</div>
</div>


<div id="preenche_dados_programa" title="Valores do Programa" class="bg-white">
	<form action="#" id="form_modal">
		<div id="append_programa"></div>
		<input type="hidden" name="id_programa_proposta_modal" value="">
		<div class="col-md-8 bg-white">
			<div class="form-group">
				<label>Valor Global do(s) Objeto(s) (R$) * </label> <span style="color: red;" id="disRepassePadrao"></span><input
					type="text" class="form-control obrigatorio campoNumerico numericoRequired" type="text"
					name="valorGlobal_modal" onmouseup="verifica()" onkeyup="verifica()" maxlength="14"
					onmouseout="hints.hide()" id="salvarValorGlobal"
					value="<?php if (isset($proposta) !== false) echo number_format($proposta->valor_global,2,",",".");?>"
					onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
			</div>
			
			<div class="form-group">
				<label>Percentual da contrapartida * </label> <input type="text"
					class="form-control" type="text" maxlength="6"
					name="percentual_modal" id="percentual" onmouseup="verifica()"
					onkeyup="verifica()" onkeypress="reais(this,event)"
					value="<?php if (isset($proposta) !== false) echo number_format($proposta->percentual,2,",",".");?>"
					onkeydown="backspace(this,event)" class="campoNumerico">
			</div>
			
			<div id="oculta_padrao">
				<div class="form-group">
					<label>Contrapartida Financeira (R$) * </label>
					<input type="text" class="form-control campoNumerico" type="text"
						name="valorContrapartidaFinanceira_modal" onmouseup="verifica()" maxlength="14"
						onkeyup="verifica()" onmouseout="hints.hide()"
						id="salvarValorContrapartidaFinanceira"
						value="<?php if (isset($proposta) !== false) echo number_format($proposta->contrapartida_financeira,2,",",".");?>"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
				</div>
				
				<div class="form-group">
					<label>Contrapartida em Bens e Serviços (R$)</label>
					<input type="text" class="form-control campoNumerico"
						name="valorContrapartidaBensServicos_modal" onmouseup="verifica()" maxlength="14"
						onkeyup="verifica()"
						onmouseout="hints.hide()" id="salvarValorContrapartidaBensServicos"
						value="<?php if (isset($proposta) !== false) echo number_format($proposta->contrapartida_bens,2,",",".");?>"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
				</div>
				
				<div class="form-group">
					<label>Total de Contrapartida (R$) </label> <input type="text"
						class="form-control campoNumerico campoSomenteLeitura"
						tabindex="1" name="valorContrapartida_modal" onmouseup="verifica()"
						onkeyup="verifica()" maxlength="14"
						onmouseout="hints.hide()" id="salvarValorContrapartida"
						value="<?php if (isset($proposta) !== false) echo number_format($proposta->total_contrapartida,2,",",".");?>"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)" readonly="readonly">
				</div>
				
				<div class="form-group">
                                    <label>Valor Repasse Específico (R$) - <span id="valorDisponivel"></span></label>
					<input type="text" class="form-control campoNumerico"
						name="valorRepasseespecifico_modal" onmouseup="verifica()"
						onkeyup="verifica()" maxlength="14"
						onmouseout="hints.hide()" id="salvarValorRepasseespecifico"
						value="<?php if (isset($proposta) !== false) echo number_format($proposta->repasse_especifico,2,",",".");?>"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
				</div>
				
				<div class="form-group">
					<label>Valor de Repasse (R$) * </label> <input type="text"
						tabindex="1" name="valorRepasse_modal" onmouseup="verifica()" maxlength="14"
						onkeyup="verifica()"
						onmouseout="hints.hide()" id="salvarValorRepasse"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)"
							value="<?php if (isset($proposta) !== false) echo number_format($proposta->repasse,2,",",".");?>"
						class="form-control campoNumerico campoSomenteLeitura obrigatorio numericoRequired" readonly="readonly">
				</div>
			</div>
		</div>
	</form>
</div>

<style type="text/css">
div#preenche_dados_programa .ui-dialog-titlebar {
   background-color: red;
}
</style>

<script type="text/javascript" language="Javascript1.1"
	src="<?php echo  base_url();?>configuracoes/js/preenchimento_valores_proposta_especifico.js"></script>

<script type="text/javascript">
$(".table tr td:nth-child(2) a").each(function(){
	var link = $(this).attr('href');
	$(this).attr('href', link+'&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest');
});

$(document).ready(function(){
	$("#loader").hide();

    var disponivelGlobal = "";

	<?php if(isset($_GET['padrao']) && $_GET['padrao'] == 1):?>
	$("#oculta_padrao").hide();
	<?php endif;?>
	
	var dialog_dados;
	var idPrograma = null;
	var codigo_programa = null;
	var nome_programa = null;
	var coluna_global = null;

	dialog_dados = $( "#preenche_dados_programa" ).dialog({
		height: parseInt(screen.height/2),
		width: parseInt((screen.width/4)*3),
		modal: true,
		buttons: {
			"Confirmar":function(){
				$("input[name='percentual_modal']").trigger('blur');
				if(enviardadosProposta(document.getElementById("form_modal"))){
					$("input[name='valorGlobal_modal']").trigger('blur');

					var objetos = new Array();
					$('#form_modal input[name="objetos[]"]').each(function(){
						if($(this).is(":checked"))
							objetos.push($(this).val());
					});
					var todosObjetos = objetos.join(",");

					if(todosObjetos.length < 1){
						//alert("Informe um objeto");
						//return false;
					}

					var campos = '<input type="hidden" name="id_programa_proposta[]" value="'+$("#form_modal input[name='id_programa_proposta_modal']").val()+'">'
									+'<input type="hidden" name="id_programa[]" value="'+idPrograma+'">'
									+'<input type="hidden" name="codigo_programa[]" value="'+codigo_programa+'">'
									+'<input type="hidden" name="nome_programa_modal[]" value="'+nome_programa+'">'
									+'<input type="hidden" name="objeto[]" value="'+todosObjetos+'">'
									+'<input type="hidden" name="qualificacao[]" value="'+$("#form_modal input[name='qualificacaoProponente']:checked").val()+'">'
									+'<input type="hidden" name="percentual[]" value="'+$("input[name='percentual_modal']").val()+'">'
									+'<input type="hidden" name="valorGlobal[]" value="'+$("input[name='valorGlobal_modal']").val()+'">'
									+'<input type="hidden" name="valorContrapartida[]" value="'+$("input[name='valorContrapartida_modal']").val()+'">'
									+'<input type="hidden" name="valorContrapartidaFinanceira[]" value="'+$("input[name='valorContrapartidaFinanceira_modal']").val()+'">'
									+'<input type="hidden" name="valorContrapartidaBensServicos[]" value="'+$("input[name='valorContrapartidaBensServicos_modal']").val()+'">'
									+'<input type="hidden" name="valorRepasse[]" value="'+$("input[name='valorRepasse_modal']").val()+'">'
									+'<input type="hidden" name="valorRepasseespecifico[]" value="'+$("input[name='valorRepasseespecifico_modal']").val()+'">';

					if($("#"+idPrograma+"_modal").length)
						$("#"+idPrograma+"_modal").html(campos);
					else
						$("#valores_programa").append("<div id='"+idPrograma+"_modal'>"+campos+"</div>");

					coluna_global.html($("input[name='valorGlobal_modal']").val());

					$("#form_modal input[name='percentual_modal']").attr('class', 'form-control');
					
					$( this ).dialog( "close" );
				}
			},
			"Voltar": function() {
				$("#form_modal input[name='percentual_modal']").attr('class', 'form-control');
				$( this ).dialog( "close" );
			}
		}
	}).position({
	    my: "center",
	    at: "center",
	    of: window
	});

	dialog_dados.dialog( "close" );

	$(".inc_valores").click(function(){
		$("#append_programa").html("");

		<?php if(!isset($_GET['padrao'])):?>
		$("#disRepassePadrao").hide();
		$("#preenche_dados_programa").find('input[type="text"]').each(function(){
			$(this).val("");
		});
		<?php endif;?>

		codigo_programa = trim($(this).parent().parent().children(':nth-child(2)').text());
		nome_programa = $(this).parent().parent().children().find('input[name="nome_programa[]"]').val();
		idPrograma = $(this).parent().parent().children().find('input[name="idRowSelectionAsArray[]"]').val();
		coluna_global = $(this).parent().parent().children(':nth-child(4)');
        
		carrega_campos_modal(idPrograma);

		var valorDisponivel = parseFloat($(this).parent().parent().children().find('input[name="disponivel_programa[]"]').val());

		<?php if(isset($_GET['edit']) && $_GET['edit'] == 1):?>
		valorDisponivel += parseFloat(replaceAll($("input[name='valorRepasseespecifico_modal']").val(), '.','').replace(",", "."));
		<?php endif;?>

		disponivelGlobal = valorDisponivel;
        
        $('#valorDisponivel').html(formatarValoresEmCentavos(parseFloat(disponivelGlobal*100)));

        $("#disRepassePadrao").html("Valor disponível de repasse específico: "+formatarValoresEmCentavos(parseFloat(disponivelGlobal*100)));
		
		$("#append_programa").html($("#"+idPrograma).html());

		dialog_dados.dialog( "open" );

		return false;
	});

	$(".del_valores").click(function(){
		idPrograma = $(this).parent().parent().children().find('input[name="idRowSelectionAsArray[]"]').val();
		$("#"+idPrograma+"_modal").remove();
		
		$(this).parent().parent().remove();

		if($('.table').find('input[name="idRowSelectionAsArray[]"]').length == 0){
			$("#loader").show();
			$("#form_submit").trigger('click');
		}

		return false;
	});

	$("#form_submit").click(function(){
		$("#loader").show();
	});

	function carrega_campos_modal(idPrograma){
		if($("#"+idPrograma+"_modal").length){
			var objetos = $("#"+idPrograma+"_modal input[name='objeto[]']").val().split(',');
			var qualificacao = $("#"+idPrograma+"_modal input[name='qualificacao[]']").val();

			$.each(objetos, function(idx, val){
				$('#'+idPrograma+' input[name="objetos[]"]').each(function(index, value){
					if($(this).val() == val)
						$(this).attr({"checked":true});
				});
			});

			
			$('#'+idPrograma+' input[name="qualificacaoProponente"]').each(function(index, value){
				if($(this).val() == qualificacao)
					$(this).attr({"checked":true});
			});			

			$("#form_modal input[name='id_programa_proposta_modal']").val($("#"+idPrograma+"_modal input[name='id_programa_proposta[]']").val());
			$("#form_modal input[name='percentual_modal']").val($("#"+idPrograma+"_modal input[name='percentual[]']").val());
			$("#form_modal input[name='valorGlobal_modal']").val($("#"+idPrograma+"_modal input[name='valorGlobal[]']").val());
			$("#form_modal input[name='valorContrapartida_modal']").val($("#"+idPrograma+"_modal input[name='valorContrapartida[]']").val());
			$("#form_modal input[name='valorContrapartidaFinanceira_modal']").val($("#"+idPrograma+"_modal input[name='valorContrapartidaFinanceira[]']").val());
			$("#form_modal input[name='valorContrapartidaBensServicos_modal']").val($("#"+idPrograma+"_modal input[name='valorContrapartidaBensServicos[]']").val());
			$("#form_modal input[name='valorRepasse_modal']").val($("#"+idPrograma+"_modal input[name='valorRepasse[]']").val());
			$("#form_modal input[name='valorRepasseespecifico_modal']").val($("#"+idPrograma+"_modal input[name='valorRepasseespecifico[]']").val());
		}
	}

	function exibe_valores_globais(){
		$("input[name='idRowSelectionAsArray[]']").each(function(idx, value){
			if($("#"+$(this).val()+"_modal").length){
				var valor_global = $("#"+$(this).val()+"_modal input[name='valorGlobal[]']").val();
				$(this).parent().parent().children(':nth-child(4)').html(valor_global);
			}
		});
	}

	exibe_valores_globais();

	$("#avanca_form").click(function(){
		var podeEnviar = false;
		$('#formprop input[name="idRowSelectionAsArray[]"]').each(function(){
			if($("#"+$(this).val()+"_modal").length)
				podeEnviar = true;
		});

		if(!podeEnviar){
			alert("Existem programas sem valores informados.");
			return false;
		}

		var total = 0;
		$('#formprop input[name="valorRepasse[]"]').each(function(){
			total += parseFloat(replaceAll($('#formprop input[name="valorRepasse[]"]').val(), '.','').replace(",", "."));
			
		});

		$("#loader").show();
	});

	$("#form_modal input[name='valorContrapartidaFinanceira_modal']").blur(function(){
        if(parseFloat(replaceAll($("input[name='valorContrapartidaFinanceira_modal']").val(), '.', '').replace(',', '.')) > 0.00){
            var valor_global = parseFloat(replaceAll($("input[name='valorGlobal_modal']").val(), '.', '').replace(',', '.'));
            var contrapartida_financeira = parseFloat(replaceAll($("input[name='valorContrapartidaFinanceira_modal']").val(), '.', '').replace(',', '.'));
            var percentual = (contrapartida_financeira/valor_global)*100;
            
            var percentual_modal = parseFloat($("#form_modal input[name='percentual_modal']").val().replace(',', '.'));

            if(percentual > percentual_modal){
                $("#form_modal input[name='percentual_modal']").val("");
                $("#form_modal input[name='percentual_modal']").attr('class', 'form-control');
            }
        }
		
		//$("#form_modal input[name='percentual_modal']").val("");
		//$("#form_modal input[name='percentual_modal']").attr('class', 'form-control numericoRequired');
	});

	$("#form_modal input[name='percentual_modal']").blur(function(){
		if($(this).val() != "")
			$("#form_modal input[name='percentual_modal']").attr('class', 'form-control obrigatorio');
	});
});
</script>

<script type="text/javascript">
var listaMarcados = document.getElementsByTagName("INPUT");  
  for (loop = 0; loop < listaMarcados.length; loop++) {  
     var item = listaMarcados[loop];
     if (item.type == "checkbox") {
       item.name = "objetos[]"; 
     }  
  }

    registraRepasseEspecifico('especifico', 'Valor Repasse Específico (R$)', 'null', 'n');
    
</script>
<script type="text/javascript">
    //Se só possuir um objeto, o mesmo já deve vir selecionado
    if (document.getElementById('salvarObjetos') != null) {
        if (document.getElementById('salvarObjetos').elements != null) {
            if (document.getElementById('salvarObjetos').elements.length == 1) {
                if (document.getElementById('salvarObjetos').elements[i].type == "checkbox") {
                    document.getElementById('salvarObjetos').elements[i].checked = 1;
                }
            }
        } else {
            document.getElementById('salvarObjetos').checked = 1;
        }
    }
    /* Retorna o ID da Qualificação do Proponente */
    function obterIdQualificacaoProponente() {
        var value;
        if (document.getElementById('salvarQualificacaoProponente') != null) {
            if (document.getElementById('salvarQualificacaoProponente').elements != null) {
                for (i = 0; i < document.getElementById('salvarQualificacaoProponente').elements.length; i++) {
                    if (document.getElementById('salvarQualificacaoProponente').elements[i].checked || document.getElementById('salvarQualificacaoProponente').elements[i].checked == 1) {
                        value = this.value;
                        break;
                    }
                }
            } else {
                if (document.getElementById('salvarQualificacaoProponente').checked || document.getElementById('salvarQualificacaoProponente').checked == 1) {
                    value = this.value;
                }
            }
        }

        return value;
    }
</script>