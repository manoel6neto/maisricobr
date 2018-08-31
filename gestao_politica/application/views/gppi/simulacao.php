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
        <link href="<?php echo base_url("layout/css/gppi.css"); ?>" rel="stylesheet" type="text/css">
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

            <!-- Simular Benefício -->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">Simular Benefício</h6>
                        </div>

                        <div class="panel-body">                            
                            <div class="form-content">                                        
                                <div class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign">Campos marcados com * são de preenchimento obrigatório.</span>
                                </div>


                                <ul class="nav nav-tabs" id="tabelaBeneficios" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#dadosIniciais" role="tab" aria-controls="home" aria-selected="true">1 - Dados Iniciais</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#limitadores" role="tab" aria-controls="profile" aria-selected="false">2 - Limitadores</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#criterioSelecao" role="tab" aria-controls="contact" aria-selected="false">3 - Critérios de Seleção</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#parametro" role="tab" aria-controls="contact" aria-selected="false">4 - Parâmetros</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#resultado" role="tab" aria-controls="contact" aria-selected="false">5 - Resultados</a>
                                    </li>
                                </ul>

                                <!-- Início Formulário -->

                                <div class="tab-content" id="conteudoTabelaBeneficios">                                    


                                    <!-- Formulário DADOS INICIAIS -->                                    


                                    <div class="tab-pane fade show active" id="dadosIniciais" role="tabpanel" aria-labelledby="home-tab">

                                        <legend class="py-2">Dados Iniciais</legend>

                                        <div class="form-group row">
                                            <label for="beneficio" class="col-sm-2 col-form-label">Benefício *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="beneficio" name="beneficio">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="orgaoGestor" class="col-sm-2 col-form-label">Órgão Gestor *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="orgaoGestor" name="orgaoGestor">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tipoBeneficio" class="col-sm-2 col-form-label">Tipo do Benefício *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="tipoBeneficio" name="tipoBeneficio">
                                                    <option>Selecione uma opção</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="publicoAlvo" class="col-sm-2 col-form-label">Público Alvo *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="publicoAlvo" name="publicoAlvo">
                                                    <option>Selecione uma opção</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mr-1 mt-5">
                                            <button type="button" class="btn btn-secondary ml-auto">Anterior</button>
                                            <button type="button" class="btn btn-primary ml-2">Próximo</button>                                            
                                        </div>

                                    </div>



                                    <!-- Formulário LIMITADORES -->  



                                    <div class="tab-pane fade" id="limitadores" role="tabpanel" aria-labelledby="profile-tab">

                                        <legend class="py-2">Limitadores</legend>

                                        <div class="form-group row">
                                            <label for="valorMensal" class="col-sm-2 col-form-label">Valor Mensal *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="valorMensal" name="valorMensal">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="quantidadeBeneficiarios" class="col-sm-2 col-form-label">Quantidade de Beneficiáios *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="quantidadeBeneficiarios" name="quantidadeBeneficiarios">
                                            </div>
                                        </div>

                                        <div class="form-group row mr-1 mt-5">
                                            <button type="button" class="btn btn-secondary ml-auto">Anterior</button>
                                            <button type="button" class="btn btn-primary ml-2">Próximo</button>                                            
                                        </div>

                                    </div>                                    



                                    <!-- Formulário CRITÉRIOS DE SELEÇÃO -->



                                    <div class="tab-pane fade" id="criterioSelecao" role="tabpanel" aria-labelledby="contact-tab">

                                        <legend class="py-2">Critérios de Seleção</legend>

                                        <div id="accordion">
                                            <div class="card">
                                                <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-light">
                                                            Campos Disponíveis
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                                    <div class="card-body row">

                                                        <div class="card bg-light col-sm">
                                                            <div class="card-header" style="width: 219px;">Localidade</div>
                                                            <div class="card-body">
                                                                <label><input class="my-1" type="checkbox"> Bairro</label>                                                              
                                                                <label><input class="my-1" type="checkbox"> CEP</label>
                                                            </div>
                                                        </div>
                                                        <div class="card bg-light col-sm ml-1">
                                                            <div class="card-header">Familia</div>
                                                            <div class="card-body">
                                                                <label><input type="checkbox"> Quantidade de Pessoas</label>                                                               
                                                                <label><input class="my-1" type="checkbox"> Quantidade de crianças</label>
                                                                <label><input class="my-1" type="checkbox"> Quantidade de idosos</label>
                                                                <label><input class="my-1" type="checkbox"> Quantidade de jovens</label>
                                                                <label><input class="my-1" type="checkbox"> Quantidade de deficientes</label>
                                                                <label><input class="my-1" type="checkbox"> Renda familiar</label>
                                                                <label><input class="my-1" type="checkbox"> Renda per capita</label>
                                                            </div>
                                                        </div>
                                                        <div class="card bg-light col-sm ml-1">
                                                            <div class="card-header">Pessoa</div>
                                                            <div class="card-body">
                                                                <label><input class="my-1" type="checkbox"> Renda</label>
                                                                <label><input class="my-1" type="checkbox"> Faixa etária</label>
                                                                <label><input class="my-1" type="checkbox"> Idade</label>
                                                                <label><input class="my-1" type="checkbox"> Sexo</label>
                                                                <label><input class="my-1" type="checkbox"> Cor ou raça</label>
                                                                <label><input class="my-1" type="checkbox"> Tipo de deficiência</label>
                                                                <label><input class="my-1" type="checkbox"> Sabe ler e escrever</label>
                                                                <label><input class="my-1" type="checkbox"> Ano/série que frequentou</label>
                                                                <label><input class="my-1" type="checkbox"> Frequenta escola</label>
                                                                <label><input class="my-1" type="checkbox"> Situação mercado de trabalho</label>
                                                            </div>
                                                        </div>
                                                        <div class="card bg-light col-sm ml-1">
                                                            <div class="card-header">Domicílio</div>
                                                            <div class="card-body">
                                                                <label><input class="my-1" type="checkbox"> Local domicílio</label>
                                                                <label><input class="my-1" type="checkbox"> N° de cômodos</label>
                                                                <label><input class="my-1" type="checkbox"> Tipo de piso</label>
                                                                <label><input class="my-1" type="checkbox"> Tipo de construção</label>
                                                                <label><input class="my-1" type="checkbox"> Possui água canalizada?</label>
                                                                <label><input class="my-1" type="checkbox"> Tipo abastecimento água</label>
                                                                <label><input class="my-1" type="checkbox"> Tipo escoamento sanitário</label>
                                                                <label><input class="my-1" type="checkbox"> Destino do lixo</label>
                                                                <label><input class="my-1" type="checkbox"> Tipo de iluminação</label>
                                                                <label><input class="my-1" type="checkbox"> Espécie do domicílio</label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-light collapsed">
                                                                Campos Selecionados
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                                        <div class="card-body">
                                                            Ordem dos Critérios
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-light collapsed">
                                                                Área de Definição de Critérios
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                                        <div class="card-body">
                                                            Nada selecionado no momento.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row mr-1 mt-5">
                                            <button type="button" class="btn btn-secondary ml-auto">Anterior</button>
                                            <button type="button" class="btn btn-primary ml-2">Próximo</button>                                            
                                        </div>
                                    </div>



                                    <!-- Formulário PARÂMETROS -->



                                    <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="contact-tab">

                                        <legend class="py-2">Parâmetros</legend>

                                        <div class="form-group row">
                                            <label for="quantidadeBeneficiarios" class="col-sm-2 col-form-label">Nome do Produto *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="quantidadeBeneficiarios" name="quantidadeBeneficiarios">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="quantidadeBeneficiarios" class="col-sm-2 col-form-label">Valor do Benefício (mês) *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="quantidadeBeneficiarios" name="quantidadeBeneficiarios">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="quantidadeBeneficiarios" class="col-sm-2 col-form-label">Quantidade *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="quantidadeBeneficiarios" name="quantidadeBeneficiarios">
                                            </div>
                                        </div>

                                        <legend class="py-2">Produtos Adicionais</legend>

                                        <div class="navbar-buttons ml-auto pb-4">
                                            <button type="button" class="btn btn-sm btn-success">Adicionar</button>                                            
                                        </div>

                                        <table id="tabelaSimulacao" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>

                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Produto<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                        </div>
                                                    </th>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Valor<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span>
                                                        </div>
                                                    </th>
                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                        <div class="DataTables_sort_wrapper">Quantidade<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s">                                                            
                                                            </span>
                                                        </div>
                                                    </th>

                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="odd">
                                                    <td valign="top" colspan="6" class="dataTables_empty">Nenhum produto adicionado.</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="form-group row mr-1 mt-5">
                                            <button type="button" class="btn btn-secondary ml-auto">Anterior</button>
                                            <button type="button" class="btn btn-primary ml-2">Próximo</button>                                            
                                        </div>

                                    </div>



                                    <!-- Formulário RESULTADOS -->



                                    <div class="tab-pane fade" id="resultado" role="tabpanel" aria-labelledby="contact-tab">

                                        <legend class="py-2">Resultados</legend>


                                        <div id="accordion">
                                            <div class="card">
                                                <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-light align-self-center">
                                                            Resultado da Simulação
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                                    <div class="card-body row">
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row mr-1 mt-5">
                                            <button type="button" class="btn btn-secondary ml-auto">Anterior</button>
                                            <button type="button" class="btn btn-primary ml-2">Próximo</button>                                            
                                        </div>
                                    </div>
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