<script src="<?php echo base_url('configuracoes/js/jcycle.js'); ?>"></script>

<style>
#divBanner{
	margin: auto;
	background-image: url('<?php echo base_url("layout/assets/images/fundo_banner.png"); ?>'); 
	background-repeat: no-repeat;
	background-size: 700px 244px;
	height: 270px;
	width: 700px;
}

.dadosTableTexto{
	text-align: center;
	margin: auto;
	margin-left: 11%;
	font-size: 11px;
	height: 250px;
	color: #fff;
}

.dadosTableTexto > div{
	margin-right: 6.5%;
	width: 140px;
	float: left;
	text-align: justify;
}
</style>

<div class="spacing">
		<br/><br/>
	
		<div class="col-md-8 col-sm-10 col-sm-offset-1">
			<img alt="" src="<?php echo base_url('layout/assets/images/sobre_esicar.png'); ?>" width="700">
		</div>
		
		<br><br><br><br><br><br><br><br><br><br><br>
		
		<img alt="" src="<?php echo base_url('layout/assets/images/o_sistema.png'); ?>" width="140" style="margin-left: -560px;">
		
		<br><br>
		
		<div id="divBanner">
			<?php foreach ($banners as $banner): ?>
			<a href=""><img alt="" src="<?php echo base_url("layout/assets/images/{$banner}"); ?>" width="666" height="220" style="margin-top: 12px; margin-left: 16px;"></a>
			<?php endforeach; ?>
		</div>
		
		<img alt="" src="<?php echo base_url('layout/assets/images/funcionalidades.png'); ?>" width="700">
		
		<br/><br/><br/><br/>
		
		<img alt="" src="<?php echo base_url('layout/assets/images/funcoes.png'); ?>" width="700">
		
		<br/><br/>
		
		<div class="dadosTableTexto">
			<div>Trata-se de um processo de busca automática dos programas disponibilizados, dentro do prazo de vigência, sejam eles de Proposta Voluntária ou específicas de Emendas Parlamentares ou dos Ministérios. Estes programas ficam, então, disponíveis em forma de relatórios PDF, ordenados de modo a serem encaminhados para os setores específicos.</div>
			
			<div>Além disso, a plataforma WEB - Info Convênios possui um Banco de Projetos com propostas semiestruturadas, sendo necessária a inclusão de algumas informações especificas da entidade do proponente (banco, endereço etc.). Estes projetos são aplicáveis a diversos Ministérios, diminuindo o tempo de trabalho na construção de uma nova proposta.</div>
			
			<div>Após a seleção dos programas na plataforma WEB - Info Convênios, o sistema permite a criação de um novo projeto “fora” do SICONV (gerando projetos em “espelho”), e o cadastramento posterior deste projeto é feito automaticamente pelo Info Convênios rapidamente para o SICONV, gerando o número da proposta. </div>
			
			<div>Com a plataforma WEB - Info Convênios, também é feito um Controle de pareceres das Propostas, atualizados em tempo real diretamente na área do cliente, o que permite a agilidade no acompanhamento das diligências exigidas pelos técnicos do SICONV, tendo assim, controle total das propostas enviadas para análise, aumentando a possibilidade de aprovação das mesmas.</div>
		</div>
	
		<br/><br/><br/>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#divBanner').cycle({
		fx     : 'scrollLeft,scrollDown,scrollRight,scrollUp',
        speed  : 5000,
        timeout: 5000,
	});
});
</script>