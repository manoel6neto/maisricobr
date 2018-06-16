<!-- Tabs -->
<div class="relativeWrap" >
    <div class="box-generic">

        <div class="tabsbar">
            <ul>
                <li class="glyphicons active"><a href="#nacional" data-toggle="tab">Nacional <strong></strong></a></li>
                <li class="glyphicons"><a href="#regional" data-toggle="tab">Regional <strong></strong></a></li>
                <li class="glyphicons"><a href="#estadual" data-toggle="tab">Estadual <strong></strong></a></li>
                <li class="glyphicons"><a href="#municipal" data-toggle="tab">Municipal <strong></strong></a></li>
            </ul>
        </div>


        <div class="tab-content">

            <!-- Nacional -->
            <div class="tab-pane active" id="nacional">
                <?php if ($nacional != NULL): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        PAÍS
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i>BRASIL</i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $nacional->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $nacional->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($nacional->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $nacional->enviadas_2013 + $nacional->enviadas_2014 + $nacional->enviadas_2015 + $nacional->enviadas_2016 + $nacional->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $nacional->aprovadas_2013 + $nacional->aprovadas_2014 + $nacional->aprovadas_2015 + $nacional->aprovadas_2016 + $nacional->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $nacional->valor_2013 + $nacional->valor_2014 + $nacional->valor_2015 + $nacional->valor_2016 + $nacional->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $nacional->enviadas_2009 + $nacional->enviadas_2010 + $nacional->enviadas_2011 + $nacional->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $nacional->aprovadas_2009 + $nacional->aprovadas_2010 + $nacional->aprovadas_2011 + $nacional->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $nacional->valor_2009 + $nacional->valor_2010 + $nacional->valor_2011 + $nacional->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->emendas_parl_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_parl_concedidas_2009_2012 + $nacional->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->emendas_espc_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_concedidas_2009_2012 + $nacional->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($nacional->valor_emenda_concedido_2009_2012 + $nacional->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_aprovadas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_aprovadas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_aprovadas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_aprovadas_2009_2012 + $nacional->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_aprovado_2009_2012 + $nacional->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_analise_2009_2012 + $nacional->emendas_espc_analise_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_analise_2009_2012 + $nacional->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_perdidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_perdidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nacional->emendas_espc_perdidas_2009_2012 + $nacional->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nacional->valor_emenda_perdido_2009_2012 + $nacional->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>


            <!-- Regional -->
            <div class="tab-pane" id="regional">

                <!-- Centro-Oeste -->

                <?php if (isset($centro_oeste)): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        REGIÃO
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i>Centro-Oeste</i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $centro_oeste->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $centro_oeste->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($centro_oeste->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $centro_oeste->enviadas_2013 + $centro_oeste->enviadas_2014 + $centro_oeste->enviadas_2015 + $centro_oeste->enviadas_2016 + $centro_oeste->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $centro_oeste->aprovadas_2013 + $centro_oeste->aprovadas_2014 + $centro_oeste->aprovadas_2015 + $centro_oeste->aprovadas_2016 + $centro_oeste->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $centro_oeste->valor_2013 + $centro_oeste->valor_2014 + $centro_oeste->valor_2015 + $centro_oeste->valor_2016 + $centro_oeste->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $centro_oeste->enviadas_2009 + $centro_oeste->enviadas_2010 + $centro_oeste->enviadas_2011 + $centro_oeste->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $centro_oeste->aprovadas_2009 + $centro_oeste->aprovadas_2010 + $centro_oeste->aprovadas_2011 + $centro_oeste->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $centro_oeste->valor_2009 + $centro_oeste->valor_2010 + $centro_oeste->valor_2011 + $centro_oeste->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->emendas_parl_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_parl_concedidas_2009_2012 + $centro_oeste->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->emendas_espc_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_concedidas_2009_2012 + $centro_oeste->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($centro_oeste->valor_emenda_concedido_2009_2012 + $centro_oeste->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_aprovadas_2017 ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_aprovadas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_aprovadas_2009_2012 + $centro_oeste->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_aprovado_2009_2012 + $centro_oeste->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_analise_2009_2012 + $centro_oeste->emendas_espc_analise_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_analise_2009_2012 + $centro_oeste->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_perdidas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $centro_oeste->emendas_espc_perdidas_2009_2012 + $centro_oeste->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($centro_oeste->valor_emenda_perdido_2009_2012 + $centro_oeste->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Norte -->

                <?php if (isset($norte)): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        REGIÃO
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i>Norte</i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $norte->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $norte->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($norte->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $norte->enviadas_2013 + $norte->enviadas_2014 + $norte->enviadas_2015 + $norte->enviadas_2016 + $norte->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $norte->aprovadas_2013 + $norte->aprovadas_2014 + $norte->aprovadas_2015 + $norte->aprovadas_2016 + $norte->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $norte->valor_2013 + $norte->valor_2014 + $norte->valor_2015 + $norte->valor_2016 + $norte->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $norte->enviadas_2009 + $norte->enviadas_2010 + $norte->enviadas_2011 + $norte->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $norte->aprovadas_2009 + $norte->aprovadas_2010 + $norte->aprovadas_2011 + $norte->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $norte->valor_2009 + $norte->valor_2010 + $norte->valor_2011 + $norte->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->emendas_parl_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_parl_concedidas_2009_2012 + $norte->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->emendas_espc_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_concedidas_2009_2012 + $norte->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($norte->valor_emenda_concedido_2009_2012 + $norte->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_aprovadas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_aprovadas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_aprovadas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_aprovadas_2009_2012 + $norte->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_aprovado_2009_2012 + $norte->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_analise_2009_2012 + $norte->emendas_espc_analise_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_analise_2009_2012 + $norte->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_perdidas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $norte->emendas_espc_perdidas_2009_2012 + $norte->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($norte->valor_emenda_perdido_2009_2012 + $norte->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Nordeste -->

                <?php if (isset($nordeste)): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        REGIÃO
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i>Nordeste</i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $nordeste->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $nordeste->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($nordeste->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $nordeste->enviadas_2013 + $nordeste->enviadas_2014 + $nordeste->enviadas_2015 + $nordeste->enviadas_2016 + $nordeste->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $nordeste->aprovadas_2013 + $nordeste->aprovadas_2014 + $nordeste->aprovadas_2015 + $nordeste->aprovadas_2016 + $nordeste->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $nordeste->valor_2013 + $nordeste->valor_2014 + $nordeste->valor_2015 + $nordeste->valor_2016 + $nordeste->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $nordeste->enviadas_2009 + $nordeste->enviadas_2010 + $nordeste->enviadas_2011 + $nordeste->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $nordeste->aprovadas_2009 + $nordeste->aprovadas_2010 + $nordeste->aprovadas_2011 + $nordeste->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $nordeste->valor_2009 + $nordeste->valor_2010 + $nordeste->valor_2011 + $nordeste->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->emendas_parl_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_parl_concedidas_2009_2012 + $nordeste->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->emendas_espc_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_concedidas_2009_2012 + $nordeste->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($nordeste->valor_emenda_concedido_2009_2012 + $nordeste->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_aprovadas_2017 ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_aprovadas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_aprovadas_2009_2012 + $nordeste->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_aprovado_2009_2012 + $nordeste->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_analise_2009_2012 + $nordeste->emendas_espc_analise_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_analise_2009_2012 + $nordeste->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_perdidas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $nordeste->emendas_espc_perdidas_2009_2012 + $nordeste->emendas_espc_perdidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($nordeste->valor_emenda_perdido_2009_2012 + $nordeste->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>


                <!-- Sul -->

                <?php if (isset($sul)): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        REGIÃO
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i>Sul</i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $sul->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $sul->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($sul->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $sul->enviadas_2013 + $sul->enviadas_2014 + $sul->enviadas_2015 + $sul->enviadas_2016 + $sul->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $sul->aprovadas_2013 + $sul->aprovadas_2014 + $sul->aprovadas_2015 + $sul->aprovadas_2016 + $sul->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $sul->valor_2013 + $sul->valor_2014 + $sul->valor_2015 + $sul->valor_2016 + $sul->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>


                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $sul->enviadas_2009 + $sul->enviadas_2010 + $sul->enviadas_2011 + $sul->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $sul->aprovadas_2009 + $sul->aprovadas_2010 + $sul->aprovadas_2011 + $sul->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $sul->valor_2009 + $sul->valor_2010 + $sul->valor_2011 + $sul->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->emendas_parl_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_parl_concedidas_2009_2012 + $sul->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->emendas_espc_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_concedidas_2009_2012 + $sul->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($sul->valor_emenda_concedido_2009_2012 + $sul->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_aprovadas_2017 ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_aprovadas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_aprovadas_2009_2012 + $sul->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_aprovado_2009_2012 + $sul->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_analise_2009_2012 + $sul->emendas_espc_analise_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_analise_2009_2012 + $sul->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_perdidas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sul->emendas_espc_perdidas_2009_2012 + $sul->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sul->valor_emenda_perdido_2009_2012 + $sul->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Sudeste -->

                <?php if (isset($sudeste)): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        REGIÃO
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i>Sudeste</i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $sudeste->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $sudeste->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($sudeste->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $sudeste->enviadas_2013 + $sudeste->enviadas_2014 + $sudeste->enviadas_2015 + $sudeste->enviadas_2016 + $sudeste->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $sudeste->aprovadas_2013 + $sudeste->aprovadas_2014 + $sudeste->aprovadas_2015 + $sudeste->aprovadas_2016 + $sudeste->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $sudeste->valor_2013 + $sudeste->valor_2014 + $sudeste->valor_2015 + $sudeste->valor_2016 + $sudeste->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>


                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $sudeste->enviadas_2009 + $sudeste->enviadas_2010 + $sudeste->enviadas_2011 + $sudeste->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $sudeste->aprovadas_2009 + $sudeste->aprovadas_2010 + $sudeste->aprovadas_2011 + $sudeste->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $sudeste->valor_2009 + $sudeste->valor_2010 + $sudeste->valor_2011 + $sudeste->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->emendas_parl_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_parl_concedidas_2009_2012 + $sudeste->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->emendas_espc_concedidas_2017, 0, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_concedidas_2009_2012 + $sudeste->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($sudeste->valor_emenda_concedido_2009_2012 + $sudeste->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_aprovadas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_aprovadas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_aprovadas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_aprovadas_2009_2012 + $sudeste->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_aprovado_2009_2012 + $sudeste->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_analise_2009_2012 + $sudeste->emendas_espc_analise_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_analise_2009_2012 + $sudeste->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_perdidas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $sudeste->emendas_espc_perdidas_2009_2012 + $sudeste->emendas_espc_perdidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($sudeste->valor_emenda_perdido_2009_2012 + $sudeste->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Estadual -->
            <div class="tab-pane" id="estadual">
                <?php if ($estadual != NULL): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        Estado
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i><?= $estado ?></i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $estadual->enviadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $estadual->aprovadas_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($estadual->valor_2017, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2015 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2015, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2014 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2014, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2013 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2013, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2013_2017 = $estadual->enviadas_2013 + $estadual->enviadas_2014 + $estadual->enviadas_2015 + $estadual->enviadas_2016 + $estadual->enviadas_2017 ?>
                                    <?php $aprovadas_nacional_municipal_2013_2017 = $estadual->aprovadas_2013 + $estadual->aprovadas_2014 + $estadual->aprovadas_2015 + $estadual->aprovadas_2016 + $estadual->aprovadas_2017 ?>
                                    <?php $valor_nacional_municipal_2013_2017 = $estadual->valor_2013 + $estadual->valor_2014 + $estadual->valor_2015 + $estadual->valor_2016 + $estadual->valor_2017 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2013_2017, 2, ',', '.'); ?> </b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2012, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2011 ?> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2011 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2011, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2010 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2010, 2, ',', '.'); ?> </i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->enviadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->aprovadas_2009 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_2009, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <?php $enviadas_nacional_municipal_2009_2012 = $estadual->enviadas_2009 + $estadual->enviadas_2010 + $estadual->enviadas_2011 + $estadual->enviadas_2012 ?>
                                    <?php $aprovadas_nacional_municipal_2009_2012 = $estadual->aprovadas_2009 + $estadual->aprovadas_2010 + $estadual->aprovadas_2011 + $estadual->aprovadas_2012 ?>
                                    <?php $valor_nacional_municipal_2009_2012 = $estadual->valor_2009 + $estadual->valor_2010 + $estadual->valor_2011 + $estadual->valor_2012 ?>
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $enviadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($valor_nacional_municipal_2009_2012, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017 ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017 ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017, 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($enviadas_nacional_municipal_2009_2012 + $enviadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($aprovadas_nacional_municipal_2009_2012 + $aprovadas_nacional_municipal_2013_2017) / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format(($valor_nacional_municipal_2009_2012 + $valor_nacional_municipal_2013_2017) / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_parl_concedidas_2017 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_parl_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_parl_concedidas_2009_2012 + $estadual->emendas_parl_concedidas_2009_2012 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_concedidas_2017 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_concedidas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_concedidas_2009_2012 + $estadual->emendas_espc_concedidas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_concedido_2017, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_concedido_2009_2012, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($estadual->valor_emenda_concedido_2009_2012 + $estadual->valor_emenda_concedido_2013_2016, 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_aprovadas_2017 ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_aprovadas_2009_2012 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_aprovadas_2009_2012 + $estadual->emendas_espc_aprovadas_2013_2016 ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_aprovado_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_aprovado_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_aprovado_2009_2012 + $estadual->valor_emenda_aprovado_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_analise_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_analise_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_analise_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_analise_2009_2012 + $estadual->emendas_espc_analise_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_analise_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_analise_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_analise_2009_2012 + $estadual->valor_emenda_analise_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_perdidas_2017  ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_perdidas_2009_2012  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $estadual->emendas_espc_perdidas_2009_2012 + $estadual->emendas_espc_perdidas_2013_2016  ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_perdido_2017, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_perdido_2009_2012, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($estadual->valor_emenda_perdido_2009_2012 + $estadual->valor_emenda_perdido_2013_2016, 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Municipal -->
            <div class="tab-pane" id="municipal">
                <?php if ($municipal != NULL): ?>
                    <div style="padding-top: 10mm;">

                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <th style="border: 1px solid ;   text-align: center;" colspan="2"> 
                                        MUNICÍPIO
                                    </th>
                                    <th style='border: 1px solid; text-align: center'> ESFERA </th>
                                    <th style='border: 1px solid; text-align: center'> POPULAÇÃO </th>
                                    <th style='border: 1px solid; text-align: center'> FATOR </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                                    <td style='border: 1px solid; text-align: center' colspan="2">
                                        <i><?= $cidade ?></i>
                                    </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $esfera ?></i></td>

                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i>-</i></td>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> VALOR R$ </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i><b>2017</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2017'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2017'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2017'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2016</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2016'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2016'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2016'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2015</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2015'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2015'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2015'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2014</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2014'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2014'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2014'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2013</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2013'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2013'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2013'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas_2016_a_2013'] + $municipal['enviadas']['2017'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas_2016_a_2013'] + $municipal['aprovadas']['2017'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_aprovadas_2016_a_2013'] + $municipal['valor_anual_aprovadas']['2017'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2012</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2012'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2012'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2012'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2011</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2011'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2011'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2011'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2010</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2010'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2010'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2010'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' colspan="2"> <i>2009</i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas']['2009'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas']['2009'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_anual_aprovadas']['2009'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['enviadas_2012_a_2009'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= $municipal['aprovadas_2012_a_2009'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#DCDCDC"> <i><b><?= number_format($municipal['valor_aprovadas_2012_a_2009'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: left; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $municipal['quantidade_geral_enviadas'] ?></b></i> </td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= $municipal['quantidade_geral_aprovadas'] ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center' bgcolor="#E0FFFF"> <i><b><?= number_format($municipal['valor_geral_aprovadas'], 2, ',', '.'); ?></b></i></td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($municipal['quantidade_geral_enviadas'] / 9, 0, ',', '.') ?></b> </i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($municipal['quantidade_geral_aprovadas'] / 9, 0, ',', '.') ?> </b></i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($municipal['valor_geral_aprovadas'] / 9, 2, ',', '.'); ?></b></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-top: 2mm;">
                        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                            <tbody>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                                </tr>
                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_parlamentar_2017'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_parlamentar_2009_a_2012'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_parlamentar_2013_a_2016'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_parlamentar_2009_a_2016'] ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_especifico_2017'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_especifico_2009_a_2012'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_especifico_2013_a_2016'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emenda_especifico_2009_a_2016'] ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emenda_2017'], 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emenda_2009_a_2012'], 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emenda_2013_a_2016'], 2, ',', '.'); ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><b><?= number_format($municipal['valor_emenda_2009_a_2016'], 2, ',', '.'); ?></b></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_aprovadas_2017'] ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_aprovadas_2009_a_2012'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_aprovadas_2013_a_2016'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_aprovadas_2009_a_2016'] ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_aprovadas_2017'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_aprovadas_2009_a_2012'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_aprovadas_2013_a_2016'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_aprovadas_2009_a_2016'], 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_analise_2017'] ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_analise_2009_a_2012'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_analise_2013_a_2016'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_emendas_analise_2009_a_2016'] ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_analise_2017'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_analise_2009_a_2012'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_analise_2013_a_2016'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_emendas_analise_2009_a_2016'], 2, ',', '.'); ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/16 </th>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_perda_emenda_2017'] ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_perda_emenda_2009_a_2012'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_perda_emenda_2013_a_2016'] ?></i> </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= $municipal['quantidade_perda_emenda_2009_a_2016'] ?></i> </td>
                                </tr>

                                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                                    <td style='border: 1px solid; text-align: right' > <i>VALOR  R$</i></td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_perda_emenda_2017'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_perda_emenda_2009_a_2012'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_perda_emenda_2013_a_2016'], 2, ',', '.'); ?></i>  </td>
                                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($municipal['valor_perda_emenda_2009_a_2016'], 2, ',', '.'); ?></i> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>