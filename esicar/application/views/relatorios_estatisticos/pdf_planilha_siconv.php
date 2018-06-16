<div class=WordSection1 style="height: 180mm; z-index: 1;">
    <div style="padding-top: 3mm;">
        <?php if (isset($anexo) && $anexo == 1): ?>
            <h3 align="center">ANEXO I – RELATÓRIO DE GESTÃO SICONV - INTEGRADO</h3>
        <?php elseif (isset($anexo) && $anexo == 2): ?>
            <h3 align="center">ANEXO II – RELATÓRIO GOVERNO MUNICIPAL</h3>
        <?php elseif (isset($anexo) && $anexo == 3): ?>
            <h3 align="center">ANEXO III – RELATÓRIO GOVERNO ESTADUAL</h3>
        <?php elseif (isset($anexo) && $anexo == 4): ?>
            <h3 align="center">ANEXO IV – RELATÓRIO O.S.C</h3>
        <?php elseif (isset($anexo) && $anexo == 5): ?>
            <h3 align="center">ANEXO V – RELATÓRIO CONSÓRCIOS.</h3>
        <?php elseif (isset($anexo) && $anexo == 6): ?>
            <h3 align="center">ANEXO V – RELATÓRIOS EMPRESAS MISTAS.</h3>
        <?php endif; ?>
        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
            <tbody>
                <tr style="border: 1px solid ; border-collapse: collapse; text-align: center;" bgcolor="#E0FFFF">
                    <th colspan="6" style="border: 1px solid ; border-collapse: collapse;  padding: 5px; text-align: center; "> DESEMPENHO SICONV - PROGRAMAS E PROPOSTAS </th>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                    <th style="border: 1px solid ;   text-align: center; padding: 5px;" colspan="2"> 
                        <?php if ($nome_empresa != NULL) : ?>
                            EMPRESA
                        <?php elseif ($cidade != NULL) : ?>
                            CIDADE
                        <?php elseif ($estado != NULL) : ?>
                            ESTADO
                        <?php elseif ($regiao != NULL) : ?>
                            REGIÃO
                        <?php else: ?>
                            PAÍS
                        <?php endif; ?>
                    </th>
                    <th style='border: 1px solid; text-align: center; padding: 5px;'> ESFERA </th>
                    <th style='border: 1px solid; text-align: center; padding: 5px;'> POPULAÇÃO </th>
                    <?php if (isset($total_osc_enviaram)): ?>
                        <th colspan="2" style='border: 1px solid; text-align: center'> OSC. COM PROPOSTA </th>
                    <?php else: ?>
                        <?php if (isset($total_municipios_enviaram)): ?>
                            <th style='border: 1px solid; text-align: center'>MUNICÍPIOS</th>
                        <?php endif; ?>
                        <?php if (isset($total_orgaos_enviaram) && !isset($total_municipios_enviaram)): ?>
                            <th colspan="2" style='border: 1px solid; text-align: center; width: 20%;'>ORGÃOS C\ ENVIO</th>
                        <?php else: ?>
                            <th style='border: 1px solid; text-align: center; width: 20%;'>ORGÃOS C\ ENVIO</th>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center;">
                    <td style='border: 1px solid; text-align: center' colspan="2">
                        <?php if ($nome_empresa != NULL) : ?>
                            <i><?= $nome_empresa ?></i>
                        <?php elseif ($cidade != NULL) : ?>
                            <i><?= $cidade ?></i>
                        <?php elseif ($estado != NULL) : ?>
                            <i><?= $estado ?></i>
                        <?php elseif ($regiao != NULL) : ?>
                            <?php if ($regiao == 'NE'): ?>
                                <i>NORDESTE</i>
                            <?php elseif ($regiao == 'N'): ?>
                                <i>NORTE</i>
                            <?php elseif ($regiao == 'CO'): ?>
                                <i>CENTRO OESTE</i>
                            <?php elseif ($regiao == 'SE'): ?>
                                <i>SUDESTE</i>
                            <?php elseif ($regiao == 'S'): ?>
                                <i>SUL</i>
                            <?php else: ?>
                                <i>BRASIL</i>
                            <?php endif; ?>
                        <?php else: ?>
                            <i>BRASIL</i>
                        <?php endif; ?>
                    </td>
                    <?php if (count($esfera) == 4) : ?>
                        <td style='border: 1px solid; text-align: center'> <i>TODAS</i></td>
                    <?php else: ?>
                        <td style='border: 1px solid; text-align: center'> <i>
                                <?php for ($i = 0; $i < count($esfera) - 1; $i++): ?>
                                    <?= $esfera[$i] . ', ' ?>
                                <?php endfor; ?>
                                <?= $esfera[count($esfera) - 1] ?>
                            </i></td>
                    <?php endif; ?>

                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($habitantes, 0, ',', '.'); ?> </i></td>
                    <?php if (isset($total_osc_enviaram)): ?>
                        <td colspan="2" style='border: 1px solid; text-align: center'> <i><?php echo $total_osc_enviaram; ?> </i></td>
                    <?php else: ?>
                        <?php if (isset($total_municipios_enviaram)): ?>
                            <td style='border: 1px solid; text-align: center'> <i><?php echo $total_municipios_enviaram; ?> </i></td>
                        <?php endif; ?>
                        <?php if (isset($total_orgaos_enviaram) && !isset($total_municipios_enviaram)): ?>
                            <td colspan="2" style='border: 1px solid; text-align: center'> <i><?php echo $total_orgaos_enviaram; ?> </i></td>
                        <?php else: ?>
                            <td style='border: 1px solid; text-align: center'> <i><?php echo $total_orgaos_enviaram; ?> </i></td>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> HISTÓRICO</th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> EXERCÍCIO </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> ENVIADAS </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> APROVADAS </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; " colspan="2"> VALOR R$ </th>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><b>2018</b></i> </td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= key_exists('2018', $enviadas) ? $enviadas['2018'] : 0 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= key_exists('2018', $aprovadas) ? $aprovadas['2018'] : 0 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: right;' bgcolor="#DCDCDC" colspan="2"> <i><?= key_exists('2018', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2018'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2017</b></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2017', $enviadas) ? $enviadas['2017'] : 0 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2017', $aprovadas) ? $aprovadas['2017'] : 0 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2017', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2017'], 2, ',', '.') : '0, 00' ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= $enviadas_2018_a_2017 ?></b> </i></td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_2018_a_2017 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: right;' bgcolor="#DCDCDC" colspan="2"> <i><b><?= number_format($valor_aprovadas_2018_a_2017, 2, ',', '.'); ?> </b></i></td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2016</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2016', $enviadas) ? $enviadas['2016'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2016', $aprovadas) ? $aprovadas['2016'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2016', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2016'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2015</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2015', $enviadas) ? $enviadas['2015'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2015', $aprovadas) ? $aprovadas['2015'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2015', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2015'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2014</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2014', $enviadas) ? $enviadas['2014'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2014', $aprovadas) ? $aprovadas['2014'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2014', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2014'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2013</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2013', $enviadas) ? $enviadas['2013'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2013', $aprovadas) ? $aprovadas['2013'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2013', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2013'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= $enviadas_2016_a_2013 ?></b> </i></td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_2016_a_2013 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: right;' bgcolor="#DCDCDC" colspan="2"> <i><b><?= number_format($valor_aprovadas_2016_a_2013, 2, ',', '.'); ?> </b></i></td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2012</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2012', $enviadas) ? $enviadas['2012'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2012', $aprovadas) ? $aprovadas['2012'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2012', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2012'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2011</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2011', $enviadas) ? $enviadas['2011'] : 0 ?> </i></td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2011', $aprovadas) ? $aprovadas['2011'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2011', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2011'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2010</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2010', $enviadas) ? $enviadas['2010'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2010', $aprovadas) ? $aprovadas['2010'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2010', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2010'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i>2009</i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2009', $enviadas) ? $enviadas['2009'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i><?= key_exists('2009', $aprovadas) ? $aprovadas['2009'] : 0 ?></i> </td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><?= key_exists('2009', $valor_anual_aprovadas) ? number_format($valor_anual_aprovadas['2009'], 2, ',', '.') : '0, 00'; ?></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL PERÍODO</b> </i></td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= $enviadas_2012_a_2009 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#DCDCDC"> <i><b><?= $aprovadas_2012_a_2009 ?></b></i> </td>
                    <td style='border: 1px solid; text-align: right;' bgcolor="#DCDCDC" colspan="2"> <i><b><?= number_format($valor_aprovadas_2012_a_2009, 2, ',', '.'); ?></b></i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>TOTAL GERAL</b></i> </td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#E0FFFF"> <i><b><?= $quantidade_geral_enviadas ?></b></i> </td>
                    <td style='border: 1px solid; text-align: center;' bgcolor="#E0FFFF"> <i><b><?= $quantidade_geral_aprovadas ?></b> </i></td>
                    <td style='border: 1px solid; text-align: right;' bgcolor="#E0FFFF" colspan="2"> <i><b><?= number_format($valor_geral_aprovadas, 2, ',', '.'); ?></b></i></td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style="border: 1px solid ; border-collapse: collapse;  text-align: right; " colspan="2"> <i><b>MÉDIA ANUAL EFETIVA</b> </i></td>
                    <td style='border: 1px solid; text-align: center;'> <i><b><?= number_format($quantidade_geral_enviadas / 10, 0, ',', '.') ?></b> </i></td>
                    <td style='border: 1px solid; text-align: center;'> <i><b><?= number_format($quantidade_geral_aprovadas / 10, 0, ',', '.') ?> </b></i></td>
                    <td style='border: 1px solid; text-align: right;' colspan="2"> <i><b><?= number_format($valor_geral_aprovadas / 10, 2, ',', '.'); ?></b></i> </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="padding-top: 1mm;">
        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
            <col>
            <colgroup span="6"></colgroup>
            <tbody>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center;" colspan="10"> MÉDIA DE OPORTUNIDADE ANUAL DE PROGRAMAS E PROPOSTAS POR MUNICÍPIO/ESTADO </th>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; font-size: 10px;" bgcolor="#E0FFFF">
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 5px;" rowspan="2" scope="rowgroup"> ESFERA ADMINISTRATIVA </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; width: 15%; padding: 5px;" rowspan="2" scope="rowgroup"> PROGRAMAS OFERTADOS </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; font-size: 9px;" colspan="6" scope="colgroup"> ENVIOS DE PROPOSTAS (FATOR - POPULAÇÃO MIL HABITANTES) </th>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 2px; width: 9%;" scope="col">ATE 30</th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 2px; width: 10%;" scope="col">30 a 100</th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 2px; width: 10%;" scope="col">100 a 500</th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 2px; width: 9%;" scope="col">> 500</th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 2px; width: 10%;" scope="col">CAPITAIS</th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; padding: 2px; width: 12%;" scope="col">GOVERNO ESTADUAL</th>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: center;'> <i> GOVERNO ESTADUAL </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 50 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 5 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 20 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 40 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 60 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 100 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 275 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: center;'> <i> GOVERNO MUNICIPAL </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 60 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 30 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 90 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 180 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 240 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 360 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 960 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: center;'> <i> ORGANIZAÇÕES SOCIAIS </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 40 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 10 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 30 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 120 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 160 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 240 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 600 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: center;'> <i> EMPRESAS MISTAS </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 20 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 5 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 10 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 15 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 20 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 30 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 100 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: center;'> <i> CONSÓRCIOS PÚBLICOS </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 20 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 5 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 10 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 15 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 20 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 30 </i> </td>
                    <td style='border: 1px solid; text-align: center;'> <i> 100 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; font-weight: bold;" bgcolor="#E0FFFF">
                    <td style='border: 1px solid; text-align: left; font-weight: bold; font-size: 9px;' colspan="2"> <i> TOTAL INTEGRADO (PROPOSTAS VOLUNTÁRIAS) </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 55 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 160 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 370 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 500 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 760 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 2035 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                    <td style='border: 1px solid; text-align: left; font-weight: bold; font-size: 9px;' colspan="2"> <i> TOTAL INTEGRADO (EMENDAS PARL. E ESPECIF.) </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 3 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 8 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 12 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 20 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 25 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 50 </i> </td>
                </tr>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                    <td style='border: 1px solid; text-align: left; font-weight: bold; font-size: 9px;' colspan="2"> <i> TOTAL GERAL INTEGRADO </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 58 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 168 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 382 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 520 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 785 </i> </td>
                    <td style='border: 1px solid; text-align: center; font-weight: bold;'> <i> 2085 </i> </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="padding-top: 1mm;">
        <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
            <tbody>
                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> ÍNDICES DE APROVEITAMENTO </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017/18 </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                    <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/18 </th>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right'> <i>ENVIADAS X OPORTUNIDADES</i> </td>
                    <td style='border: 1px solid; text-align: center'>  <i><?= number_format($percentual_enviadas_2017_2018, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($percentual_enviadas_2013_a_2016, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($percentual_enviadas_2009_a_2012, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($percentual_enviadas_2009_a_2018, 2, ',', '.'); ?> %</i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right'> <i>APROVADAS X OPORTUNIDADES</i> </td>
                    <td style='border: 1px solid; text-align: center'>  <i><?= number_format($percentual_aprovadas_2017_a_2018, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($percentual_aprovadas_2013_a_2016, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($percentual_aprovadas_2009_a_2012, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'> <i><?= number_format($percentual_aprovadas_2009_a_2018, 2, ',', '.'); ?> %</i> </td>
                </tr>

                <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                    <td style='border: 1px solid; text-align: right'> <i>APROVADAS X ENVIADAS</i> </td>
                    <td style='border: 1px solid; text-align: center'>  <i><?= number_format($percentual_enviadas_aprovadas_2017_a_2018, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'>  <i><?= number_format($percentual_enviadas_aprovadas_2013_a_2016, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'>  <i><?= number_format($percentual_enviadas_aprovadas_2009_a_2012, 2, ',', '.'); ?> %</i> </td>
                    <td style='border: 1px solid; text-align: center'>  <i><?= number_format($percentual_enviadas_aprovadas_2009_a_2018, 2, ',', '.'); ?> %</i> </td>
                </tr>
            </tbody>
        </table>
        <div style="padding-top: 1mm;">
            <table style="table-layout: fixed; width:100%; border: 1px solid ; border-collapse: collapse; ">
                <tbody>
                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                        <th colspan="5" style="border: 1px solid ; border-collapse: collapse;  padding: 1px; text-align: center; "> DESEMPENHO SICONV - EMENDAS PARLAMENTARES E ESPECÍFICO DO CONCEDENTE </th>
                    </tr>
                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS CONCEDIDAS.</th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017/18 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/18 </th>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right'> <i>PARLAMENTARES</i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= number_format($quantidade_emenda_parlamentar_2017_a_2018, 0, ',', '.'); ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emenda_parlamentar_2013_a_2016 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emenda_parlamentar_2009_a_2012 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emenda_parlamentar_2009_a_2018 ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right'> <i>ESPECIFICO DO CONCEDENTE</i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= number_format($quantidade_emenda_especifico_2017_a_2018, 0, ',', '.'); ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emenda_especifico_2013_a_2016 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emenda_especifico_2009_a_2012 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emenda_especifico_2009_a_2018 ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right;'> <i>VALOR  R$</i> </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format(($valor_emendas_aprovadas_2017_a_2018 + $valor_emendas_analise_2017_a_2018 + $valor_perda_emenda_2017_a_2018), 2, ',', '.'); ?></i> </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_aprovadas_2013_a_2016 + $valor_emendas_analise_2013_a_2016 + $valor_perda_emenda_2013_a_2016, 2, ',', '.'); ?></i> </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_aprovadas_2009_a_2012 + $valor_emendas_analise_2009_a_2012 + $valor_perda_emenda_2009_a_2012, 2, ',', '.'); ?></i> </td>
                        <td style='border: 1px solid; text-align: right;'> <i><b><?= number_format($valor_emendas_aprovadas_2009_a_2018 + $valor_emendas_analise_2009_a_2018 + $valor_perda_emenda_2009_a_2018, 2, ',', '.'); ?></b></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS APROVADAS </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017/18 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/18 </th>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_aprovadas_2017_a_2018 ?></i>  </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_aprovadas_2013_a_2016 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_aprovadas_2009_a_2012 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_aprovadas_2009_a_2018 ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right;'> <i>VALOR  R$</i></td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_aprovadas_2017_a_2018, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_aprovadas_2013_a_2016, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_aprovadas_2009_a_2012, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_aprovadas_2009_a_2018, 2, ',', '.'); ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS EM ANÁLISE </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017/18 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/18 </th>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_analise_2017_a_2018 ?></i>  </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_analise_2013_a_2016 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_analise_2009_a_2012 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_emendas_analise_2009_a_2018 ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right;' > <i>VALOR  R$</i></td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_analise_2017_a_2018, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_analise_2013_a_2016, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_analise_2009_a_2012, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_emendas_analise_2009_a_2018, 2, ',', '.'); ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; " bgcolor="#E0FFFF">
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: left; "> EMENDAS PERDIDAS </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2017/18 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2013/16 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/12 </th>
                        <th style="border: 1px solid ; border-collapse: collapse;  text-align: center; "> 2009/18 </th>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right'> <i>QUANTIDADE</i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_perda_emenda_2017_a_2018 ?></i>  </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_perda_emenda_2013_a_2016 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_perda_emenda_2009_a_2012 ?></i> </td>
                        <td style='border: 1px solid; text-align: center'> <i><?= $quantidade_perda_emenda_2009_a_2018 ?></i> </td>
                    </tr>

                    <tr style="border: 1px solid ; border-collapse: collapse;  text-align: center; ">
                        <td style='border: 1px solid; text-align: right;' > <i>VALOR  R$</i></td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_perda_emenda_2017_a_2018, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_perda_emenda_2013_a_2016, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_perda_emenda_2009_a_2012, 2, ',', '.'); ?></i>  </td>
                        <td style='border: 1px solid; text-align: right;'> <i><?= number_format($valor_perda_emenda_2009_a_2018, 2, ',', '.'); ?></i> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
