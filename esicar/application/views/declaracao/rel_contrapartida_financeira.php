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
<p style="font-size: 18px; font-weight: bold; text-align: center;">Declaração de Previsão Orçamentária de Contrapartida</p>
</div>


<div>
<p style="text-align: justify;">
Declaro, sob as penas da Lei, em conformidade com a Lei de Diretrizes Orçamentárias e com a Lei Complementar nº 101, de 04 de maio de 2000, que dispomos dos recursos orçamentários, 
no valor de R$ <?php echo number_format($dados_rel->valor_contrapartida, 2, ",", "."); ?> (<?php echo $dados_rel->vlr_extenso_contrapartida; ?>), para participação, a título de contrapartida, 
no repasse dos recursos destinados ao Convênio SICONV para o Programa nº <?php echo $dados_rel->codigo_programa; ?> – <?php echo $dados_rel->nome_programa; ?>, 
e estes recursos encontram-se alocados na Lei nº <?php echo $dados_rel->num_lei; ?>, de <?php echo implode("/", array_reverse(explode("-", $dados_rel->data_pub_lei))); ?> – LOA <?php echo $dados_rel->ano_loa; ?>, na seguinte classificação orçamentária:
</p>
</div>

<div>
<p>Órgão: <?php echo $dados_rel->orgao; ?></p>
<p>Unidade: <?php echo $dados_rel->unidade; ?></p>
<p>Projeto/Atividade: <?php echo $dados_rel->proj_atividade; ?></p> 
<p>Natureza da Despesa: <?php echo $dados_rel->nat_despesa; ?></p>
</div>


<div>
<p style="text-align: justify;">
Declaro ainda que, na hipótese de eventual necessidade de aporte adicional de recursos, esta Instituição se compromete com sua integralização, durante a vigência do Convênio que vier a ser celebrado.
</p>
</div>



<br/>

<p style="text-align: left;">
<?php echo ucwords(strtolower($proponente_siconv_model->get_municipio_nome($dados_rel->municipio)->municipio)); ?>, <?php echo date('d'); ?> de <?php echo $dados_rel_capacidade_model->get_nome_mes(date('m')); ?> de <?php echo date('Y'); ?>. 		           
</p>

<br/>


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