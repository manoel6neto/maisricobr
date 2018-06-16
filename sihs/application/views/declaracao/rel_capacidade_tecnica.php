<html>
<meta charset="UTF-8">

<div align="center">
<img src="<?php echo base_url("upload_assinatura/{$dados_rel->brasao_prefeitura}"); ?>" width="102,80" height="105,44">
</div>

<br/><br/>

<div align="center">
<p style="font-size: 18px; text-align: center;">ESTADO <?php echo $dados_rel->estado; ?></p>
<p style="font-size: 18px; text-align: center;">PREFEITURA MUNICIPAL DE <?php echo $proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio; ?></p>
</div>

<div align="center">
<p style="font-size: 18px; font-weight: bold; text-align: center;">Declaração de Capacidade Técnica e Gerencial</p>
</div>

<div>
<p style="text-align: justify;">
A Prefeitura Municipal de <?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?>, por seu representante legal, <?php echo $dados_rel->nome_prefeito?>, em cumprimento ao disposto no inciso V do artigo 15 da Portaria Interministerial 507/2011 e, 
com base nas instruções do programa nº <?php echo $dados_rel->codigo_programa; ?> – <?php echo $dados_rel->nome_programa?>. <b>DECLARA</b> que o Município de <?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?> possui equipe técnica qualificada para elaboração e acompanhamento do projeto pleiteado. 

<?php if($dados_rel->nome_engenheiro != "" && $dados_rel->crea_engenheiro != ""):?>
Sob a responsabilidade do Engenheiro Civil <?php echo $dados_rel->nome_engenheiro; ?> – CREA nº <?php echo $dados_rel->crea_engenheiro; ?>.
<?php endif;?> 

Informa, ainda, já ter executado ou estar prestando a contento serviços com qualidade, compatíveis aos de que trata o presente convênio.
</p>
</div>

<br/><br/>

<p style="text-align: left;">
<?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?>, <?php echo date('d'); ?> de <?php echo $dados_rel_capacidade_model->get_nome_mes(date('m')); ?> de <?php echo date('Y'); ?>. 		           
</p>

<br/><br/><br/><br/>

<?php if($dados_rel->tipo_assinatura == "SEM_ASSINATURA"){?>

<div align="center">
<p style="text-align: center;"><?php echo $dados_rel->nome_prefeito?><br/>
<span style="font-size: 12px;">Prefeitura Municipal de <?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?></span></p>
</div>

<?php }else if($dados_rel->tipo_assinatura == "SOMENTE_ASSINATURA"){?>

<div align="center">
<p style="text-align: center;">
<img src="<?php echo base_url("upload_assinatura/{$dados_rel->arquivo_assinatura}"); ?>" height="100" width="300"/>
</p>
<p style="text-align: center;"><?php echo $dados_rel->nome_prefeito?><br/>
<span style="font-size: 12px;">Prefeitura Municipal de <?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?></span></p>
</div>

<?php }else if($dados_rel->tipo_assinatura == "ASSINATURA_NOME"){?>

<p style="text-align: center;">
<img src="<?php echo base_url("upload_assinatura/{$dados_rel->arquivo_assinatura}"); ?>" height="100" width="300"/>
</p>
<p style="text-align: center; font-size: 12px;">Prefeitura Municipal de <?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?></p>

<?php }else if($dados_rel->tipo_assinatura == "ASSINATURA_COMPLETA"){?>

<div align="center">
<p style="text-align: center;">
<img src="<?php echo base_url("upload_assinatura/{$dados_rel->arquivo_assinatura}"); ?>" height="100" width="300"/>
</p>
</div>

<?php }?>

</html>