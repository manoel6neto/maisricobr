<div class="login spacing-x2">
	<div class="col-md-8 col-sm-6 col-sm-offset-2">
            <div style="background-color: white; border-top: solid #D8D8D8; border-left: solid #D8D8D8; border-right: solid #D8D8D8; border-width: thin;">
                <img src="<?php echo base_url(); ?>layout/assets/images/cabecalho_compra.png" style="width: 100%; height: 100%; padding-left: 5px; padding-right: 5px; padding-top: 5px;"/>
            </div>
		<!--<h4 class="innerAll margin-none text-center"><i class="fa fa-certificate"></i> Monte o seu Plano de Servicos</h4>-->
			
		<div class="panel panel-default">
                    <div class="panel-body innerAll" style="border-bottom: hidden; border-top: hidden;">
                            <table class="table" style="border-width: thin; width: 65%; float: left; margin-left: 20px;">
					<tr>
						<td style="border: solid #808080; border-width: thin; margin-top: 20px;">
                                                    <fieldset style="margin-top: 15px;">
                                                            <legend>
                                                                <h2 style="text-align: center; margin-top: 15px;">Novos clientes</h2>
                                                                <h6 style="text-align: center;">Crie uma conta na Physis Brasil</h6>
                                                            </legend>
							<?php echo form_open(base_url('index.php/compra/cadastro_usuario'));?>
                                                            <input type="hidden" name="obj_dados" value='<?php echo $obj_dados;?>'>
                                                            <br/><br/>
                                                            <button type="submit" class="btn btn-primary btn-block" style="width: 50%; margin-left: 25%;">Continuar</button>
							<?php echo form_close();?>
							</fieldset>
						</td>
                                                <td style="border: solid #808080; border-width: thin; margin-top: 20px;">
                                                    <fieldset style="margin-bottom: 30px; margin-top: 15px;">
                                                            <legend>
                                                                <h2 style="text-align: center; margin-top: 15px;">Já sou cliente</h2>
                                                                <h6 style="text-align: center;">Já possui conta? Entre com dados de login.</h6>
                                                            </legend>
                                                        <p></p>
							<?php echo form_open(base_url('index.php/compra/reativa_plano'));?>
								<input type="hidden" name="obj_dados" value='<?php echo $obj_dados;?>'>
					  	  		<div class="form-group">
                                                                <p></p>
                                                                <label for="exampleInputEmail1" style="margin-left: 50px;">Login <span style="font-size: small;">(CPF) <span style="font-size: x-small;">(somente numeros)</span></span></label>
						    		&nbsp;&nbsp;<span id="retornoLogin"></span>
                                                                <input type="text" class="form-control" style="width: 75%; margin-left: 50px;" id="login" name="login" placeholder="Digite o CPF do usuário" />
						  		</div>
						  		
						  		<div class="form-group">
                                                                <label for="exampleInputPassword1" style="margin-left: 50px;">Senha</label>
						    		&nbsp;&nbsp;<span id="retornoSenha"></span>
                                                                <input type="password" class="form-control" style="width: 75%; margin-left: 50px;" id="senha" name="senha" placeholder="Digite a senha" />
						  		</div>
                                                                <button type="submit" id="logar" class="btn btn-primary btn-block col-md-4" style="width: 75%; margin-left: 50px; margin-top: 5px;">Entrar</button>
							<?php echo form_close();?>
							</fieldset>
						</td>
                                            </tr>
                                        </table>
                                                    <table class="table" style="border-width: thin; width: 30%; height: 100%; float: left; margin-left: 20px; margin-bottom: 15px;">
                                                        <tr>
                                                            <td style="border: solid #808080; border-width: thin; margin-top: 0px; padding-top: 10px;">
                                                                    <fieldset>
                                                                        <legend>
                                                                            <h4 style="color: red">Resumo do pedido</h4>
                                                                        </legend>
                                                                            <table class="table">
                                                                                    <tr>
                                                                                            <td>Tipo de Serviço</td>
                                                                                            <td><?php echo implode("<br/>", $tipo_servico);?></td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                            <td>Tipo de Plano</td>
                                                                                            <td><?php echo $tipo_plano;?></td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                            <td><i><b>Valor Total</b></i></td>
                                                                                            <td><span style="color: green; font-size: 16px;"><b>R$ <?php echo $valor_pagar; ?></b></span></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <img src="<?php echo base_url(); ?>layout/assets/images/pagseguro2.jpg" style="width: 260px; height: 65px; vertical-align: middle; margin-left: 30px; margin-bottom: 1px; margin-top: 15px;"/>
                                                                                        </td>
                                                                                    </tr>
                                                                            </table>
                                                                    </fieldset>
                                                            </td>
                                                        </tr>
                                                    </table>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#logar").click(function(){
		if($("#login").val() == "" || $("#senha").val() == ""){
			alert("O login e senha são obrigatórios");
			return false;
		}
	});
});
</script>