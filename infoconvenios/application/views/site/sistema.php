<script src="<?php echo base_url('configuracoes/js/jcycle.js'); ?>"></script>

<style>
#divBanner{
	margin: auto;
	background-image: url('<?php echo base_url("layout/assets/images/fundo_banner.png"); ?>'); 
	background-repeat: no-repeat;
	background-size: 700px 296px;
	height: 296px;
	width: 700px;
}

.dadosTable{
	text-align: center;
	margin: auto;
	margin-left: 5%;
}

.dadosTable > img{
	margin-right: 5%;
}

.dadosTableTexto{
	text-align: center;
	margin: auto;
	margin-left: 14%;
	font-size: 11px;
	height: 250px;
}

.dadosTableTexto > div{
	margin-right: 4%;
	width: 140px;
	float: left;
	text-align: justify;
}
</style>

<div id="conteudoSistema">			
		<img src="<?php echo base_url("layout/assets/images/texto_sistema.png"); ?>" width="540" height="100">
				
		<br><br><br>
		
		<div id="divBanner">
			<?php foreach ($banners as $banner): ?>
			<a href=""><img alt="" src="<?php echo base_url("layout/assets/images/{$banner}"); ?>" width="680" height="234" style="margin-top: 13px; margin-left: 10px;"></a>
			<?php endforeach; ?>
		</div>
		
		<br/><br/>
			
		<img src="<?php echo base_url("layout/assets/images/logo_e_sicar_desc.png"); ?>" width="580" height="100">
		
		<br/><br/><br/><br/><br/><br/>
		
		<div class="dadosTable">
			<img src="<?php echo base_url("layout/assets/images/relatorios.png"); ?>" width="140" height="60">
			<img src="<?php echo base_url("layout/assets/images/banco_projetos.png"); ?>" width="100" height="60">
			<img src="<?php echo base_url("layout/assets/images/cadastro.png"); ?>" width="146" height="60">
			<img src="<?php echo base_url("layout/assets/images/parecer.png"); ?>" width="120" height="60">
		</div>
		
		<div class="dadosTableTexto">
			<div>Trata-se de um processo de busca automática dos programas disponibilizados, dentro do praza de vigência, sejam eles de Proposta Voluntária ou específicas de Emendas Parlamentares ou dos Ministérios. Estes programas ficam, então, disponíveis em forma de relatórios PDF, ordenados de modo a serem encaminhado para os setores específicos.</div>
			
			<div>Além disso, a plataforma WEB - Info Convênios possui um Banco de Projetos com propostas semiestruturadas, sendo necessária a inclusão de algumas informações especificas da entidade do proponente (banco, endereço etc...). Estes projetos são aplicáveis a diversos Ministérios, diminuindo assim, o tempo de trabalho na construção de uma nova proposta.</div>
			
			<div>Após a seleção dos programas na plataforma WEB - Info Convênios, o sistema permite a criação de um novo projeto “fora” do SICONV (gerando projetos em “espelho”), e o cadastramento posterior deste projeto é feito automaticamente pelo Info Convênios rapidamente para o SICONV, gerando o número da proposta. </div>
			
			<div>Com a plataforma WEB - Info Convênios, também é feito um Controle de pareceres das Propostas, atualizados em tempo real diretamente na área do cliente, o que permite a agilidade no acompanhamento das diligências exigidas pelos técnicos do SICONV, tento assim, controle total das propostas enviadas para analise, aumenta a possibilidade de aprovação das mesmas.</div>
		</div>
	
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#divBanner').cycle({
		delay: -2000
	});
});
</script>