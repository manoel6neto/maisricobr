<h1 class="innerAll margin-none text-center"><i class="fa fa-lock"></i> Recuperar Senha</h1>
<div class="login spacing-x2">

	<div class="col-sm-6 col-sm-offset-3">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
				
					<?php echo validation_errors(); ?>
					<?php if (isset($erro_login) !== false) echo "<p class=\"error\">".$erro_login."</p>"; ?>
					<?php echo form_open();?>
					
                                        <div class="form-group">
                                            <label for="nivel">Nível</label>
                                            <?php $niveis[''] = 'Escolha'; ?>
                                            <?php foreach ($niveis_user as $nivel):?>
                                                    <?php $niveis[$nivel->id_nivel_usuario] = $nivel->nome; ?>
                                            <?php endforeach;?>

                                            <?php echo form_dropdown('nivel', $niveis, '', 'class="form-control" id="nivel"');?>
                                        </div>
                            
					<div class="form-group">
			    		<label for="exampleInputEmail1">Email</label>
		    			<input type="text" class="form-control" id="email" name="email" placeholder="Email" />
			  		</div>
					<button type="submit" class="btn btn-primary btn-block">Enviar email de recuperação</button>
							
					<?php echo form_close(); ?>
				
			</div>
		</div>
	</div>
	
</div>
