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
<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>

<style type="text/css">
.f-navExp{  /* To fix main menu container */
    z-index: 9998;
    position: fixed;
    top: 116px;
    width: 100%;
    padding: 15px;
}
</style>
	
<script language="JavaScript" type="text/javascript">

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
         
function transferir_cidade() {

var valor = document.getElementById('municipio_nome').value;
//alert(valor);
campo = document.getElementById('municipio_sigla');
campo.value = valor;
}

function transferir_endereco() {
var ind = document.getElementById('endereco_select').selectedIndex;
var valor = document.getElementById('endereco_select')[ind].innerHTML;
endereco = document.getElementById('alterarEndereco');
cep = document.getElementById('alterarCep');
sigla = document.getElementById('municipio_sigla');
var n=valor.split(" # ");
//sigla.value = n[0];
endereco.value = n[0];
cep.value = n[1];
}

function transferir_uf() {
var valor = document.getElementById('uf_nome').value;
campo = document.getElementById('fornecimento');
campo.value = valor;
}

function ValidaCep(cep){
	exp = /\d{5}\-\d{3}/
	if(!exp.test(cep))
		return false;
	return true;	
}

//valida data
function ValidaData(data){
	exp = /\d{2}\/\d{2}\/\d{4}/
	if(!exp.test(data))
		return false;
	return true;
}

function enviardados_form(form){
	var cep = document.getElementById('alterarCep').value;
	if(ValidaCep(cep) == false && cep != ''){
		alert('Verifique se o CEP está no formato válido XXXXX-XXX');
		return false;
	}
	
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

    uf = document.getElementById('incluirUf').value;
    municipio = document.getElementById('municipio_sigla').value;
	if (uf != '' && municipio == '') {
		alert('Ao escolher a UF, o municipio torna-se obrigatório');
        return false;
	}
	
	var dtini = document.getElementById('data_inicio').value;
    var dtfim = document.getElementById('data_termino').value;
   if(ValidaData(dtini) == false || ValidaData(dtfim) == false){
		alert('Verifique se as datas estão no formato válido dd/mm/aaaa');
		return false;
	}
    datInicio = new Date(dtini.substring(6,10),
                         dtini.substring(3,5),
                         dtini.substring(0,2));
    datInicio.setMonth(datInicio.getMonth() - 1);

    datFim = new Date(dtfim.substring(6,10),
                      dtfim.substring(3,5),
                      dtfim.substring(0,2));
                     
    datFim.setMonth(datFim.getMonth() - 1);

    if(datInicio > datFim){
		alert('Data final deve ser maior do que a data inicial.');
		return false;
	}
	
	if (podeEnviar == true) {
        return true;
    } else {
        alert('existem campos obrigatórios em branco!')
        return false;
    }
	return true;
}
function altera_botao(e){
if (e.keyCode == '13'){
	return false; 
	}
}

function desativa_formulario(){
   var x=document.getElementById("form_meta");
 for (var i=0;i<x.length;i++)
 {
   x.elements[i].setAttribute('readonly','readonly');
 }
 document.getElementById("cadastrar").style.display = "none";
 document.getElementById("inserir").style.display = "none";
}
</script>
<style type="text/css">
.carregando {
	color: #666;
	display: none;
}

textarea {
	color: #686868;
}

.dados{
	width: 50%;
}
</style>

<div class="dados innerAll spacing" style="background-color: white;">
<table class="table">
<thead>
<tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr>
<tr>
<th>Valor Global</th>
<th>Valor Cadastrado</th>
<th>Valor a Cadastrar</th>
</tr>

<tr>
<td><?php echo "R$ ".number_format($valorGlobal,2,",","."); ?></td>
<td><?php 
$valorAssociado = 0;
foreach($metas as $m)
	$valorAssociado += $m->total;
echo "<span style='color:green;'>R$ ".number_format($valorAssociado,2,",",".")."</span>"; 
?></td>
<td><?php echo "<span style='color:red;'>R$ ".number_format(round($valorGlobal,2)-round($valorAssociado,2),2,",",".")."</span>"; ?></td>
</tr>
</thead>     
</table>
</div>

