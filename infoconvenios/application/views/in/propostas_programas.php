<div id="content">
	<div class="innerAll spacing-x2">

		<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">PROPOSTAS POR CIDADE</h4>
			</div>
		<div class="widget-body center">
		<script language="javascript">
				$(document).ready(function() {
				$(".loadinggif").hide();
					$("#carrega_dados").submit(function() {
						$(".loadinggif").show();
					})
				});

		function selecionar(classe){
			var divs = document.getElementsByClassName(classe);
			for(var i=0; i<divs.length; i++) {
				if(document.getElementById(classe).checked == 1)
					divs[i].checked = 1;
				else
					divs[i].checked = 0;
			}
		}

		function ativa(nome){
			var classe = document.getElementById("div_"+nome);
			if (classe.style.display == 'none') classe.style.display = '';
			else classe.style.display = 'none';
		}
		</script>
		<div id="ConteudoDiv">
			<div id="salvar" class="action">
				<div class="trigger">
						<style type="text/css">
					.carregando{
						color:#666;
						display:none;
					}
				</style>
				<form name="carrega_dados" method="post" id="carrega_dados">
			</head>

				<label for="cod_estados">Estado:</label>
				<select name="cod_estados" id="cod_estados">
					<option value=""></option>
					<?php
						foreach ($cidades as $estado) {
							echo '<option value="'.$estado['cod_estados'].'">'.$estado['sigla'].'</option>';
						}
					?>
				</select>

				<label for="cod_cidades">Cidade:</label>
				<span class="carregando">Aguarde, carregando...</span>
				<select name="cod_cidades" id="cod_cidades">
					<option value="">-- Escolha um estado --</option>
				</select>
				<br />
				
				<br />
				<div class="widget-body center">
					<input class="btn btn-primary" type="submit" name="operation" onclick="" value="Carregar informações" />
				</div>
				
				
				<div class='loadinggif'><center><img src='<?= base_url();?>configuracoes/loading1.gif'/><br/>
				<p>CARREGANDO DADOS DO SICONV PARA GERAÇÃO DO RELATÓRIO. ESSA TAREFA PODE DEMORAR ALGUNS MINUTOS...</p></center></div>
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
				</script>
				</div>
			</div>
		</div>
		</div>
		</div>
	</div>
</div>

