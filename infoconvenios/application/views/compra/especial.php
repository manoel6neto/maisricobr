<div class="login spacing-x2">
	<div class="col-md-6 col-sm-6 col-sm-offset-3">
            <div style="background-color: white; border: solid #D8D8D8; border-width: thin; border-bottom-color: #3a7ec0;">
                <img src="<?php echo base_url(); ?>layout/assets/images/cabecalho_compra.png" style="width: 100%; height: 100%; padding-left: 5px; padding-right: 5px; padding-top: 5px;"/>
            </div>
			
            <div class="panel panel-default" style="margin-bottom: 0px; border-top-color: transparent">
			<div class="panel-body innerAll">
				<?php echo form_open(base_url('index.php/compra/resumo_pedido?'.$http_query));?>
                                <input type="hidden" name="desconto" value='<?php if (isset($desconto)) { echo $desconto; } ?>'>
		  			<div class="form-group">
		  				<fieldset>
		  				<legend>Tipo de Serviço</legend>
		  					<table class="table">
		  						<tr>
		  							<td>
		  								<input type="checkbox" name="tipo_servico_PA" class="tipo_servico" id="tipo_servico_PA" value="PA" <?php if($PA){echo "checked='checked'";}?>/>
				    					<label for="">Programas Abertos</label>
				    				</td>
				    				
				    				<td>
		  								<input type="checkbox" name="tipo_servico_SP" class="tipo_servico" id="tipo_servico_SP" value="SP" <?php if($SP){echo "checked='checked'";}?>/>
					    				<label for="">Status e Pareceres</label>
				    				</td>
                                                                
                                                                <td id="td_emenda" hidden="true">
                                                                    <input type="checkbox" name="tipo_servico_ED" disabled="true" class="tipo_servico" id="tipo_servico_ED" value="ED" <?php if($PA && $SP){echo "checked='checked'";}?>/>
                                                                    <label for="">Emendas Disponíveis  <span style="color: red"> *(Bônus)</span></label>
				    				</td>
		  						</tr>
		  						
		  						<tr style="visibility: collapse;">
		  							<td colspan="3">
		  								<label for="">Quantidade de CNPJ</label>
		  								<input type="text" name="qtd_cnpj" id="qtd_cnpj" class="form-control" value="1" readonly="readonly"/>
		  							</td>
		  						</tr>
		  					</table>
			    		</fieldset>
			    		
			    		<fieldset>
		  				<legend>Tipo de Plano</legend>
			  				<table class="table">
			  					<tr>
			  						<td>
			  							<input type="radio" name="tipo_plano" class="tipo_plano" value="M" <?php if($TP == "M"){echo "checked='checked'";}?>> Mensal
			  						</td>
			  						<td>
			  							<span id="valor_m"></span>
			  						</td>
			  						<td>
			  							<span id="valor_desc_m"></span>
			  						</td>
			  					</tr>
			  					
			  					<tr>
			  						<td>
			  							<input type="radio" name="tipo_plano" class="tipo_plano" value="T" <?php if($TP == "T"){echo "checked='checked'";}?>> Trimestral
			  						</td>
			  						<td>
			  							<span id="valor_t"></span>
			  						</td>
			  						<td>
			  							<span id="valor_desc_t"></span>
			  						</td>
			  					</tr>
			  					
			  					<tr>
			  						<td>
			  							<input type="radio" name="tipo_plano" class="tipo_plano" value="S" <?php if($TP == "S"){echo "checked='checked'";}?>> Semestral
			  						</td>
			  						<td>
			  							<span id="valor_s"></span>
			  						</td>
			  						<td>
			  							<span id="valor_desc_s"></span>
			  						</td>
			  					</tr>
			  					
			  					<tr>
			  						<td>
			  							<input type="radio" name="tipo_plano" class="tipo_plano" value="A" <?php if($TP == "A"){echo "checked='checked'";}?>> Anual
			  						</td>
			  						<td>
			  							<span id="valor_a"></span>
			  						</td>
			  						<td>
			  							<span id="valor_desc_a"></span>
			  						</td>
			  					</tr>
			  				</table>
		  				</fieldset>
			    		
			    		<fieldset>
			    		<legend>Resumo do Pedido</legend>
			    			<table class="table">
			    				<tr>
			    					<td>Tipo de Serviço:</td>
			    					<td id="res_tipo_servico"></td>
			    				</tr>
			    				
			    				<tr style="visibility: collapse;">
			    					<td>Quant. CNPJ:</td>
			    					<td id="res_qtd_cnpj"></td>
			    				</tr>
			    				
			    				<tr>
			    					<td>Tipo de Plano</td>
			    					<td id="res_tipo_plano"></td>
			    				</tr>
			    			</table>
			    		</fieldset>
			    		
			    		<input type="hidden" name="valor_pagar" id="valor_pagar">
			    		<label style="color:red; font-size: 20px;">Valor a Pagar: &nbsp;</label><b><span style="font-size: 20px; color: green;" id="valor_final">R$ 0.00 / mês</span></b>
			  		</div>
			  		
			  		<br/>
			  		
			  		<button type="submit" class="btn btn-primary col-sm-4 col-sm-offset-4">Continuar</button>
			  		
			  		<br/><br/><br/><br/>
			  		&nbsp;
				<?php echo form_close();?>
			</div>
		</div>
                <div style="background-color: white; border: solid #D8D8D8; border-top-color: #3a7ec0; border-width: thin; margin-bottom: 70px; margin-top: 0px;">
                    <img src="<?php echo base_url(); ?>layout/assets/images/rodape_compra.png" style="margin-top: 0px; width: 100%; height: 100%; padding-left: 2px; padding-right: 2px; padding-bottom: 5px;"/>
                </div>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	function calculaValor(){
		var valor = 0;
		var fator = 1;
		//var fator_cnpj = 0;
		var verifica_combo = 0;
		var fator_combo = 1;

		var fator_m = 1;
		var fator_t = 0.95;
		var fator_s = 0.9;
		var fator_a = 0.85;

		var fator_valor_final = 1;

		var desc_plano = "";

		var desc_servico = Array();
		
		if($("#tipo_servico_PA").is(":checked")){
                        if ($("input[name='desconto']").val() != "") {
                            verifica_combo++;
                            valor += 75;
                        } else {
                            verifica_combo++;
                            valor += 75;
                        }
			desc_servico.push("Programas Abertos");
		}

//		if($("#tipo_servico_ED").is(":checked")){
//			verifica_combo++;
//			
//			//if($("#qtd_cnpj").val() > 1)
//				//fator_cnpj = 100*(0.2*$("#qtd_cnpj").val());
//			
//			valor += (100+fator_cnpj);
//
//			desc_servico.push("Emendas Parlamentares");
//		}

		if($("#tipo_servico_SP").is(":checked")){
                    if ($("input[name='desconto']").val() != "") {
                        verifica_combo++;
			valor += 65;
                    } else {
                        verifica_combo++;
			valor += 65;
                    }
                    desc_servico.push("Status e Pareceres");
		}
                
                if($("#tipo_servico_PA").is(":checked") && $("#tipo_servico_SP").is(":checked")){
                    desc_servico.push("Emendas Disponíveis *(Bônus)");
                    $("#td_emenda").show();
                    $("#tipo_servico_ED").prop("checked", true);
                } else {
                    $("#tipo_servico_ED").prop("checked", false);
                    $("#td_emenda").hide();
                }
		
		if(verifica_combo == 2)
			fator_combo = 0.65;
		else if(verifica_combo == 3)
			fator_combo = 0.9;

		switch($("input[name='tipo_plano']:checked").val()){
			case "M":
				fator = fator_m;
				fator_valor_final = 1;
				desc_plano = "Mensal";
				break;
			case "T":
				fator = fator_t;
				fator_valor_final = 3;
				desc_plano = "Trimestral";
				break;
			case "S":
				fator = fator_s;
				fator_valor_final = 6;
				desc_plano = "Semestral";
				break;
			case "A":
				fator = fator_a;
				fator_valor_final = 12;
				desc_plano = "Anual";
				break;
			default:
				fator = 1;
				fator_valor_final = 1;
				break; 
		}

		//Valor mensal
		$("#valor_m").html("R$ "+((valor*fator_m)*fator_combo).toFixed(2)+" / mês");

		//Valores trimestrais
		$("#valor_t").html("R$ "+((valor*fator_t)*fator_combo).toFixed(2)+" / mês");;
		$("#valor_desc_t").html("<span style='color: red;'>Pagamento único de  R$ </span><b>"+(((valor*fator_t)*fator_combo)*3).toFixed(2)+"</b>");

		//Valores semestrais
		$("#valor_s").html("R$ "+((valor*fator_s)*fator_combo).toFixed(2)+" / mês");;
		$("#valor_desc_s").html("<span style='color: red;'>Pagamento único de  R$ </span><b>"+(((valor*fator_s)*fator_combo)*6).toFixed(2)+"</b>");

		//Valores anuais
		$("#valor_a").html("R$ "+((valor*fator_a)*fator_combo).toFixed(2)+" / mês");;
		$("#valor_desc_a").html("<span style='color: red;'>Pagamento único de  R$ </span><b>"+(((valor*fator_a)*fator_combo)*12).toFixed(2)+"</b>");

		$("#res_tipo_servico").html(desc_servico.join("<br/>"));
		$("#res_qtd_cnpj").html($("#qtd_cnpj").val());
		$("#res_tipo_plano").html(desc_plano);
		
		valor = (valor*fator)*fator_combo;

		$("#valor_pagar").val((valor*fator_valor_final).toFixed(2));
		$("#valor_final").html("R$ "+(valor*fator_valor_final).toFixed(2));
	}

	function setValoresDefault(){
		$(".tipo_servico").each(function(){
			$(this).attr("checked", false);
		});

		$("#qtd_cnpj").val(1);

		$("#valor_final").html("R$ "+valor.toFixed(2)+" / mês")
	}

	$(".tipo_plano").click(function(){
		if($(this).val() == "")
			setValoresDefault();
		else
			calculaValor();
	});

	$(".tipo_servico").click(function(){
		calculaValor();
	});

	$("#qtd_cnpj").keyup(function(){
		calculaValor();
	});

	$("#qtd_cnpj").blur(function(){
		if($(this).val() == "" || $(this).val() < 1){
			$(this).val(1);
			calculaValor();
		}
	});

	calculaValor();
});
</script>