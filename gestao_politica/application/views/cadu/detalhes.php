<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_administracao">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Detalhamento de Famílias (CADU)</title>
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
        <div class="container" style="padding: 10px; margin-top: 20px !important; margin-bottom: 20px !important;">

            <h1 class="my-4"></h1>

            <?php if (isset($integrantes_familia) && isset($pessoa_detalhar)): ?>
                <div class="wrap-table100 ver1 m-b-16">
                    <div class="panel-heading">
                        <h6 class="panel-title"> Código da Familia: <?php echo $familia->codigo; ?></h6>
                    </div>
                    <div class="table100 ver1 m-b-16">
                        <div class="table100-head" style="padding-top: 0 !important;">
                            <table>
                                <thead>
                                    <tr class="row100 head" style="margin: 0 !important;">
                                        <th class="cell100 column1">Nome Integrante</th>
                                        <th class="cell100 column4">Relação</th>
                                        <th class="cell100 column5">Ações</th>
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
                                            <td class="cell100 column4"><?php echo $integra['descricao']; ?>
                                                <?php
                                                if ($integra['responsavel'] == 1)
                                                    echo '(Responsável)';
                                                else
                                                    echo '';
                                                ?></td>
                                            <td class="cell100 column5"><a style="background-color: transparent !important; font-weight: 700;" href="<?php echo base_url("index.php/CadastroUnico/detalhar_familia?id={$familia->id}&idpessoa={$integra['id']}"); ?>">Detalhar</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="row100 body" style="margin: 0 !important;" style="background-color: #D0D0D0 !important;">
                                        <td colspan="3" class="cell100 column1" style="background-color: #D0D0D0 !important; color: #008080; font-weight: 700;">Renda Total: <?php echo 'R$&nbsp;' . number_format($renda_familia, 2, ',', '.'); ?></td>
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
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="2" class="column1" style="align-content: center; text-align: center; vertical-align: central;"> <span style="color: #fff; font-weight: 700;">Informações Pessoais</span> </td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php if ($pessoa_detalhar->id_foto != NULL): ?>
                                        <td class="cell100 column1" rowspan="8"><img style="margin-right: 10px;" width="350" height="350" src="data:image/jpeg;base64,<?php echo base64_encode($model_cad_unico->get_foto_from_id($pessoa_detalhar->id_foto)); ?>"></td>
                                    <?php else: ?>
                                        <td class="cell100 column1" rowspan="8"><img style="margin-right: 10px;" src="https://via.placeholder.com/350x350"></td>
                                    <?php endif; ?>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo column1">Nome: </span><?php echo $pessoa_detalhar->nome; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php if (isset($pessoa_detalhar->rg)): ?>
                                        <td class="cell100"><span class="titulo column1">RG: </span><?php echo $pessoa_detalhar->rg; ?></td>
                                    <?php else: ?>
                                        <td class="cell100"><span class="titulo column1">RG: </span>Não Informado</td>
                                    <?php endif; ?>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo column1">CPF: </span><?php echo $pessoa_detalhar->cpf; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo column1">CNS: </span><?php echo $pessoa_detalhar->cns; ?> </td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo column1">NIS: </span><?php echo $pessoa_detalhar->nis; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100"><span class="titulo column1">Data Nascimento: </span><?php echo $model_cad_unico->date_format($pessoa_detalhar->data_nascimento); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="3" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Informações Gerais</span></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td class="cell100 column1"><span class="titulo">Relação Familiar: </span><?= $pessoa_detalhar->id_funcao != NULL ? $model_cad_unico->get_funcao_from_id_funcao($pessoa_detalhar->id_funcao) : "Não Informado" ?></td>
                                    <td class="cell100 column1"><span class="titulo">Sexo: </span><?php echo $model_cad_unico->get_sexo_from_id_sexo($pessoa_detalhar->id_sexo); ?></td>
                                    <td class="cell100 column1"><span class="titulo">Escolaridade: </span><?= $pessoa_detalhar->id_escolaridade != NULL ? $model_cad_unico->get_escolaridade_from_id_escolaridade($pessoa_detalhar->id_escolaridade) : "Não Informado"; ?></td>
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
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="3" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Informações Trabalhistas</span></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php if (isset($pessoa_detalhar->ctps)): ?>
                                        <td colspan="2" class="cell100 column1"><span class="titulo">N° Carteira de Trabalho: </span><?php echo $pessoa_detalhar->ctps; ?></td>
                                    <?php else: ?>
                                        <td colspan="2" class="cell100 column1"><span class="titulo">N° Carteira de Trabalho: </span>Não Informado</td>
                                    <?php endif; ?>
                                    <td class="cell100 column1"><span class="titulo">Profissão: </span><?= $pessoa_detalhar->id_profissao != NULL ? $model_cad_unico->get_profissao_from_id_profissao($pessoa_detalhar->id_profissao) : "Não Informado"; ?></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <?php //if($pessoa_detalhar->carteira_assinada == 0):    ?>
                                        <!--<td colspan="2" class="cell100 column1"><span class="titulo">Trabalho com carteira assinada: </span>Não</td>-->
                                    <?php //else:    ?>
                                    <td colspan="2" class="cell100 column1"><span class="titulo">Trabalho com carteira assinada: </span></td>
                                    <?php //endif;    ?>
                                    <td class="cell100 column1"><span class="titulo">Renda Atual: </span><?php echo 'R$&nbsp;' . number_format($pessoa_detalhar->renda, 2, ',', '.'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="8" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Dados Gerais da Saúde</span></td>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important;">
                                    <td colspan="3" class="cell100 column1"><span class="titulo">Cartão Nacional de Saúde: </span><?php echo $pessoa_detalhar->cns; ?></td>
                                    <td colspan="2" class="cell100 column1"><span class="titulo">Cartão Municipal de Saúde: </span><?php ?></td>
                                    <?php //if ($pessoa_detalhar->vacinacao_em_dia == 1):   ?>
                                    <td  colspan="2" class="cell100 column1"><span class="titulo">Vacinação em dia: </span><?php echo "Sim"; ?></td>
                                    <?//php else: ?>
                                        <!--<td colspan="2" class="cell100 column1"><span class="titulo">Vacinação em dia: </span>Não</td>-->
                                    <?//php endif; ?>
                                </tr>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="8" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Atendimentos na Saúde</span></td>
                                </tr>
                                <?php if ($model_cad_unico->get_dados_saude_from_id_pessoa($pessoa_detalhar->id) != NULL): ?>
                                    <?php foreach ($model_cad_unico->get_dados_saude_from_id_pessoa($pessoa_detalhar->id) as $dados_saude): ?> 
                                        <tr class="row100 body" style="margin: 0 !important;">
                                            <td class="cell100 column2" style="padding-left: 20px !important;"><span class="titulo">Atendimento: </span><?php echo $dados_saude->tipo_atendimento; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Convênio: </span><?php echo $dados_saude->convenio; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Data: </span><?php echo $model_cad_unico->date_format($dados_saude->data); ?></td>
                                            <td class="cell100 column2"><span class="titulo">Status: </span><?php echo $dados_saude->status; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Profissional: </span><?php echo $dados_saude->profissional; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Unidade: </span><?php echo $dados_saude->unidade; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="8" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Controle de Zoonoses</span></td>
                                </tr>
                                <?php if ($model_cad_unico->get_dados_zoo_from_id_pessoa($pessoa_detalhar->id) != NULL): ?>
                                    <?php foreach ($model_cad_unico->get_dados_zoo_from_id_pessoa($pessoa_detalhar->id) as $dados_zoo): ?> 
                                        <tr class="row100 body" style="margin: 0 !important;">
                                            <td class="cell100 column2" style="padding-left: 20px !important;"><span class="titulo">Categoria: </span><?php echo $dados_zoo->categoria; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Nome: </span><?php echo $dados_zoo->nome; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Nascimento: </span><?php echo $model_cad_unico->date_format($dados_zoo->data_nascimento); ?></td>
                                            <td class="cell100 column2"><span class="titulo">Raça: </span><?php echo $dados_zoo->raca; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Cor: </span><?php echo $dados_zoo->cor; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Sexo: </span><?php echo $dados_zoo->sexo; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="8" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Dados Aplicativo Cidadão</span></td>
                                </tr>
                                <?php if ($model_cad_unico->get_dados_app_cidadao_from_id_pessoa($pessoa_detalhar->id) != NULL): ?>
                                    <?php foreach ($model_cad_unico->get_dados_app_cidadao_from_id_pessoa($pessoa_detalhar->id) as $dados_app_cidadao): ?> 
                                        <tr class="row100 body" style="margin: 0 !important;">
                                            <td class="cell100 column2" style="padding-left: 20px !important;"><span class="titulo">Código: </span><?php echo $dados_app_cidadao->codigo; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Chamado: </span><?php echo $dados_app_cidadao->tipo_chamado; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Data: </span><?php echo $model_cad_unico->date_format($dados_app_cidadao->data); ?></td>
                                            <td class="cell100 column2"><span class="titulo">Endereço: </span><?php echo $dados_app_cidadao->endereco; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Situação: </span><?php echo $dados_app_cidadao->situacao; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="8" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Dados Educação</span></td>
                                </tr>
                                <?php if ($model_cad_unico->get_dados_educacao_from_id_pessoa($pessoa_detalhar->id) != NULL): ?>
                                    <?php foreach ($model_cad_unico->get_dados_educacao_from_id_pessoa($pessoa_detalhar->id) as $dados_educacao): ?> 
                                        <tr class="row100 body" style="margin: 0 !important;">
                                            <td class="cell100 column2" style="padding-left: 20px !important;"><span class="titulo">Exercício: </span><?php echo $dados_educacao->exercicio; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Unidade: </span><?php echo $dados_educacao->unidade; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Turma: </span><?php echo $dados_educacao->turma; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Curso: </span><?php echo $dados_educacao->curso; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Situação: </span><?php echo $dados_educacao->situacao; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Dados Imobiliário</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="6" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Participação em Pesquisas</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table100 ver1 m-b-16" style="margin-top: 10px !important; padding: 0 !important;">
                    <div class="table100-body js-pscroll" style="margin: 0 !important; padding: 0 !important;">
                        <table>
                            <tbody>
                                <tr class="row100 body" style="margin: 0 !important; background-color: #8ca8bb;">
                                    <td colspan="8" class="column1" style="align-content: center; text-align: center; vertical-align: central;"><span style="color: #fff; font-weight: 700;">Participação em Programas Sociais</span></td>
                                </tr>
                                <?php if ($model_cad_unico->get_dados_suas_from_id_pessoa($pessoa_detalhar->id) != NULL): ?>
                                    <?php foreach ($model_cad_unico->get_dados_suas_from_id_pessoa($pessoa_detalhar->id) as $dados_suas): ?> 
                                        <tr class="row100 body" style="margin: 0 !important;">
                                            <td class="cell100 column2" style="padding-left: 20px !important;"><span class="titulo">Tipo atendimento: </span><?php echo $dados_suas->tipo; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Unidade: </span><?php echo $dados_suas->unidade; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Data: </span><?php echo $model_cad_unico->date_format($dados_suas->data); ?></td>
                                            <td class="cell100 column2"><span class="titulo">Status: </span><?php echo $dados_suas->status; ?></td>
                                            <td class="cell100 column2"><span class="titulo">Responsável: </span><?php echo $dados_suas->cadastrador; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
