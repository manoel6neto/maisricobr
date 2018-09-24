<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_modulos">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Simulação de Benefícios (GPPI)</title>
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
                                        <a class="nav-link active" id="iniciais-tab" data-toggle="tab" href="#dadosIniciais" role="tab" aria-controls="home" aria-selected="true">1 - Dados Iniciais</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="limitadores-tab" data-toggle="tab" href="#limitadores" role="tab" aria-controls="limitadores" aria-selected="false">2 - Limitadores</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="criterios-tab" data-toggle="tab" href="#criterioSelecao" role="tab" aria-controls="criterios" aria-selected="false">3 - Critérios de Seleção</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="parametros-tab" data-toggle="tab" href="#parametro" role="tab" aria-controls="parametros" aria-selected="false">4 - Parâmetros</a>
                                    </li>
                                </ul>

                                <!-- Início Formulário -->
                                <form id="formSimulacao" action="simulacao" method="post">
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
                                                    <select class="form-control" id="orgaoGestor" name="orgaoGestor">
                                                        <option value="">Selecione uma opção</option>
                                                        <?php foreach ($orgaos as $orgao): ?>
                                                            <option value="<?php echo $orgao->id; ?>"><?php echo $orgao->nome_orgao; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="tipoBeneficio" class="col-sm-2 col-form-label">Tipo do Benefício *</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" id="tipoBeneficio" name="tipoBeneficio">
                                                        <option value="">Selecione uma opção</option>
                                                        <?php foreach ($tipos_beneficio as $tipo_beneficio): ?>
                                                            <option value="<?php echo$tipo_beneficio->id; ?>"><?php echo $tipo_beneficio->descricao; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="publicoAlvo" class="col-sm-2 col-form-label">Público Alvo *</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" id="publicoAlvo" name="publicoAlvo">
                                                        <option value="">Selecione uma opção</option>
                                                        <?php foreach ($publicos_alvo as $publico_alvo): ?>
                                                            <option value="<?php echo $publico_alvo->id; ?>"><?php echo $publico_alvo->descricao; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row mr-1 mt-5">
                                                <button type="button" id="next_inicial" class="btn btn-primary ml-auto">Próximo</button>                                            
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
                                                <button type="button" id="ant_limitadores" class="btn btn-secondary ml-auto">Anterior</button>
                                                <button type="button" id="next_limitadores" class="btn btn-primary ml-2">Próximo</button>                                            
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
                                                    <div id="collapseOne" class="collapse show" id="camposDisponiveis" name="camposDisponiveis" data-toggle="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                        <div class="card-body row">

                                                            <div class="card bg-light col-sm" id="checkLocalidade" name="checkLocalidade">
                                                                <div class="card-header" style="width: 219px;">Localidade</div>
                                                                <div class="card-body">
                                                                    <label><input class="chk01 my-1" id="chk1" name="chk1" type="checkbox" value=""> Bairro</label>                                                              
                                                                    <label><input class="chk02 my-1" id="chk2" name="chk2" type="checkbox" value=""> CEP</label>
                                                                </div>
                                                            </div>
                                                            <div class="card bg-light col-sm ml-1" id="checkFamilia" name="checkFamilia">
                                                                <div class="card-header">Familia</div>
                                                                <div class="card-body">
                                                                    <!--<label><input type="checkbox"> Quantidade de Pessoas</label>-->                                                               
                                                                    <label><input class="chk03 my-1" name="chk3" type="checkbox" id="chk3" value=""> Quantidade de crianças</label>
                                                                    <label><input class="chk04 my-1" name="chk4" type="checkbox" id="chk4" value=""> Quantidade de idosos</label>
                                                                    <!--<label><input class="my-1" type="checkbox"> Quantidade de jovens</label>-->
                                                                    <!--<label><input class="my-1" type="checkbox"> Quantidade de deficientes</label>-->
                                                                    <label><input class="chk05 my-1" name="chk5" type="checkbox" id="chk5" value=""> Renda familiar</label><br><br>
                                                                    <!--<label><input class="my-1" type="checkbox"> Renda per capita</label>-->
                                                                </div>
                                                            </div>
                                                            <div class="card bg-light col-sm ml-1" id="checkPessoa" name="checkPessoa">
                                                                <div class="card-header">Pessoa</div>
                                                                <div class="card-body">
                                                                    <label><input class="chk06 my-1" name="chk6" type="checkbox" id="chk6" value=""> Renda</label>
                                                                    <label><input class="chk07 my-1" name="chk7" type="checkbox" id="chk7" value=""> Faixa etária</label>
                                                                    <label><input class="chk08 my-1" name="chk8" type="checkbox" id="chk8" value=""> Idade</label>
                                                                    <label><input class="chk09 my-1" name="chk9" type="checkbox" id="chk9" value=""> Sexo</label>
                                                                    <label><input class="chk10 my-1" name="chk10" type="checkbox" id="chk10" value=""> Cor ou raça</label><br><br>
                                                                    <!--<label><input class="my-1" type="checkbox"> Tipo de deficiência</label>-->
                                                                    <!--<label><input class="my-1" type="checkbox"> Sabe ler e escrever</label>-->
                                                                    <!--<label><input class="my-1" type="checkbox"> Ano/série que frequentou</label>-->
                                                                    <!--<label><input class="my-1" type="checkbox"> Frequenta escola</label>-->
                                                                    <!--<label><input class="my-1" type="checkbox"> Situação mercado de trabalho</label>-->
                                                                </div>
                                                            </div>
                                                            <!-- <div class="card bg-light col-sm ml-1">
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
                                                                </div>-->

                                                        </div>
                                                    </div>
                                                    <div class="card" id="camposSelecionados" name="camposSelecionados">
                                                        <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                            <h5 class="mb-0">
                                                                <button class="btn btn-light collapsed">
                                                                    Campos Selecionados
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div id="collapse" class="collapsed" aria-expanded="true">
                                                            <div class="card-body">

                                                                <!--Bairro-->

                                                                <div id="sb1" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Bairro</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="bairro" name="bairro" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectBairro" name="selectBairro">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk1" name="chk1" value="X" class="btn01 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--CEP-->

                                                                <div id="sb2" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">CEP</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="cep" name="cep" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectCep" name="selectCep">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk2" name="chk2" value="X" class="btn02 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--Quantidade de crianças-->

                                                                <div id="sb3" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Quantidade de crianças</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="quantidadeCriancas" name="quantidadeCriancas" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectCriancas" name="selectCriancas">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk3" name="chk3" value="X" class="btn03 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--Quantidade de idosos-->

                                                                <div id="sb4" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Quantidade de idosos</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="quantidadeIdosos" name="quantidadeIdosos" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectIdosos" name="selectIdosos">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk4" name="chk4" value="X" class="btn04 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--Renda Familiar-->

                                                                <div id="sb5" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Renda familiar</span>
                                                                        </div>
                                                                        <select class="custom-select" id="selectRendaFamiliar" name="selectRendaFamiliar">
                                                                            <option value="" selected>Escolha uma opção...</option>
                                                                            <option value=">">>(maior que)</option>
                                                                            <option value=">=">>=(maior ou igual)</option>
                                                                            <option value="=">=(igual)</option>
                                                                            <option value="!=">!=(diferente de)</option>
                                                                            <option value="<"><(menor que)</option>
                                                                            <option value="<="><=(menor ou igual)</option>
                                                                        </select>
                                                                        <input type="text" class="form-control" id="rendaFamiliar" name="rendaFamiliar" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectCondicionalRendaFamiliar" name="selectCondicionalRendaFamiliar">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk5" name="chk5" value="X" class="btn05 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>                                                               
                                                                </div>

                                                                <!--Renda Pessoal-->

                                                                <div id="sb6" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Renda pessoal</span>
                                                                        </div>
                                                                        <select class="custom-select" id="selectRendaPessoal" name="selectRendaPessoal">
                                                                            <option value="" selected>Escolha uma opção...</option>
                                                                            <option value=">">>(maior que)</option>
                                                                            <option value=">=">>=(maior ou igual)</option>
                                                                            <option value="=">=(igual)</option>
                                                                            <option value="!=">!=(diferente de)</option>
                                                                            <option value="<"><(menor que)</option>
                                                                            <option value="<="><=(menor ou igual)</option>
                                                                        </select>
                                                                        <input type="text" class="form-control" id="rendaPessoal" name="rendaPessoal" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectCondicionalRendaPessoal" name="selectCondicionalRendaPessoal">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk6" name="chk6" value="X" class="btn06 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--Faixa Etária-->

                                                                <div id="sb7" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Faixa etária entre</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="faixaEtariaInicial" name="faixaEtariaInicial">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">até</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="faixaEtariaFinal" name="faixaEtariaFinal" >
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectFaixaEtaria" name="selectFaixaEtaria">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk7" name="chk7" value="X" class="btn07 btn btn-outline-danger">
                                                                        </div>
                                                                    </div> 
                                                                </div>

                                                                <!--Idade-->

                                                                <div id="sb8" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Idade</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="idade" name="idade" aria-describedby="basic-addon3">
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectIdade" name="selectIdade">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk8" name="chk8" value="X" class="btn08 btn btn-outline-danger">
                                                                        </div>
                                                                    </div> 
                                                                </div>

                                                                <!--Sexo-->

                                                                <div id="sb9" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Sexo</span>
                                                                        </div>
                                                                        <select class="custom-select" id="sexo" name="sexo">
                                                                            <option value="" selected>Selecione uma opção</option>                                                                            
                                                                            <?php foreach ($sexo as $sex): ?>
                                                                                <option value="<?php echo $sex->id; ?>"><?php echo $sex->descricao; ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectSexo" name="selectSexo">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk9" name="chk9" value="X" class="btn09 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--Cor ou Raça-->

                                                                <div id="sb10" style="display:none">
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Cor ou raça</span>
                                                                        </div>
                                                                        <select class="custom-select" id="corRaca" name="corRaca">
                                                                            <option value="" selected>Selecione uma opção</option>
                                                                            <?php foreach ($cor as $cor_raca): ?>
                                                                                <option value="<?php echo $cor_raca->id; ?>"><?php echo $cor_raca->descricao; ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                        <div class="input-group-append">
                                                                            <select class="custom-select" id="selectCorRaca" name="selectCorRaca">
                                                                                <option selected value="e">e</option>
                                                                                <option value="ou">ou</option>
                                                                            </select>
                                                                            <input type="button" id="chk10" name="chk10" value="X" class="btn10 btn btn-outline-danger">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--                                                <div class="card">
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
                                                                                                    </div>-->
                                                </div>

                                            </div>
                                            <div class="form-group row mr-1 mt-5">
                                                <button type="button" id="ant_criterios" class="btn btn-secondary ml-auto">Anterior</button>
                                                <button type="button" id="next_criterios" class="btn btn-primary ml-2">Próximo</button>                                            
                                            </div>
                                        </div>

                                        <!-- Formulário PARÂMETROS -->
                                        <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="contact-tab">
                                            <legend class="py-2">Parâmetros</legend>
                                            <div id="adicionarParametros">
                                                <div  class="form-group row">
                                                    <label for="valorUnitario" class="col-sm-2 col-form-label">Valor Unitário (mês) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="valorUnitario" name="valorUnitario">
                                                    </div>
                                                </div>


                                                <div id="2" class="produtoQuantidade">
                                                    <div class="produtoQuantidade form-group row" style="display: none">
                                                        <label for="nomeProduto" class="col-sm-2 col-form-label">Nome do Produto *</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="nomeProduto" name="nomeProduto">
                                                        </div>
                                                    </div>

                                                    <div  class="produtoQuantidade form-group row" style="display: none">
                                                        <label for="quantProduto" class="col-sm-2 col-form-label">Quantidade *</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="quantProduto" name="quantProduto">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <legend class="py-2">Produtos Adicionais</legend>

                                            <div class="navbar-buttons ml-auto pb-4">
                                                <button id="adicionarParametrosBtn" type="button" class="btn btn-sm btn-primary">Adicionar</button>                                            
                                            </div>

                                            <table id="tabelaSimulacao" name="tabelaSimulacao" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="ui-state-default" rowspan="1" colspan="1">
                                                            <div class="DataTables_sort_wrapper">Valor<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span>
                                                            </div>
                                                        </th>
                                                        <th class="ui-state-default" rowspan="1" colspan="1">
                                                            <div class="DataTables_sort_wrapper">Produto<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span>
                                                            </div>
                                                        </th>
                                                        <th class="ui-state-default" rowspan="1" colspan="1">
                                                            <div class="DataTables_sort_wrapper">Quantidade<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s">                                                            
                                                                </span>
                                                            </div>
                                                        </th>
    <!--                                                    <th class="ui-state-default" rowspan="1" colspan="1">
                                                            <div class="DataTables_sort_wrapper" style="text-align: center;">-<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s">                                                            
                                                                </span>
                                                            </div>
                                                        </th>-->

                                                    </tr>
                                                </thead>
                                                <tbody>
    <!--                                            <tr class="odd">
                                                        <td valign="top" colspan="6" class="dataTables_empty"></td>
                                                    </tr>-->                                            
                                                </tbody>                                            
                                            </table>
                                            <div  class="form-group row" style="display:">
                                                <div class="col-sm-10">
                                                    <button type="button" class="btn btn-sm btn-primary ml-auto mt-2" data-toggle="tooltip" data-placement="top" title="Para excluir um benefício, clique na linha onde estão os dados e depois no botão 'Remover Benefício'" onclick="removerParametros();">Remover Benefício</button>
                                                </div>
                                            </div>

                                            <div class="form-group row mr-1 mt-5">
                                                <button type="button" id="ant_parametros" class="btn btn-secondary ml-auto">Anterior</button>
                                                <button type="submit" id="next_parametros" class="btn btn-primary ml-2">Simular Benefício</button>                                            
                                            </div>
                                        </div>
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
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.inputmask.bundle.js"); ?>"></script>
        <script type="text/javascript">
                                                        $("#valorMensal").inputmask('decimal', {
                                                            radixPoint: ",",
                                                            groupSeparator: ".",
                                                            autoGroup: true,
                                                            digits: 2,
                                                            digitsOptional: false,
                                                            placeholder: '0',
                                                            rightAlign: false,
                                                            onBeforeMask: function (value, opts) {
                                                                return value;
                                                            }
                                                        });

                                                        $("#valorUnitario").inputmask('decimal', {
                                                            radixPoint: ",",
                                                            groupSeparator: ".",
                                                            autoGroup: true,
                                                            digits: 2,
                                                            digitsOptional: false,
                                                            placeholder: '0',
                                                            rightAlign: false,
                                                            onBeforeMask: function (value, opts) {
                                                                return value;
                                                            }
                                                        });

                                                        $("#rendaPessoal").inputmask('decimal', {
                                                            radixPoint: ",",
                                                            groupSeparator: ".",
                                                            autoGroup: true,
                                                            digits: 2,
                                                            digitsOptional: false,
                                                            placeholder: '0',
                                                            rightAlign: false,
                                                            onBeforeMask: function (value, opts) {
                                                                return value;
                                                            }
                                                        });

                                                        $("#rendaFamiliar").inputmask('decimal', {
                                                            radixPoint: ",",
                                                            groupSeparator: ".",
                                                            autoGroup: true,
                                                            digits: 2,
                                                            digitsOptional: false,
                                                            placeholder: '0',
                                                            rightAlign: false,
                                                            onBeforeMask: function (value, opts) {
                                                                return value;
                                                            }
                                                        });

                                                        $(document).ready(function () {
                                                            $("#cep").inputmask("99999-999");
                                                            $("#idade").inputmask("999");
                                                            $("#faixaEtariaInicial").inputmask("999");
                                                            $("#faixaEtariaFinal").inputmask("999");
                                                        });

                                                        //Função oculta os inputs na aba parâmetros
                                                        $(function () {
                                                            $('#tipoBeneficio').change(function () {
                                                                $('.produtoQuantidade').show();
                                                                $('#' + $(this).val()).hide();
                                                            });
                                                        });

                                                        $("#next_inicial").click(function () {
                                                            if ($('#beneficio').val() != '' && $('#orgaoGestor').val() != '' && $('#tipoBeneficio').val() != '' && $('#publicoAlvo').val() != '') {
                                                                $('#tabelaBeneficios li:nth-child(2) a').tab('show');
                                                            }
                                                        });

                                                        $("#next_limitadores").click(function () {
                                                            if ($('#valorMensal').val() != '' || $('#quantidadeBeneficiarios').val() != '') {
                                                                $('#tabelaBeneficios li:nth-child(3) a').tab('show');
                                                            }
                                                        });

                                                        $("#next_criterios").click(function () {
                                                            $('#tabelaBeneficios li:nth-child(4) a').tab('show');
                                                        });

                                                        $("#ant_limitadores").click(function () {
                                                            $('#tabelaBeneficios li:nth-child(1) a').tab('show');
                                                        });

                                                        $("#ant_criterios").click(function () {
                                                            $('#tabelaBeneficios li:nth-child(2) a').tab('show');
                                                        });

                                                        $("#ant_parametros").click(function () {
                                                            $('#tabelaBeneficios li:nth-child(3) a').tab('show');
                                                        });

                                                        $('#tabelaBeneficios a').on('click', function (e) {
                                                            e.preventDefault();
                                                            return false;
                                                        });

                                                        //Função checkbox para seleção dos campos de Critério de Seleção

                                                        $(document).ready(function ()
                                                        {
                                                            //esconde todo o conteúdo
                                                            $('p[id^=sb]').hide();

                                                            $('input[id^=chk]').click(function () {

                                                                // recebe checkbox index
                                                                var index = $(this).attr('id').replace('chk', '');
                                                                // mostra o conteúdo de cada checkbox
                                                                if ($(this).is(':checked'))
                                                                    $('#sb' + index).show();
                                                                else
                                                                    $('#sb' + index).hide();
                                                            });

                                                        });

                                                        $(".btn01").click(function () {
                                                            $(".chk01").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn02").click(function () {
                                                            $(".chk02").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn03").click(function () {
                                                            $(".chk03").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn04").click(function () {
                                                            $(".chk04").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn05").click(function () {
                                                            $(".chk05").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn06").click(function () {
                                                            $(".chk06").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn07").click(function () {
                                                            $(".chk07").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn08").click(function () {
                                                            $(".chk08").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn09").click(function () {
                                                            $(".chk09").prop("click", $(this).prop("click"));
                                                        });
                                                        $(".btn10").click(function () {
                                                            $(".chk10").prop("click", $(this).prop("click"));
                                                        });

                                                        var addLinha,
                                                                table = document.getElementById("tabelaSimulacao");

                                                        // função verificando se há campos vazios ou incorretos
                                                        function verificarCampoVazio()
                                                        {
                                                            var campoVazio = false,
                                                                    valorUnitario = document.getElementById("valorUnitario").value,
                                                                    nomeProduto = document.getElementById("nomeProduto").value,
                                                                    quantProduto = document.getElementById("quantProduto").value;

                                                            if (valorUnitario === "") {
                                                                alert("O campo VALOR não foi preenchido ou está incorreto. Por favor, verifique.");
                                                                campoVazio = true;
                                                            } else if (nomeProduto === "zzz") {
                                                                alert("O campo PRODUTO não foi preenchido ou está incorreto. Por favor, verifique.");
                                                                campoVazio = true;
                                                            } else if (quantProduto === "zzz") {
                                                                alert("O campo QUANTIDADE não foi preenchido ou está incorreto. Por favor, verifique.");
                                                                campoVazio = true;
                                                            }
                                                            return campoVazio;
                                                        }

                                                        // função para add nova linha
                                                        $("#adicionarParametrosBtn").click(function ()
                                                        {
                                                            // capturando a tabela pelo ID
                                                            // criando nova linha e células
                                                            // capturando valor do campo input
                                                            // imprimindo valores nas células e linhas
                                                            if (!verificarCampoVazio()) {
                                                                var novaLinha = table.insertRow(table.length),
                                                                        campo1 = novaLinha.insertCell(0),
                                                                        campo2 = novaLinha.insertCell(1),
                                                                        campo3 = novaLinha.insertCell(2),
                                                                        valorUnitario = document.getElementById("valorUnitario").value,
                                                                        nomeProduto = document.getElementById("nomeProduto").value,
                                                                        quantProduto = document.getElementById("quantProduto").value;

                                                                campo1.innerHTML = valorUnitario;
                                                                campo2.innerHTML = nomeProduto;
                                                                campo3.innerHTML = quantProduto;
                                                                // chamando função para inserir campo na nova linha
                                                                linhaSelecionadaAdd();
                                                            }
                                                        });

                                                        // exibir dados da linha selecionada
                                                        function linhaSelecionadaAdd()
                                                        {
                                                            for (var i = 1; i < table.rows.length; i++)
                                                            {
                                                                table.rows[i].onclick = function ()
                                                                {
                                                                    // indice da linha selecionada
                                                                    addLinha = this.rowIndex;
                                                                    document.getElementById("valorUnitario").value = this.cells[0].innerHTML;
                                                                    document.getElementById("nomeProduto").value = this.cells[1].innerHTML;
                                                                    document.getElementById("quantProduto").value = this.cells[2].innerHTML;
                                                                };
                                                            }
                                                        }
                                                        linhaSelecionadaAdd();

                                                        function editarParametros()
                                                        {
                                                            var valorUnitario = document.getElementById("valorUnitario").value,
                                                                    nomeProduto = document.getElementById("nomeProduto").value,
                                                                    quantProduto = document.getElementById("quantProduto").value;
                                                            if (!verificarCampoVazio()) {
                                                                table.rows[addLinha].cells[0].innerHTML = valorUnitario;
                                                                table.rows[addLinha].cells[1].innerHTML = nomeProduto;
                                                                table.rows[addLinha].cells[2].innerHTML = quantProduto;
                                                            }
                                                        }

                                                        function removerParametros()
                                                        {
                                                            table.deleteRow(addLinha);
                                                            // remover linha
                                                            document.getElementById("valorUnitario").value = "";
                                                            document.getElementById("nomeProduto").value = "";
                                                            document.getElementById("quantProduto").value = "";
                                                        }

                                                        $('.table tbody').on('click', '.btn', function () {
                                                            $(this).closest('tr').remove();
                                                        });
        </script>
    </body>
</html>