<div class=" innerAll spacing-x2">
	<h1 class="bg-white content-heading border-bottom" style="color: #428bca;">DADOS DA META</h1>
	<div class="col-md-12 bg-white">
		<div id="ConteudoDiv">
			<div class="action" id="incluir">
				<div class="trigger" onKeyDown="return altera_botao(event)">
					<form class="form-horizontal" enctype="multipart/form-data"
						method="post" id="form_meta"
						onSubmit="return enviardados_form(this);">
						<input type="hidden" name="id" value="<?php echo $id;?>"> 
						<input type="hidden" name="idMeta" value="<?php if (isset($meta->idMeta) !== false) echo $meta->idMeta;?>">
						<input type="hidden" id="cnpjProponente" value="<?php echo $proposta->proponente; ?>">
						
						<div class="form-group">
							<label>Programa*</label><br>
							
							<?php foreach ($valores_programas as $id_programa=>$valor):?>
							<div class="info_programas" id="<?php echo $id_programa."_info"; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Valor Dispon&iacute;vel:</b> <span style="color: green;">R$ <?php echo number_format($valor, 2, ",", "."); ?></span></div>
							<?php endforeach;?>
							
							<div class="col-sm-6">
								<select class='form-control obrigatorio' name="id_programa" id="id_programa">
									<option value=""></option>
									<?php
									if(count($programas_proposta) == 1)
										$selected = "selected='selected'";
									else
										$selected = "";
									foreach ( $programas_proposta as $programa ) {
										if(isset($meta->idMeta)){
											if($meta->id_programa == $programa->id_programa)
												$selected = "selected='selected'";
											else
												$selected = "";
										}
										echo '<option value="' . $programa->id_programa . '" '.$selected.'>' . $programa->nome_programa . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label>Especificação*</label><br>
							<div class="col-sm-6">
								<textarea class='obrigatorio form-control' id="especificacao"
									onkeyup="verifica();textCounter(this.form.especificacao,this.form.remLen,5000);"
									onchange="textCounter(this.form.especificacao,this.form.remLen,5000);"
									onkeydown="textCounter(this.form.especificacao,this.form.remLen,5000);"
									rows="5" cols="60" size="5000" onkeypress="keySubmit(event)"
									onmouseup="verifica();" style="width: 500"
									onmouseout="hints.hide()"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.especificacao.title')"
									name="especificacao">
<?php if (isset($meta->especificacao) !== false) echo $meta->especificacao;?></textarea>
								<br>
								<div id="caracteresRestantes">
									Caracteres restantes: <input type="text" value="5000"
										maxlength="4" size="4" name="remLen" readonly="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Unidade Fornecimento*</label><br>
							<div class="col-sm-6">
								<select class='form-control' name="uf_nome" id="uf_nome"
									onchange="transferir_uf()">
									<option value=""></option>
							<?php
							foreach ( $ufs as $uf ) {
								echo '<option value="' . $uf->Codigo . '">' . $uf->Nome . '</option>';
							}
							?>
							</select> <input class='obrigatorio form-control' type="text"
									value="<?php if (isset($meta->fornecimento) !== false) echo $meta->fornecimento;?>"
									id="fornecimento" onmouseout="hints.hide()"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.cod.unidade.fornecimento.title')"
									onkeypress="keySubmit(event)" onkeyup="verifica()"
									onmouseup="verifica()" name="fornecimento" size="9">

							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Valor Total (R$)*</label><br> <input
									class='obrigatorio form-control' type="text"
									value="<?php if (isset($meta->total) !== false) echo number_format($meta->total,2,",",".");?>"
									onkeydown="backspace(this,event)"
									onblur="calculaValorUnitario(valorUnitario,quantidade.value,valor.value)"
									onkeypress="reais(this,event)" id="total"
									onmouseout="hints.hide()" maxlength="14"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.valor.title')"
									onkeyup="verifica()" onmouseup="verifica()" name="valor">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Quantidade*</label><br> <input
									class='obrigatorio form-control' type="text"
									value="<?php if (isset($meta->quantidade) !== false) echo number_format($meta->quantidade,2,",",".");?>"
									onkeydown="backspace(this,event)"
									onblur="calculaValorUnitario(valorUnitario,quantidade.value,valor.value)"
									onkeypress="reais(this,event)" id="incluirQuantidade"
									onmouseout="hints.hide()" maxlength="14"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.quantidade.title')"
									onkeyup="verifica()" onmouseup="verifica()" name="quantidade">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Valor Unitário (R$)*</label><br> <input
									class='obrigatorio form-control' type="text"
									value="<?php if (isset($meta->valorUnitario) !== false) echo number_format($meta->valorUnitario,2,",",".");?>"
									onkeydown="backspace(this,event)"
									onkeypress="reais(this,event)" id="incluirValorUnitario"
									readonly="readonly" onmouseout="hints.hide()"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.valor.unitario.title')"
									onkeyup="verifica()" onmouseup="verifica()"
									name="valorUnitario">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Data de Início*</label><br> <input
									class='obrigatorio form-control' type="text"
									OnKeyUp="mascaraData(this);"
									value="<?php if (isset($meta->data_inicio) !== false) echo implode("/",array_reverse(explode("-",$meta->data_inicio)));?>"
									id="data_inicio" maxlength="10" name="data_inicio">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>Data de Término*</label><br> <input
									class='obrigatorio form-control' type="text"
									OnKeyUp="mascaraData(this);"
									value="<?php if (isset($meta->data_termino) !== false) echo implode("/",array_reverse(explode("-",$meta->data_termino)));?>"
									id="data_termino" maxlength="10" name="data_termino">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>UF*</label><br> <select class='form-control obrigatorio'
									id="incluirUf" onkeypress="keySubmit(event)"
									onkeyup="verifica()" onmouseup="verifica()"
									onmouseout="hints.hide()"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.uf.title')"
									name="UF">
									<option value=""></option>
								<?php if (isset($meta->UF) !== false){ ?>
								<option <?php if ($meta->UF == '1') echo "selected=\"true\"";?>
										value="1">AC</option>
									<option
										<?php if (($meta->UF) == '2') echo "selected=\"true\"";?>
										value="2">AL</option>
									<option
										<?php if (($meta->UF) == '4') echo "selected=\"true\"";?>
										value="4">AM</option>
									<option
										<?php if (($meta->UF) == '3') echo "selected=\"true\"";?>
										value="3">AP</option>
									<option
										<?php if (($meta->UF) == '5') echo "selected=\"true\"";?>
										value="5">BA</option>
									<option
										<?php if (($meta->UF) == '6') echo "selected=\"true\"";?>
										value="6">CE</option>
									<option
										<?php if (($meta->UF) == '27') echo "selected=\"true\"";?>
										value="27">DF</option>
									<option
										<?php if (($meta->UF) == '7') echo "selected=\"true\"";?>
										value="7">ES</option>
									<option
										<?php if (($meta->UF) == '8') echo "selected=\"true\"";?>
										value="8">GO</option>
									<option
										<?php if (($meta->UF) == '9') echo "selected=\"true\"";?>
										value="9">MA</option>
									<option
										<?php if (($meta->UF) == '12') echo "selected=\"true\"";?>
										value="12">MG</option>
									<option
										<?php if (($meta->UF) == '11') echo "selected=\"true\"";?>
										value="11">MS</option>
									<option
										<?php if (($meta->UF) == '10') echo "selected=\"true\"";?>
										value="10">MT</option>
									<option
										<?php if (($meta->UF) == '13') echo "selected=\"true\"";?>
										value="13">PA</option>
									<option
										<?php if (($meta->UF) == '14') echo "selected=\"true\"";?>
										value="14">PB</option>
									<option
										<?php if (($meta->UF) == '16') echo "selected=\"true\"";?>
										value="16">PE</option>
									<option
										<?php if (($meta->UF) == '17') echo "selected=\"true\"";?>
										value="17">PI</option>
									<option
										<?php if (($meta->UF) == '15') echo "selected=\"true\"";?>
										value="15">PR</option>
									<option
										<?php if (($meta->UF) == '18') echo "selected=\"true\"";?>
										value="18">RJ</option>
									<option
										<?php if (($meta->UF) == '19') echo "selected=\"true\"";?>
										value="19">RN</option>
									<option
										<?php if (($meta->UF) == '21') echo "selected=\"true\"";?>
										value="21">RO</option>
									<option
										<?php if (($meta->UF) == '22') echo "selected=\"true\"";?>
										value="22">RR</option>
									<option
										<?php if (($meta->UF) == '20') echo "selected=\"true\"";?>
										value="20">RS</option>
									<option
										<?php if (($meta->UF) == '23') echo "selected=\"true\"";?>
										value="23">SC</option>
									<option
										<?php if (($meta->UF) == '25') echo "selected=\"true\"";?>
										value="25">SE</option>
									<option
										<?php if (($meta->UF) == '24') echo "selected=\"true\"";?>
										value="24">SP</option>
									<option
										<?php if (($meta->UF) == '26') echo "selected=\"true\"";?>
										value="26">TO</option>
								</select>
<?php } else { ?>
<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '1') echo "selected=\"true\"";?>
									value="1">AC</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '2') echo "selected=\"true\"";?>
									value="2">AL</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '4') echo "selected=\"true\"";?>
									value="4">AM</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '3') echo "selected=\"true\"";?>
									value="3">AP</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '5') echo "selected=\"true\"";?>
									value="5">BA</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '6') echo "selected=\"true\"";?>
									value="6">CE</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '27') echo "selected=\"true\"";?>
									value="27">DF</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '7') echo "selected=\"true\"";?>
									value="7">ES</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '8') echo "selected=\"true\"";?>
									value="8">GO</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '9') echo "selected=\"true\"";?>
									value="9">MA</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '12') echo "selected=\"true\"";?>
									value="12">MG</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '11') echo "selected=\"true\"";?>
									value="11">MS</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '10') echo "selected=\"true\"";?>
									value="10">MT</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '13') echo "selected=\"true\"";?>
									value="13">PA</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '14') echo "selected=\"true\"";?>
									value="14">PB</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '16') echo "selected=\"true\"";?>
									value="16">PE</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '17') echo "selected=\"true\"";?>
									value="17">PI</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '15') echo "selected=\"true\"";?>
									value="15">PR</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '18') echo "selected=\"true\"";?>
									value="18">RJ</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '19') echo "selected=\"true\"";?>
									value="19">RN</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '21') echo "selected=\"true\"";?>
									value="21">RO</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '22') echo "selected=\"true\"";?>
									value="22">RR</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '20') echo "selected=\"true\"";?>
									value="20">RS</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '23') echo "selected=\"true\"";?>
									value="23">SC</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '25') echo "selected=\"true\"";?>
									value="25">SE</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '24') echo "selected=\"true\"";?>
									value="24">SP</option>
								<option
									<?php if ((isset($endereco_proposta->UF) !== false) && ($endereco_proposta->UF) == '26') echo "selected=\"true\"";?>
									value="26">TO</option>
								</select>
