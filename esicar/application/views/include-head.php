<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"</script>
<![endif]-->
<!--Transfusão de CSS-->
<script src="<?php echo base_url('configuracoes/js/jquery-1.11.0.min.js'); ?>"></script>
<script src="<?php echo base_url('configuracoes/js/jquery-migrate-1.2.1.min.js'); ?>"></script>
<link href="<?php echo base_url('layout/styleless.less'); ?>" rel="stylesheet/less" type="text/css"/>
<script src="<?php echo base_url('layout/assets/components/plugins/less-js/less.min.js'); ?>"></script>
<!--Transfusão de JS-->
<script src="<?php echo base_url('layout/assets/components/library/modernizr/modernizr.js'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/excanvas.js'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/plugins/browser/ie/ie.prototype.polyfill.js'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/library/jquery-ui/js/jquery-ui.min.js?v=v1.2.3'); ?>"></script>
<!--charts-->
<script src="<?php echo base_url('Chart.js/Chart.bundle.js'); ?>"></script>
<script src="<?php echo base_url('Chart.js/Chart.bundle.min.js'); ?>"></script>
<!--Tiles-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('tiles/programasabertos.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('tiles/magic.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('tiles/swashIn.css'); ?>"/>
<?php if ($this->session->userdata('gp') == TRUE || $this->input->get('gp', TRUE) !== FALSE): ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('tiles/gp.css'); ?>"/>
<?php endif; ?>
