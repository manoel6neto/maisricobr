<h1 class="innerAll margin-none text-center"><i class="fa fa-lock"></i> Recuperar Senha</h1>
<div class="login spacing-x2">

	<div class="col-sm-6 col-sm-offset-3">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
				
					<?php echo validation_errors(); ?>
					<?php if (isset($erro_login) !== false) echo "<p class=\"error\">".$erro_login."</p>"; ?>
					<?php echo form_open("in/login/gera_senha?codigo=".$_GET['codigo']);?>
					<input type="hidden" name="acao" value="mudar" />
					
					<div class="form-group">
			    		<label for="novasenha">Nova Senha</label>
		    			<input type="password" class="form-control" name="novasenha" value="" />
			  		</div>
					<button type="submit" class="btn btn-primary btn-block">Mudar Senha</button>
							
					<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	
</div>
