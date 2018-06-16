<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
		<script type="text/javascript" src="<?= base_url();?>configuracoes/js/jquery-1.8.2.min.js"></script>
		<script src="<?= base_url();?>configuracoes/js/jquery-css-transform.js" type="text/javascript"></script>
<script language="javascript">
		$(document).ready(function() {
		$(".loadinggif").hide();
			$("#carrega_dados").submit(function() {
				$(".loadinggif").show();
			})
		});
	</script>
<div id="content">
    <h1 class="bg-white content-heading border-bottom">PROGRAMAS</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
				<style type="text/css">
			.carregando{
				color:#666;
				display:none;
			}
		</style>
		<form name="carrega_dados" method="post" id="carrega_dados" action="lista_programas">
	</head>

		<br />
		Programas escolhidos pela cidade:
		<br />
		<br />
		<?php
		var_dump($orgaos);
			foreach($lista as $programa){
				echo "<p><a href=\"".$programa->link."\" target=\"_blank\">".$programa->codigo."</a> - ".$programa->nome."</p>";
			}
		?>
		<br />
		<a class="buttonLink" href="<?= base_url();?>index.php/in/usuario/relatorio">Gerar Relatório</a>
		<a class="buttonLink" href="<?= base_url();?>index.php/in/usuario/solicitar_mudanca">Solicitar mudanças</a>
		<br />
		<br />
		Obs. O siconv pode não funcionar corretamente ao clicar no link do programa e não é de nossa responsabilidade quaisquer inconstâncias encontradas lá.
		<br /> Se tiver a sessão expirada tente novamente (mais 2 vezes) para que o siconv abra uma nova sessão e você possa continuar navegando na plataforma online.
		<div class='loadinggif'><center><img src='<?= base_url();?>configuracoes/loading1.gif'/><br/>
		<p>CARREGANDO DADOS DO SICONV. ESSA TAREFA PODE DEMORAR ALGUNS MINUTOS...</p></center></div>
		</form>
		<script src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load('jquery', '1.3');
		</script>		

		<script type="text/javascript">
		$(function(){
			$('#cod_estados').change(function(){
				if( $(this).val() ) {
					$('#cod_cidades').hide();
					$('.carregando').show();
					$.getJSON('cidades_ajax?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
						var options = '<option value=""></option>';	
						for (var i = 0; i < j.length; i++) {
							options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
						}	
						$('#cod_cidades').html(options).show();
						$('.carregando').hide();
					});
				} else {
					$('#cod_cidades').html('<option value="">– Escolha um estado –</option>');
				}
			});
		});
		
function formatar(src, mask,e) 
{
	var tecla =""
	if (document.all) // Internet Explorer
		tecla = event.keyCode;
	else
		tecla = e.which;
	//code = evente.keyCode;
	if(tecla != 8){


	if (src.value.length == src.maxlength){
	return;
	}
  var i = src.value.length;
  var saida = mask.substring(0,1);
  var texto = mask.substring(i);
  if (texto.substring(0,1) != saida) 
  {
	src.value += texto.substring(0,1);
  }
	  }
}
		</script>
	</div>
</div>
</div>
</div>