<?php } ?>
					</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-6">
								<label for="cod_cidades">Cidade*</label><br> <span
									class="carregando">Aguarde, carregando...</span> <select
									class="form-control obrigatorio" name="municipio_nome" id="municipio_nome"
									onchange="transferir_cidade()">
									<option value="">-- Escolha um estado --</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-6">
								<label>Código Munícipio*</label><br> <input class="form-control"
									type="text" readonly="readonly"
									value="<?php if (isset($meta->municipio_sigla) !== false) echo $meta->municipio_sigla; else if (isset($endereco_proposta->municipio_sigla) !== false) echo $endereco_proposta->municipio_sigla;?>"
									id="municipio_sigla" maxlength="5" size="5"
									name="municipio_sigla">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-6">
								<label style="color: red;">Recuperar Endereço</label><br> <select class="form-control"
									name="endereco_select" id="endereco_select"
									onchange="transferir_endereco()">
									<option value=""></option>
						<?php
						foreach ( $enderecos as $endereco ) {
							echo '<option value="' . $endereco->endereco . '">' . $endereco->endereco . ' # ' . $endereco->cep . '</option>';
						}
						?>
					</select><br />
					<label>Endereço de Localização*</label><br>
								<textarea id="alterarEndereco" onkeypress="keySubmit(event)"
									onkeyup="verifica()" onmouseup="verifica()" cols="60"
									onmouseout="hints.hide()"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.endereco.title')"
									name="endereco" class="obrigatorio"><?php if (isset($meta->endereco) !== false) echo $meta->endereco; else if (isset($endereco_proposta->endereco) !== false) echo $endereco_proposta->endereco;?></textarea>
									<div id="pageHelpSection">
						<blockquote style="color: red;">Endereço de execução do serviço, da instalação do bem
							ou de localização da obra</blockquote>
					</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>CEP*</label><br> <input class="obrigatorio form-control" type="text"
									value="<?php if (isset($meta->cep) !== false) echo $meta->cep; else if (isset($endereco_proposta->cep) !== false) echo $endereco_proposta->cep;?>"
									id="alterarCep" onmouseout="hints.hide()"
									onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.cep.title')"
									onkeypress="keySubmit(event)" onkeyup="verifica()" maxlength="9"
									onmouseup="verifica()" name="cep">
								<div id="pageHelpSection">
									<blockquote>Campos marcados com (*) são obrigatórios</blockquote>
								</div>
							</div>
						</div>
						<div class="separator bottom"></div>
						<input class="btn btn-primary" type="submit" name="cadastra"
							value="Salvar" id="cadastrar"> <input class="btn btn-primary"
							type="button" value="Voltar"
							onclick="location.href='<?php echo base_url(); ?>index.php/in/usuario/listar_metas?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>';">
