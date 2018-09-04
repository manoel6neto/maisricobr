<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_resultado_simulacao_beneficio">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Módulos</title>
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
        <div class="container" style="padding-top: 10px;">
            <!-- Page Heading -->
            <h1 class="my-4">
                <small></small>
            </h1>
            <!-- Simular Benefício -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">Resultado da Simulação de Benefício</h6>
                        </div>

                        <div class="panel-body">                            
                            <div class="form-content">                                        
                                <!-- Formulário RESULTADOS -->
                                <div class="tab-pane fade show active" id="resultado" role="tabpanel" aria-labelledby="home-tab">
                                    <legend class="py-2 resultado">Resultado</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Nome do Benefício<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr class="odd">
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio->descricao; ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Nome do Orgão Gestor<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Público Alvo<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Data da Simulação<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr class="odd">
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio_model->get_orgao_gestor_by_id($beneficio->id_orgao_gestor)->nome_orgao; ?></td>
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio_model->get_publico_alvo_by_id($beneficio->id_publico_alvo)->descricao; ?></td>
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $util_model->formata_data_padrao_br($beneficio->data_simulacao); ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Usuário Responsável<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr class="odd">
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $usuario_sistema_model->get_usuario_sistema_from_id($beneficio->id_usuario_responsavel)->nome; ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <legend class="py-2 resultado">Parâmetros</legend>
                                    <?php foreach ($parametros as $parametro): ?> 
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Valor do Benefício (Mês)<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <tr class="odd">
                                                    <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario, 2, ',', '.'); ?></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    <?php endforeach; ?>
                                    <legend class="py-2 resultado">Limitadores</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Valor Mensal Investido<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">Quantidade de Beneficiários<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr class="odd">
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo number_format($beneficio->valor_mensal_investido, 2, ',', '.'); ?></td>
                                                <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $beneficio->quantidade_beneficiarios; ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <legend class="py-2 resultado">Critérios de Seleção</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper">
                                                        <?php if ($beneficio->id_publico_alvo == 1): ?>
                                                            Quantidade de Famílias<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                        <?php else: ?>
                                                            Quantidade de Beneficiários<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </th>
                                            </tr>
                                            <?php foreach ($criterios as $criterio): ?> 
                                                <?php
                                                switch ($criterio->id_criterio):
                                                    case 6:
                                                        ?>
                                                        <tr class="odd">
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $criterio->descricao . ' ' . $util_model->substituir_simbolo_por_texto($criterio->tipo_filtro) . ' R$&nbsp;' . number_format($criterio->valor_filtro, 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <?php break; ?>
                                                    <?php case 1: ?>
                                                        <tr class="odd">
                                                            <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $criterio->descricao . ' ' . $util_model->substituir_simbolo_por_texto($criterio->tipo_filtro) . ' '; ?></td>
                                                        </tr>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            <?php endforeach; ?>
                                        </thead>
                                    </table>
                                    <legend class="py-2 resultado">Beneficiários que atendem ao critério</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;"><span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n" style="background-color: #8ebdc6; color: #ffffff; padding: 5px; border-radius: 5px;">15</span>
                                                        <p style="padding-top: 10px !important;">Famílias Selecionadas</p>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1" colspan="3">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;"><span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n" style="background-color: #8ebdc6; color: #ffffff; padding: 5px; border-radius: 5px;">25</span>
                                                        <p style="padding-top: 10px !important;">Pessoas Selecionadas</p>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" style="width: 50%;">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Descrição<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Quantidade<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Valor Unitário<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Valor Total<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <?php foreach ($parametros as $parametro): ?>
                                                <tr class="odd">
                                                    <?php if ($beneficio->id_publico_alvo == 2): ?>
                                                        <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $parametro->nome_produto . ' (Benefício Concedido a pessoa)'; ?></td>
                                                    <?php else: ?>
                                                        <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $parametro->nome_produto . ' (Benefício Concedido a família)'; ?></td>
                                                    <?php endif; ?>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo ($parametro->quantidade * 25); ?></td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario, 2, ',', '.'); ?></td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario * 25, 2, ',', '.'); ?></td>
                                                </tr>
                                                <tr class="odd">
                                                    <td valign="top" colspan="2" class="dataTables_wrapper" style="text-align: center;"></td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="font-weight: 900;">Total:</td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario * 250, 2, ',', '.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </thead>
                                    </table>
                                    <div class="panel-body">                            
                                        <div class="form-content">
                                            <ul class="nav nav-pills nav-justified" id="tabelaFamiliasPessoasComFiltro" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="familias-com-filtro-tab" data-toggle="tab" href="#familiascomfiltro" role="tab" aria-controls="familiascomfiltro" aria-selected="true">Famílias Selecionadas</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="pessoas-com-filtro-tab" data-toggle="tab" href="#pessoascomfiltro" role="tab" aria-controls="pessoascomfiltro" aria-selected="false">Pessoas Selecionadas</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" style="border: solid #f1f1f1 1px; border-radius: 2px;">
                                                <!-- TABELA FAMILIAS COM FILTRO -->                                    
                                                <div class="tab-pane fade show active" id="familiascomfiltro" role="tabpanel" aria-labelledby="familias-tab" style="padding: 2px;">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 20%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Código Família<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 20%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Quantidade Integrantes<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 60%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Nome Responsável Familiar<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <?php foreach ($parametros as $parametro): ?>
                                                                <tr class="odd">
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <!-- TABELA PESSOAS COM FILTRO -->                                    
                                                <div class="tab-pane fade" id="pessoascomfiltro" role="tabpanel" aria-labelledby="pessoas-tab" style="padding: 2px;">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 46%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Nome<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 18%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">CPF<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 16%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">RG<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 20%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Função Familiar<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <?php foreach ($parametros as $parametro): ?>
                                                                <tr class="odd">
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <legend class="py-2 resultado">Resultado Desconsiderando os Limitadores</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" colspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;"><span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n" style="background-color: #8ebdc6; color: #ffffff; padding: 5px; border-radius: 5px;">150</span>
                                                        <p style="padding-top: 10px !important;">Famílias Selecionadas</p>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1" colspan="3">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;"><span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n" style="background-color: #8ebdc6; color: #ffffff; padding: 5px; border-radius: 5px;">250</span>
                                                        <p style="padding-top: 10px !important;">Pessoas Selecionadas</p>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="ui-state-default" rowspan="1" style="width: 50%;">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Descrição<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Quantidade<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Valor Unitário<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                                <th class="ui-state-default" rowspan="1">
                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Valor Total<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <?php foreach ($parametros as $parametro): ?>
                                                <tr class="odd">
                                                    <?php if ($beneficio->id_publico_alvo == 2): ?>
                                                        <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $parametro->nome_produto . ' (Benefício Concedido a pessoa)'; ?></td>
                                                    <?php else: ?>
                                                        <td valign="top" colspan="1" class="dataTables_wrapper"><?php echo $parametro->nome_produto . ' (Benefício Concedido a família)'; ?></td>
                                                    <?php endif; ?>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo ($parametro->quantidade * 250); ?></td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario, 2, ',', '.'); ?></td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario * 250, 2, ',', '.'); ?></td>
                                                </tr>
                                                <tr class="odd">
                                                    <td valign="top" colspan="2" class="dataTables_wrapper" style="text-align: center;"></td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="font-weight: 900;">Total:</td>
                                                    <td valign="top" colspan="1" class="dataTables_wrapper" style="text-align: center;"><?php echo 'R$&nbsp;' . number_format($parametro->valor_unitario * 250, 2, ',', '.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </thead>
                                    </table>
                                    <div class="panel-body">                            
                                        <div class="form-content">
                                            <ul class="nav nav-pills nav-justified" id="tabelaFamiliasPessoasSemFiltro" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="familias-sem-filtro-tab" data-toggle="tab" href="#familiassemfiltro" role="tab" aria-controls="familiassemfiltro" aria-selected="true">Famílias Selecionadas</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="pessoas-sem-filtro-tab" data-toggle="tab" href="#pessoassemfiltro" role="tab" aria-controls="pessoassemfiltro" aria-selected="false">Pessoas Selecionadas</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" style="border: solid #f1f1f1 1px; border-radius: 2px;">
                                                <!-- TABELA FAMILIAS SEM FILTRO -->                                    
                                                <div class="tab-pane fade show active" id="familiassemfiltro" role="tabpanel" aria-labelledby="familias-tab" style="padding: 2px;">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 20%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Código Família<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 20%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Quantidade Integrantes<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 60%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Nome Responsável Familiar<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <?php foreach ($parametros as $parametro): ?>
                                                                <tr class="odd">
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <!-- TABELA PESSOAS SEM FILTRO -->                                    
                                                <div class="tab-pane fade" id="pessoassemfiltro" role="tabpanel" aria-labelledby="pessoas-tab" style="padding: 2px;">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 46%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Nome<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 18%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">CPF<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 16%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">RG<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                                <th class="ui-state-default" rowspan="1" colspan="1" style="width: 20%;">
                                                                    <div class="DataTables_sort_wrapper" style="text-align: center;">Função Familiar<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <?php foreach ($parametros as $parametro): ?>
                                                                <tr class="odd">
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br><br>
            </div>

            <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
            <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>