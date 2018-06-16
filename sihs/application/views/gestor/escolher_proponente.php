<div class="login spacing-x2" style="padding-top: 5%;">
    <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">


        <script type="text/javascript">
            function transferir_orgao() {
                var valor = document.getElementById('select2_1').value;
                campo = document.getElementById('orgao');
                campo.value = valor;
            }
        </script>
        <!--   ##Madruga: Script para validar o Órgão com JS-->
        <script type="text/javascript">
            $(function() {
                $("#formprop").validate({
                    rules: {
                        orgao: {
                            required: true
                        },
                        cnpjProponente:{
                            required:true
                        }
                    },
                    messages: {
                        orgao: {
                            required: "Escolha o órgão!"
                        }
                    }
                });
            });
        </script>

        <form name="" method="post" id="formprop" action="selecionar_programas"
              enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php if (isset($id)) {
    echo $id;
} ?>">
      <input type="hidden" name="offline" value="<?php if (isset($offline)) { echo $offline;} ?>">
            
<?php if(!isset($ocultaEntidade)):?>
            <h3 style="color: #428bca;">Escolher Entidade</h3>
            <!-- Group -->
            <br>
            <div class="form-group">
                <table class="table">
                    <tbody class="body_table">
<?php echo $tabela; ?>
                    </tbody>
                    <tr>
                    	<td>
                    	<label for="cnpjProponente" class="error" style="display:none;">Escolha um CNPJ</label>
                    	</td>
                    </tr>
                </table>
            </div>
<?php else:?>
<?php echo $tabela; ?>
<?php endif;?>
            <h3 style="color: #428bca;">Escolher Orgão&nbsp;<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader"></h3>
            <hr>
            <!-- Group -->
<!--             <div class="form-group"> -->
<!--                 <label for="orgao_nome">Código do Orgão</label>  -->
<!--                 <input type="text" class="form-control" id="orgao" name="orgao" -->
<!--                        placeholder="Digite o Orgão" /> -->
<!--             </div> -->

            <!-- Group -->
            <div class="form-group">
                <label for="orgao_nome">Orgão</label>
                <div class="row innerLR">
                    <select style="width: 100%;" id="select2_1" name="orgao"
                            onchange="transferir_orgao()">
                        <option value=""></option>
                        <?php
                        foreach ($orgaos as $orgao) {
							if(isset($proposta) && $proposta->orgao == $orgao->codigo)
								$selected = $orgao->codigo." - ".$orgao->nome;
								
                            echo '<option value="' . $orgao->codigo . '" >' . $orgao->codigo." - ".$orgao->nome . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <?php $ehProjPadrao = (isset($_GET['padrao']) && $_GET['padrao'] != "") ? "padrao=1&" : "" ; ?>
            
            <?php if (isset($id)) { ?>
            <?php $edit = (isset($_GET['padrao']) && $_GET['padrao'] != "") ? "" : "&edit=1"; ?>
            <button type="submit" formaction="selecionar_programas?<?php echo $ehProjPadrao; ?>id=<?php echo $id; ?><?php echo $edit; ?>"
                    id="form_submit" class="btn btn-primary btn-block">Selecionar</button>
            <?php }else{ ?>        
            <button type="submit" formaction="selecionar_programas<?php echo $ehProjPadrao; ?>"
                    id="form_submit" class="btn btn-primary btn-block">Selecionar</button>
            <?php }?>

        </form>

    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$(".body_table > tr > td").css("color", "red").css("font-weight", "normal");

	$("#loader").hide();

	$("#form_submit").click(function(){
		if($("#formprop").valid())
			$("#loader").show();
	});

<?php if(isset($proposta) && !isset($_GET['padrao'])): ?>
	//$("#select2_1").select2("data", { id: "22000", text:"teste" });

	$("input[type=radio][name=cnpjProponente]").each(function(index, value){
  	  	if($(this).val() == '<?php echo $proposta->proponente;?>')
  	  	  	$(this).attr({"checked":true});
  	  	else
  	  		$(this).attr({"checked":false});
	});
<?php endif; ?>
});
</script>