<div class="login spacing-x2" style="padding-top: 5%;">
	<div class="col-md-4 col-sm-6 col-sm-offset-4 bg-white">
		<div class="panel-body innerAll">
		<h1 style="color: #428bca;">Logar no Siconv</h1>
		</br>
		<div id="form_login">
				<?php echo validation_errors(); ?>
				<?php if (isset($erro_login) !== false) echo "<p class=\"error\">".$erro_login."</p>"; ?>
				
				<?php 
				if(isset($_GET['padrao']))
					$action = "in/gestor/escolher_proponente?padrao=1&id=".$_GET['id'];
				else if(isset($_GET['id']))
					$action = "in/gestor/escolher_proponente?id=".$_GET['id'];
				else
					$action = "";
				?>
				
				<?php echo form_open($action);?>
				
			<div class="form-group">
				<label for="exampleInputEmail1">Usuário</label> <input type="text"
					class="form-control" id="exampleInputEmail1" name="login"
					placeholder="Digite o nome de usuário" />
			</div>
			
			<div class="form-group">
				<label for="exampleInputPassword1">Senha</label> <input
					type="password" class="form-control" id="exampleInputPassword1"
					name="senha" placeholder="Digite a senha" />
			</div>
			
			<button type="submit" class="btn btn-primary btn-block">Entrar</button>
			<?php echo form_close(); ?>
		</div>
		</div>
	</div>
</div>

<?php 
if($this->session->userdata('falha_login') == 'S'){
	//$this->alert("Verifique se a senha do usuário está correta ou atualizada, por favor, verifique se a senha está atualizada no siconv (deve ser atualizada de tempos em tempos)");
	$this->session->set_userdata('falha_login', '');
	$this->session->set_userdata('altera_senha_siconv', '');
}
?>