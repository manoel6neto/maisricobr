<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script>
function enviardados_form(form){
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
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
	
	if (podeEnviar == true) {
        return true;
    } else {
        alert('Existem campos obrigatórios em branco!')
        return false;
    }
	return true;
}

setTimeout(function(){
	$("#msgEmail").hide();
}, 3000);
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
</style>

<div id="content" class="bg-white">
	<h1 class="bg-white content-heading border-bottom">Novo Documento</h1>


	<div class="bg-white">
		<div class="login spacing-x2" style="padding-top: 5%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">

				<form class="form-vertical" role="form" enctype="multipart/form-data" method="post" name="" onSubmit="return enviardados_form(this);">
					<div class="form-group">
						<label for="mensagem">Descrição*</label>
						<div class="row innerLR">
							<textarea id="descricao" class="form-control obrigatorio" name="descricao"
							 onkeydown="textCounter(this.form.descricao,this.form.remLen,5000);" onkeyup="verifica();textCounter(this.form.descricao,this.form.remLen,5000);" onchange="textCounter(this.form.descricao,this.form.remLen,5000);"></textarea>
							<div id="caracteresRestantes"> Caracteres restantes: <input readonly="" type="text" name="remLen" size="4" maxlength="4" value="5000"></div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="mensagem">Arquivo para Download*</label>
						<div class="row innerLR">
							<input id="arquivo" class="form-control obrigatorio" name="arquivo" type="file">
						</div>
					</div>
					
					<div class="form-group">
						<label for="mensagem">Print do Arquivo</label>
						<div class="row innerLR">
							<input id="print_arquivo" class="form-control" name="print_arquivo" type="file">
						</div>
					</div>
					
					<div class="form-group">
						<label for="tipo_arquivo">Tipo do Arquivo</label>
						<div class="row innerLR">
							<input type="radio" value="D" name="tipo_arquivo" checked="checked">Declaração
							<input type="radio" value="I" name="tipo_arquivo">Informação
						</div>
					</div>
					
					<div class="form-group">
						<div class="row innerLR">
							<input class="btn btn-primary" type="submit" name="cadastra" value="Salvar" id="cadastrar"> 
							<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/usuario/modelos_documentos';">
						</div>
					</div>
					
					<div id="msgEmail">
					<div class="form-group">
						<div class="row innerLR">
							<?php echo $retornoEnvio; ?>
						</div>
					</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>