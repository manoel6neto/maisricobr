<?php
define ( "LATIN1_UC_CHARS", "Ã€Ã�Ã‚ÃƒÃ„Ã…Ã†Ã‡ÃˆÃ‰ÃŠÃ‹ÃŒÃ�ÃŽÃ�Ã�Ã‘Ã’Ã“Ã”Ã•Ã–Ã˜Ã™ÃšÃ›ÃœÃ�" );
define ( "LATIN1_LC_CHARS", "Ã Ã¡Ã¢Ã£Ã¤Ã¥Ã¦Ã§Ã¨Ã©ÃªÃ«Ã¬Ã­Ã®Ã¯Ã°Ã±Ã²Ã³Ã´ÃµÃ¶Ã¸Ã¹ÃºÃ»Ã¼Ã½" );
function uc_latin1($str) {
	$str = strtoupper ( strtr ( $str, LATIN1_LC_CHARS, LATIN1_UC_CHARS ) );
	return strtr ( $str, array (
			"ÃŸ" => "SS" 
	) );
}
if (isset($orgao)){}else $orgao="nada";
?>
<style>
.panel-heading {
	margin: 10px;
}

.panel-heading:hover {
	background-color: #DDD !important;
}

#form-search {
	z-index: 1000;
	display: block;
	position: relative;
}
.codigo{font-size:small; float:right;}
</style>

<div id="content" class="bg-white">
	
	<?php if(isset($lista) AND $lista!=null){ ?>
	<h1 class="bg-white content-heading border-bottom">Programas Abertos</h1>
	<h1 class="bg-white" style="text-align: right;">
	
		<span><?php echo $paginas; ?></span>
		
		<span class="badge badge-info"><?php if(isset($num_rows)){echo ($offSet+1)." a ".($offSet+$num_rows)." de {$total_rows} resultados";}?></span>

	</h1>
	<div class="widget borders-none">
		<div class="widget-body ">
		
		<?php 
	
		$i=1; 
		foreach($lista as $programa){  
		
			?>
		
			<div class="innerAll border-bottom tickets">
					<div class="row">
						<div class="col-sm-12">
							<div class="pull-right ">
								<label class="label label-primary codigo"><?php echo $programa->codigo;?></label>
							</div>
							<ul class="media-list">
								<li class="media">
									<div class="pull-left">
										<div class="center">
<!-- 											<div class="checkbox"> -->
<!-- 												<label class="checkbox-custom"> <i -->
<!-- 													class="fa fa-fw fa-square-o"></i> <input type="checkbox"> -->
<!-- 												</label> -->
<!-- 											</div> -->
										</div>
									</div>
	
									<div class="media-body">
	
	
										<a href="<?php echo $programa->link;?>" target="_blank" class="media-heading"><?php echo $i ." - "; $i++?> <?php echo $programa->nome;?></a>
	
	
	
										<div class="clearfix"></div>
										<?php echo $programa->descricao;?>
										<br><br>
										<b>Qualificação do Proponente:</b> <?php echo $programa->qualificacao;?>
										<br>
										<b>Qualificação da Proposta:</b> <?php echo $programa->atende;?>
										<br>
										<b>Estados Atendidos:</b> <?php echo $programa->estados;?>
										<br><br>
	
										<div class="clearfix pull-left">
											Data de Inicio <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio)));?></label>
											| Data Final <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim)));?></label>
											| Orgão <label class="label label-info"><?php echo $programa->orgao;?></label>
										</div>
										
										
	
									</div>
	
								</li>
	
							</ul>
	
	
						</div>
	
					</div>
	
				</div>
			<?php 
		 	}
		 ?>
	
	
		</div>
	</div>
	<?php } //fim if lista
	else { ?>
	<h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
	<?php }?>
</div>

<script language="javascript">
							
$(document).ready(function() {
	$(".loadinggif").hide();
		$("#carrega_dados").submit(function() {
			$(".loadinggif").show();
		});
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

function selecionar_parte(classe){
	var divs = document.getElementsByClassName("prop1");
	for(var i=0; i<divs.length; i++) {
		divs[i].checked = 0;
	}
	var divs = document.getElementsByClassName(classe);
	for(var i=0; i<divs.length; i++) {
		divs[i].checked = 1;
	}
}

function ativa1(){
	var data2 = document.getElementById("Data2");
	var data1 = document.getElementById("Data1");
	if (data2.disabled == true){ data2.disabled = false; data1.disabled = false; }
	else { data2.disabled = true; data1.disabled = true; }
}

function ativa(nome){
	var classe = document.getElementById("div_"+nome);
	if (classe.style.display == 'none') classe.style.display = '';
	else classe.style.display = 'none';
}
function formatar(src, mask,e) 
{
	var tecla = "";
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

