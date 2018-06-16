<script src="<?php echo base_url('configuracoes/js/maskedinput.min.js'); ?>"></script>
<script>
function transferir_cidade() {
var valor = document.getElementById('municipio_nome').value;
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
endereco.value = n[1];
cep.value = n[2];
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

function enviardados_form(form){
	
	var cep = document.getElementById('alterarCep').value;

	if(ValidaCep(cep) == false){
		alert('Verifique se o CEP está no formato válido XXXXX-XXX');
		return false;
	}
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if ((classe.indexOf('obrigatorio') != -1) && (valor == '')) 
            podeEnviar = false;
    }
    
    var campos = form.getElementsByTagName('select');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if ((classe.indexOf('obrigatorio') != -1) && (valor == '')) 
            podeEnviar = false;
    }
    
    var campos = form.getElementsByTagName('textarea');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if ((classe.indexOf('obrigatorio') != -1) && (valor.trim() == '')) 
            podeEnviar = false;
    }

    uf = document.getElementById('incluirUf').value;
    municipio = document.getElementById('municipio_sigla').value;
	if (uf != '' && municipio == '') {
		alert('Ao escolher a UF, o municipio torna-se obrigatório');
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
</script>
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

.codigo {
	font-size: small;
	float: right;
}

.media {
	width: 100%;
}

label{
	color: #428bca;
}
</style>

<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">Alterar Endereço</h1>


	<div class="bg-white">
		<div class="login spacing-x2" style="padding-top: 5%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">

				<form class="form-vertical" role="form"
					enctype="multipart/form-data" method="post" name=""
					onSubmit="return enviardados_form(this);">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<input type="hidden" id="cnpjProponente" value="<?php echo $proposta->proponente; ?>">


					<div class="form-group">
						<label for="orgao_nome">Estado</label>
						<div class="row innerLR">
							<select id="incluirUf" onkeypress="keySubmit(event)"
								class="form-control" onkeyup="verifica()" onmouseup="verifica()"
								onmouseout="hints.hide()"
								onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.incluir.param.uf.title')"
								name="UF">
								<option value=""></option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '1') echo "selected=\"true\"";?>
									value="1">AC</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '2') echo "selected=\"true\"";?>
									value="2">AL</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '4') echo "selected=\"true\"";?>
									value="4">AM</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '3') echo "selected=\"true\"";?>
									value="3">AP</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '5') echo "selected=\"true\"";?>
									value="5">BA</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '6') echo "selected=\"true\"";?>
									value="6">CE</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '27') echo "selected=\"true\"";?>
									value="27">DF</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '7') echo "selected=\"true\"";?>
									value="7">ES</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '8') echo "selected=\"true\"";?>
									value="8">GO</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '9') echo "selected=\"true\"";?>
									value="9">MA</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '12') echo "selected=\"true\"";?>
									value="12">MG</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '11') echo "selected=\"true\"";?>
									value="11">MS</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '10') echo "selected=\"true\"";?>
									value="10">MT</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '13') echo "selected=\"true\"";?>
									value="13">PA</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '14') echo "selected=\"true\"";?>
									value="14">PB</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '16') echo "selected=\"true\"";?>
									value="16">PE</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '17') echo "selected=\"true\"";?>
									value="17">PI</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '15') echo "selected=\"true\"";?>
									value="15">PR</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '18') echo "selected=\"true\"";?>
									value="18">RJ</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '19') echo "selected=\"true\"";?>
									value="19">RN</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '21') echo "selected=\"true\"";?>
									value="21">RO</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '22') echo "selected=\"true\"";?>
									value="22">RR</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '20') echo "selected=\"true\"";?>
									value="20">RS</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '23') echo "selected=\"true\"";?>
									value="23">SC</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '25') echo "selected=\"true\"";?>
									value="25">SE</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '24') echo "selected=\"true\"";?>
									value="24">SP</option>
								<option
									<?php if ((isset($meta->UF) !== false) && ($meta->UF) == '26') echo "selected=\"true\"";?>
									value="26">TO</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="orgao_nome">Código do Município</label>
						<div class="row innerLR">
							<input type="text" class="form-control"
								value="<?php if (isset($meta->municipio_sigla) !== false) echo $meta->municipio_sigla;?>"
								id="municipio_sigla" maxlength="5" size="5"
								name="municipio_sigla">
						</div>
					</div>

					<div class="form-group">
						<label for="orgao_nome">Município</label>
						<div class="row innerLR">
							<select name="municipio_nome" class="form-control"
								id="municipio_nome" onchange="transferir_cidade()">
								<option value=""></option>
						<?php
						foreach ( $cidades as $cidade ) {
							echo '<option value="' . $cidade->id_cidade . '">' . $cidade->Nome . '</option>';
						}
						?>
						</select>
						</div>
					</div>

					<div class="form-group">
						<label for="orgao_nome">Lista de endereços</label>
						<div class="row innerLR">

							<select name="endereco_select" id="endereco_select"
								class="form-control" onchange="transferir_endereco()">
								<option value=""></option>
						<?php
						foreach ( $enderecos as $endereco ) {
							echo '<option value="' . $endereco->endereco . '">' . $endereco->municipio_sigla . ' # ' . $endereco->endereco . ' # ' . $endereco->cep . '</option>';
						}
						?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="orgao_nome">Endereço</label>
						<div class="row innerLR">
							<textarea id="alterarEndereco" onkeypress="keySubmit(event)"
								class="form-control obrigatorio" onkeyup="verifica()" onmouseup="verifica()"
								onmouseout="hints.hide()"
								onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.endereco.title')"
								name="endereco"><?php if (isset($meta->endereco) !== false) echo $meta->endereco;?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="orgao_nome">CEP</label>
						<div class="row innerLR">

							<input type="text" class="form-control obrigatorio"
								value="<?php if (isset($meta->cep) !== false) echo $meta->cep;?>"
								id="alterarCep" onmouseout="hints.hide()" maxlength="9"
								onmouseover="hints.show('cadastrar.meta.crono.fisico.meta.alterar.param.cep.title')"
								onkeypress="keySubmit(event)" onkeyup="verifica()"
								onmouseup="verifica()" name="cep">
						</div>
					</div>
					<div class="form-group">
						<div class="row innerLR">
							<input class="btn btn-primary" type="submit" name="cadastra"
								value="Salvar" id="cadastrar"> <input class="btn btn-primary"
								type="button" value="Voltar"
								onclick="location.href='<?php echo base_url(); ?>index.php/in/gestor/gerencia_proposta';">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
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

$(document).ready(function(){
	$("#alterarCep").mask("99999-999");
});
</script>