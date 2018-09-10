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

            <?php if (isset($erro_editar) !== false) echo "<p style='margin-top: 20px; color: #cc0000; font-size: 14px; font-weight: bold;' class=\"error\">" . $erro_editar . "</p>"; ?>
            <?php if (isset($sucesso_editar) !== false) echo "<p style='margin-top: 20px; color: #3399ff; font-size: 14px; font-weight: bold;' class=\"error\">" . $sucesso_editar . "</p>"; ?>

            <!-- Page Heading -->
            <h1 class="my-4">
                <small style="color: #008080;">CADASTRO CIDADÃO UNIFICADO</small>
            </h1>

            <?php if (isset($cidade) && isset($familias)): ?>
                <div class="wrap-table100">
                    <div class="table100 ver1 m-b-16">
                        <div class="table100-head" style="padding-top: 0 !important;">
                            <table>
                                <thead>
                                    <tr class="row100 head" style="margin: 0 !important;">
                                        <th class="cell100 column1" style="padding-top: 0 !important; text-align: center !important; margin: 0 !important;">Cidade:<p style="color: #008080; font-weight: bold; font-size: 18px;"><?php echo $cidade[0]->nome; ?></p></th>
                                        <th class="cell100 column2" style="padding-top: 0 !important; text-align: center !important; margin: 0 !important;">Total Habitantes:<p style="color: #008080; font-weight: bold; font-size: 18px;"><?php echo number_format($cidade[0]->populacao, 0, ',', '.'); ?></p></th>
                                        <th class="cell100 column3" style="padding-top: 0 !important; text-align: center !important; margin: 0 !important;">População Atendida:<p style="color: #008080; font-weight: bold; font-size: 18px;">10.000</p></th>
                                        <th class="cell100 column4" style="padding-top: 0 !important; text-align: center !important; margin: 0 !important;">Total Famílias:<p style="color: #008080; font-weight: bold; font-size: 18px;">5.642</p></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table100-body js-pscroll">
                            <div id="mapa" style="width: 100%; height: 500px;">
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h3>Nenhuma família encontrada!</h3>
            <?php endif; ?> 
        </div>

        <!-- Maps API Javascript -->
        <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAYb-pdADJlCklVP1BuJAouviuEBjCzZxw&amp;sensor=false"></script>
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>

        <script>
            $(document).ready(function () {

                var map;
                var markers = [];
                var markerCluster;

                function initialize() {
                    var latlng = new google.maps.LatLng(<?php echo $cidade[0]->latitude; ?>, <?php echo $cidade[0]->longitude; ?>);

                    var styledMapType = new google.maps.StyledMapType(
                            [
                                {elementType: 'geometry', stylers: [{color: '#ebe3cd'}]},
                                {elementType: 'labels.text.fill', stylers: [{color: '#523735'}]},
                                {elementType: 'labels.text.stroke', stylers: [{color: '#f5f1e6'}]},
                                {
                                    featureType: 'administrative',
                                    elementType: 'geometry.stroke',
                                    stylers: [{color: '#c9b2a6'}]
                                },
                                {
                                    featureType: 'administrative.land_parcel',
                                    elementType: 'geometry.stroke',
                                    stylers: [{color: '#dcd2be'}]
                                },
                                {
                                    featureType: 'administrative.land_parcel',
                                    elementType: 'labels.text.fill',
                                    stylers: [{color: '#ae9e90'}]
                                },
                                {
                                    featureType: 'landscape.natural',
                                    elementType: 'geometry',
                                    stylers: [{color: '#dfd2ae'}]
                                },
                                {
                                    featureType: 'poi',
                                    elementType: 'geometry',
                                    stylers: [{color: '#dfd2ae'}]
                                },
                                {
                                    featureType: 'poi',
                                    elementType: 'labels.text.fill',
                                    stylers: [{color: '#93817c'}]
                                },
                                {
                                    featureType: 'poi.park',
                                    elementType: 'geometry.fill',
                                    stylers: [{color: '#a5b076'}]
                                },
                                {
                                    featureType: 'poi.park',
                                    elementType: 'labels.text.fill',
                                    stylers: [{color: '#447530'}]
                                },
                                {
                                    featureType: 'road',
                                    elementType: 'geometry',
                                    stylers: [{color: '#f5f1e6'}]
                                },
                                {
                                    featureType: 'road.arterial',
                                    elementType: 'geometry',
                                    stylers: [{color: '#fdfcf8'}]
                                },
                                {
                                    featureType: 'road.highway',
                                    elementType: 'geometry',
                                    stylers: [{color: '#f8c967'}]
                                },
                                {
                                    featureType: 'road.highway',
                                    elementType: 'geometry.stroke',
                                    stylers: [{color: '#e9bc62'}]
                                },
                                {
                                    featureType: 'road.highway.controlled_access',
                                    elementType: 'geometry',
                                    stylers: [{color: '#e98d58'}]
                                },
                                {
                                    featureType: 'road.highway.controlled_access',
                                    elementType: 'geometry.stroke',
                                    stylers: [{color: '#db8555'}]
                                },
                                {
                                    featureType: 'road.local',
                                    elementType: 'labels.text.fill',
                                    stylers: [{color: '#806b63'}]
                                },
                                {
                                    featureType: 'transit.line',
                                    elementType: 'geometry',
                                    stylers: [{color: '#dfd2ae'}]
                                },
                                {
                                    featureType: 'transit.line',
                                    elementType: 'labels.text.fill',
                                    stylers: [{color: '#8f7d77'}]
                                },
                                {
                                    featureType: 'transit.line',
                                    elementType: 'labels.text.stroke',
                                    stylers: [{color: '#ebe3cd'}]
                                },
                                {
                                    featureType: 'transit.station',
                                    elementType: 'geometry',
                                    stylers: [{color: '#dfd2ae'}]
                                },
                                {
                                    featureType: 'water',
                                    elementType: 'geometry.fill',
                                    stylers: [{color: '#b9d3c2'}]
                                },
                                {
                                    featureType: 'water',
                                    elementType: 'labels.text.fill',
                                    stylers: [{color: '#92998d'}]
                                }
                            ],
                            {name: 'Styled Map'});

                    var options = {
                        zoom: 11,
                        center: latlng,
                        fullscreenControl: false,
                        rotateControl: false,
                        mapTypeControl: false,
                        scaleControl: false,
                        mapTypeControlOptions: {
                            mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
                                'styled_map']},
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    map = new google.maps.Map(document.getElementById("mapa"), options);
                    map.mapTypes.set('styled_map', styledMapType);
                    map.setMapTypeId('styled_map');
                }

                function bindInfoWindow(marker, map, infoWindow, html) {
                    google.maps.event.addListener(marker, 'click', function () {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    });
                }

                function carregarPontos() {
                    var pontos = <?php echo $json_familias; ?>;

                    $.each(pontos, function (index, ponto) {

                        var infoWindow = new google.maps.InfoWindow({maxWidth: 1000});

                        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(ponto.latitude, ponto.longitude),
                            title: ponto.codigo,
                            label: labels[index % labels.length],
                            animation: google.maps.Animation.DROP,
                            map: map
                        });

                        var html = "<div><h3>" + "<p>Código família: " + ponto.codigo + "</p>" + "<p>Bairro: " + ponto.bairro + "</p>" + "<p>Endereço: " + ponto.logradouro + ", " + ponto.numero + "</p>" + "<p>Cep: " + ponto.cep + "</p>" + "<p><a href='http://localhost/gestao_politica/index.php/CadastroUnico/detalhar_familia?id=" + ponto.id + "' target='_blank'>Detalhes</a></p></h3></div>";
                        bindInfoWindow(marker, map, infoWindow, html);

                        markers.push(marker);
                    });
                    markerCluster = new MarkerClusterer(map, markers, {gridSize: 100, maxZoom: 18, imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
                }

                initialize();
                carregarPontos();
            });
        </script>

    </body>
</html>
