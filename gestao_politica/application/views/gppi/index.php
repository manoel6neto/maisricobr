<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_modulos">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - GPPI</title>
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
                        <li class="nav-item gppi-nav active">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi'); ?>">DashBoard <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item gppi-nav">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi/processos'); ?>">Gestão de Processos</a>
                        </li>
                        <li class="nav-item gppi-nav">
                            <a class="nav-link gppi-nav" href="<?php echo base_url('index.php/Gppi/beneficios'); ?>">Simulação de Benefícios</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <h1 class="my-4">
                <small></small>
            </h1>

            <!-- Início Tabela de Simulação de Benefícios -->            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">DashBoard GPPI</h6>
                        </div>
                        <div class="panel-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <!--Div that will hold the pie chart-->
                                        <div id="chart_div1"></div>
                                    </div>
                                    <div class="col">
                                        <!--Div that will hold the pie chart-->
                                        <div id="chart_div2"></div>
                                    </div>
                                    <div class="col">
                                        <!--Div that will hold the pie chart-->
                                        <div id="chart_div3"></div>
                                    </div>
                                    <div class="col">
                                        <!--Div that will hold the pie chart-->
                                        <div id="chart_div4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Charts Core -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- Chart 1 -->
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Secretaria');
                data.addColumn('number', 'Total');
                data.addRows([
                    ['Esporte', 3],
                    ['Saúde', 1],
                    ['Educação', 1],
                    ['Assistência Social', 1],
                    ['Planejamento', 2]
                ]);

                var options = {'title': 'Programas por secretaria',
                    'width': 510,
                    'height': 400};

                var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
                chart.draw(data, options);
            }
        </script>

        <!-- Chart 2 -->
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Secretaria');
                data.addColumn('number', 'Total');
                data.addRows([
                    ['Esporte', 3],
                    ['Saúde', 1],
                    ['Educação', 1],
                    ['Assistência Social', 1],
                    ['Planejamento', 2]
                ]);

                var options = {'title': 'Programas por secretaria',
                    'width': 510,
                    'height': 400};

                var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
                chart.draw(data, options);
            }
        </script>

        <!-- Chart 3 -->
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Secretaria');
                data.addColumn('number', 'Total');
                data.addRows([
                    ['Esporte', 3],
                    ['Saúde', 1],
                    ['Educação', 1],
                    ['Assistência Social', 1],
                    ['Planejamento', 2]
                ]);

                var options = {'title': 'Programas por secretaria',
                    'width': 510,
                    'height': 400};

                var chart = new google.visualization.PieChart(document.getElementById('chart_div3'));
                chart.draw(data, options);
            }
        </script>

        <!-- Chart 4 -->
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Secretaria');
                data.addColumn('number', 'Total');
                data.addRows([
                    ['Esporte', 3],
                    ['Saúde', 1],
                    ['Educação', 1],
                    ['Assistência Social', 1],
                    ['Planejamento', 2]
                ]);

                var options = {'title': 'Programas por secretaria',
                    'width': 510,
                    'height': 400};

                var chart = new google.visualization.BarChart(document.getElementById('chart_div4'));
                chart.draw(data, options);
            }
        </script>

        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>
