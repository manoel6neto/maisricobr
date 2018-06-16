<div id="content" class="bg-white">
    <h3 class="bg-white content-heading border-bottom">Relatório Estatístico Perdas Emendas Cidades - Ano: <?php echo $ano; ?> - <?php echo count($dados_tabela); ?> Cidades</h3>

    <div class="bg-white innerAll col-md-11">
        <form action="<?php echo base_url('index.php/relatorio_ganho_perca_controller/lista_percas_emendas_maiores_cidades'); ?>" method="post">
            <div class="form-group">
                <label for="ano">Ano</label>
                <select name="ano" id="ano_input" class="form-control" style="width: 150px;">
                    <?php foreach ($anos as $ano): ?>
                        <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <?php echo form_label('Min. População', 'min'); ?>
                <?php echo form_input(array('name' => 'min', 'class' => 'form-control', 'style' => 'width: 150px'), set_value('min', isset($min) ? $min : '')); ?>
            </div>      

            <div class="form-group">
                <?php echo form_label('Max. População', 'max'); ?>
                <?php echo form_input(array('name' => 'max', 'class' => 'form-control', 'style' => 'width: 150px'), set_value('max', isset($max) ? $max : '')); ?>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Filtrar" id="filtraano" name="filtraano">
            </div>
        </form>

        <div class="bg-white innerAll col-md-11">
            <div style="padding-top: 1%;">
                <table style="width: 100%; margin-left: 100px; border-collapse: collapse;">
                    <thead style="width: 100%">
                        <tr style="color: #428bca; font-size: 16px; margin: 20px;">
                            <th style="margin-left: 20px;">Cidade</th>
                            <th style="margin-left: 20px;">Emendas Destinadas</th>
                            <th style="margin-left: 20px;">Valor Destinado</th>
                            <th style="margin-left: 20px;">Emendas Perdidas.</th>
                            <th style="margin-left: 20px;">Valor Perdido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($dados_tabela) && count($dados_tabela) > 0): ?>
                            <?php foreach ($dados_tabela as $linha): ?>
                                <tr style="color: #31708f; font-size: 14px; margin: 20px;">
                                    <td style="margin-left: 20px;"><?php echo $linha['cidade']; ?></td>
                                    <td style="margin-left: 20px;"><?php echo $linha['quantidade_destinadas']; ?></td>
                                    <td style="margin-left: 20px;"><?php echo 'R$ ' . number_format($linha['soma_destinadas'], 2, ',', '.'); ?></td>
                                    <td style="margin-left: 20px;"><?php echo $linha['quantidade_emendas']; ?></td>
                                    <td style="margin-left: 20px;"><?php echo 'R$ ' . number_format($linha['soma'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr style="color: #31708f; font-size: 14px; margin: 20px;">
                                <td style="margin-left: 20px; padding: 10px;">Total: </td>
                                <td style="margin-left: 20px;"><?php echo $quantidade_total_destinadas; ?></td>
                                <td style="margin-left: 20px;"><?php echo 'R$ ' . number_format($soma_total_destinadas, 2, ',', '.'); ?></td>
                                <td style="margin-left: 20px;"><?php echo $quantidade_total_utilizadas; ?></td>
                                <td style="margin-left: 20px;"><?php echo 'R$ ' . number_format($soma_total_utilizadas, 2, ',', '.'); ?></td>
                            </tr>
                        <?php else: ?>
                        <h3>Nenhum dado encontrado</h3>
                    <?php endif; ?>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>
</div>
