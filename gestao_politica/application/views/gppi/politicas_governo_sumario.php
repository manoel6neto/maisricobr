<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_modulos">
        <meta name="author" content="Manoel Carvalho Neto">

        <?php if (isset($projeto)): ?>
            <title>Gestão & Política - Editar Projeto (GPPI)</title>
        <?php else: ?>
            <title>Gestão & Política - Novo Projeto (GPPI)</title>
        <?php endif; ?>
        <link rel="icon" href="<?php echo base_url("layout/images/favicon.png"); ?>"/>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url("layout/vendor/bootstrap/css/bootstrap.min.css"); ?>"  rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url("layout/css/3-col-portfolio.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/login.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/administracao.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/gppi.css"); ?>" rel="stylesheet" type="text/css">
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top navbars">
            <div class="container">
                <a id="link-home" style="width: fit-content;" href="<?php echo base_url("index.php/modulos"); ?>"><img style="width: 58%;" src="<?php echo base_url("layout/images/logo_gestao_menu.jpg"); ?>"/></a>
                <div style="display: inline;">
                    <?php if ($this->session->userdata("sessao") != FALSE): ?>
                        <h5 style="margin-left: -100px;" class="titulo_menu">Olá, <?php echo $this->session->userdata("sessao")['nome_usuario']; ?></h5>      
                    <?php else: ?>
                        <h5 style="margin-left: -100px;" class="titulo_menu">Olá, Visitante</h5>      
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
        <div class="container py-5" style="margin-top: -30px;">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav gppi-nav">
                        <li class="nav-item gppi-nav">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi'); ?>">DashBoard </a>
                        </li>
                        <li class="nav-item gppi-nav active">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi/processos'); ?>">Gestão de Processos <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item gppi-nav">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi/beneficios'); ?>">Gestão de Benefícios</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page Heading -->
            <h1 class="my-4">
                <small></small>
            </h1>

            <!-- Simular Benefício -->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php if (isset($projeto)): ?>
                                <h6 class="panel-title">Editar Projeto: <?php echo $projeto->nome . ' (Política de Governo)'; ?></h6>
                            <?php else: ?>
                                <h6 class="panel-title">Novo Projeto (Política de Governo)</h6>
                            <?php endif; ?>
                        </div>

                        <div class="panel-body">                            
                            <div class="form-content">                                        
                                <div class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign">Campos marcados com * são de preenchimento obrigatório.</span>
                                </div>

                                <!-- Formulário politicas de governo -->   
                                <form id="form_projeto_politicas" name="form_projeto_politicas" action="politicas_governo" method="post">
                                    <legend class="py-2">Dados do programa</legend>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="nome">Nome *</label>
                                            <input type="text" class="form-control" id="nome" name="nome">
                                        </div>
                                        <div class="form-group col">
                                            <label for="endereco">Endereço *</label>
                                            <input type="text" class="form-control" id="endereco" name="endereco">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="site">Site</label>
                                            <input type="text" class="form-control" id="site" name="site">
                                        </div>
                                        <div class="form-group col">
                                            <label for="midia">Rede Social</label>
                                            <input type="text" class="form-control" id="midia" name="midia">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="unidade">Unidade *</label>
                                            <input type="text" class="form-control" id="unidade" name="unidade">
                                        </div>
                                        <div class="form-group col">
                                            <label for="responsavel">Responsável *</label>
                                            <input type="text" class="form-control" id="responsavel" name="responsavel">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-8">
                                            <label for="qdd">QDD *</label>
                                            <textarea type="text" class="form-control noresize" id="qdd" name="qdd" rows="5"></textarea>
                                        </div>
                                        <div class="form-group col" style="margin-left: 20px !important;">
                                            <label for="qdd">Vigência *</label>
                                            <div class="form-group" style="padding: 10px !important;">
                                                <div class="form-group form-row col-sm-8">
                                                    <label for="data_inicio">Data inicial</label>
                                                    <input type="text" class="form-control" id="data_inicio" name="data_inicio">
                                                </div>
                                                <div class="form-group form-row col-sm-8">
                                                    <label for="data_final">Data final</label>
                                                    <input type="text" class="form-control" id="data_final" name="data_final">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-row mr-1 mt-5" style="margin-top: 20px !important;">
                                        <button type="submit" id="avancar" class="btn btn-primary ml-auto">&nbsp;Avançar&nbsp;</button>                                            
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>