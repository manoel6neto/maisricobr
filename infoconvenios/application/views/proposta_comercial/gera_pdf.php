<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name=Generator content="Microsoft Word 14 (filtered)">
<style>
<!--
/* Font Definitions */
@font-face
	{font-family:"Cambria Math";
	panose-1:0 0 0 0 0 0 0 0 0 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:Tahoma;
	panose-1:0 0 0 0 0 0 0 0 0 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	line-height:115%;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{mso-style-link:"Cabeçalho Char";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{mso-style-link:"Rodapé Char";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
a:link, span.MsoHyperlink
	{color:blue;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{color:purple;
	text-decoration:underline;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-link:"Texto de balão Char";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:7.0pt;
	font-family:"Tahoma","sans-serif";}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:36.0pt;
	line-height:115%;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	line-height:115%;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	line-height:115%;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:36.0pt;
	line-height:115%;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";}
span.CabealhoChar
	{mso-style-name:"Cabeçalho Char";
	mso-style-link:Cabeçalho;}
span.RodapChar
	{mso-style-name:"Rodapé Char";
	mso-style-link:Rodapé;}
span.TextodebaloChar
	{mso-style-name:"Texto de balão Char";
	mso-style-link:"Texto de balão";
	font-family:"Tahoma","sans-serif";}
.MsoChpDefault
	{font-family:"Calibri","sans-serif";}
.MsoPapDefault
	{margin-bottom:10.0pt;
	line-height:115%;}
 /* Page Definitions */
 @page WordSection1
	{size:595.3pt 841.9pt;
	margin:70.85pt 3.0cm 70.85pt 3.0cm;}
div.WordSection1
	{page:WordSection1;}
 /* List Definitions */
 ol
	{margin-bottom:0cm;}
ul
	{margin-bottom:0cm;}
-->
</style>

</head>

<body lang=PT-BR link=blue vlink=purple>

<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; z-index: 0;">
<img style="width: 210mm; height: 297mm; margin: 0;" src="<?php echo base_url('layout/assets/images/marca_dagua.png'); ?>" />
</div>

<div class=WordSection1>


<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;text-justify:inter-ideograph;line-height:150%'><span style='font-size:
10.0pt;line-height:150%'>&nbsp;</span></p>

<?php $data_cadastro = explode("-", $proposta->data_cadastro); ?>

<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;text-justify:inter-ideograph;line-height:150%'><span style='font-size:
10.0pt;line-height:150%'>Itabuna, <?php echo $data_cadastro[2]." de ".$proposta_comercial_model->get_nome_mes($data_cadastro[1])." de ".$data_cadastro[0]; ?>.</span></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm;text-align:justify;text-justify:inter-ideograph;text-indent:
35.4pt;line-height:normal'><span style='font-size:10.0pt'>A empresa PHYSIS
BRASIL, com sede na Rua Almirante Tamandaré, nº 502, Centro, inscrita no
CNPJ/MF número 17.299.932/0001-80, apresenta a presente <b><i>proposta
comercial</i></b>, com validade de até 30 dias, com o objetivo de oferecer
serviços através de licença de uso de software para Gestão do Processo de
planejamento e a elaboração de propostas para o SICONV visando à captação de recursos federais
para <?php echo $proposta->nome_entidade; ?> <?php if($proposta->tipo_proposta == "Governos Municipais"){?>de <?php echo $municipio." - ".$estado; }?>.  </span></p>

<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:0cm;text-align:center;line-height:150%'><b>Serviço</b></p>

<p class=MsoListParagraph style='margin-top:6.0pt;margin-right:0cm;margin-bottom:
6.0pt;margin-left:14.2pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-14.2pt;line-height:normal'><b>1-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></b><b>Concessão de uso do software - Sistema Info Convênios (Sistema Eletrônico
de Captação de Recursos)</b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm;text-align:justify;text-justify:inter-ideograph;text-indent:
14.2pt'>Utilização do software – Sistema Info Convênios voltado ao acompanhamento de
Sistemas Gerenciais Governamentais, especialmente o SICONV, contendo os seguintes
serviços:</p>

<p class=MsoListParagraphCxSpFirst style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Triagem de programas abertos por meio de relatórios diariamente
atualizados;</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Cadastramento das propostas de projetos no Info Convênios;</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Exportação automática dos projetos para o SICONV;</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Relatórios diários de acompanhamentos das propostas (emendas e propostas
voluntárias);</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Acompanhamento dos pareceres das propostas do SICONV;</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>6.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Utilização do banco de projetos da Info Convênios disponível no Info Convênios;</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>7.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Produção própria e armazenamento dos projetos no Info Convênios;</p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>8.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Capacidade de replicar projetos da mesma natureza;</p>

<p class=MsoListParagraphCxSpLast style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:36.0pt;text-align:justify;text-justify:inter-ideograph;
text-indent:-18.0pt'>9.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span>Capacitação técnica da Info Convênios, com carga horária de 8h, juntos
aos técnicos da prefeitura para utilização do Info Convênios;</p>

<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:0cm;
margin-bottom:6.0pt;margin-left:0cm;text-align:center;line-height:normal'><b>&nbsp;</b></p>

<p class=MsoListParagraph style='margin-left:14.2pt;text-align:justify;
text-justify:inter-ideograph;text-indent:-14.2pt'><b>2-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></b><b>PROPOSTA COMERCIAL </b></p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width="100%"
 style='width:100.0%;border-collapse:collapse;border:none'>
 <tr style='height:32.05pt'>
  <td width="100%" colspan=3 style='width:100.0%;border:solid black 1.0pt;
  background:#FDE9D9;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt;text-align:center;'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Licença de uso do software - Sistema Info Convênios</b></p>
  </td>
 </tr>
 <tr style='height:32.05pt'>
  <td width="47%" style='width:47.76%;border:solid black 1.0pt;border-top:none;
  background:#D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Especificações</b></p>
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Período de uso</b></p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Total</b></p>
  </td>
 </tr>
 
 <tr style='height:19.1pt'>
  <td width="47%" style='width:47.76%;border:solid black 1.0pt;border-top:none;
  background:#D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'>Itens de 1 ao 9</p>
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><?php echo $proposta->periodo_proposta_comercial; ?> meses</p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>R$ <?php echo number_format(($proposta->valor_proposta_comercial-$proposta->valor_adicional-$proposta->valor_adicional_autarquias-$proposta->valor_adicional_sem_fim), 2, ",", "."); ?></b></p>
  </td>
 </tr>
 
 <?php if($proposta->num_cnpj >= 1 || $proposta->num_cnpj_sem_fim >= 1 || $proposta->num_cnpj_autarquias >= 1):?>
 <tr style='height:19.1pt'>
  <td width="47%" style='width:47.76%;border:solid black 1.0pt;border-top:none;
  background:#D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><?php echo $proposta->num_cnpj+$proposta->num_cnpj_autarquias+$proposta->num_cnpj_sem_fim;?> CNPJs extras</p>
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><?php echo $proposta->periodo_proposta_comercial; ?> meses</p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>R$ <?php echo number_format(($proposta->valor_adicional+$proposta->valor_adicional_autarquias+$proposta->valor_adicional_sem_fim), 2, ",", "."); ?></b></p>
  </td>
 </tr>
 
 
 <tr style='height:19.1pt'>
  <td width="47%" style='width:47.76%;border-top:none;border-left:none;border-bottom:none;
  padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Total</b></p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>R$ <?php echo number_format($proposta->valor_proposta_comercial, 2, ",", "."); ?></b></p>
  </td>
 </tr>
 <?php endif;?>
 
</table>
<br><br>


<p class=MsoListParagraphCxSpLast style='margin-left:14.2pt;text-align:justify;
text-justify:inter-ideograph;text-indent:-14.2pt'><b>3-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></b><b>Forma de pagamento</b></p>

<?php
$pagamentoUnico = true; 
if($proposta->parcelas_proposta_comercial > 1)
	$pagamentoUnico = false;
?>



<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width="100%"
 style='width:100.0%;border-collapse:collapse;border:none'>
 <!--<tr style='height:32.05pt'>
  <td width="100%" colspan=3 style='width:100.0%;border:solid black 1.0pt;
  background:#FDE9D9;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt;text-align:center;'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Forma de Pagamento</b></p>
  </td>
 </tr>-->
 <tr style='height:32.05pt'>
  <td width="47%" style='width:47.76%;border:solid black 1.0pt;border-top:none;
  background:#D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Especificações</b></p>
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Parcelas</b></p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:32.05pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>Valor</b></p>
  </td>
 </tr>
 
 <!-- <tr style='height:19.1pt'>
  <td width="47%" style='width:47.76%;border:solid black 1.0pt;border-top:none;
  background:#D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'>Entrada</p>
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'>01</p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>R$ <?php echo number_format($proposta->entrada_proposta_comercial, 2, ",", "."); ?></b></p>
  </td>
 </tr> -->
 
 
 <tr style='height:19.1pt'>
  <td width="47%" style='width:47.76%;border:solid black 1.0pt;border-top:none;
  background:#D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'>Parcelamento</p>
  </td>
  <td width="31%" style='width:31.34%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><?php echo str_pad($proposta->parcelas_proposta_comercial, 2, "0", STR_PAD_LEFT); ?></p>
  </td>
  <td width="20%" style='width:20.9%;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;background:
  #D6E3BC;padding:0cm 5.4pt 0cm 5.4pt;height:19.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b>R$ <?php echo number_format((($proposta->valor_proposta_comercial)/($proposta->parcelas_proposta_comercial)), 2, ",", "."); ?></b></p>
  </td>
 </tr>
 
</table>

<!-- 

<table>
<tr>
<td>- <?php //echo !$pagamentoUnico ? "Entrada " : "Pagamento único "; ?></td>
<td><b style='font-size: 9.0pt;'>R$ <?php //echo number_format($proposta->entrada_proposta_comercial, 2, ",", "."); ?></b> no primeiro mês.</td>
</tr>

<?php //if(!$pagamentoUnico):?>
<tr>
<td>- <?php //echo $proposta->parcelas_proposta_comercial-1; ?> parcela(s) de</td> 
<td><b style='font-size: 9.0pt;'>R$ <?php //echo number_format((($proposta->valor_proposta_comercial-$proposta->entrada_proposta_comercial)/($proposta->parcelas_proposta_comercial-1)), 2, ",", "."); ?></b></td>
<?php //endif;?>

</table>

 -->
<br><br>


<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;margin-left:300px;
text-justify:inter-ideograph;text-indent:17.85pt;line-height:normal'><span
style='position:absolute;z-index:251659262;left:0px;margin-left:202px;font-size:10.0pt;
width:214px;'>
<?php echo $this->session->userdata('nome_usuario')."<br>".$this->session->userdata('entidade');?>
</span></p>

<p class=MsoNormal>&nbsp;</p>


</div>

</body>

</html>
