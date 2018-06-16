<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>configuracoes/css/fancybox/fancybox.css" media="screen" />
<div class="login spacing-x2">
    <div class="col-md-4 col-sm-6 col-sm-offset-4">

        <?php if ($this->input->get('gp', TRUE) !== FALSE): ?>
            <div class="innerAll text-center"><img src="<?php echo base_url(); ?>layout/assets/images/logo_gestao.png" width="50%"/></div>
        <?php else: ?>
            <div class="innerAll text-center"><img src="<?php echo base_url(); ?>layout/assets/images/logo_e_sicar_desc.png" width="100%" height="100%"/></div>
        <?php endif; ?>

<!-- <div style="background-image: url('<?php echo base_url('layout/assets/images/fundo_login.png'); ?>'); border: solid 1px white; background-repeat: no-repeat; background-size: 625px 398px;"> -->

        <h4 class="innerAll margin-none text-center"><i class="fa fa-lock"></i> Faça o seu login</h4>

        <div class="panel panel-default" style="border-radius: 2%;">
            <div class="panel-body innerAll">
                <?php echo validation_errors(); ?>
                <?php if (isset($erro_login) !== false) echo "<p class=\"error\">" . $erro_login . "</p>"; ?>
                <?php echo form_open(); ?>

                <div class="form-group">
                    <label for="exampleInputEmail1">Login <span style="font-size: small;">(CPF) <span style="font-size: x-small;">(somente numeros)</span></span></label>
                    &nbsp;&nbsp;<span id="retornoLogin"></span>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="login" placeholder="Digite o CPF do usuário" />
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Senha</label>
                    &nbsp;&nbsp;<span id="retornoSenha"></span>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="senha" placeholder="Digite a senha" />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>

                <?php echo form_close(); ?>

                <br>
                <a href="<?php echo base_url('index.php/in/login/recupera'); ?>" class=""> Esqueci minha senha </a>

            </div>
        </div>
        <!-- </div> -->
        <?php if ($this->input->get('gp', TRUE) === FALSE): ?>
            <div class="innerAll text-center"><a href="http://physisbrasil.com.br"><img src="<?php echo base_url(); ?>layout/assets/images/logo_physis_login.png" width="100%" height="100%"/></a></div>		
        <?php endif; ?>

    </div>
</div>

<div style="visibility: hidden;">
<!-- <a class="fancybox" rel="fancybox-button" id="abre_aviso" href="<?php echo base_url('layout/assets/images/aviso_manutencao.png'); ?>">
<img src="<?php echo base_url('layout/assets/images/aviso_manutencao.png'); ?>" width="600" height="400"> -->
</a>
</div>

<script type="text/javascript">
    $(document).ready(function () {
// 	$(".fancybox").fancybox({
// 		closeBtn: true,
// 		arrows: false
// 	});

        //$("#abre_aviso").trigger("click");

        function checaCampo(valor, msgRetorno, ehSenha) {
            $.ajax({
                url: '<?php echo base_url('index.php/in/login/validaCampos') ?>',
                type: 'post',
                dataType: 'html',
                data: {
                    valorCampo: valor,
                    campoSenha: ehSenha
                            //nivel:$("#nivel").val()
                },
                success: function (data) {
                    if (data > 0)
                        $("#" + msgRetorno).html("<i class='btn-sm btn-success fa fa-check-square'></i>");
                    else
                        $("#" + msgRetorno).html("<i class='btn-sm btn-primary fa fa-warning'></i>");
                }
            });
        }

        $("#exampleInputEmail1").blur(function () {
            if ($(this).val() != "")
                checaCampo($(this).val(), "retornoLogin", "");
            else
                $("#retornoLogin").html("<i class='btn-sm btn-primary fa fa-warning'></i>");
        });

        $("#exampleInputPassword1").blur(function () {
            if ($(this).val() != "")
                checaCampo($("#exampleInputEmail1").val(), "retornoSenha", $(this).val());
            else
                $("#retornoSenha").html("<i class='btn-sm btn-primary fa fa-warning'></i>");
        });

        $("#exampleInputEmail1").focus(function () {
            $("#retornoLogin").html("");
        });

        $("#exampleInputPassword1").focus(function () {
            $("#retornoSenha").html("");
        });
    });
</script>