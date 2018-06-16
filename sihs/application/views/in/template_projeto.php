<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html> <![endif]-->
<!--[if !IE]><!-->
<html>
    <!-- <![endif]-->
    <head>
        <noscript>Javascript desabilitado ! Por favor habilite !</noscript>
        <!-- Meta -->
        <meta charset="UTF-8" />
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
        <link rel="SHORTCUT ICON" href="<?php echo base_url(); ?>layout/assets/images/favicon.ico" type="image/x-icon">
        <title><?php echo $title; ?></title>
        <?php $this->load->view('include-head'); ?>

    </head>
    <body class="bg-white">
        <style>
            .bwizard-steps li.last-child:after {
                position: absolute;
                right: -20px;
                top: 0;
                height: 0;
                width: 0;
                border-bottom: 20px inset transparent;
                border-left: 0px solid #fafafa;
                border-top: 20px inset transparent;
                content: "";
                z-index: 2
            }
            .bwizard-steps li a {
                display: block;
                height: 40px;
                line-height: 40px;
                padding: 0 10px 0 30px !important;
                color: #7c7c7c;
                font-weight: 600;
            }


            .f-nav{  /* To fix main menu container */
                z-index: 9999;
                position: fixed;
                top: 48px;
                width: 100%;
                padding: 15px;
            }

        </style>
        <?php $this->load->view('in/sidebar2'); ?>
        <?php
        $this->load->view('in/navigation');
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        $activeEscPropo = "";
//$activeSelProg = "";
        $activeIncProp = "";
        $activeIncJus = "";
        $activeListMetas = "";
        $activeListCron = "";
        $activeListObra = "";
        $activeVisProp = "";

        switch ($this->uri->segment(3)) {
            case "escolher_proponente":
            case "selecionar_programas":
            case "informa_valores_programa":
                $activeEscPropo = "active";
                break;
            case "incluir_proposta":
                $activeIncProp = "active";
                break;
            case "incluir_justificativa":
                $activeIncJus = "active";
                break;
            case "listar_metas":
            case "meta":
            case "listar_etapas":
            case "incluir_etapa_da_meta":
                $activeListMetas = "active";
                break;
            case "listar_cronograma":
            case "incluir_parcela_do_cronograma_de_desembolso":
            case "incluir_meta_do_cronograma_de_desembolso":
            case "incluir_etapa_do_cronograma_de_desembolso":
                $activeListCron = "active";
                break;
            case "listar_obras":
            case "incluir_bens_da_proposta":
                $activeListObra = "active";
                break;
            case "visualiza_proposta":
                $activeVisProp = "active";
                break;
        }

        $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));

        $editaProjeto = true;
        if ($this->session->userdata('pagAtual') != "escolher_proponente" && !$permissoes->editar_projeto)
            $editaProjeto = false;

        $dadosProjeto = null;
        if ($this->input->get('id', TRUE) != false)
            $dadosProjeto = $this->proposta_model->get_by_id($this->input->get('id', TRUE));

        $ehPadrao = false;
        if ($dadosProjeto != null && !isset($_GET['padrao']))
            $ehPadrao = (bool) $dadosProjeto->padrao;
// else if(isset($_GET['padrao']))
// 	$ehPadrao = (bool)$_GET['padrao'];

        $enviado = false;
        if ($dadosProjeto != null)
            $enviado = (bool) $dadosProjeto->enviado;
        ?>
        <div id="content" class="bg-white">
            <h1 class="bg-white content-heading border-bottom"><?php echo $enviado ? "" : ((isset($_GET['id']) && !$ehPadrao) ? "Editar Proposta <span style='font-size: 14px; color:#428bca;'>(" . $dadosProjeto->nome . ")</span>" : (!$ehPadrao ? "Nova Proposta" : "Banco de Propostas")); ?></h1>
            <div class="bg-white innerAll menu">
                <div class="wizard-head hidden-xs">
                    <ul class="bwizard-steps">
                        <?php if (!$ehPadrao || ($ehPadrao && $this->session->userdata('nivel') == 1)): ?>
    <?php if ($editaProjeto && !$enviado): ?>
                                <li class="<?php echo $activeEscPropo; ?>"><a href="<?php if (isset($_GET['id']) && !isset($_GET['padrao'])) {
            echo base_url() . "index.php/in/gestor/informa_valores_programa?id=" . $id . "&edit=1";
        } ?>">Orgão &amp; Programa</a></li>
                                <!--<li class="<?php echo $activeSelProg; ?>"><a href="<?php if (isset($_GET['id'])) {
                            echo base_url() . "index.php/in/gestor/selecionar_programas?id=" . $id;
                        } ?>">Programa</a></li>-->
                                <li class="<?php echo $activeIncProp; ?>"><a href="<?php if (isset($_GET['id']) && !isset($_GET['padrao'])) {
                            echo base_url() . "index.php/in/gestor/incluir_proposta?edit=1&id=" . $id;
                        } ?>">Dados da Proposta</a></li>

                                <?php if (!isset($_GET['padrao']) || (isset($_GET['padrao']) && $this->session->userdata('nivel') == 1)): ?>
                                    <li class="<?php echo $activeIncJus; ?>"><a href="<?php if (isset($_GET['id'])) {
                            echo base_url() . "index.php/in/usuario/incluir_justificativa?id=" . $id . "&edita_gestor=1";
                        } ?>">Justificativa</a></li>
                                    <li class="<?php echo $activeListMetas; ?>"><a href="<?php if (isset($_GET['id'])) {
                            echo base_url() . "index.php/in/usuario/listar_metas?id=" . $id . "&edita_gestor=1";
                        } ?>">Crono Físico</a></li>
                                    <li class="<?php echo $activeListCron; ?>"><a href="<?php if (isset($_GET['id'])) {
                                echo base_url() . "index.php/in/usuario/listar_cronograma?id=" . $id . "&edita_gestor=1";
                            } ?>">Crono Desembolso</a></li>
                                    <li class="<?php echo $activeListObra; ?>"><a href="<?php if (isset($_GET['id'])) {
                                echo base_url() . "index.php/in/usuario/listar_obras?id=" . $id . "&edita_gestor=1";
                            } ?>">Plano Detalhado</a></li>
        <?php endif; ?>

    <?php endif; ?>
