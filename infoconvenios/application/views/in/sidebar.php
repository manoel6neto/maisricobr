<div class="sidebar sidebar-default">
    <div class="sidebarMenuWrapper" style="top: 0px;">
        <ul class="list-unstyled">
            <?php
            $this->load->model('usuario_model');
            $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));

            if ($usuario_logado->tipoPessoa == 2) {
                ?>
                <li><a href="<?= base_url(); ?>index.php/in/gestor"><span>Dashboard</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/escolher_proponente"><span>Novo Projeto</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/atribui_permissoes"><span>Distribuir Trabalhos</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/gerencia_proposta"><span>Gerenciar Trabalhos</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/visualiza_propostas"><span>Gerenciar Trabalhos individuais</span></a></li>

                <li><a href="<?= base_url(); ?>index.php/in/gestor/gerencia_usuarios"><span>Gerenciamento de municípios</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas"><span>Propostas por cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_sistema"><span>Propostas do sistema</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_programas"><span>Quantitativo de propostas por Cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/programas_tabela"><span>Programas Abertos</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas"><span>Relatório de programas</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas_emenda"><span>Programas abertos com emenda</span></a></li>  

                <li><a href="<?= base_url(); ?>index.php/in/gestor/criar_sala_conferencia"><span>Criar Sala de videoconferência</span></a></li>  

            <?php } else if ($usuario_logado->tipoPessoa == 6) {
                ?>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/escolher_proponente"><span>Novo Projeto</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/gerencia_proposta"><span>Gerenciar Trabalhos</span></a></li>

                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas"><span>Propostas por cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_sistema"><span>Propostas do sistema</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_programas"><span>Quantitativo de propostas por Cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/programas_tabela"><span>Programas Abertos</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas"><span>Relatório de programas</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas_emenda"><span>Programas abertos com emenda</span></a></li>  

            <?php } else if ($usuario_logado->tipoPessoa == 7) {
                ?>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/escolher_proponente"><span>Novo Projeto</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/atribui_permissoes"><span>Distribuir Trabalhos</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/gestor/gerencia_proposta"><span>Gerenciar Trabalhos</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/usuario/adiciona_usuario_trabalho?tipo_usuario=99"><span>Adiciona Usuário de trabalho</span></a></li>

                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas"><span>Propostas por cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_sistema"><span>Propostas do sistema</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_programas"><span>Quantitativo de propostas por Cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/programas_tabela"><span>Programas Abertos</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas"><span>Relatório de programas</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas_emenda"><span>Programas abertos com emenda</span></a></li>  

            <?php } else if ($usuario_logado->tipoPessoa == 8 || $usuario_logado->tipoPessoa == 9) {
                ?>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas"><span>Propostas por cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_sistema"><span>Propostas do sistema</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/propostas_programas"><span>Quantitativo de propostas por Cidade</span></a></li>
                <li><a href="<?= base_url(); ?>index.php/in/dados_siconv/programas_tabela"><span>Programas Abertos</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas"><span>Relatório de programas</span></a></li>  
                <li><a href="<?= base_url(); ?>index.php/in/usuario/relatorio_programas_emenda"><span>Programas abertos com emenda</span></a></li>  


            <?php } ?>
            <li><a href="<?= base_url(); ?>index.php/in/usuario/sala_conferencia"><span>Participar de videoconferência</span></a></li>
        </ul>
    </div>
</div>
