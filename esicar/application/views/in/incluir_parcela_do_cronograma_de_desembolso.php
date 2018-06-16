<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" language="Javascript1.1"
	src="<?php echo base_url(); ?>configuracoes/js/form-validation.js"></script>

<script type="text/javascript">
function UpdateDoubleSelect(combo, valor) {
  eval('combo = document.' + 
    combo + ';');
  for(index = 0; 
    index < combo.length; 
    index++) {
   if(combo[index].value == valor)
     combo.selectedIndex = index;
   }
}

function transferir_meta() {

	var valor = document.getElementById("associarMetaMeta").options[document.getElementById("associarMetaMeta").selectedIndex].text;
	var n=valor.split("(");
	var texto = n[n.length-1];
	campo = document.getElementById('valorMeta');
	campo.value = texto.substring(0,texto.length-1).replace(".",",");
}

function enviardados_form(form){
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if(valor != "Avançar" && valor != "Salvar" && valor != "Voltar"){
	        if (classe.indexOf('obrigatorio') != -1 && valor == ''){
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
    
    var campos = form.getElementsByTagName('textarea');
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
    
	parcela = document.getElementById('valorParcela').value.replace(".","");
   
	parcela = parseFloat(parcela.replace(",","."));

	if(parcela <= 0){
		alert('O valor da parcela deve ser maior zero.');
		return false;
	}
	
	if (podeEnviar == true) {
        return true;
    } else {
        alert('existem campos obrigatórios em branco!')
        return false;
    }
}

function altera_botao(e){
if (e.keyCode == '13'){
	return false; 
	}
}

function desativa_formulario(){
   var x=document.getElementById("form_cronograma");
 for (var i=0;i<x.length;i++)
 {
   x.elements[i].setAttribute('readonly','readonly');
 }
 document.getElementById("cadastrar").style.display = "none";
}
</script>

<div class="innerALl">
<div class="col-md-6 innerAll spacing-x2" style="margin-bottom: 60px !important;">
					<form class="form-horizontal" id="form_cronograma" method="post"
						enctype="multipart/form-data"
						onSubmit="return enviardados_form(this);">
						<input type="hidden" name="idProposta" value="<?php echo $id; ?>">
						<div class="form-group">
							<div class="col-sm-6">
								<label>Responsável*</label> <br> <select
									class='obrigatorio form-control' name="responsavel"
									onmouseover="hints.show(&#39;incluir.parcela.crono.desembolso.incluir.parcela.cronograma.desembolso.incluir.parcela.param.responsavel.title&#39;)"
									onmouseout="hints.hide()" onmouseup="verifica()"
									onkeyup="verifica()" onkeypress="keySubmit(event)"
									id="incluirParcelaResponsavel">
									<option value=""></option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->responsavel) == 'CONCEDENTE') echo "selected=\"true\"";?>
                                                                                <?php if($contrapartida != $tconvenente) echo " disabled"; ?>
										value="CONCEDENTE">CONCEDENTE</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->responsavel) == 'CONVENENTE') echo "selected=\"true\"";?>
                                                                                <?php if($contrapartida == $tconvenente) echo " disabled"; ?>
										value="CONVENENTE">CONVENENTE</option>
								</select>
                                                                <?php if($contrapartida != $tconvenente): ?>
                                                                <span style="color: red;">&nbsp;* Cadastre primeiramente o convenente.</span>
                                                                <?php else: ?>
                                                                <span style="color: red;">&nbsp;* Cadastre agora o concedente.</span>
                                                                <?php endif; ?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-9">
								<label>Mês*</label> <br>
								<div class=""> 
								
								<div style="color: red; font-size: 11px; width:30%; float:right;">
								Data da vigência<br>
								<?php echo $data_ini." à ".$data_fim; ?>
								</div>
								
								<select class='obrigatorio form-control' name="mes" id="incluirParcelaMes"  style="width: 66%;">
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '01') echo "selected=\"true\"";?>
										value="01">Janeiro</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '02') echo "selected=\"true\"";?>
										value="02">Fevereiro</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '03') echo "selected=\"true\"";?>
										value="03">Março</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '04') echo "selected=\"true\"";?>
										value="04">Abril</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '05') echo "selected=\"true\"";?>
										value="05">Maio</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '06') echo "selected=\"true\"";?>
										value="06">Junho</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '07') echo "selected=\"true\"";?>
										value="07">Julho</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '08') echo "selected=\"true\"";?>
										value="08">Agosto</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '09') echo "selected=\"true\"";?>
										value="09">Setembro</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '10') echo "selected=\"true\"";?>
										value="10">Outubro</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '11') echo "selected=\"true\"";?>
										value="11">Novembro</option>
									<option
										<?php if ((isset($cronograma->mes) !== false) && ($cronograma->mes) == '12') echo "selected=\"true\"";?>
										value="12">Dezembro</option>
								</select>
								
								
								
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Ano</label><br> <input class='obrigatorio form-control'
									type="text" name="ano"
									value="<?php if (isset($cronograma->ano) !== false) echo $cronograma->ano;?>"
									onmouseup="verifica()" onkeyup="verifica()"
									onkeypress="keySubmit(event)"
									onmouseover="hints.show(&#39;incluir.parcela.crono.desembolso.incluir.parcela.cronograma.desembolso.incluir.parcela.param.ano.title&#39;)"
									onmouseout="hints.hide()" size="4" id="incluirParcelaAno">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Valor da Parcela (R$)</label><br>
								<div id="infoParcelaConcedente">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Valor Dispon&iacute;vel:</b> <span style="color: green;">R$ <?php echo number_format($total_concedente, 2, ",", "."); ?></span></div>
								<div id="infoParcelaConvenente">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Valor Dispon&iacute;vel:</b> <span style="color: green;">R$ <?php echo number_format($total_convenente, 2, ",", "."); ?></span></div>
								<input class='obrigatorio form-control' type="text" style="margin-top: 6px;"
									name="valorParcela" id="valorParcela"
									value="<?php if (isset($cronograma->parcela) !== false) echo number_format($cronograma->parcela,2,",",".");?>"
									onmouseup="verifica()" onkeyup="verifica()"
									onmouseover="hints.show(&#39;incluir.parcela.crono.desembolso.incluir.parcela.cronograma.desembolso.incluir.parcela.param.valor.parcela.title&#39;)"
									onmouseout="hints.hide()" id="valorParcela"
									onkeypress="reais(this,event)" maxlength="14"
									onkeydown="backspace(this,event)">
							</div>
						</div>
						<input class="btn btn-primary" type="submit" name="cadastra"
							value="Salvar" id="cadastrar"> <input class="btn btn-primary"
							type="button" value="Voltar"
							onclick="location.href='<?php echo base_url(); ?>index.php/in/usuario/listar_cronograma?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>';">
			<?php if (count($cronograma) > 0){ ?>
			<a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/incluir_meta_do_cronograma_de_desembolso?cronograma=<?php echo $cronograma->idCronograma ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>" >Associar
							Meta</a>
			<?php } ?>
							
				
				
					
					</form>
				</div>
			</div>


