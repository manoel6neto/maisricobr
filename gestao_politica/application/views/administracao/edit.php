<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_administracao">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Usuário</title>
        <link rel="icon" href="<?php echo base_url("layout/images/favicon.png"); ?>"/>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url("layout/vendor/bootstrap/css/bootstrap.min.css"); ?>"  rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url("layout/css/3-col-portfolio.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/login.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/administracao.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/table.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/util.css"); ?>" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/fonts/font-awesome-4.7.0/css/font-awesome.min.css"); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/vendor/animate/animate.css"); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/vendor/select2/select2.min.css"); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/vendor/perfect-scrollbar/perfect-scrollbar.css"); ?>">
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top navbars">
            <div class="container">
                <a id="link-home" style="width: fit-content;" href="<?php echo base_url("index.php/modulos"); ?>"><img style="width: 58%;" src="<?php echo base_url("layout/images/logo_gestao_menu.jpg"); ?>"/></a>
                <div style="display: inline;">
                    <?php if ($this->session->userdata("sessao") != FALSE): ?>
                        <h5 style="margin-left: -250px;" class="titulo_menu">Olá, <?php echo $this->session->userdata("sessao")['nome_usuario']; ?></h5>      
                    <?php else: ?>
                        <h5 style="margin-left: -250px;" class="titulo_menu">Olá, Visitante</h5>      
                    <?php endif; ?>
                </div>
                <div>
                    <div style="float: left;">
                        <?php if ($this->session->userdata("sessao") != FALSE && $this->session->userdata("sessao")['is_admin'] == 1): ?> 
                            <a href="<?php echo base_url("index.php/administracao"); ?>" title="Configuração" class="login100-form-btn link-button" style="width: 80px; height: 40px;"><img src="<?php echo base_url("layout/images/icone_config.png"); ?>" style="width: 200%;"/></a>
                        <?php else: ?>
                            <a href="<?php echo base_url("index.php/administracao/edit?user={$this->session->userdata('sessao')['id_usuario_sistema']}"); ?>" title="Configuração" class="login100-form-btn link-button" style="width: 80px; height: 40px;"><img src="<?php echo base_url("layout/images/icone_config.png"); ?>" style="width: 200%;"/></a>
                        <?php endif; ?>
                    </div>
                    <div style="float: right; padding-left: 10px;">
                        <form action="<?php echo base_url("index.php/login/sair"); ?>">
                            <button id="sair_btn" title="Sair" class="login100-form-btn" style="width: 80px; height: 40px;">Sair</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container" style="padding: 10px;">
            <?php echo validation_errors(); ?>
            <div style="padding-top: 10px; margin-top: 20px;">
                <?php if (isset($erro_editar) !== false) echo "<p style='margin-top: 20px; color: #cc0000; font-size: 14px; font-weight: bold;' class=\"error\">" . $erro_editar . "</p>"; ?>
                <?php if (isset($sucesso_editar) !== false) echo "<p style='margin-top: 20px; color: #3399ff; font-size: 14px; font-weight: bold;' class=\"error\">" . $sucesso_editar . "</p>"; ?>
            </div>
            <?php echo form_open(); ?>

            <!-- Page Heading -->
            <!-- Inicio Dados Usuario Sistema -->
            <h1 class="my-4 group-title">
                <?php if (isset($usuario->nome)): ?>
                    <?php echo form_hidden("usuario_sistema_id", $usuario->id); ?>
                    <small class="small-class">Dados do(a) <?php echo $usuario->nome; ?></small>
                <?php else: ?>
                    <small class="small-class">Novo Usuário</small>
                <?php endif; ?>
            </h1>
            <div class="sistema-group">
                <div class="form-group">
                    <?php echo form_label("Nome:", "label_sistema_nome", array('class' => 'label-form')); ?>
                    <?php echo form_input(array('name' => 'usuario_sistema_nome', 'id' => 'usuario_sistema_nome', 'value' => isset($usuario->nome) ? $usuario->nome : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label("Email:", "label_sistema_email", array('class' => 'label-form')); ?>
                    <?php echo form_input(array('name' => 'usuario_sistema_email', 'id' => 'usuario_sistema_email', 'value' => isset($usuario->email) ? $usuario->email : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label("Cpf:", "label_sistema_email", array('class' => 'label-form')); ?>
                    <?php echo form_input(array('name' => 'usuario_sistema_cpf', 'id' => 'usuario_sistema_cpf', 'value' => isset($usuario->cpf) ? $usuario->cpf : '', 'maxlenght' => '11', 'class' => 'input-form')); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label("Senha:", "label_sistema_senha", array('class' => 'label-form')); ?>
                    <?php echo form_password(array('name' => 'usuario_sistema_senha', 'id' => 'usuario_sistema_senha', 'value' => '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                </div>
            </div>
            <!-- Fim Dados Usuario Sistema -->

            <?php if ($this->session->userdata("sessao")['is_admin'] == 1): ?>
                <!-- Inicio Cadastro Unico -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Cadastro Único</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_cad_unico_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'cad_unico_login', 'id' => 'cad_unico_login', 'value' => isset($usuario_cad_unico->login) ? $usuario_cad_unico->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_cad_unico_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'cad_unico_senha', 'id' => 'cad_unico_senha', 'value' => isset($usuario_cad_unico->senha) ? $usuario_cad_unico->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_cad_unico_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'cad_unico_url', 'id' => 'cad_unico_url', 'value' => isset($usuario_cad_unico->url_cliente) ? $usuario_cad_unico->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Cadastro Unico -->

                <!-- Inicio Saude -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Saúde Pública</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_saude_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'saude_login', 'id' => 'saude_login', 'value' => isset($usuario_saude->login) ? $usuario_saude->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_saude_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'saude_senha', 'id' => 'saude_senha', 'value' => isset($usuario_saude->senha) ? $usuario_saude->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_saude_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'saude_url', 'id' => 'saude_url', 'value' => isset($usuario_saude->url_cliente) ? $usuario_saude->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Saude -->

                <!-- Inicio Educacao -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Educação Pública</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_educacao_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'educacao_login', 'id' => 'educacao_login', 'value' => isset($usuario_educacao->login) ? $usuario_educacao->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_educacao_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'educacao_senha', 'id' => 'educacao_senha', 'value' => isset($usuario_educacao->senha) ? $usuario_educacao->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_educacao_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'educacao_url', 'id' => 'educacao_url', 'value' => isset($usuario_educacao->url_cliente) ? $usuario_educacao->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Educacao -->

                <!-- Inicio Assistencia Social -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Assistência Social</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_assistencia_social_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'assistencia_social_login', 'id' => 'assistencia_social_login', 'value' => isset($usuario_ass_social->login) ? $usuario_ass_social->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_assistencia_social_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'assistencia_social_senha', 'id' => 'assistencia_social_senha', 'value' => isset($usuario_ass_social->senha) ? $usuario_ass_social->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_assistencia_social_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'assistencia_social_url', 'id' => 'assistencia_social_url', 'value' => isset($usuario_ass_social->url_cliente) ? $usuario_ass_social->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Assistencia Social -->

                <!-- Inicio Cad Imobiliario -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Cadastro Imobiliário</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_cad_imobiliario_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'cad_imobiliario_login', 'id' => 'cad_imobiliario_login', 'value' => isset($usuario_cad_imobiliario->login) ? $usuario_cad_imobiliario->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_cad_imobiliario_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'cad_imobiliario_senha', 'id' => 'cad_imobiliario_senha', 'value' => isset($usuario_cad_imobiliario->senha) ? $usuario_cad_imobiliario->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_cad_imobiliario_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'cad_imobiliario_url', 'id' => 'cad_imobiliario_url', 'value' => isset($usuario_cad_imobiliario->url_cliente) ? $usuario_cad_imobiliario->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Cad Imobiliario -->

                <!-- Inicio Captacao de Recursos Federais -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Captação de Recursos Federais</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_captacao_recursos_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'captacao_recursos_login', 'id' => 'captacao_recursos_login', 'value' => isset($usuario_esicar->login) ? $usuario_esicar->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_captacao_recursos_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'captacao_recursos_senha', 'id' => 'captacao_recursos_senha', 'value' => isset($usuario_esicar->senha) ? $usuario_esicar->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_captacao_recursos_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'captacao_recursos_url', 'id' => 'captacao_recursos_url', 'value' => isset($usuario_esicar->url_cliente) ? $usuario_esicar->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Captacao de Recursos Federais -->

                <!-- Inicio Terceiro Setor -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Terceiro Setor e Projetos Sociais</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_terceiro_setor_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'terceiro_setor_login', 'id' => 'terceiro_setor_login', 'value' => isset($usuario_terceiro_setor->login) ? $usuario_terceiro_setor->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_terceiro_setor_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'terceiro_setor_senha', 'id' => 'terceiro_setor_senha', 'value' => isset($usuario_terceiro_setor->senha) ? $usuario_terceiro_setor->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_terceiro_setor_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'terceiro_setor_url', 'id' => 'terceiro_setor_url', 'value' => isset($usuario_terceiro_setor->url_cliente) ? $usuario_terceiro_setor->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Terceiro Setor -->

                <!-- Inicio Comunicacao Social -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Comuicação Social</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_comunicacao_social_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'comunicacao_social_login', 'id' => 'comunicacao_social_login', 'value' => isset($usuario_comunicacao_social->login) ? $usuario_comunicacao_social->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_comunicacao_social_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'comunicacao_social_senha', 'id' => 'comunicacao_social_senha', 'value' => isset($usuario_comunicacao_social->senha) ? $usuario_comunicacao_social->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_comunicacao_social_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'comunicacao_social_url', 'id' => 'comunicacao_social_url', 'value' => isset($usuario_comunicacao_social->url_cliente) ? $usuario_comunicacao_social->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Comunicacao Social -->

                <!-- Inicio Aplicativo Cidadao -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Aplicativo Cidadão</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_aplicativo_cidadao_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'aplicativo_cidadao_login', 'id' => 'aplicativo_cidadao_login', 'value' => isset($usuario_aplicativo_cidadao->login) ? $usuario_aplicativo_cidadao->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_aplicativo_cidadao_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'aplicativo_cidadao_senha', 'id' => 'aplicativo_cidadao_senha', 'value' => isset($usuario_aplicativo_cidadao->senha) ? $usuario_aplicativo_cidadao->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_aplicativo_cidadao_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'aplicativo_cidadao_url', 'id' => 'aplicativo_cidadao_url', 'value' => isset($usuario_aplicativo_cidadao->url_cliente) ? $usuario_aplicativo_cidadao->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Aplicativo Cidadao -->

                <!-- Inicio Integracao Politicas Publicas -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Integração Políticas Públicas</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_politicas_publicas_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'politicas_publicas_login', 'id' => 'politicas_publicas_login', 'value' => isset($usuario_politicas_publicas->login) ? $usuario_politicas_publicas->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_politicas_publicas_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'politicas_publicas_senha', 'id' => 'politicas_publicas_senha', 'value' => isset($usuario_politicas_publicas->senha) ? $usuario_politicas_publicas->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_politicas_publicas_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'politicas_publicas_url', 'id' => 'politicas_publicas_url', 'value' => isset($usuario_politicas_publicas->url_cliente) ? $usuario_politicas_publicas->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Integracao Politicas Publicas -->
                
                <!-- Inicio Pesquisa -->
                <h1 class="my-4 group-title">
                    <small class="small-class">Módulo Monitoramento de Satisfação e Pesquisa</small>
                </h1>
                <div class="sistema-group">
                    <div class="form-group">
                        <?php echo form_label("Login:", "label_pesquisa_login", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'pesquisa_login', 'id' => 'pesquisa_login', 'value' => isset($usuario_pesquisa->login) ? $usuario_pesquisa->login : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("Senha:", "label_pesquisa_senha", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'pesquisa_senha', 'id' => 'pesquisa_senha', 'value' => isset($usuario_pesquisa->senha) ? $usuario_pesquisa->senha : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_label("URL Customizada:", "label_pesquisa_url", array('class' => 'label-form')); ?>
                        <?php echo form_input(array('name' => 'pesquisa_url', 'id' => 'pesquisa_url', 'value' => isset($usuario_pesquisa->url_cliente) ? $usuario_pesquisa->url_cliente : '', 'maxlenght' => '255', 'class' => 'input-form')); ?>
                    </div>
                </div>
                <!-- Fim Pesquisa -->
            <?php endif; ?>

            <div style="margin-top: 40px;">
                <button class="login100-form-btn" id="salvar_btn" style="margin-bottom: 20px;" type="submit">Salvar</button>
            </div>
            <?php echo form_close(); ?>
        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>
