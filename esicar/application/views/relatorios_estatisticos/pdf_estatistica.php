<div style="text-align: center;">
    <?php if ($nomeEstado != null && $nomeMunicipio == null): ?>
        <h3>Relatório Nacional de Propostas e Programas - <?php echo ($nomeEstado . " - " . $ano); ?></h3>
    <?php elseif ($nomeMunicipio != null): ?>
        <h3>Relatório Nacional de Propostas e Programas - <?php echo ($nomeMunicipio . " - " . $ano); ?></h3>
    <?php else: ?>
        <h3>Relatório Nacional de Propostas e Programas - <?php echo ($ano); ?></h3>
    <?php endif; ?>
</div>

<table>
    <thead>
        <tr style="background-color:#FFA07A; font-size: 16px;">
            <?php if ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="espec" colspan="2">ESPECIFICAÇÕES</th>
            <?php endif; ?>
            <th style='border: 1px solid; text-align: center' id="prog" colspan="3">PROGRAMAS</th>
            <th style='border: 1px solid; text-align: center' id="qual" colspan="6">QUALIFICAÇÃO DO PROGRAMA</th>
            <th style='border: 1px solid; text-align: center' id="status" colspan="5">QUALIFICAÇÃO DAS PROPOSTAS</th>
            <?php if ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="demand" colspan="3">DEMANDAS ESPECIAIS</th>
            <?php endif; ?>
        </tr>
        <tr style="background-color:#D6E0F5; font-size: 16px;">
            <?php if ($ehPorRegiao): ?>
                <th style='border: 1px solid; text-align: center' id="nome" headers="espec">Estados</th>
            <?php elseif ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="nomeVazio" headers="espec"> - </th>
            <?php endif; ?>
            <?php if ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="muni" headers="espec">Munic.</th>
            <?php endif; ?>

            <th style='border: 1px solid; text-align: center' id="open" headers="prog">Abertos</th>
            <th style='border: 1px solid; text-align: center' id="utiliz" headers="prog">Utilizados</th>
            <th style='border: 1px solid; text-align: center' id="percUtiliz" headers="prog">% Utiliz.</th>
            <th style='border: 1px solid; text-align: center' id="volunt" headers="qual">Voluntárias</th>
            <th style='border: 1px solid; text-align: center' id="percVolunt" headers="qual">% Vonlunt.</th>
            <th style='border: 1px solid; text-align: center' id="emenda" headers="qual">Emenda</th>
            <th style='border: 1px solid; text-align: center' id="percEmenda" headers="qual">% Emenda</th>
            <th style='border: 1px solid; text-align: center' id="especf" headers="qual">Prop. Específico</th>
            <th style='border: 1px solid; text-align: center' id="percEspecf" headers="qual">% Específico</th>
            <th style='border: 1px solid; text-align: center' id="send" headers="status">Enviadas</th>
            <?php if ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="sendForCity" headers="status">Méd. Env/Cid</th>
            <?php endif; ?>
            <th style='border: 1px solid; text-align: center' id="aproved" headers="status">Aprovadas</th>
            <?php if ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="aprovedForCity" headers="status">Méd. Aprov/Cid</th>
            <?php endif; ?>
            <th style='border: 1px solid; text-align: center' id="percAproved" headers="status">% Aprovação</th>
            <?php if ($mostraQtdMunicipio): ?>
                <th style='border: 1px solid; text-align: center' id="notSend" headers="demand">Não Env.</th>
                <th style='border: 1px solid; text-align: center' id="notAproved" headers="demand">Não Aprov.</th>
                <th style='border: 1px solid; text-align: center' id="oneAproved" headers="demand">Apenas 1 Aprov.</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!$ehPorRegiao): ?>
            <tr>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'> - </td>
                <?php endif; ?>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $dados["quantidade_municipios"]; ?></td>
                <?php endif; ?>

                <td style='border: 1px solid; text-align: center'><?php echo $dados["programas_abertos"]; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["programas_utilizados"]; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_utilizacao_programas"] . "%"; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["qualificacao_voluntaria"]; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_voluntaria"] . "%"; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["qualificacao_emenda"]; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_emenda"] . "%"; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["qualificacao_especifico"]; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_especifico"] . "%"; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["propostas_enviadas"]; ?></td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_envio_por_municipio"] . "%"; ?></td>
                <?php endif; ?>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["propostas_aprovadas"]; ?></td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_aprovada_por_municipio"] . "%"; ?></td>
                <?php endif; ?>
                <td style='border: 1px solid; text-align: center'><?php echo $dados["percentual_aprovacao_propostas"] . "%"; ?></td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $dados["cidades_sem_propostas_enviadas"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dados["cidades_sem_propostas_aprovadas"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dados["cidades_apenas_uma_proposta_aprovada"]; ?></td>
                <?php endif; ?>
            </tr>
        <?php else: ?>
            <?php foreach ($dados as $cidade => $dado): ?>
                <tr>
                    <td style='border: 1px solid; text-align: center'><?php echo $cidade; ?></td>

                    <?php if ($mostraQtdMunicipio): ?>
                        <td style='border: 1px solid; text-align: center'><?php echo $dado["quantidade_municipios"]; ?></td>
                    <?php endif; ?>

                    <td style='border: 1px solid; text-align: center'><?php echo $dado["programas_abertos"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["programas_utilizados"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_utilizacao_programas"] . "%"; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["qualificacao_voluntaria"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_voluntaria"] . "%"; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["qualificacao_emenda"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_emenda"] . "%"; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["qualificacao_especifico"]; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_especifico"] . "%"; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["propostas_enviadas"]; ?></td>
                    <?php if ($mostraQtdMunicipio): ?>
                        <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_envio_por_municipio"] . "%"; ?></td>
                    <?php endif; ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["propostas_aprovadas"]; ?></td>
                    <?php if ($mostraQtdMunicipio): ?>
                        <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_aprovada_por_municipio"] . "%"; ?></td>
                    <?php endif; ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $dado["percentual_aprovacao_propostas"] . "%"; ?></td>
                    <?php if ($mostraQtdMunicipio): ?>
                        <td style='border: 1px solid; text-align: center'><?php echo $dado["cidades_sem_propostas_enviadas"]; ?></td>
                        <td style='border: 1px solid; text-align: center'><?php echo $dado["cidades_sem_propostas_aprovadas"]; ?></td>
                        <td style='border: 1px solid; text-align: center'><?php echo $dado["cidades_apenas_uma_proposta_aprovada"]; ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <tr style="background-color:#D6E0F5; font-size: 16px; font-style: oblique">
                <td style='border: 1px solid; text-align: center'>TOTAL</td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $totalMunicipios; ?></td>
                <?php endif; ?>
                <td style='border: 1px solid; text-align: center'><?php echo $totalProgramasAbertos; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalProgramasUtilizados; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalVoluntarias; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalEmenda; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalEspecifico; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalEnviadas; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalAprovadas; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo $totalSemEnvio; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $totalSemAprovada; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo $totalUmaAprovada; ?></td>
                <?php endif; ?>
            </tr>
            <tr style="background-color:#D6E0F5; font-size: 16px; font-style: oblique">
                <td style='border: 1px solid; text-align: center'>TOTAL ÚNICO</td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <?php endif; ?>
                <td style='border: 1px solid; text-align: center'><?php echo $totalProgramasAbertosUnicos; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo $totalProgramasUtilizadosUnicos; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <?php if ($mostraQtdMunicipio): ?>
                    <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                    <td style='border: 1px solid; text-align: center'><?php echo ""; ?></td>
                <?php endif; ?>
            </tr>
            <tr style="font-size: 11px;">
                <td colspan="19" style='border: 1px solid; text-align: left'><?php echo "*Total Único refere-se ao agrupamento dos programas destinados a mais de um estado."; ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
