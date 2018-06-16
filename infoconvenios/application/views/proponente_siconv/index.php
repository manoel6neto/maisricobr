<div id="content" class="innerAll bg-white">

	<h1 class="bg-white content-heading border-bottom">Buscar Entidades</h1>
	
	<br>
	
	<div class="input-group input-lg ">
		<form class="form-horizontal" action="relacao_entidades" method="post" id="carrega_dados" role="form" name="carrega_dados"></form>
		<input type="text" class="form-control" placeholder="Pesquisa" name="nome_entidade" id="nome_entidade" form="carrega_dados" value="<?php echo $filtro != null ? $filtro : ""; ?>">
	
		<div class="input-group-btn">
			<button class="btn btn-info btnPesquisa" type="submit" form="carrega_dados" id="gera_pesquisa">
				<i class="fa fa-search"></i>
			</button>
		</div>
	</div>
	
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-12 col-sm-12 bg-white">
                <?php if ($lista_proponentes != null): ?>
                	<form method="post" id="gera_pdf" action="gerar_pdf_rel"  target="_blank">
						<input type="submit" class="btn btn-primary" id="gerarPdf" style="float: left;" value="Gerar PDF"/>
					</form>
					
                    <table class="table">
                    	<tr><th colspan="10" style='color: red; text-align:center; font-size: 16px;'><?php echo "<span>Total de Registros: " . count($lista_proponentes)."</span>";?></th></tr>
                    	
                    	<tr style="color: #428bca; font-size: 16px;">
                    		<th><input type="checkbox" class="marcaTodos" checked="checked"></th>
                    		<th>Nome Entidade</th>
                    		<th>CNPJ</th>
                    		<th>Natureza Jurídica</th>
                    		<th>Esfera Administrativa</th>
                    		<th>Município</th>
                    		<th>UF</th>
                    		<th>Responsável</th>
                    		<th>Situação</th>
                    		<th>Apta</th>
                    	</tr>
                    	
                    	<?php foreach ($lista_proponentes as $p):?>
                    	
                    	<?php $situacao_privada = $proponente_siconv_model->busca_situacao_apta($p->cnpj); ?>
                    	
                    		<tr>
                    			<td><input type="checkbox" value="<?php echo $p->id_proponente_siconv; ?>" name="id_proponente[]" class="id_proponente" checked="checked"></td>
	                    		<td><?php echo $p->nome; ?></td>
	                    		<td><?php echo $p->cnpj ?></td>
	                    		<td><?php echo $p->natureza_juridica; ?></td>
	                    		<td><?php echo $p->esfera_administrativa; ?></td>
	                    		<td><?php echo $p->municipio; ?></td>
	                    		<td><?php echo $p->municipio_uf_nome; ?></td>
	                    		<td><?php echo $p->nome_responsavel; ?></td>
	                    		<td><?php echo $p->situacao; ?></td>
	                    		<td style="width: 180px; text-align: center;"><?php if(!empty($situacao_privada)) { echo str_replace("_", " ", $situacao_privada->situacao)."<br/>".implode("/", array_reverse(explode("-", $situacao_privada->data_inicio)))." - ".implode("/", array_reverse(explode("-", $situacao_privada->data_vencimento))); } ?></td>
	                    	</tr>
                    	<?php endforeach;?>
                    </table>
                <?php else: ?>
                    <h1 style="text-align: center;">Nenhum dado encontrado.</h1>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#gerarPdf").click(function(){
		$(".id_proponente").each(function(){
			$(this).attr('form', 'gera_pdf');
		});
	});

	$(".marcaTodos").click(function(){
		$(".id_proponente").each(function(){
			$(this).attr('checked', $(".marcaTodos").is(":checked"));
		});
	});

	$(".id_proponente").click(function(){
		if(!$(this).is(":checked"))
			$(".marcaTodos").attr("checked", false);
	});
});
</script>