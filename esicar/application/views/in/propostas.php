<div id="content">
    <h1 class="bg-white content-heading border-bottom">PROPOSTAS
				<?php if ($sistema == true) echo "ENVIADAS PELO USUÁRIO ATUAL";?>
				</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
			<form name="carrega_dados" method="post" id="carrega_dados" action="proposta_tabela">
			<input type="hidden" name="sistema" value="<?= $sistema;?>">
			<div class="widget-body">
			<div class="innerB">
				<div class="row">
				  <div class="col-xs-3">
					<label for="cod_estados">Ano</label>
					<input class="ie form-control" type="text" size="4" name="ano" title="Entre com o ano" id="ano" maxlength="4" placeholder="Entre com o intervalo">
				  </div>
				  <div class="col-xs-4">
					<label for="cod_estados">Estado</label>
				<select name="cod_estados" id="cod_estados" class="form-control">
					<option value=""></option>
					<?php
						foreach ($cidades as $estado) {
							echo '<option value="'.$estado['cod_estados'].'">'.$estado['sigla'].'</option>';
						}
					?>
				</select>
				  </div>
				  <div class="col-xs-5">
					<label for="cod_cidades">Cidade</label>
					<select name="cod_cidades" id="cod_cidades" class="form-control">
						<option value="">-- Escolha um estado --</option>
					</select>
				  </div>
				</div>
			</div>		
			
			<h1>Situação:</h1>
			<label class="checkbox"><input type="checkbox" id="checkAll" name="todos" value="todos"/><b>TODOS</b><br>
			<div class="widget-body uniformjs">
			<div class="row">
			<div class="col-md-7">
			

			<label class="checkbox"><input type="checkbox" id="checkItem"  name="RASCUNHO" value="Proposta/Plano de Trabalho em rascunho"/>Proposta/Plano de Trabalho em rascunho</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_RECEBIDA_COM_PENDENCIA" value="Proposta/Plano de Trabalho Recebidos com pendência"/>Proposta/Plano de Trabalho Recebidos com pendência</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_CADASTRADA" value="Proposta/Plano de Trabalho Cadastrados"/>Proposta/Plano de Trabalho Cadastrados</label></br>
		
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_ENVIADA_ANALISE" value="Proposta/Plano de Trabalho enviado para Análise"/>Proposta/Plano de Trabalho enviado para Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_EM_ANALISE" value="Proposta/Plano de Trabalho em Análise"/>Proposta/Plano de Trabalho em Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_EM_COMPLEMENTACAO" value="Proposta/Plano de Trabalho em Complementação"/>Proposta/Plano de Trabalho em Complementação</label></br>
		
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_COMPLEMENTADA_ENVIADA_ANALISE" value="Proposta/Plano de Trabalho complementado enviada para Análise"/>Proposta/Plano de Trabalho complementado enviada para Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_COMPLEMENTADA_EM_ANALISE" value="Proposta/Plano de Trabalho complementado em Análise"/>Proposta/Plano de Trabalho complementado em Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PLANO_TRABALHO_ENVIADO_ANALISE" value="Proposta Aprovada e Plano de Trabalho enviado para Análise"/>Proposta Aprovada e Plano de Trabalho enviado para Análise</label></br>
		
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PLANO_TRABALHO_EM_ANALISE" value="Proposta Aprovada e Plano de Trabalho em Análise"/>Proposta Aprovada e Plano de Trabalho em Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PLANO_TRABALHO_EM_COMPLEMENTACAO" value="Proposta Aprovada e Plano de Trabalho em Complementação"/>Proposta Aprovada e Plano de Trabalho em Complementação</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PLANO_TRABALHO_COMPLEMENTADO_ENVIADO_ANALISE" value="Proposta Aprovada e Plano de Trabalho Complementado enviado para Análise"/>Proposta Aprovada e Plano de Trabalho Complementado enviado para Análise</label></br>

			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PLANO_TRABALHO_COMPLEMENTADO_EM_ANALISE" value="Proposta Aprovada e Plano de Trabalho Complementado em Análise"/>Proposta Aprovada e Plano de Trabalho Complementado em Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PLANO_TRABALHO_APROVADO" value="Proposta/Plano de Trabalho Aprovados"/>Proposta/Plano de Trabalho Aprovados</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_CANCELADA" value="Proposta/Plano de Trabalho Cancelados"/>Proposta/Plano de Trabalho Cancelados</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PROPOSTA_REPROVADA" value="Proposta/Plano de Trabalho Rejeitados"/>Proposta/Plano de Trabalho Rejeitados</label></br>
			</div>
			
			<div class="col-md-5">
			
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="ASSINADA" value="Assinado"/>Assinado</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="EM_EXECUCAO" value="Em execução"/>Em execução</label></br>
					
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="AGUARDANDO_PRESTACAO_CONTAS" value="Aguardando Prestação de Contas"/>Aguardando Prestação de Contas</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_ENVIADA_ANALISE" value="Prestação de Contas enviada para Análise"/>Prestação de Contas enviada para Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_EM_COMPLEMENTACAO" value="Prestação de Contas em Complementação"/>Prestação de Contas em Complementação</label></br>

			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_APROVADA" value="Prestação de Contas Aprovada"/>Prestação de Contas Aprovada</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_REJEITADA" value="Prestação de Contas Rejeitada"/>Prestação de Contas Rejeitada</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_APROVADA_COM_RESSALVAS" value="Prestação de Contas Aprovada com Ressalvas"/>Prestação de Contas Aprovada com Ressalvas</label></br>

			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_ATRASADA" value="Prestação de Contas Atrasada"/>Prestação de Contas Atrasada</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_EM_ANALISE" value="Prestação de Contas em Análise"/>Prestação de Contas em Análise</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="CONVENIO_ANULADO" value="Convênio Anulado"/>Convênio Anulado</label></br>

			<label class="checkbox"><input type="checkbox" id="checkItem"  name="CONVENIO_RESCINDIDO" value="Convênio Rescindido"/>Convênio Rescindido</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="CONVENIO_EXTINTO" value="Convênio Extinto"/>Convênio Extinto</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="INADIMPLENTE" value="INADIMPLENTE"/>INADIMPLENTE</label></br>

			<label class="checkbox"><input type="checkbox" id="checkItem"  name="ASSINATURA_PENDENTE_REGISTRO_TV_SIAFI" value="Assinatura Pendente Registro TV Siafi"/>Assinatura Pendente Registro TV Siafi</label></br>
			<label class="checkbox"><input type="checkbox" id="checkItem"  name="PRESTACAO_CONTAS_ANTECIPACAO" value="Prestação de Contas Iniciada por Antecipação"/>Prestação de Contas Iniciada por Antecipação</label></br>
			
			</div>
		</div>
		</div>
		
			<div class="widget-body center">
			<input class="btn btn-primary" type="submit" name="operation" onclick="" value="Gerar Relatório" />
			</div>
		
		</form>
		</div>
	</div>
		
	</div>
</div>
</div>
</div>
<script type="text/javascript">//<![CDATA[ 
$(function(){
 $("#checkAll").click(function () {
	 $('input:checkbox').not(this).prop('checked', this.checked);
 });
});//]]>  

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

