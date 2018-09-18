<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_modulos">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Login</title>
        <link rel="icon" href="<?php echo base_url("layout/images/favicon.png"); ?>"/>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url("layout/vendor/bootstrap/css/bootstrap.min.css"); ?>"  rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url("layout/css/login.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/fonts.css"); ?>" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100 p-t-50 p-b-90">
                    <img style="margin-top: -100px; margin-bottom: 40px; width: 60%; margin-left: 18%; padding: 0px !important;" class="wrap-login100 p-t-50 p-b-90" title="Logo Gestão e Política" alt="Logo Gestão e Política" src="<?php echo base_url("layout/images/logo_gestao.png"); ?>"/>
                    <span class="login100-form-title p-b-51 title-color" style="margin-bottom: -20px;">
                        Login
                    </span>

                    <?php echo validation_errors(); ?>
                    <?php if (isset($erro_login) !== false) echo "<p class=\"error\">" . $erro_login . "</p>"; ?>
                    <?php echo form_open(); ?>

                    <div class="wrap-input100 validate-input m-b-16">
                        <input class="input100" type="text" id="username_login_cpf" name="username_login_cpf" placeholder="Usuário (CPF)">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-16">
                        <input class="input100" type="password" id="pass_senha_geral" name="pass_senha_geral" placeholder="Senha">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="container-login100-form-btn m-t-17">
                        <button class="login100-form-btn" id="login_btn" type="submit">
                            Login
                        </button>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </body>
</html>
