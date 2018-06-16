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
	<h1 class="bg-white content-heading border-bottom">Suporte</h1>


	<div class="bg-white">
		<div class="login spacing-x2" style="padding-top: 5%;">
			<div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">

				<form class="form-vertical" role="form" enctype="multipart/form-data" method="post" name="" onSubmit="return enviardados_form(this);">
					<div class="form-group">
						<label for="assunto">Assunto*</label>
						<div class="row innerLR">
							<input type="text" name="assunto" class="form-control obrigatorio">
						</div>
					</div>
					
					<div class="form-group">
						<label for="orgao_nome">Email Secundário <span style="font-size: x-small;">(Opcional)</span></label>
						<div class="row innerLR">
							<input type="text" name="email" class="form-control">
						</div>
					</div>
					
					<div class="form-group">
						<label for="mensagem">Mensagem*</label>
						<div class="row innerLR">
							<textarea id="mensagem" class="form-control obrigatorio" name="mensagem"></textarea>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row innerLR">
							<input class="btn btn-primary" type="submit" name="cadastra" value="Enviar" id="cadastrar"> 
							<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/gestor/';">
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