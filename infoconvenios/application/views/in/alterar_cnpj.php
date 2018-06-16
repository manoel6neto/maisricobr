<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">
			<h4 class="heading">Gerenciamento de Propostas</h4>
		</div>
		<div class="widget-body center">
<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">

	<div id="salvar" class="action">
		<div class="trigger">

		<h1>Tela de Login do usuário do siconv</h1>
			<div id="form_login">
				<?php echo validation_errors(); ?>
				<?php if (isset($erro_login) !== false) echo "<p class=\"error\">".$erro_login."</p>"; ?>
				<?php
				echo form_open('in/gestor/alterar_senha?id_programa='.$id_programa);

				echo form_label('Login', 'login');
				echo form_input('login', '');

				echo form_label('Senha', 'senha');
				echo form_password('senha', '');

				echo form_submit('submit', 'Entrar no sistema');
				?>
				<?php form_close(); ?>
			</div>
		</div>
	</div>
</div>

</div>
</div>
</div>
</div>
