<div id="content" class="bg-white">
    <h1 class="bg-white content-heading border-bottom">Status Nacional</h1>
    
    <div class="bg-white innerAll col-md-3">
	    <form action="<?php echo base_url('index.php/controle_relatorios/gera_estatistica'); ?>" target="_blank" method="post">
	    	<div class="form-group">
			    <label for="regiao">Regiões</label>
			    <select name="regiao" id="regiao" class="form-control">
			    	<option value="TODOS">Todos</option>
			    	<option value="N">Norte</option>
			    	<option value="NE">Nordeste</option>
			    	<option value="CO">Centro Oeste</option>
			    	<option value="SE">Sudeste</option>
			    	<option value="S">Sul</option>
			    </select>
		    </div>
		    
		    <div class="form-group">
			    <label for="estado">Estados</label>
			    <select name="estado" id="estado" class="form-control">
			    	<option value="">Todos</option>
			    </select>
		    </div>
		    
		    <div class="form-group">
			    <label for="municipio">Municípios</label>
			    <select name="municipio" id="municipio" class="form-control">
			    	<option value="">Todos</option>
			    </select>
		    </div>
		    
		    <div class="form-group">
			    <label for="ano">Ano</label>
			    <select name="ano" id="ano" class="form-control">
			    	<?php foreach ($anos as $ano):?>
			    	<option value="<?php echo $ano->ano; ?>"><?php echo $ano->ano; ?></option>
			    	<?php endforeach;?>
			    </select>
		    </div>
		    
		    <div class="form-group">
				<input type="submit" class="btn btn-primary" value="Gerar Relatório" id="cadastrar">
			</div>
	    </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#regiao").change(function(){
		$.ajax({
			url:'<?php echo base_url('index.php/controle_relatorios/busca_estados')?>',
			dataType:'html',
			type:'post',
			data:{
				regiao:$(this).val()
			},
			beforeSend:function(){
				$("#estado").html("<option value=''>Todos</option>");
				$("#municipio").html("<option value=''>Todos</option>");
			},
			success:function(data){
				$("#estado").html(data);
			}
		});
	});

	$("#estado").change(function(){
		$.ajax({
			url:'<?php echo base_url('index.php/controle_relatorios/busca_municipios')?>',
			dataType:'html',
			type:'post',
			data:{
				estado:$(this).val()
			},
			beforeSend:function(){
				$("#municipio").html("<option value=''>Todos</option>");
			},
			success:function(data){
				$("#municipio").html(data);
			}
		});
	});
});
</script>