<?php endif; ?>

<?php if (!isset($_GET['padrao'])): ?>
    <?php $label = (!$ehPadrao && $permissoes->exportar_siconv && !$enviado) ? "Visualizar e Exportar" : "Visualizar" . str_repeat("&nbsp;", 6); ?>
                            <li class="last-child <?php echo $activeVisProp; ?>"><a href="<?php if (isset($_GET['id'])) {
        echo "visualiza_proposta?id=" . $id;
    } ?>"><?php echo $label; ?></a></li>
<?php endif; ?>

                    </ul>
                </div>
            </div> 
<?php $this->load->view($main); ?>

        </div>

        <script type="text/javascript">
            $(function () {
                $(window).scroll(function () {
                    var topo = $('.menu').height();

                    var scrollTop = $(window).scrollTop();

                    if (scrollTop > topo) {
                        $('.menu').addClass('f-nav');
                    } else {
                        $('.menu').removeClass('f-nav');
                    }
                });
            });
        </script>

        <script>
            var basePath = '../../../../../layout/',
                    commonPath = '../../../../../layout/assets/',
                    rootPath = '../',
                    DEV = false,
                    componentsPath = '../../../../../layout/assets/components/';
            var primaryColor = '#3a7ec0',
                    dangerColor = '#b55151',
                    infoColor = '#466baf',
                    successColor = '#8baf46',
                    warningColor = '#ab7a4b',
                    inverseColor = '#45484d';
            var themerPrimaryColor = primaryColor;
        </script>

        <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/validator/assets/lib/jquery-validation/dist/jquery.validate.min.js?v=v1.2.3'); ?>"></script>


        <script
        src="<?php echo base_url('layout/assets/components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/plugins/breakpoints/breakpoints.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/core/js/animations.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/jquery.flot.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/jquery.flot.resize.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.tooltip.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotcharts.common.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotchart-simple.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotchart-simple-bars.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/widgets/widget-chat/assets/js/widget-chat.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/plugins/slimscroll/jquery.slimscroll.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-datepicker/assets/lib/js/bootstrap-datepicker.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-datepicker/assets/custom/js/bootstrap-datepicker.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/easy-pie/assets/lib/js/jquery.easy-pie-chart.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/easy-pie/assets/custom/easy-pie.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/widgets/widget-scrollable/assets/js/widget-scrollable.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/plugins/holder/holder.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/core/js/sidebar.main.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/core/js/sidebar.collapse.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/core/js/core.init.js?v=v1.2.3'); ?>"></script>

        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.orderBars.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotchart-bars-ordered.init.js?v=v1.2.3'); ?>"></script>

        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/gallery/blueimp-gallery/assets/lib/js/blueimp-gallery.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/gallery/blueimp-gallery/assets/lib/js/jquery.blueimp-gallery.min.js?v=v1.2.3'); ?>"></script>

        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/select2/assets/lib/js/select2.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/select2/assets/custom/js/select2.init.js?v=v1.2.3'); ?>"></script>

        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/lib/js/jquery.dataTables.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/lib/extras/TableTools/media/js/TableTools.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/lib/extras/ColVis/media/js/ColVis.min.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/custom/js/datatables.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/fuelux-checkbox/fuelux-checkbox.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-select/assets/lib/js/bootstrap-select.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-select/assets/custom/js/bootstrap-select.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/inputmask/assets/custom/inputmask.init.js?v=v1.2.3'); ?>"></script>
        <script
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/inputmask/assets/lib/jquery.inputmask.bundle.min.js?v=v1.2.3'); ?>"></script>

    </body>
</html>