<?php if (isset($meta->idMeta) !== false) {?>
	<a class="btn btn-primary" id="inserir" 
							href="<?php echo base_url(); ?>index.php/in/usuario/incluir_etapa_da_meta?meta=<?php echo $meta->idMeta ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>">Inserir
							Etapa</a>
<?php } ?></form>
<br><br>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
		$("#id_programa").change(function(){
			$(".info_programas").slideUp();
			$("#"+$(this).val()+"_info").slideDown();
		});
						
		<?php if($this->session->userdata('nivel') == 1):?>
		
		$(function(){
			$('#incluirUf').change(function(){
				if( $(this).val() ) {
					carregaCidades($(this).val());
				} else {
					$('#municipio_nome').html('<option value="">– Escolha um estado –</option>');
				}
			});

			$(window).scroll(function(){
				var topo = $('.exporta').height();

				var scrollTop = $(window).scrollTop();
		 
				if(scrollTop > topo){
		  			$('.dados').addClass('f-navExp');
				}else{
		  			$('.dados').removeClass('f-navExp');
				}               
			});

			function carregaCidades(valorUF){
				if(valorUF != ""){
					$("#municipio_sigla").val("");
					$('#municipio_nome').hide();
					$('.carregando').show();
					$.getJSON('cidades_ajax?search=',{cod_estados: valorUF, ajax: 'true'}, function(j){
						var options = '<option value=""></option>';	
						for (var i = 0; i < j.length; i++) {
							options += '<option value="' + j[i].Codigo + '">' + j[i].nome + '</option>';
						}	
						$('#municipio_nome').html(options).show();
						$('.carregando').hide();
					});
				}
			}

			carregaCidades($('#incluirUf').val());

			$("#alterarCep").mask("99999-999");
		});
		
		<?php else:?>
		
		function carregaDadosEndereco(){
			var url = "<?php echo base_url(); ?>index.php/in/usuario/busca_dados_endereco?id=<?php echo $_GET['id']; ?>&cnpjProp="+$("#cnpjProponente").val();
			var urlProp = "<?php echo base_url(); ?>index.php/in/usuario/busca_dados_proposta?id=<?php echo $_GET['id']; ?>";
			//$("#idProj").val($(this).attr("id"));
			$.getJSON(url, function(data){
				var optionsUF = '';
				var optionsMun = '';
					
		        $.each(data.estado, function(key, value){
		            optionsUF += '<option value="'+key+'">'+value+'</option>';
		        });

		        $.each(data.municipio, function(key, value){
		        	optionsMun += '<option value="'+key+'">'+value+'</option>';
		        });
		        
		        $("#incluirUf").html(optionsUF);
		        $("#municipio_nome").html(optionsMun);
		       	$("#municipio_sigla").val($("#municipio_nome").val());
		       	$("#municipio_sigla").attr({"readonly":true}); 
		    });
		}

		carregaDadosEndereco();
		
		<?php endif;?>

		<?php foreach ($valores_programas as $id_programa=>$valor):?>
		$("#<?php echo $id_programa;?>_info").hide();
		<?php endforeach;?>
</script>
<?php
if ($leitura_pessoa == true) { // aceito e esperando alterações
	?>
<script type="text/javascript">
desativa_formulario();

</script>
<?php } ?>