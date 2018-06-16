<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			
		  			<?php echo validation_errors(); ?>
					<?php if (isset($erro_login) !== false) echo "<p class=\"error\">".$erro_login."</p>"; ?>
					<?php echo form_open();?>
					<input type="hidden" name="id" value="<?php echo $id;?>"/>
		  	  		<div class="form-group">
			    		<label for="exampleInputEmail1">Usuário</label>
		    			<input type="text" class="form-control" id="exampleInputEmail1" name="login" placeholder="Digite o nome de usuário" />
			  		</div>
			  		<div class="form-group">
			    		<label for="exampleInputPassword1">Senha</label>
			    		<input type="password" class="form-control" id="exampleInputPassword1" name="senha" placeholder="Digite a senha" />
			  		</div>
			  		<button type="submit" class="btn btn-primary btn-block">Entrar</button>
		  			
				<?php echo form_close();?>
				
				<br>
				
			</div>
		</div>
	</div>
	
</div>