<script type="text/javascript">
$(document).ready(function(){
	$("#infoParcelaConcedente").hide();
	$("#infoParcelaConvenente").hide();

	$("#incluirParcelaResponsavel").change(function(){
		verificaTipoResponsavel($(this).val());
	});

	function verificaTipoResponsavel(valor){
		if(valor == "CONCEDENTE"){
			$("#infoParcelaConcedente").slideDown();
			$("#infoParcelaConvenente").slideUp();
		}else if(valor == "CONVENENTE"){
			$("#infoParcelaConcedente").slideUp();
			$("#infoParcelaConvenente").slideDown();
		}else{
			$("#infoParcelaConcedente").slideUp();
			$("#infoParcelaConvenente").slideUp();
		}
	}

	verificaTipoResponsavel($("#incluirParcelaResponsavel").val());
});
										
var valor = document.getElementById("associarMetaMeta").options[document.getElementById("associarMetaMeta").selectedIndex].text;
	var n=valor.split("(");
	var texto = n[n.length-1];
// 	campo = document.getElementById('valorMeta');
// 	if (campo.value == '')
// 		campo.value = texto.substring(0,texto.length-1).replace(".",",");
</script>
<?php
if ($leitura_pessoa == true) { // aceito e esperando alterações
	?>
<script type="text/javascript">
desativa_formulario();
</script>
<?php } ?>
