<style type="text/css">
    .error{
        color: red;
    }
</style>

<div id="content" class="innerAll bg-white col-md-8">

    <h1 class="bg-white content-heading border-bottom">Avaliação de visita<span style="font-size: 14px; color: #428bca;"> - <?php echo $proponente_siconv_model->get_municipio_nome($dados_usuario->id_municipio)->municipio; ?></span></h1>

    <h2 class="bg-white border-bottom">Dados do contato</h2>
    <?php if (isset($dados_usuario)): ?>
        <table class="table">
            <tr style="color: #428bca; font-size: 16px;">
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
            </tr>
            <tr>
                <td><?php echo $dados_usuario->nome_contato; ?></td>
                <td><?php echo $dados_usuario->email_contato; ?></td>
                <td><?php echo $contato_municipio_model->formataCelular($dados_usuario->telefone_contato); ?></td>
            </tr>
        </table>

        <!-- Tabs que determinam a meta -->
        <h3 class="bg-white border-bottom">Metas</h3>
        <ul class="nav nav-tabs">
            <li role="link" <?php echo $etapas[0]->id_meta == 1 ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/controle_usuarios/avalia_contato?id=' . $dados_usuario->id_contato_municipio . '&meta=1'); ?>">Apresentação</a></li>
            <li role="link" <?php echo $etapas[0]->id_meta == 2 ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/controle_usuarios/avalia_contato?id=' . $dados_usuario->id_contato_municipio . '&meta=2'); ?>">Documentação</a></li>
            <li role="link" <?php echo $etapas[0]->id_meta == 3 ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/controle_usuarios/avalia_contato?id=' . $dados_usuario->id_contato_municipio . '&meta=3'); ?>">Contratação</a></li>
            <li role="link" <?php echo $etapas[0]->id_meta == 4 ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/controle_usuarios/avalia_contato?id=' . $dados_usuario->id_contato_municipio . '&meta=4'); ?>">Pós venda</a></li>
        </ul>
        <form action="" method="post">
            <?php echo validation_errors(); ?>

            <?php foreach ($etapas as $etapa) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $etapa->id_etapa . 'ª ETAPA - ' . $etapa->nome ?></h3>
                        <div class="form-group">
                            <br>
                            <h4>Selecione os participantes</h4>
                            <?php foreach ($participantes as $participante) { ?>
                                <?php $participante_contato = $avaliacao_visita_model->get_participante_contato($dados_usuario->id_contato_municipio, $etapa->id_etapa, $participante->id_participante); ?>
                                <?php echo form_checkbox('part_' . $etapa->id_etapa . '[]', $participante->id_participante, $avaliacao_visita_model->is_participante_in_etapa($participante->id_participante, $etapa->id_etapa, $dados_usuario->id_contato_municipio) ? true : false, $etapa->habilitada ? '' : 'disabled'); ?>
                                <?php
                                echo ("<a title='Clique para expandir os campos de contato' data-toggle = 'collapse' href = '#contato_participante_" . $participante->id_participante . '_' . $etapa->id_etapa."'>".form_label($participante->nome, 'part_' . $etapa->id_etapa . '[]',array('style'=>'cursor: pointer; margin-right:5px')).'<i class="fa fa-arrow-circle-down"></i></a>');
                                ?>
                                <br/>
                                <div class="collapse" id=<?php echo ("contato_participante_" . $participante->id_participante . '_' . $etapa->id_etapa) ?> >
                                    <?php echo form_label('Nome ' . $participante->nome . ' *', 'nome_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, array('class' => (form_error('nome_participante') != "" ? "error" : ""))); ?>
                                    <?php echo form_input('nome_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, set_value('nome_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, isset($participante_contato->nome) ? $participante_contato->nome : ""), 'class="form-control"'.' '.($etapa->habilitada ? '' : 'disabled')); ?>
                                    <br/>
                                    <?php echo form_label('E-mail ' . $participante->nome . ' *', 'email_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, array('class' => (form_error('email_participante') != "" ? "error" : ""))); ?>
                                    <?php echo form_input('email_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, set_value('email_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, isset($participante_contato->email) ? $participante_contato->email : ""), 'class="form-control"'.' '.($etapa->habilitada ? '' : 'disabled')); ?>
                                    <br/>
                                    <?php echo form_label('Telefone ' . $participante->nome . ' *', 'telefone_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, array('class' => (form_error('telefone_participante') != "" ? "error" : ""))); ?>
                                    <?php echo form_input('telefone_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, set_value('telefone_participante_' . $participante->id_participante . '_' . $etapa->id_etapa, isset($participante_contato->telefone) ? $participante_contato->telefone : ""), 'class="form-control"'.' '.($etapa->habilitada ? '' : 'disabled')); ?>
                                </div>
                            <?php } ?>
                            <br>
                            <input type="submit" class="btn btn-primary" <?php echo $etapa->habilitada ? 'value="Atualizar"' : 'disabled value="Indisponível"' ?>>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table style="width:100%;">
                            <colgroup>
                                <col span="1" style="width: 5%;">
                                <col span="1" style="width: 35%;">
                                <col span="1" style="width: 15%;">
                                <col span="1" style="width: 20%;">
                                <col span="1" style="width: 25%;">
                            </colgroup>
                            <thead>
                            <th>&nbsp;</th>
                            <th>Atividade</th>
                            <th>Data</th>
                            <th>Status</th>
                            </thead>

                            <?php foreach ($etapa->atividades as $atividade) { ?>
                                <tr>
                                    <td><?php echo form_checkbox('atividades[]', $atividade->id_atividade, false, $etapa->habilitada ? '' : 'disabled'); ?></td>
                                    <td><?php echo $atividade->nome ?></td>
                                    <td><?php echo ($atividade->data != null) ? date('d/m/Y', strtotime($atividade->data)) : '-----------------' ?></td>
                                    <td>
                                        <?php echo form_radio("status_visita_" . $atividade->id_atividade, 'P', (isset($atividade->status) && $atividade->status == 1 ? true : false), $etapa->habilitada ? '' : 'disabled'); ?>
                                        <?php echo form_label('Realizado'); ?>
                                        <br/>
                                        <?php echo form_radio("status_visita_" . $atividade->id_atividade, 'N', (isset($atividade->status) && $atividade->status == 0 ? true : false), $etapa->habilitada ? '' : 'disabled'); ?>
                                        <?php echo form_label('Não realizado'); ?>

                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </form>
    <?php else: ?>
        <?php echo "Contato não encontrado"; ?>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function ()
    {
        $("tr:odd td").css("background-color", "#F5F5F5");
    });
</script>