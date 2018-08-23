<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_modulos">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Módulos</title>
        <link rel="icon" href="<?php echo base_url("layout/images/favicon.png"); ?>"/>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url("layout/vendor/bootstrap/css/bootstrap.min.css"); ?>"  rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url("layout/css/3-col-portfolio.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/login.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/administracao.css"); ?>" rel="stylesheet" type="text/css">
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top navbars">
            <div class="container">
                <img style="width: 20%;" src="<?php echo base_url("layout/images/logo_gestao_menu.jpg"); ?>"/>
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
        <div class="container" style="padding-top: 10px;">

            <!-- Page Heading -->
            <h1 class="my-4">
                <small></small>
            </h1>

            <div class="row">
                <!-- INICIO MODULO CADASTRO UNICO -->
                <?php if (isset($usuario_cad_unico)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_cad_unico->url_cliente) || $usuario_cad_unico->url_cliente == ''): ?>
                                <a href="<?php echo base_url("index.php/CadastroUnico"); ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_cad_unico.png"); ?>" alt="Cadastro Único"></a>
                                <a href="<?php echo base_url("index.php/CadastroUnico"); ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DO CADASTRO ÚNICO</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_cad_unico->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_cad_unico.png"); ?>" alt="Cadastro Único"></a>
                                <a href="<?php echo $usuario_cad_unico->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DO CADASTRO ÚNICO</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_cad_unico_disabled.png"); ?>" alt="Cadastro Único">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DO CADASTRO ÚNICO</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO CADASTRO UNICO -->

                <!-- INICIO MODULO SAUDE -->
                <?php if (isset($usuario_saude)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <form action="https://app.benjimed.com.br/auth" method="post" target="_blank">
                                <input hidden="true" name="login" id="login" value="<?php echo $usuario_saude->login; ?>"/>
                                <input hidden="true" name="password" id="password" value="<?php echo $usuario_saude->senha; ?>"/>

                                <input type="image" class="card-img-top" src="<?php echo base_url("layout/images/modulo_saude.png"); ?>" alt="Educação Pública"/>
                                <div class="card-body card-body-color">
                                    <p class="card-text card-text-color"><input style="background-color: #c4c4c4 !important;" class="card-text card-text-color" type="submit" value="Módulo para GESTÃO DA"/></p>
                                    <p class="card-text card-text-color"><input style="background-color: #c4c4c4 !important;" class="card-text card-text-color" type="submit" value="SAÚDE PÚBLICA"/></p>
                                </div>
                            </form>    
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_saude_disabled.png"); ?>" alt="Saúde Pública">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DA SAÚDE PÚBLICA</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO SAUDE -->

                <!-- INICIO MODULO EDUCACAO -->
                <?php if (isset($usuario_educacao)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <form action="http://186.216.174.121/login" method="post" target="_blank">
                                <input hidden="true" name="email" id="email" value="<?php echo $usuario_educacao->login; ?>"/>
                                <input hidden="true" name="password" id="password" value="<?php echo $usuario_educacao->senha; ?>"/>

                                <input type="image" class="card-img-top" src="<?php echo base_url("layout/images/modulo_educacao.png"); ?>" alt="Educação Pública"/>
                                <div class="card-body card-body-color">
                                    <p class="card-text card-text-color"><input style="background-color: #c4c4c4 !important;" class="card-text card-text-color" type="submit" value="Módulo para GESTÃO DA"/></p>
                                    <p class="card-text card-text-color"><input style="background-color: #c4c4c4 !important;" class="card-text card-text-color" type="submit" value="EDUCAÇÃO PÚBLICA"/></p>
                                </div>
                            </form>
    <!--                            <a href="<?php echo base_url("index.php/modulos/login_proesc"); ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_educacao.png"); ?>" alt="Educação Pública"></a>
                            <a href="<?php echo base_url("index.php/modulos/login_proesc"); ?>" target="_blank">
                                <p class="card-text card-text-color">Módulo para GESTÃO DA EDUCAÇÃO PÚBLICA</p>
                            </a>-->
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_educacao_disabled.png"); ?>" alt="Educação Pública">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DA EDUCAÇÃO PÚBLICA</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO EDUCACAO -->

                <!-- INICIO MODULO ASSISTENCIA SOCIAL -->
                <?php if (isset($usuario_ass_social)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_ass_social->url_cliente) || $usuario_ass_social->url_cliente == ''): ?>
                                <a href="http://administracao-app.azurewebsites.net/Seguranca/RealizarLogin#" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_ass_social.png"); ?>" alt="Assistência Social"></a>
                                <a href="http://administracao-app.azurewebsites.net/Seguranca/RealizarLogin#" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DA ASSISTÊNCIA SOCIAL</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_ass_social->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_ass_social.png"); ?>" alt="Assistência Social"></a>
                                <a href="<?php echo $usuario_ass_social->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DA ASSISTÊNCIA SOCIAL</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_ass_social_disabled.png"); ?>" alt="Assistência Social">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DA ASSISTÊNCIA SOCIAL</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO ASSISTENCIA SOCIAL -->

                <!-- INICIO MODULO CAD IMOBILIARIO -->
                <?php if (isset($usuario_cad_imobiliario)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_cad_imobiliario->url_cliente) || $usuario_cad_imobiliario->url_cliente == ''): ?>
                                <a href="http://geocascavel.cascavel.pr.gov.br/geo-view/index.ctm" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_cad_imobiliario.png"); ?>" alt="Cadastro Imobiliário"></a>
                                <a href="http://geocascavel.cascavel.pr.gov.br/geo-view/index.ctm" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DO CADASTRO IMOBILIÁRIO</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_cad_imobiliario->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_cad_imobiliario.png"); ?>" alt="Cadastro Imobiliário"></a>
                                <a href="<?php echo $usuario_cad_imobiliario->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DO CADASTRO IMOBILIÁRIO</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_cad_imobiliario_disabled.png"); ?>" alt="Cadastro Imobiliário">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DO CADASTRO IMOBILIÁRIO</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO CAD IMOBILIARIO -->

                <!-- INICIO MODULO ESICAR -->
                <?php if (isset($usuario_esicar)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_esicar->url_cliente) || $usuario_esicar->url_cliente == ''): ?>
                                <a href="http://convenios.physisbrasil.com.br/esicar/index.php/in/login?login=<?php echo base64_encode($usuario_esicar->login); ?>&senha=<?php echo base64_encode($usuario_esicar->senha); ?>&gp=1" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_esicar.png"); ?>" alt="Recursos Federais"></a>
                                <a href="http://convenios.physisbrasil.com.br/esicar/index.php/in/login?login=<?php echo base64_encode($usuario_esicar->login); ?>&senha=<?php echo base64_encode($usuario_esicar->senha); ?>&gp=1" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DE CAPTAÇÃO DE RECURSOS FEDERAIS</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_esicar->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_esicar.png"); ?>" alt="Recursos Federais"></a>
                                <a href="<?php echo $usuario_esicar->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DE CAPTAÇÃO DE RECURSOS FEDERAIS</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_esicar_disabled.png"); ?>" alt="Recursos Federais">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DE CAPTAÇÃO DE RECURSOS FEDERAIS</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO ESICAR -->

                <!-- INICIO MODULO TERCEIRO SETOR -->
                <?php if (isset($usuario_terceiro_setor)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_terceiro_setor->url_cliente) || $usuario_terceiro_setor->url_cliente == ''): ?>
                                <a href="https://hyb.com.br/web/index.php" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_terceiro_setor.png"); ?>" alt="Terceiro Setor e Projetos Sociais"></a>
                                <a href="https://hyb.com.br/web/index.php" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DO TERCEIRO SETOR e PROJETOS SOCIAIS</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_terceiro_setor->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_terceiro_setor.png"); ?>" alt="Terceiro Setor e Projetos Sociais"></a>
                                <a href="<?php echo $usuario_terceiro_setor->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO DO TERCEIRO SETOR e PROJETOS SOCIAIS</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_terceiro_setor_disabled.png"); ?>" alt="Terceiro Setor e Projetos Sociais">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DO TERCEIRO SETOR e PROJETOS SOCIAIS</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO TERCEIRO SETOR -->

                <!-- INICIO MODULO COMUNICACAO SOCIAL -->
                <?php if (isset($usuario_comunicacao_social)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <a href="javascript:window.open('http://convenios.physisbrasil.com.br/gestao_politica/layout/images/autland_full.png','window','location=false, toolbar=no, menubar=no, resizable=yes');" title="Autland" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_comunicacao_social.png"); ?>" alt="Comunicação Social"></a>
                            <a href="javascript:window.open('http://convenios.physisbrasil.com.br/gestao_politica/layout/images/autland_full.png','window','location=false, toolbar=no, menubar=no, resizable=yes');" title="Autland" target="_blank">
                                <div class="card-body card-body-color">
                                    <p class="card-text card-text-color">Módulo para GESTÃO DA COMUNICAÇÃO SOCIAL</p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_comunicacao_social_disabled.png"); ?>" alt="Comunicação Social">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO DA COMUNICAÇÃO SOCIAL</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO COMUNICACAO SOCIAL -->

                <!-- INICIO MODULO APLICATIVO CIDADAO -->
                <?php if (isset($usuario_aplicativo_cidadao)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_aplicativo_cidadao->url_cliente) || $usuario_aplicativo_cidadao->url_cliente == ''): ?>
                                <a href="http://radarcidadao.com.br/cascavel/PR" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_aplicativo_cidadao.png"); ?>" alt="Aplicativo Cidadão"></a>
                                <a href="http://radarcidadao.com.br/cascavel/PR" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo APLICATIVO CIDADÃO</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_aplicativo_cidadao->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_aplicativo_cidadao.png"); ?>" alt="Aplicativo Cidadão"></a>
                                <a href="<?php echo $usuario_aplicativo_cidadao->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo APLICATIVO CIDADÃO</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_aplicativo_cidadao_disabled.png"); ?>" alt="Aplicativo Cidadão">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo APLICATIVO CIDADÃO</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO APLICATIVO CIDADAO -->

                <!-- INICIO MODULO POLITICAS PUBLICAS -->
                <?php if (isset($usuario_politicas_publicas)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <?php if (!isset($usuario_politicas_publicas->url_cliente) || $usuario_politicas_publicas->url_cliente == ''): ?>
                                <a href="http://administracao-app.azurewebsites.net/Seguranca/RealizarLogin#" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_politicas_publicas.png"); ?>" alt="Gestão Integrada Políticas Públicas"></a>
                                <a href="http://administracao-app.azurewebsites.net/Seguranca/RealizarLogin#" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO INTEGRADA DAS POLÍTICAS PÚBLICAS</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $usuario_politicas_publicas->url_cliente; ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_politicas_publicas.png"); ?>" alt="Gestão Integrada Políticas Públicas"></a>
                                <a href="<?php echo $usuario_politicas_publicas->url_cliente; ?>" target="_blank">
                                    <div class="card-body card-body-color">
                                        <p class="card-text card-text-color">Módulo para GESTÃO INTEGRADA DAS POLÍTICAS PÚBLICAS</p>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_politicas_publicas_disabled.png"); ?>" alt="Gestão Integrada Políticas Públicas">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para GESTÃO INTEGRADA DAS POLÍTICAS PÚBLICAS</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO POLITICAS PUBLICAS -->

                <!-- INICIO MODULO MONITORAMENTO SATISFAÇÃO E PESQUISA -->
                <?php if (isset($usuario_pesquisa)): ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <a href="http://149.56.74.30/clickpesquisa/index.php/login?login=<?php echo base64_encode($usuario_pesquisa->login); ?>&senha=<?php echo base64_encode($usuario_pesquisa->senha); ?>" target="_blank"><img class="card-img-top" src="<?php echo base_url("layout/images/modulo_monitor_sat_pesquisa.png"); ?>" alt="G&P Pesquisa"></a>
                            <a href="http://149.56.74.30/clickpesquisa/index.php/login?login=<?php echo base64_encode($usuario_pesquisa->login); ?>&senha=<?php echo base64_encode($usuario_pesquisa->senha); ?>" target="_blank">
                                <div class="card-body card-body-color">
                                    <p class="card-text card-text-color">Módulo para MONITORAMENTO DE SATISFAÇÃO e PESQUISA</p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-sm-4 portfolio-item">
                        <div class="card h-100 card-body-color">
                            <img class="card-img-top" src="<?php echo base_url("layout/images/modulo_monitor_sat_pesquisa_disabled.png"); ?>" alt="G&P Pesquisa">
                            <div class="card-body card-body-color">
                                <p class="card-text card-text-color-disabled">Módulo para MONITORAMENTO DE SATISFAÇÃO e PESQUISA</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- FIM MODULO MONITORAMENTO SATISFAÇÃO E PESQUISA -->
            </div>
        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>
