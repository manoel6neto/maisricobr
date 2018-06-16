<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
    <head>
        <noscript>Javascript desabilitado ! Por favor habilite !</noscript>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />  
        <link rel="SHORTCUT ICON" href="<?php echo base_url(); ?>layout/assets/capacitare/logo_capacitare.png" type="image/x-icon" />
        <script src="<?php echo base_url('configuracoes/js/jquery-1.11.0.min.js'); ?>"></script>
        <script src="<?php echo base_url('configuracoes/js/jquery-migrate-1.2.1.min.js'); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('capacitare/capacitare.css'); ?>"/>
        <title><?php echo $title; ?></title>
        <script type="text/javascript">	//<![CDATA[urlApp = '<?= base_url(); ?>';//]]></script>
    </head>

    <body class="loginWrapper" style="padding-top:2%;">
        <?php $this->load->view($main); ?>
    </body>
</html>