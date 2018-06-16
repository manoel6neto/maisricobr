<div id="content" class="bg-white">

<?php
if(isset($proposta->parecer_proposta) && $proposta->parecer_proposta != "")
	echo $proposta->parecer_proposta;
else
	echo '<h1 style="text-align: center;">Nenhum parecer foi encontrado.</h1>';
?>
&nbsp;&nbsp;
<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href='<?php echo base_url(); ?>index.php/in/gestor/visualiza_propostas'">
<a class="btn btn-info" title="Atualizar Parecer" id="atualizaParecer"><i class="fa fa-refresh"></i></a>
<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#loader").hide();
	
	$(".buttonLink").each(function(){
		var href = $(this).attr('href');

		var parteLink = href.split("?");
		var parteId = parteLink[1].split("&");
		var url = window.location.pathname;

		$(this).attr('href', $(location).attr('href')+"&"+parteId[0]+"&"+parteId[1]);
	});

	$("#atualizaParecer").click(function(){
		var url = $(location).attr('href');
		var idProposta = url.split("?id=");
		var urlParecer = '<?php echo base_url().'index.php/in/get_propostas/update_status_porposta/'?>'+idProposta[1];
		$.ajax({
			url:urlParecer,
			type:'get',
			dataType:'html',
			beforeSend:function(){
				$("#loader").slideDown();
			},
			success:function(data){
				location.href=$(location).attr('href');
			}
		});
		return false;
	});

	var dialog;
	
	dialog = $( "#dialog-message" ).dialog({
		height: 320,
		width: 550,
		modal: true,
		buttons: {
			"Fechar": function() {
				$( this ).dialog( "close" );
			}
		}
	}).position({
	    my: "center",
	    at: "center",
	    of: window
	});

	dialog.dialog( "close" );
	
	$(".buttonLink").click(function(){
		var data_parecer = $(this).parent().parent().parent().children("td:nth-child(1)").html().replace('<div class="data">', "").replace("</div>", "");
		dialog.dialog("option", "title", "Parecer - "+data_parecer);
		$.ajax({
			url:$(this).attr('href'),
			dataType:'html',
			success:function(data){
				$("#msgParecer").html(data);
			}
		});
		
		dialog.dialog("open");

		return false;
	});
});
</script>

<div id="dialog-message" title="Parecer">
<p style="font-size: 16px;" id="msgParecer"></p>
</div>