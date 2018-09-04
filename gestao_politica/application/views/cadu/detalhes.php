<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_administracao">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Administração</title>
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

            <?php if (isset($erro_editar) !== false) echo "<p style='margin-top: 20px; color: #cc0000; font-size: 14px; font-weight: bold;' class=\"error\">" . $erro_editar . "</p>"; ?>
            <?php if (isset($sucesso_editar) !== false) echo "<p style='margin-top: 20px; color: #3399ff; font-size: 14px; font-weight: bold;' class=\"error\">" . $sucesso_editar . "</p>"; ?>

            <!-- Page Heading -->
            <h1 class="my-4">
                <small style="color: #008080;">Código da Família: <?php echo $familia->codigo; ?></small>
            </h1>

            <?php if ( isset($integrantes_familia) && isset($pessoa_detalhar)): ?>
                <div class="wrap-table100">
                    <div class="table100 ver1 m-b-16">
                        <div class="table100-head" style="padding-top: 0 !important;">
                            <table>
                                <thead>
                                    <tr class="row100 head" style="margin: 0 !important;">
                                        <th class="cell100 column1">Nome Integrante</th>
                                        <th class="cell100 column4">Relação</th>
                                        <th class="cell100 column5">Detalhar</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table100-body js-pscroll">
                            <table>
                                <tbody>
                                    <?php foreach ($integrantes_familia as $integra): ?>
                                        <tr class="row100 body" style="margin: 0 !important;">
                                            <td class="cell100 column1"><?php echo $integra['nome']; ?></td>
                                            <td class="cell100 column4"><?php echo $integra['descricao']; ?></td>
                                            <!--<td class="cell100 column5"><a href="<?//php echo base_url("index.php/CadastroUnico/detalhar_familia?id={$familia->id}&idpessoa={$integra['id']}"); ?>">Detalhes</a></td>-->
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="row100 body" style="margin: 0 !important;" style="background-color: #D0D0D0 !important;">
                                        <td colspan="3" class="cell100 column1" style="background-color: #D0D0D0 !important; color: #008080;">Renda Total: R$ <?php echo $renda_familia ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="table100 ver1 m-b-16" style="margin: 0 !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="2" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Informações Pessoais </td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100 column1" rowspan="6"><img src="https://via.placeholder.com/350x350"></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo">Nome: </span><?php echo $pessoa_detalhar->nome; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php if (isset($pessoa_detalhar->rg)): ?>
                                        <td class="cell100"><span class="titulo">RG: </span><?php echo $pessoa_detalhar->rg; ?></td>
                                    <?php else: ?>
                                        <td class="cell100"><span class="titulo">RG: </span>Não Informado</td>
                                    <?php endif; ?>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo">CPF: </span><?php echo $pessoa_detalhar->cpf; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo">NIS: </span><?php echo $pessoa_detalhar->nis; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo">Data Nascimento: </span><?php echo $model_cad_unico->date_format($pessoa_detalhar->data_nascimento); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="3" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Informações Gerais </td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100 column1"><span class="titulo">Relação Familiar: </span><?php ?></td>
                                    <td class="cell100 column1"><span class="titulo">Sexo: </span><?php ?></td>
                                    <td class="cell100 column1"><span class="titulo">Escolaridade: </span><?php ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100 column1"><span class="titulo">Telefone Celular: </span><?php echo $pessoa_detalhar->celular; ?></td>
                                    <td class="cell100 column1"><span class="titulo">Facebook: </span><?php echo $pessoa_detalhar->facebook; ?></td>
                                    <td class="cell100 column1"><span class="titulo">Instagram: </span><?php echo $pessoa_detalhar->instagram; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="3" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Informações Trabalhistas </td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php if (isset($pessoa_detalhar->ctps)): ?>
                                        <td colspan="2" class="cell100 column1"><span class="titulo">N° Carteira de Trabalho: </span><?php echo $pessoa_detalhar->ctps; ?></td>
                                    <?php else: ?>
                                        <td colspan="2" class="cell100 column1"><span class="titulo">N° Carteira de Trabalho: </span>Não Informado</td>
                                    <?php endif; ?>
                                    <td class="cell100 column1"><span class="titulo">Profissão: </span><?php ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php //if($pessoa_detalhar->carteira_assinada == 0): ?>
                                        <!--<td colspan="2" class="cell100 column1"><span class="titulo">Trabalho com carteira assinada: </span>Não</td>-->
                                    <?php //else: ?>
                                        <td colspan="2" class="cell100 column1"><span class="titulo">Trabalho com carteira assinada: </span>Sim</td>
                                    <?php //endif; ?>
                                    <td class="cell100 column1"><span class="titulo">Renda Atual: </span>R$ <?php echo $pessoa_detalhar->renda; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Dados Gerais da Saúde </td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td colspan="2" class="cell100 column1"><span class="titulo">Cartão Nacional de Saúde: </span><?php echo $pessoa_detalhar->cns; ?></td>
                                    <td colspan="2" class="cell100 column1"><span class="titulo">Cartão Municipal de Saúde: </span><?php ?></td>
                                    <?php //if ($pessoa_detalhar->vacinacao_em_dia == 1): ?>
                                        <td  colspan="2" class="cell100 column1"><span class="titulo">Vacinação em dia: </span>Sim</td>
                                    <?//php else: ?>
                                        <!--<td colspan="2" class="cell100 column1"><span class="titulo">Vacinação em dia: </span>Não</td>-->
                                    <?//php endif; ?>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Atendimentos na Saúde </td>
                                </tr>
                                <?php //foreach ($consultas_pessoa_detalhar as $consulta): ?> 
                                    <tr class="row100 body" style="margin: 0 !important;">
                                        <td class="cell100 column2" style="padding-left: 20px !important;"><span class="titulo">Convenio: </span><?php //echo $consulta->convenio; ?></td>
                                        <td class="cell100 column2"><span class="titulo">Data: </span><?php //echo $model_cad_unico->date_format($consulta->data); ?></td>
                                        <td colspan="2" class="cell100 column2"><span class="titulo">Profissional: </span><?php //echo $consulta->profissional; ?></td>
                                        <td class="cell100 column2"><span class="titulo">Status: </span><?php //echo $consulta->status; ?></td>
                                        <td class="cell100 column2"><span class="titulo">Unidade: </span><?php //echo $consulta->unidade; ?></td>
                                    </tr>
                                <?//php endforeach; ?>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Controle de Zoonoses </td>
                                </tr>
                                <?php //foreach ($zoonoses_pessoa_detalhar as $zoo): ?> 
                                    <tr class="row100 body" style="margin: 0 !important;">
                                        <td class="cell100 column3" style="padding-left: 20px !important;"><span class="titulo">Categoria: </span><?php //echo $zoo->categoria; ?></td>
                                        <td class="cell100 column5"><span class="titulo">Nome: </span><?php //echo $zoo->nome; ?></td>
                                        <td class="cell100 column3"><span class="titulo">Nascimento: </span><?php //echo $model_cad_unico->date_format($zoo->data_nascimento); ?></td>
                                        <td class="cell100 column5"><span class="titulo">Raça: </span><?php //echo $zoo->raca; ?></td>
                                        <td class="cell100 column5"><span class="titulo">Cor: </span><?php //echo $zoo->cor; ?></td>
                                        <td class="cell100 column3"><span class="titulo">Sexo: </span><?php //echo $zoo->sexo; ?></td>
                                    </tr>
                                <?//php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Dados Aplicativo Cidadão </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Dados Educação </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Dados Imobiliário </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Participação em Pesquisas </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #d3d9df;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> Participação em Programas Sociais </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <h3>Nenhum dado encontrado!</h3>
            <?php endif; ?>
        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>
