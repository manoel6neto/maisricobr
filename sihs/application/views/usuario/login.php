<title>Área Restrita</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
           
            #form_login { width: 200px; margin: 0 auto; padding: 20px; background: #F2F2F2; border: 1px solid #B7B7B7; }
            label { display: block; margin-bottom: 0.3em; }
            input[type=text], input[type=password] { border: 1px solid #666; display: block; margin-bottom: 1em; padding: 2px; width: 100%; }
            input[type=text], input[type=password] { display: block; }
            h1 { margin: 0 0 1em 0; text-align: center; }
            .error { background: none repeat scroll 0 0 #FBE6F2; border: 1px solid #D893A1; padding: 5px; }
        </style>

<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
<div id="content">
<div class="login spacing-x2" style="padding-top:10%;">

	<div class="col-md-8 col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-body innerAll">

		<h1>Tela de Login</h1>
            <?php echo validation_errors(); ?>
            <?php if (isset($erro_login) !== false) echo "<p class=\"error\">".$erro_login."</p>"; ?>
            <?php
            echo form_open();

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
