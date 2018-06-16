<div id="content" class="bg-white">
    <h1 class="bg-white content-heading border-bottom">Tabela de exemplo</h1>
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
                <table class="table">
                    <thead>
                        <tr style="color: #428bca; font-size: 16px;">
                            <th>Nome</th>
                            <th>quantidade_municipios</th>
                            <th>quantidade_propostas_cadastradas</th>
                            <th>quantidade_propostas_aprovadas</th>
                            <th>quantidade_propostas_voluntarias</th>
                            <th>quantidade_propostas_emenda</th>
                            <th>quantidade_propostas_especifico</th>
                            <th>valor_total_cadastrado</th>
                            <th>valor_total_aprovadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Minas Gerais</td>
                            <td><?php echo $quantidade_municipios; ?></td>
                            <td><?php echo $quantidade_propostas_cadastradas; ?></td>
                            <td><?php echo $quantidade_propostas_aprovadas; ?></td>
                            <td><?php echo $quantidade_propostas_voluntarias; ?></td>
                            <td><?php echo $quantidade_propostas_emenda; ?></td>
                            <td><?php echo $quantidade_propostas_especifico; ?></td>
                            <td><?php echo $valor_total_cadastrado; ?></td>
                            <td><?php echo $valor_total_aprovadas; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>