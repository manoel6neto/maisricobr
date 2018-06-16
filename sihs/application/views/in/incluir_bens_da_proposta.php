<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url(); ?>configuracoes/css/style.css">

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
<script type="text/javascript"
	src="<?php echo base_url(); ?>configuracoes/js/thumbnailviewer.js"></script>

<script type="text/javascript"
	src="<?php echo base_url(); ?>configuracoes/js/jquery-1.8.2.min.js"
	charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo base_url(); ?>configuracoes/js/jquery-ui-1.9.0.custom.min.js"
	charset="utf-8"></script>
<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>
<script type="text/javascript">
function transferir_cidade() {
var valor = document.getElementById('municipio_nome').value;
campo = document.getElementById('municipio');
campo.value = valor;
}

function transferir_uf() {
var valor = document.getElementById('uf_nome').value;
campo = document.getElementById('fornecimento');
campo.value = valor;
}

function transferir_unidade(){
	var valor = document.getElementById('fornecimento').value;
	campo = document.getElementById('uf_nome');
	campo.value = valor;
}

function transferir_natureza() {
var valor = document.getElementById('select2_1').value;
campo = document.getElementById('natureza_despesa');
campo.value = valor;

// var tipo_despesa_natureza = "";
// if(valor.substr(0,2) == 33)
// 	tipo_despesa_natureza =  "Despesa de custeio";
// else if(valor.substr(0,2) == 44)
// 	tipo_despesa_natureza =  "Despesa de capital";

// document.getElementById('tipo_despesa_natureza').innerHTML = tipo_despesa_natureza;
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

function enviardados_form(form){
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if(valor != "Avançar" && valor != "Salvar" && valor != "Voltar" && valor != "Sair"){
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
    
    if (podeEnviar == true) {
        return true;
    } else {
        alert('existem campos obrigatórios em branco!')
        return false;
    }

	var dtini = document.getElementById('data_inicio').value;
    var dtfim = document.getElementById('data_termino').value;
   
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
	return true;
}
function altera_botao(e){
if (e.keyCode == '13'){
	return false; 
	}
}

function desativa_formulario(){
   var x=document.getElementById("form_bens");
 for (var i=0;i<x.length;i++)
 {
   x.elements[i].setAttribute('readonly','readonly');
 }
 document.getElementById("cadastrar").style.display = "none";
}
</script>

<div class="innerALl spacing-x2">

<table class="table">
<thead>
<tr><th style="color: red; font-size: 16px;" colspan="3">Valor de Referência</th></tr>
<tr>
<th>Valor Global</th>
<th>Status</th>
<th style="color: red;">Valor Cadastrado</th>
<th style="color: green;">Valor a Cadastrar</th>
</tr>

<tr>
<td><?php echo number_format($dadosProposta[0]['valor_global'], 2, ",", "."); ?></td>
<td>
<?php 
$status = '<i title="Pendente" class="btn-sm btn-primary fa fa-warning"></i>';

if(round($dadosProposta[0]['valor_global'],2)-round($dadosProposta[0]['valor_despesa'],2) == 0)
	$status = '<i title="Completo" class="btn-sm btn-success fa fa-check-square"></i>';

echo $status;
?>
</td>
<td style="color: red;"><?php echo number_format($dadosProposta[0]['valor_despesa'], 2, ",", "."); ?></td>
<td style="color: green;"><?php echo number_format(round($dadosProposta[0]['valor_global'],2)-round($dadosProposta[0]['valor_despesa'],2), 2, ",", "."); ?></td>
</tr>
</thead>     
</table>

<div class="col-md-12 col-sm-12">
	
<span style="color: red; font-size: 16px;" >Valor Crono Físico</span>


	<div class="widget-body ">
		<div class="panel-group accordion" id="accordion">
		
		</div>
	</div>
	
	<div class="panel" style="background-color: #f3f3f3;">
	<?php $i = 0;?>
	<?php foreach ($dadosEtapas as $dadosEtapa):?>
		<div class="panel-heading">
			<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $i; ?>"><span style="color: red; font-size: 14px;"><?php echo $dadosEtapa[0]; ?></span></a></h4>
		</div>
		<div id="collapse-<?php echo $i; ?>" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="border-bottom tickets">
					<div class="row">
<!-- 	                	<div class="col-md-10 pull-left"> -->
							<div class="pull-left">
								<?php  foreach ($dadosEtapa[1] as $dados): ?>
									<p style="font-size: 14px;"><?php echo $dados['especificacao']; ?>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo "R$ ".number_format($dados['total'],2,",","."); ?></p>
								<?php endforeach; ?>
							</div>
<!-- 						</div> -->
					</div>
				</div>
			</div>
		</div>
		<?php $i++;?>
	<?php endforeach; ?>
	</div>



	<h1 class="bg-white content-heading border-bottom" style="color: #428bca;">Incluir Plano Detalhado</h1>
		<div class="innerAll spacing-x2">
		<br>
		<form class="form-horizontal" name="form" id="form_bens" method="post"
			enctype="multipart/form-data"
			onSubmit="return enviardados_form(this);">
			<input type="hidden" name="idProposta" value="<?php echo $id; ?>"> <input
				type="hidden" name="idDespesa_update"
				value="<?php if (isset($despesa->idDespesa) !== false) echo $despesa->idDespesa; ?>">
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
							if(isset($despesa->id_programa)){
								if($despesa->id_programa == $programa->id_programa)
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
				<div class="col-sm-6">
					<label>Tipo Despesa*</label> <br> <select
						class='obrigatorio  form-control' name="tipoDespesa"
						id="tipo_despesa">
						<option value="<?php echo $tipo_despesa->idTipo_despesa; ?>"
							selected="selected"><?php echo $tipo_despesa->nome; ?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>Descricao Item*</label> <br>
					<textarea class='obrigatorio form-control' name="descricao"
						size="5000" cols="60" rows="5" onmouseup="verifica()"
						onkeyup="textCounter(this.form.descricao,this.form.remLen,5000);"
						onchange="textCounter(this.form.descricao,this.form.remLen,5000);"
						onkeypress="keySubmit(event)"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.descricao.item.title&#39;)"
						onmouseout="hints.hide()" id="descricao">
<?php if (isset($despesa->descricao) !== false) echo $despesa->descricao;?></textarea>
					<div id="caracteresRestantes">
						Caracteres restantes: <input readonly="" type="text" name="remLen"
							size="4" maxlength="4" value="5000">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>Natureza Aquisição*</label> <br>
					<script type="text/javascript">
         		 corrigeDescricao(); 
			</script>
					<select class='obrigatorio form-control' name="natureza_aquisicao"
						id="natureza_aquisicao">
						<option
							<?php if ((isset($despesa->natureza_aquisicao) !== false) && ($despesa->natureza_aquisicao) == '1') echo "selected=\"true\"";?>
							value="1">Recursos do convênio</option>
						<?php if($proposta->contrapartida_bens > 0){?>
						<option
							<?php if ((isset($despesa->natureza_aquisicao) !== false) && ($despesa->natureza_aquisicao) == '2') echo "selected=\"true\"";?>
							value="2">Contrapartida bens e serviços</option>
						<?php }?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-7">
					<label>Código da Natureza de Despesa*</label> <br>
					<div class=""> 
					<select name="natureza_nome" id="select2_1" style="width: 85%;" onchange="transferir_natureza()">
						<option value=""></option>
						<?php
						foreach ( $natureza as $nat ) {
							echo '<option ';
							if (isset ( $despesa->natureza_despesa ) !== false && $despesa->natureza_despesa == $nat->Codigo)
								echo "selected=\"true\"";
							echo 'value="' . $nat->Codigo . '">' . $nat->Codigo." - ".$nat->Nome . '</option>';
						}
						?>
					</select>
					
					<div id="tipo_despesa_natureza" style="color: red; font-size: 11px; width:14%; float:right;">
					33 - Despesa de custeio<br>
					44 - Despesa de capital
					</div>
					
					</div>
					
					<input
						class='obrigatorio form-control' type="text"
						name="natureza_despesa" size="8" maxlength="8" style="width: 85%;"
						value="<?php if (isset($despesa->natureza_despesa) !== false) echo $despesa->natureza_despesa;?>"
						onmouseup="verifica()" onkeyup="verifica()"
						onkeypress="keySubmit(event)"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.codigo.natureza.despesa.title&#39;)"
						onmouseout="hints.hide()" id="natureza_despesa" readonly="readonly">
				</div>
			</div>
			<!-- <div class="form-group">
				<div class="col-sm-6">
					<label>Descrição da Natureza de Despesa*</label> <br>
					<textarea name="natureza_despesa_descricao" cols="60"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.descricao.natureza.despesa.title&#39;)"
						onmouseout="hints.hide()" onmouseup="verifica()"
						onkeyup="verifica()" onkeypress="keySubmit(event)"
						id="natureza_despesa_descricao">
<?php if (isset($despesa->natureza_despesa_descricao) !== false) echo $despesa->natureza_despesa_descricao;?></textarea>
					<div id="pageHelpSection">
						<blockquote>Este campo é preenchido automaticamente</blockquote>
					</div>
				</div>
			</div> -->
			<div class="form-group">
				<div class="col-sm-6">
					<label>Unidade Fornecimento*</label> <br> <select
						class='form-control' name="uf_nome" id="uf_nome"
						onchange="transferir_uf()">
						<option value=""></option>
						<?php
						foreach ( $ufs as $uf ) {
							echo '<option ';
							if (isset ( $despesa->fornecimento ) !== false && $despesa->fornecimento == $uf->Codigo)
								echo "selected=\"true\"";
							echo 'value="' . $uf->Codigo . '">' . $uf->Nome . '</option>';
						}
						?>
					</select>
					<input
						class='obrigatorio form-control' type="text" size="9"
						name="fornecimento" onchange="transferir_unidade()"
						value="<?php if (isset($despesa->fornecimento) !== false) echo $despesa->fornecimento;?>"
						onmouseup="verifica()" onkeyup="verifica()" 
						onkeypress="keySubmit(event)"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.cod.unidade.fornecimento.title&#39;)"
						onmouseout="hints.hide()" id="fornecimento">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>Valor Total (R$)*</label> <br> <input
						class='obrigatorio form-control' type="text" name="total"
						value="<?php if (isset($despesa->total) !== false) echo number_format($despesa->total,2,",",".");?>"
						onmouseup="verifica()"
						onblur="calculaValorUnitario(valor_unitario,quantidade.value,total.value)"
						onkeyup="verifica()"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.valor.title&#39;)"
						onmouseout="hints.hide()" id="total" maxlength="14"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>Quantidade*</label> <br> <input
						class='obrigatorio form-control' type="text" name="quantidade"
						value="<?php if (isset($despesa->quantidade) !== false) echo number_format($despesa->quantidade,2,",",".");?>"
						onblur="calculaValorUnitario(valor_unitario,quantidade.value,total.value)"
						onmouseup="verifica()" onkeyup="verifica()"
						onkeypress="reais(this,event)" maxlength="14"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.quantidade.title&#39;)"
						onmouseout="hints.hide()" id="quantidade">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>Valor Unitário (R$)*</label> <br> <input
						class='obrigatorio form-control' type="text" name="valor_unitario"
						value="<?php if (isset($despesa->valor_unitario) !== false) echo number_format($despesa->valor_unitario,2,",",".");?>"
						readonly="readonly" onmouseup="verifica()" onkeyup="verifica()"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.valor.unitario.title&#39;)"
						onmouseout="hints.hide()" id="incluirBensValorUnitario"
						onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label style="color: red;">Recuperar Endereço</label> <br> <select
						class='form-control' name="endereco_select" id="endereco_select"
						onchange="transferir_endereco()">
						<option value=""></option>
						<?php
						foreach ( $enderecos as $endereco ) {
							echo '<option value="' . $endereco->endereco . '">' . $endereco->endereco . ' # ' . $endereco->cep . '</option>';
						}
						?>
					</select><br />
					<label>Endereço de Localização *</label><br>
					<textarea class='obrigatorio' name="endereco" cols="60"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.endereco.title&#39;)"
						onmouseout="hints.hide()" onmouseup="verifica()"
						onkeyup="verifica()" onkeypress="keySubmit(event)"
						id="alterarEndereco"><?php if (isset($despesa->endereco) !== false) echo $despesa->endereco; else if (isset($endereco_proposta->endereco) !== false) echo $endereco_proposta->endereco;?></textarea>
					<div id="pageHelpSection">
						<blockquote style="color: red;">Endereço de execução do serviço, da instalação do bem
							ou de localização da obra</blockquote>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>CEP*</label> <br> <input class='obrigatorio form-control'
						type="text" size="9" maxlength="9" name="cep" maxlength="9"
						value="<?php if (isset($despesa->cep) !== false) echo $despesa->cep; else if (isset($endereco_proposta->cep) !== false) echo $endereco_proposta->cep;?>"
						onmouseup="verifica()" onkeyup="verifica()"
						onkeypress="keySubmit(event);formatar_mascara(this, &#39;#####-###&#39;)"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.cep.title&#39;)"
						onmouseout="hints.hide()" id="alterarCep">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
					<label>UF*</label> <br> <select id="incluirUf" class="form-control obrigatorio"
							onkeypress="keySubmit(event)" onkeyup="verifica()"
							onmouseup="verifica()" onmouseout="hints.hide()"
							onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.uf.title')"
							name="UF">
							<option value=""></option>
							<?php
							foreach ( $programa_model->getListaEstados() as $codigo=>$nome ) {
								echo '<option value="' . $codigo . '">' . $nome . '</option>';
							}
							?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
				<label>Cidade *</label>
					<select
						class='form-control obrigatorio' name="municipio_nome" id="municipio_nome"
						onchange="transferir_cidade()">
						<option value=""></option>
					</select>
					<label>Código do Município*</label> <br> <input
						class='obrigatorio form-control' type="text" name="municipio" id="municipio_sigla" readonly="readonly"
						size="5" maxlength="5"
						value="<?php if (isset($despesa->municipio) !== false) echo $despesa->municipio; else if (isset($endereco_proposta->municipio_sigla) !== false) echo $endereco_proposta->municipio_sigla;?>"
						onmouseup="verifica()" onkeyup="verifica()"
						onkeypress="keySubmit(event)"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.codigo.municipio.title&#39;)"
						onmouseout="hints.hide()" id="municipio"> 
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label>Observação</label> <br>
					<textarea name="observacao" size="5000" cols="60" rows="5"
						value="<?php if (isset($despesa->observacao) !== false) echo $despesa->observacao;?>"
						onmouseup="verifica();"
						onchange="textCounter(this.form.observacao,this.form.remLen1,5000);"
						onkeydown="textCounter(this.form.observacao,this.form.remLen1,5000);"
						onkeyup="verifica();textCounter(this.form.observacao,this.form.remLen1,5000);"
						onkeypress="keySubmit(event)"
						onmouseover="hints.show(&#39;incluir.bens.proposta.incluir.bens.proposta.incluir.bens.param.observacao.title&#39;)"
						onmouseout="hints.hide()" id="incluirBensObservacao">
<?php if (isset($despesa->observacao) !== false) echo $despesa->observacao;?></textarea>
					<div id="caracteresRestantes">
						Caracteres restantes: <input readonly="" type="text"
							name="remLen1" size="4" maxlength="4" value="5000">
					</div>
					</div>
			</div>
					<input class="btn btn-primary" type="submit" name="cadastra"
							value="Salvar" id="cadastrar"> <input class="btn btn-primary" type="button"
							value="Sair"
							onclick="location.href='<?php echo base_url(); ?>index.php/in/usuario/listar_obras?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>';">
		
		</form>
	</div></div>
</div>

<script type="text/javascript">
$("#id_programa").change(function(){
	$(".info_programas").slideUp();
	$("#"+$(this).val()+"_info").slideDown();
});

$(document).ready(function(){
	$("#alterarCep").mask("99999-999");
});
							
<?php if($this->session->userdata('nivel') != 1):?>
							
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

<?php else:?>

$('#incluirUf').change(function(){
	if( $(this).val() ) {
		carregaCidades($(this).val());
	} else {
		$('#municipio_nome').html('<option value="">– Escolha um estado –</option>');
	}
});

function carregaCidades(valorUF, id_cidade){
	if(valorUF != ""){
		$("#municipio_sigla").val("");
		$('#municipio_nome').html('<option value="">Carregando...</option>');
		$.getJSON('cidades_ajax?search=',{cod_estados: valorUF, ajax: 'true'}, function(j){
			var options = '<option value=""></option>';
			var selected = "";	
			for (var i = 0; i < j.length; i++) {
				if(j[i].Codigo == id_cidade)
					selected = "selected='selected'";
				else
					selected = "";
				
				options += '<option value="' + j[i].Codigo + '" '+selected+'>' + j[i].nome + '</option>';
			}	
			$('#municipio_nome').html(options);
		});
	}
}

$("#municipio_nome").change(function(){
	$("#municipio_sigla").val($("#municipio_nome").val());
});

carregaCidades($('#incluirUf').val());

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

