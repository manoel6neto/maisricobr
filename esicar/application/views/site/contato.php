<script src="<?php echo base_url('configuracoes/js/mapa.js'); ?>"></script>

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

    var field = document.getElementById('email');
    if(field.value != ""){
	    usuario = field.value.substring(0, field.value.indexOf("@"));
	    dominio = field.value.substring(field.value.indexOf("@")+ 1, field.value.length);
	
	    if ((usuario.length >=1) &&
	        (dominio.length >=3) && 
	        (usuario.search("@")==-1) && 
	        (dominio.search("@")==-1) &&
	        (usuario.search(" ")==-1) && 
	        (dominio.search(" ")==-1) &&
	        (dominio.search(".")!=-1) &&      
	        (dominio.indexOf(".") >=1)&& 
	        (dominio.lastIndexOf(".") < dominio.length - 1)) {
	    		
	    }else{
	    	alert("Email invalido");
	    	return false;
	    }
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
        
<style type="text/css">
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

#conteudoSistema{
	margin: auto;
	padding: 2%;
}

#mapa{
	width: 49%;
	height: 500px;
	border: 1px solid #ccc;
	margin-bottom: 20px;
	float: left;
}

#form{
	width: 49%;
	height: 500px;
	margin-left: 51%;
	text-align: left;
}
</style>

<div id="conteudoSistema">

<div id="mapa"></div>

<div id="form">
	<h1 class="content-heading border-bottom" style="margin-top: 0; color: white;">Contato</h1>

	<div class="bg-white">
		<div class="login spacing-x2">
			<div class="col-md-12 bg-white">

				<form class="form-vertical" role="form" enctype="multipart/form-data" method="post" name="" onSubmit="return enviardados_form(this);">
					<?php echo validation_errors(); ?>
					<div class="form-group">
						<label for="orgao_nome">Nome*</span></label>
						<div class="row innerLR">
							<input type="text" name="nome" maxlength="50" class="form-control obrigatorio">
						</div>
					</div>
					
					<div class="form-group">
						<label for="assunto">Email*</label>
						<div class="row innerLR">
							<input type="text" name="email" id="email" class="form-control obrigatorio">
						</div>
					</div>
					
					<div class="form-group">
						<label for="assunto">Assunto*</label>
						<div class="row innerLR">
							<input type="text" name="assunto" class="form-control obrigatorio">
						</div>
					</div>
					
					<div class="form-group">
						<label for="mensagem">Mensagem*</label>
						<div class="row innerLR">
							<textarea id="mensagem" rows="5" class="form-control obrigatorio" name="mensagem"></textarea>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row innerLR">
							<input class="btn btn-primary" type="submit" name="envia" value="Enviar" id="cadastrar">
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

</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#geral").css('background-size', '900px 536px');
});
</script>