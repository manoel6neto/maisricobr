<!DOCTYPE html>
<html lang="pt_BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Gestao_e_politica_administracao">
        <meta name="author" content="Manoel Carvalho Neto">

        <title>Gestão & Política - Administração</title>
        <link rel="icon" href="<?php echo base_url("layout/images/favicon.png"); ?>"/>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url("layout/vendor/bootstrap/css/bootstrap.min.css"); ?>"  rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url("layout/css/3-col-portfolio.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/login.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/administracao.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/table.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url("layout/css/util.css"); ?>" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/fonts/font-awesome-4.7.0/css/font-awesome.min.css"); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/vendor/animate/animate.css"); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/vendor/select2/select2.min.css"); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("layout/vendor/perfect-scrollbar/perfect-scrollbar.css"); ?>">
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top navbars">
            <div class="container">
                <a id="link-home" style="width: fit-content;" href="<?php echo base_url("index.php/modulos"); ?>"><img style="width: 58%;" src="<?php echo base_url("layout/images/logo_gestao_menu.jpg"); ?>"/></a>
                <div style="display: inline;">
                    <?php if ($this->session->userdata("sessao") != FALSE): ?>
                        <h5 style="margin-left: -250px;" class="titulo_menu">Olá, <?php echo $this->session->userdata("sessao")['nome_usuario']; ?></h5>      
                    <?php else: ?>
                        <h5 style="margin-left: -250px;" class="titulo_menu">Olá, Visitante</h5>      
                    <?php endif; ?>
                </div>
                <div>
                    <div style="float: left;">
                        <?php if ($this->session->userdata("sessao") != FALSE && $this->session->userdata("sessao")['is_admin'] == 1): ?> 
                            <a href="<?php echo base_url("index.php/administracao"); ?>" title="Configuração" class="login100-form-btn link-button" style="width: 80px; height: 40px;"><img src="<?php echo base_url("layout/images/icone_config.png"); ?>" style="width: 200%;"/></a>
                        <?php else: ?>
                            <a href="<?php echo base_url("index.php/administracao/edit?user={$this->session->userdata('sessao')['id_usuario_sistema']}"); ?>" title="Configuração" class="login100-form-btn link-button" style="width: 80px; height: 40px;"><img src="<?php echo base_url("layout/images/icone_config.png"); ?>" style="width: 200%;"/></a>
                        <?php endif; ?>
                    </div>
                    <div style="float: right; padding-left: 10px;">
                        <form action="<?php echo base_url("index.php/login/sair"); ?>">
                            <button id="sair_btn" title="Sair" class="login100-form-btn" style="width: 80px; height: 40px;">Sair</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container" style="padding: 10px;">

            <?php if (isset($erro_editar) !== false) echo "<p style='margin-top: 20px; color: #cc0000; font-size: 14px; font-weight: bold;' class=\"error\">" . $erro_editar . "</p>"; ?>
            <?php if (isset($sucesso_editar) !== false) echo "<p style='margin-top: 20px; color: #3399ff; font-size: 14px; font-weight: bold;' class=\"error\">" . $sucesso_editar . "</p>"; ?>

            <!-- Page Heading -->
            <h1 class="my-4">
                <small>Usuários do sistema</small>
            </h1>

            <?php if (isset($usuarios)): ?>
                <div class="wrap-table100">
                    <div class="table100 ver1 m-b-16">
                        <div class="table100-head">
                            <table>
                                <thead>
                                    <tr class="row100 head">
                                        <th class="cell100 column1">Nome</th>
                                        <th class="cell100 column2">Cpf</th>
                                        <th class="cell100 column3">Email</th>
                                        <th class="cell100 column4">Admin</th>
                                        <th class="cell100 column5"> Ações </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table100-body js-pscroll">
                            <table>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <tr class="row100 body">
                                            <td class="cell100 column1"><?php echo $usuario->nome; ?></td>
                                            <td class="cell100 column2"><?php echo $usuario->cpf; ?></td>
                                            <td class="cell100 column3"><?php echo $usuario->email; ?></td>
                                            <?php if ($usuario->is_admin == 1): ?>
                                                <td class="cell100 column4">Sim</td>
                                            <?php else: ?>
                                                <td class="cell100 column4">Não</td>
                                            <?php endif; ?>
                                            <td class="cell100 column5"><a title="Editar Usuário" style="background: transparent;" href="<?php echo base_url("index.php/administracao/edit?user={$usuario->id}"); ?>"><img src="<?php echo base_url("layout/images/edit_icon.png"); ?>" style="width: 22%; padding: 5px;"/></a>&nbsp;<a title="Apagar Usuário" style="background: transparent;" href="<?php echo base_url("index.php/administracao/delete?user={$usuario->id}"); ?>"><img src="<?php echo base_url("layout/images/delete_icon.png"); ?>" style="width: 22%; padding: 5px;"/></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h3>Nenhum usuário encontrado!</h3>
            <?php endif; ?>
            <a href="<?php echo base_url("index.php/administracao/edit"); ?>" title="Adicionar Usuário" class="login100-form-btn link-button" style="width: 120px !important; height: 40px;">Adicionar</a>
        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="<?php echo base_url("layout/vendor/jquery/jquery.min.js"); ?>"></script>
        <script src="<?php echo base_url("layout/vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    </body>
</html>
