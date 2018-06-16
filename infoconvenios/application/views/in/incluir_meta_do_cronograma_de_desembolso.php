<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?php echo base_url(); ?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">
<div class="innerALl">
<div class="col-md-12 col-sm-12">

		<table class="table">
		<thead><tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr></thead>
				<tbody>
				
				<tr class="ano" id="tr-incluirParcelaAno">
              <th >
                Responsável
                </th>
              <th>
                 Parcela
              </th>
              <td style="color: red;">
              	Valor Cadastrado
              </td>
              <td style="color: green;">
              	Valor a Cadastrar
              </td>
            </tr>
            <tr id="tr-incluirParcelaAno">
              <td>
               <?php if (isset($cronograma->responsavel) !== false) echo $cronograma->responsavel; ?>
                </td>
              <td>
                 <?php if (isset($cronograma->parcela) !== false) echo number_format($cronograma->parcela,2,",","."); ?>
              </td>
              <td style="color: red;">
              	<?php 
              	$total = 0;
              	foreach ($metas as $meta)
              		$total += $trabalho_model->obter_meta_cronograma_valor($cronograma_id, $meta->idMeta);
              	
              	echo number_format($total,2,",",".");
              	?>
              </td>
              <td style="color: green;">
              	<?php
              	$valor = 0;
              	if(isset($cronograma->parcela))
              		$valor = $cronograma->parcela;
              	
              	$v = round($valor,2)-round($total,2);
              	echo number_format($v,2,",","."); ?>
              </td>
            </tr>
          </tr>
				</tbody>
			</table>
<h1 class="bg-white content-heading border-bottom" style="color: #428bca;">Associar Meta</h1>
<table class="table">
<thead>
<tr>
<th class="numero">Especificação da meta</th>
<th class="especificacao">Valor Total da meta</th>
<th class="especificacao">Valor vinculado à meta</th>
<th class="especificacao">Valor disponível</th>
<th class="especificacao">Valor a ser associado</th>
<th></th></tr></thead>
<tbody>
<?php
	foreach ($metas as $meta){
?>
<form name="" method="post" enctype="multipart/form-data">
<input type="hidden" name="idMeta" value="<?php echo $meta->idMeta ?>">
<input type="hidden" name="cronograma_id" value="<?php echo $cronograma_id ?>">

<tr class="odd">
<td style="width: 40%;">
            <div class="numero"><?php echo $meta->especificacao ?></div>
        </td>
		<td>
            <div class="especificacao">R$ <?php echo number_format($meta->total,2,",","."); ?></div>
        </td>
        <td>
            <div class="especificacao">R$ <?php echo number_format($trabalho_model->obter_restante_meta($meta->idMeta, true),2,",","."); ?></div>
        </td>
        <td>
        	<div class="especificacao">R$ <?php echo number_format(round($meta->total,2)-round($trabalho_model->obter_restante_meta($meta->idMeta, true),2),2,",","."); ?></div>
        </td>
        <td>
            <div class="especificacao">
            <?php 
				if ($leitura_pessoa == true){
			?>
			</div>
        </td>
<td>
              
            <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/incluir_etapa_do_cronograma_de_desembolso?meta=<?php echo $meta->idMeta ?>&cronograma=<?php echo $cronograma_id ?>&id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>">Etapas Associadas</a>
            <?php 
				} else {
			?>
            <input class='form-control' type="text" value="<?php
            $valor = $trabalho_model->obter_meta_cronograma_valor($cronograma_id, $meta->idMeta);
             if (isset($valor) !== false) echo number_format($valor,2,",",".");?>"name="valor" maxlength="14" onkeypress="reais(this,event)" onkeydown="backspace(this,event)">
			</div>
        </td>
<td>
            <?php $disabled = $valor > 0 ? "" : "disabled"; ?>
            <input class="btn btn-sm btn-primary" type="submit" name="Associar" value="Associar" id="Associar">
            <input class="btn btn-sm btn-primary" type="submit" name="Desassociar" value="Desassociar" id="Desassociar" <?php echo $disabled; ?>>
            
            <?php 
            $class = "primary";
            $label = "<i class='fa fa-plus'></i> Associar Etapas";
            
            $etapas = $trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
            $valor = 0;
            $total = 0;
            $meta_cronograma = $trabalho_model->obter_meta_cronograma($cronograma_id, $meta->idMeta);
            if(isset($meta_cronograma->idCronograma_meta)){        
	            foreach ($etapas as $etapa)
	            	$total += $trabalho_model->obter_etapa_cronograma_valor($meta_cronograma->idCronograma_meta, $etapa->idEtapa);
	            
	            if(isset($meta_cronograma->valor))
	            	$valor = $meta_cronograma->valor;
	            
	            if((round($valor,2)-round($total,2)) == 0){
	            	$class = "success";
	            	$label = "<i class='fa fa-eye'></i> Etapas Associadas";
	            }else{
	            	$class = "primary";
	            	$label = "<i class='fa fa-plus'></i> Associar Etapas";
	            }
            }
            ?>
            
            <a class="btn btn-sm btn-<?php echo $class; ?>"  <?php echo $disabled; ?> href="<?php echo base_url(); ?>index.php/in/usuario/incluir_etapa_do_cronograma_de_desembolso?meta=<?php echo $meta->idMeta ?>&cronograma=<?php echo $cronograma_id ?>&id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>"><?php echo $label; ?></a>
            <?php 
				}
			?>
               </form>
        </td></tr>
        <?php
}
?>
        </tbody></table>
        <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/listar_cronograma?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>">Voltar</a>
		
	</div>
</div>
