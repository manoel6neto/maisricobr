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
        <meta charset="UTF-8"/>
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
        <?php if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false): ?>
            <link rel="SHORTCUT ICON" href="<?php echo base_url('layout/assets/images/favicon.png'); ?>" type="image/x-icon">
        <?php else: ?>
            <link rel="SHORTCUT ICON" href="<?php echo base_url('layout/assets/images/logophysis_icon.png'); ?>" type="image/x-icon">
        <?php endif; ?>
        <title><?php echo $title; ?></title>
        <?php $this->load->view('include-head'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('apresentacao/apresentacao.css'); ?>"/>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/js/bootstrap-tour-standalone.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/css/bootstrap-tour-standalone.min.css" rel="stylesheet"/>
    </head>
    <body class="bg-white">
        <?php $this->load->view('apresentacao/navigation'); ?>
        <?php $this->load->view($main); ?>
        <div class="clearfix"></div>
        <script>
            var basePath = '../../../../../layout/',
                    commonPath = '../../../../../layout/assets/',
                    rootPath = '../',
                    DEV = false,
                    componentsPath = '../../../../../layout/assets/components/';
            var primaryColor = '#cb4040',
                    dangerColor = '#b55151',
                    infoColor = '#466baf',
                    successColor = '#8baf46',
                    warningColor = '#ab7a4b',
                    inverseColor = '#45484d';
            var themerPrimaryColor = primaryColor;
        </script>
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
        src="<?php echo base_url('layout/assets/components/modules/admin/forms/validator/assets/lib/jquery-validation/dist/jquery.validate.min.js?v=v1.2.3'); ?>"></script>
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
