<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_modulos">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Gestão de Benefícios (GPPI)</title>
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

        <!-- Page Content - Limita o espaço lateral-->
        <div class="container py-5" style="margin-top: -30px;"> 
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav gppi-nav">
                        <li class="nav-item gppi-nav">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi'); ?>">DashBoard </a>
                        </li>
                        <li class="nav-item gppi-nav">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi/processos'); ?>">Gestão de Processos</a>
                        </li>
                        <li class="nav-item gppi-nav active">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi/beneficios'); ?>">Gestão de Benefícios <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page Heading -->
            <h1 class="my-4">
                <small></small>
            </h1>

            <!-- Início Tabela de Simulação de Benefícios -->            

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">Simulação de Benefícios</h6>
                        </div>

                        <div class="panel-body">                            
                            <div class="navbar navbar-main">

                                <!-- Botão Simular Benefícios -->

                                <div class="navbar-buttons">
                                    <a href="<?php echo base_url("index.php/gppi/simulacao"); ?>"  alt="">
                                        <button type="button" class="btn btn-sm btn-primary"><b>+</b> Simular Benefício</button>
                                    </a>
                                </div>

                                <!-- Input Buscar por benefício -->

                                <div class="form-group nav-busca clearfix">
                                    <button type="button" class="btn btn-default btn-search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-large" placeholder="Buscar por benefício..." max="70" disabled="true">
                                        <div class="input-group-btn">
                                            <button id="adv-search-btn" class="btn btn-default" type="button" data-original-titel title>
                                                <span class="caret"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>                                

                                <!-- Tabela com os resultados da Silmulação -->

                                <div class="table-responsive">
                                    <div class="dataTables_wrapper" id="tabelaSimulacao_wrapper">
                                        <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">

                                        </div>
                                        <table id="tabelaSimulacao" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Benefício<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                        </div>
                                                    </th>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Órgão Gestor<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span>
                                                        </div>
                                                    </th>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Público Alvo<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s">                                                            
                                                            </span>
                                                        </div>
                                                    </th>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Usuário<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span>
                                                        </div>
                                                    </th>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Data Simulação<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span>
                                                        </div>
                                                    </th>
                                                    <th class="th-wd-xs ui-state-default" rowspan="1" colspan="2">
                                                        <div class="DataTables_sort_wrapper" style="text-align: center;">-<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php if (isset($beneficios) && count($beneficios) > 0): ?>
                                                    <?php foreach ($beneficios as $beneficio): ?> 
                                                        <tr class="odd">
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio->descricao; ?></td>
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio_model->get_orgao_gestor_by_id($beneficio->id_orgao_gestor)->nome_orgao; ?></td>
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio_model->get_publico_alvo_by_id($beneficio->id_publico_alvo)->descricao; ?></td>
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $usuario_sistema_model->get_usuario_sistema_from_id($beneficio->id_usuario_responsavel)->nome; ?></td>
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $util_model->formata_data_padrao_br($beneficio->data_simulacao); ?></td>
                                                            <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center; border-right: none !important;"><a style="background: transparent !important; background-color: transparent !important; outline: none;" title="Executar Simulação" href="<?php echo base_url("index.php/gppi/executa_simulacao?id_beneficio={$beneficio->id}"); ?>" target="_blank"><img src="<?php echo base_url('layout/images/simulacao_icon.png'); ?>" alt="Executar Simulação" style="background-color: transparent;"></a></td>
                                                            <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center; border-left: none !important;"><a style="background: transparent !important; background-color: transparent !important; outline: none;" title="Excluir Benefício" href="<?php echo base_url("index.php/gppi/excluir_beneficio?id_beneficio={$beneficio->id}"); ?>"><img src="<?php echo base_url('layout/images/delete_vazado_icon.png'); ?>" alt="Excluir Benefício" style="background-color: transparent;"></a></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="6" class="dataTables_empty">Registro não encontrado.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                        <div class="container">
                                            <div class="row">
                                                <div class="dataTables_info p-0" id="tabelaSimulacao_info">Mostrando de 1 até 10 de <?php echo count($beneficios); ?> registros</div>
                                                <div class="btn-group p-0 ml-auto" role="group" aria-label="">
                                                    <button type="button" class="btn btn-light">Anterior</button>
                                                    <button type="button" class="btn btn-light">Seguinte</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>
