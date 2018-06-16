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
		<h3 style="color: #428bca;">Selecionar Programas para Proposta</h3>
		
				<form name="" id="formprop" method="post" action="incluir_proposta" enctype="multipart/form-data">
					<input type="hidden" name="cnpjProponente" value="<?php echo $cnpjProponente;?>">
					<input type="hidden" name="orgao" value="<?php echo $orgao;?>">
                    <input type="hidden" name="id" value="<?php if (isset($id)) { echo $id; } ?>">
					<input type="hidden" name="obj_programa" value='<?php echo $options_programa; ?>'>
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
                    
                    <input class="btn btn-primary" type="button" value="Voltar" id="voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/gestor/escolher_proponente<?php echo $action; ?>'">
                    <input  class="btn btn-primary" type="submit" formaction="informa_valores_programa?<?php echo $ehProjPadrao; ?>id=<?php echo $id; ?><?php echo $edit; ?>" id="form_submit" value="Selecionar">
                    
                    <?php }else{?>
                    
                    <?php //if($this->session->userdata('nivel') == 1):?>
	                <input  class="btn btn-primary" type="submit" formaction="escolher_proponente?id=<?php echo $id; ?><?php echo $edit; ?>" id="form_submit" value="Alterar Orgão">
	                <?php //endif;?>
                    
                    <input  class="btn btn-primary" type="submit" name="finaliza_selecao" formaction="informa_valores_programa?id=<?php echo $id; ?><?php echo $edit; ?>" id="finaliza_selecao" value="Finalizar">
                    
                    <?php }?>
                    
                    <?php }else{ ?>
                    <input class="btn btn-primary" type="button" value="Voltar" id="voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/gestor/escolher_proponente<?php echo $action; ?>'">
                    <input  class="btn btn-primary" type="submit" formaction="informa_valores_programa?<?php echo $ehProjPadrao; ?>" id="form_submit" value="Selecionar">		
                    <?php }?>
                    <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
				</form>

		</div>
	</div>

<script type="text/javascript">
<?php 
if(isset($valores_programa) && !isset($_GET['padrao'])){
	foreach ($valores_programa as $programa){
?>
		$("input[type=checkbox][name=idRowSelectionAsArray]").each(function(index, value){
			if($(this).val() == '<?php echo $programa->id_programa;?>')
				$(this).attr({"checked":true, "disabled":true});
		});
<?php 
	}
}
?>

// $("#finaliza_selecao").click(function(){
// 	$("input[type=checkbox][name=idRowSelectionAsArray]").each(function(index, value){
// 		$(this).attr({"disabled":false});
// 	});
// });
		
$("input[type=checkbox][name=idRowSelectionAsArray]").each(function(index, value){
	$(this).attr({"name":$(this).attr('name')+"[]"});
	<?php if(isset($_GET['padrao'])):?>
	$(this).attr({"type":"radio"});
	<?php endif;?>
});

$(".table tr td:nth-child(2) a").each(function(){
	var link = $(this).attr('href');
	$(this).attr('href', link+'&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest');
});

$(document).ready(function(){
	$("#loader").hide();

	$("#form_submit").click(function(){
		$("#loader").show();
	});

	$("#voltar").click(function(){
		$("#loader").show();
	});
});
</script>