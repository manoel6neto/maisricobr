<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
    <head>

        <noscript>Javascript desabilitado ! Por favor habilite !</noscript>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">  
        <link rel="SHORTCUT ICON" href="<?php echo base_url(); ?>layout/assets/images/favicon.ico" type="image/x-icon">
            <title><?php echo $title; ?></title>
            <script type="text/javascript">	//<![CDATA[urlApp = '<?= base_url(); ?>';//]]></script>
            <?php $this->load->view('include-head'); ?>

    </head>

                <body style="padding: 2%;">

                    <?php $this->load->view($main); ?>

                    <script src="<?php echo base_url('layout/assets/components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/plugins/breakpoints/breakpoints.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/core/js/animations.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/jquery.flot.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/jquery.flot.resize.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.tooltip.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotcharts.common.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotchart-simple.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotchart-simple-bars.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/widgets/widget-chat/assets/js/widget-chat.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/plugins/slimscroll/jquery.slimscroll.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-datepicker/assets/lib/js/bootstrap-datepicker.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-datepicker/assets/custom/js/bootstrap-datepicker.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/easy-pie/assets/lib/js/jquery.easy-pie-chart.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/easy-pie/assets/custom/easy-pie.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/widgets/widget-scrollable/assets/js/widget-scrollable.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/plugins/holder/holder.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/core/js/sidebar.main.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/core/js/sidebar.collapse.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/core/js/core.init.js?v=v1.2.3'); ?>"></script>	

                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.orderBars.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/custom/js/flotchart-bars-ordered.init.js?v=v1.2.3'); ?>"></script>

                    <script src="<?php echo base_url('layout/assets/components/modules/admin/gallery/blueimp-gallery/assets/lib/js/blueimp-gallery.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/gallery/blueimp-gallery/assets/lib/js/jquery.blueimp-gallery.min.js?v=v1.2.3'); ?>"></script>

                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/select2/assets/lib/js/select2.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/select2/assets/custom/js/select2.init.js?v=v1.2.3'); ?>"></script>

                    <script src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/lib/js/jquery.dataTables.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/lib/extras/TableTools/media/js/TableTools.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/lib/extras/ColVis/media/js/ColVis.min.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/tables/datatables/assets/custom/js/datatables.init.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/fuelux-checkbox/fuelux-checkbox.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-select/assets/lib/js/bootstrap-select.js?v=v1.2.3'); ?>"></script>
                    <script src="<?php echo base_url('layout/assets/components/modules/admin/forms/elements/bootstrap-select/assets/custom/js/bootstrap-select.init.js?v=v1.2.3'); ?>"></script>

                </body>

                </html>

