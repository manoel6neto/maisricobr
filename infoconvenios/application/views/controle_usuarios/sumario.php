<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Sumário de <?php echo $usuario->nome; ?></h1>
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
            
                
                    <table class="table">
                        <thead>
                            <tr style="color: #428bca; font-size: 16px;">
                                <th>Informação</th>
                                <th>Valor</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Nome</td>
                                <td><?php echo $usuario->nome; ?></td>
                            </tr>
                            <tr>
                                <td>CPF</td>
                                <td><?php echo $usuario->login; ?></td>
                            </tr>
                            <tr>
                                <td>Validade</td>
                                <td><?php echo $gestor->validade; ?></td>
                            </tr>
                            <tr>
                                <td>Ativo</td>
                                <?php if ($usuario->status == "A"): ?>
                                    <td>Ativo</td>
                                <?php else: ?>
                                    <td>Inativo</td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td>Desconto</td>
                                <td><?php echo $usuario->tem_desconto ? "Sim" : "Não"; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><?php echo $usuario->email; ?></td>
                            </tr>
                            <tr>
                                <td>Telefone</td>
                                <td><?php echo $usuario->telefone; ?></td>
                            </tr>
                            <tr>
                                <td>Celular</td>
                                <td><?php echo $usuario->celular; ?></td>
                            </tr>
                            
                            <tr>
                                <td><span style="font-weight: bolder; padding-top: 40px; margin-left: 450px; padding-bottom: 40px;">Dados da Entidade</span></td>
                                <td></td>
                            </tr>
                            
                            <tr>
                                <td>Cnpj</td>
                                <td><?php echo $cnpj->cnpj; ?></td>
                            </tr>
                            <tr>
                                <td>Instituição</td>
                                <td><?php echo $cnpj->cnpj_instituicao; ?></td>
                            </tr>
                            <tr>
                                <td>Município</td>
                                <td><?php echo $proponente->municipio; ?></td>
                            </tr>
                            <tr>
                                <td>Estado</td>
                                <td><?php echo $cnpj->sigla; ?></td>
                            </tr>
                            <tr>
                                <td>Esfera</td>
                                <td><?php echo $cnpj->esfera_administrativa; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <a class="btn btn-primary" href="<?php echo base_url('index.php/controle_usuarios/lista_usuario_avulso');?>">Voltar</a>
            </div>
        </div>
    </div>
</div>