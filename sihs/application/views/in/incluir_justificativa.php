<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>configuracoes/css/style.css">
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/form-validation.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">
<script type="text/javascript">
function desativa_formulario(){
   var x=document.getElementById("form_justificativa");
 for (var i=0;i<x.length;i++)
 {
   x.elements[i].setAttribute('readonly','readonly');
 }
 document.getElementById("cadastrar").style.display = "none";
  document.getElementById("enviar").style.display = "none";
  document.getElementById("obs_gestor").removeAttribute('readonly');
}

function enviardados_form(form){
	
	var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'obrigatorio' && valor == '')
	            podeEnviar = false;
    }
    
    var campos = form.getElementsByTagName('select');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'obrigatorio' && valor == '') podeEnviar = false;
    }
    
    var campos = form.getElementsByTagName('textarea');
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'obrigatorio' && valor == ''){
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
}
</script>
<div class="col-md-16 bg-white innerAll spacing-x2">
    <h1 class="bg-white content-heading border-bottom" style="color: #428bca;">INCLUIR JUSTIFICATIVA E OBJETO</h1>
    	<div class="innerAll spacing-x2">
		<div class="trigger">
			<form class="form-horizontal" name="justificativa" id="form_justificativa" method="post" enctype="multipart/form-data" onSubmit="return enviardados_form(this);">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			
<div class="form-group">

<label>Justificativa*</label>
<br>
</nobr>
<div id="pageHelpSection">
	<blockquote>
		Descrever os objetivos e benefícios a serem alcançados com a execução do objeto do convênio/contrato de repasse.
	</blockquote>
</div>
<textarea style="width: 700px;color: #686868;" class='obrigatorio' name="Justificativa" cols="60" rows="10" onmouseup="verifica();" onkeydown="textCounter(this.form.Justificativa,this.form.remLen,5000);" onkeyup="verifica();textCounter(this.form.Justificativa,this.form.remLen,5000);" onchange="textCounter(this.form.Justificativa,this.form.remLen,5000);" onkeypress="keySubmit(event)" onmouseover="hints.show(&#39;incluir.dados.proposta.incluir.proposta.cadastrar.proposta.param.justificativa.title&#39;)" onmouseout="hints.hide()" id="inserirPropostaJustificativa">
<?php if (isset($justificativa->Justificativa) !== false) echo $justificativa->Justificativa;?></textarea>
<div id="caracteresRestantes"> Caracteres restantes: <input readonly="readonly" type="text" name="remLen" size="4" maxlength="4" value="5000"></div>

<input type="checkbox" id="inc_texto_padrao"><span style="color: red;">&nbsp;Adicionar Dados do Município(IBGE)</span>
<?php if($this->session->userdata('nivel') == 1):?>
<br><input type="checkbox" name="necessita_completar" value="1" <?php if(isset($justificativa->necessita_completar) && $justificativa->necessita_completar){echo "checked='checked'";}?>><span style="color: red;">&nbsp;Completar Justificativa</span>
<br>
<?php endif;?>
<br><br>
<label>Objeto do Convênio*</label>
<br>
					<div id="pageHelpSection">
						<blockquote>
							Descrever o objeto a que se destina o convênio/contrato de repasse de forma clara e resumida.
						</blockquote>
       				</div>
						 
						<textarea style="width: 700px;color: #686868;" class='obrigatorio' name="objeto" cols="60" rows="10" onmouseup="verifica();" onkeydown="textCounter(this.form.objeto,this.form.remLen1,5000);" onkeyup="verifica();textCounter(this.form.objeto,this.form.remLen1,5000);" onchange="textCounter(this.form.objeto,this.form.remLen1,5000);" onkeypress="keySubmit(event)" onmouseover="hints.show(&#39;incluir.dados.proposta.incluir.proposta.cadastrar.proposta.param.objeto.convenio.title&#39;)" onmouseout="hints.hide()" id="cadastrarPropostaObjetoConvenio">
<?php if (isset($justificativa->objeto ) !== false) echo $justificativa->objeto ;?></textarea>
						<div id="caracteresRestantes"> Caracteres restantes: <input readonly="readonly" type="text" name="remLen1" size="4" maxlength="4" value="5000"></div>
					</td>
				</tr>
<tr><td class="FormLinhaBotoes">
            </td></tr>
   	          <tr>
            <div class="separator bottom"></div>
            <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url().'index.php/in/gestor/incluir_proposta?edit=1&id='.$_GET['id'];?>';">
                <input class="btn btn-primary" type="submit" name="cadastra" value="Salvar" id="cadastrar">
                <input class="btn btn-primary" type="submit" name="avanca" value="Avançar" id="avancar">
                 <?php 
if ($edita_gestor != 1){ //aceito e esperando alterações
	?>
                <input class="btn btn-primary" type="submit" name="envia" value="enviar para análise > id="enviar">
              <?php }
if (isset($idTrabalho)) {              
if (($idTrabalho->Status_idstatus != 1 && $idTrabalho->Status_idstatus != 5) && $voltar_gestor == 1 && $edita_gestor != 1){ //aceito e esperando alterações
	?>
	Observações de correção
	<textarea size="5000" cols="60" rows="10" name="obs_gestor" id="obs_gestor"></textarea>
<input class="btn btn-primary" type="submit" name="aprova" value="Aprovar" id="aprova">
<input class="btn btn-primary" type="submit" name="corrige" value="enviar para correção > id="corrige">

<?php } } ?>

            </td>
                      </tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
</div>

<div id="texto_padrao" style="visibility: collapse;">
<?php echo $texto_padrao; ?>
</div>

<script type="text/javascript">
	textCounter(document.getElementById("form_justificativa").Justificativa,document.getElementById("form_justificativa").remLen,5000);
	textCounter(document.getElementById("form_justificativa").objeto,document.getElementById("form_justificativa").remLen1,5000);
</script>


<script type="text/javascript">
$(document).ready(function(){
	$("#inc_texto_padrao").click(function(){
		var justificativa = $("#inserirPropostaJustificativa").val();
		if($(this).is(":checked")){
			justificativa += " "+trim($("#texto_padrao").html());
			$("#inserirPropostaJustificativa").val(justificativa);
		}else{
			justificativa = justificativa.replace(" "+trim($("#texto_padrao").html()), "");
			$("#inserirPropostaJustificativa").val(justificativa);
		}

		textCounter(document.getElementById("form_justificativa").Justificativa, document.getElementById("form_justificativa").remLen, 5000);
	});
});
</script>