<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>

<noscript>Javascript desabilitado ! Por favor habilite !</noscript>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">  
<link rel="SHORTCUT ICON" href="<?php echo base_url('layout/assets/images/favicon.png');?>" type="image/x-icon">

<title><?php echo $title; ?></title>
		
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
			
</head>

<style type="text/css">
#geral {
	margin: auto;
	width:900px;
	height:auto;
	text-align:center;
	background-image: url('<?php echo base_url("layout/assets/images/background.png"); ?>'); 
	background-repeat: repeat-x;
	background-size: 900px 972px;
	margin-top: -1px;
}

body{
	background-color: #fff;
}

#menu{
	margin: auto;
	height: 94px;
	width: 300px;
}

#menu ul{
	list-style-type: none;
	margin-top: 20px;
}

#menu ul li{
	display: inline;
	padding: 10px;
}

#topo{
	margin: auto;
	background-color: #fff;
	background-image: url('<?php echo base_url("layout/assets/images/topo.png"); ?>'); 
	background-repeat: no-repeat;
	background-size: 900px 450px;
	width: 900px;
	height: 450px;
	margin-top: -40px;
	padding: 20px;
	text-align: center;
}

#rodape{
	margin: auto;
	background-image: url('<?php echo base_url("layout/assets/images/rodape_site.png"); ?>'); 
	background-repeat: no-repeat;
	background-size: 900px 120px;
	height: 120px;
	width: 900px;
	margin-top: -1px;
}
</style>
	
<body>

<div id="topo">
	
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	
	<div id="menu">
		<ul>
	    	<li style="margin-top: 14px;">
	        	<a href="<?php echo base_url('site'); ?>"><img src="<?php echo base_url('layout/assets/images/home.png'); ?>" width="60" height="20" class="img_menu"></a>
	        </li>
	        <li style="margin-top: 14px;">
	        	<a href="<?php echo base_url('site/contato'); ?>"><img src="<?php echo base_url('layout/assets/images/contato.png'); ?>" width="74" height="21" class="img_menu"></a>
	        </li>
	        <li style="margin-top: 14px;">
	        	<a href="<?php echo base_url('in/login'); ?>"><img src="<?php echo base_url('layout/assets/images/login.png'); ?>" height="22" width="60" class="img_menu"></a>
	        </li>
	    </ul>
    </div>
    
</div>

<div id="geral">
	<?php $this->load->view($main); ?>
</div>

<div id="rodape"></div>

<script src="<?php echo base_url('layout/assets/components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/plugins/breakpoints/breakpoints.js?v=v1.2.3'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/core/js/animations.init.js?v=v1.2.3'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/jquery.flot.js?v=v1.2.3'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/jquery.flot.resize.js?v=v1.2.3'); ?>"></script>
<script src="<?php echo base_url('layout/assets/components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.tooltip.min.js?v=v1.2.3'); ?>"></script>
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

<script type="text/javascript">
$(document).ready(function(){
	$(".img_menu").hover(function(){
		var attr = $(this).attr('src');
		$(this).attr('src', attr.replace('.png', '_verde.png'));
	});

	$(".img_menu").mouseout(function(){
		var attr = $(this).attr('src');
		$(this).attr('src', attr.replace('_verde.png', '.png'));
	});

	$(".img_menu").mouseleave(function(){
		var attr = $(this).attr('src');
		$(this).attr('src', attr.replace('_verde.png', '.png'));
	});
})
</script>