<div id="content" class="innerAll bg-white">
    <h1 class="bg-white content-heading border-bottom">Lista de Cadastros</h1>
    <?php if (isset($lista_contatos)): ?>
        <?php if ($lista_contatos != null): ?>
            <table class="table">
                <tr style="color: #428bca; font-size: 16px;">
                    <th>Município</th>
                    <th>Estado</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php foreach ($lista_contatos as $contato): ?>
                    <tr>
                        <td><?php echo $proponente_siconv_model->get_municipio_nome($contato->id_municipio)->municipio; ?></td>
                        <td><?php echo $contato->sigla_uf; ?></td>
                        <td><?php echo $contato->nome_contato; ?></td>
                        <td><?php echo $contato_municipio_model->formataCelular($contato->telefone_contato); ?></td>
                        <td><?php echo $contato->email_contato; ?></td>
                        <td>
                            &nbsp;&nbsp;&nbsp;
                            <?php if ($contato->status == 'ABERTO'): ?>
                                <i title="Visitas em andamento" class="btn-sm btn-info fa fa-warning"></i>
                            <?php elseif ($contato->status == 'PENDENTE'): ?>
                                <i title="As informações do contato precisam ser atualizadas" class="btn-sm btn-primary fa fa-times-circle"></i>
                            <?php else: ?>
                                <i title="Série de visitas concluída" class="btn-sm btn-success fa fa-check-square"></i>
                            <?php endif; ?>
                        </td>
                        <td><a href="<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=' . $contato->id_contato_municipio); ?>">Atualizar Informações</a></td>
                        <td><a href="<?php echo base_url('index.php/controle_usuarios/avalia_contato?id=' . $contato->id_contato_municipio . '&meta=1'); ?>">Avaliar Visita</a></td>
                    </tr>
                <?php endforeach; ?>

            </table>
        <?php else: ?>

        <?php endif; ?>
    <?php else: ?>
    <h2 style="background-color: #cb4040; color: #fff">Você não tem permissão para realizar esta operação!</h1>
    <?php endif; ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#nova_visita").click(function () {
            $.ajax({
                url: "<?php echo base_url('index.php/controle_usuarios/check_contato_avaliacao_pendente'); ?>",
                success: function (data) {
                    if (data == "SEM_CADASTRO") {
                        if (confirm("Deseja realizar um cadastro de visita do município?")) {
                            $.ajax({
                                url: "<?php echo base_url('index.php/controle_usuarios/cria_cadastro_visita') ?>",
                                success: function (data) {
                                    location.href = "<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=') ?>" + data;
                                }
                            });
                        }

                        return false;
                    } else if (data.indexOf("SEM_CONTATO") != -1) {
                        if (confirm("Já existe um cadastro de visita ativo, porém incompleto.\r\nDeseja completá-lo agora?")) {
                            id = data.split("#");
                            location.href = "<?php echo base_url('index.php/controle_usuarios/completa_cadastro_contato?id=') ?>" + id[1];
                        }

                        return false;
                    } else
                        alert("Já existe um cadastro de visita ativo.");
                }
            });

            return false;
        });
    });
</script>