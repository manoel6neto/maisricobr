<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>configuracoes/css/fancybox/fancybox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>configuracoes/css/login.css" media="screen" />

<div id="header" style="position: initial; float: top;">
    <div id="header-info">
        <div id="inst-bar">
            <ul id="inst-bar-opts">
                <li id="inst-bar-ba"><a href="http://www.ba.gov.br/" target="_blank">Governo do Estado da Bahia</a></li> 
                <li id="inst-bar-opt-sites"><a href="http://www.ba.gov.br/modules/conteudo/conteudo.php?conteudo=6" target="_blank">Sites do Governo</a></li>
                <li id="inst-bar-opt-transparencia"><a href="http://www.ba.gov.br/modules/conteudo/conteudo.php?conteudo=7" target="_blank">Transparência</a></li>
                <li id="inst-bar-opt-ouvidoria"><a href="http://www.ouvidoriageral.ba.gov.br" target="_blank">Ouvidoria Geral</a></li>
                <li id="inst-bar-opt-acessibilidade"><a href="http://www.ba.gov.br/modules/conteudo/conteudo.php?conteudo=8" accesskey="4" target="_blank">Acessibilidade</a></li>
            </ul>
        </div>
        <h1 class="logo">Secretaria de Infraestrutura Hídrica e Saneamento - Governo da Bahia</h1>
    </div>
</div>

<div style="margin-bottom: 10px; alignment-adjust: central; float: none; overflow: visible;">
    <div class="col-md-4 col-sm-6 col-sm-offset-4" style="margin-bottom: 10px;">
        <h4 class="margin-none text-center">SISTEMA DE CAPTAÇÂO DE RECURSOS SIHS</h4>

        <h4 class="innerAll margin-none text-center"><i class="fa fa-lock"></i> Faça o seu login</h4>

        <div class="panel panel-default" style="margin-bottom: 10px;">
            <div class="panel-body innerAll" style="margin-bottom: 10px;">
                <?php echo validation_errors(); ?>
                <?php if (isset($erro_login) !== false) echo "<p class=\"error\">" . $erro_login . "</p>"; ?>
                <?php echo form_open(); ?>

                <div class="form-group">
                    <label for="exampleInputEmail1" style="text-align: left;">Login <span style="font-size: small;">(CPF) <span style="font-size: x-small;">(somente numeros)</span></span></label>
                    &nbsp;&nbsp;<span id="retornoLogin"></span>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="login" placeholder="Digite o CPF do usuário" />
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" style="text-align: left;">Senha</label>
                    &nbsp;&nbsp;<span id="retornoSenha"></span>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="senha" placeholder="Digite a senha" />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>

                <?php echo form_close(); ?>

                <br>
                <a href="<?php echo base_url('index.php/in/login/recupera'); ?>" class=""> Esqueci minha senha </a>
            </div>
        </div>
    </div>
</div>

<div id="footer" class="vcard" style="position: relative; float: bottom;">
    <div id="footer-geral">
        <div id="navegacao-geral-rodape">
            <ul id="mn-navegacao-geral-rodape">
                <li class="first"><a href="http://www.ba.gov.br" target="_blank">Portal do Governo</a></li>
                <li><a href="http://www.ouvidoriageral.ba.gov.br" target="_blank">Ouvidoria Geral</a></li>
                <li class="last"><a href="http://www.ba.gov.br/modules/conteudo/conteudo.php?conteudo=18">Acesso à Informação</a></li>
            </ul>
        </div>
        <div id="destaque-geral-rodape"><a href="http://www.sac.ba.gov.br" target="_blank"><img src="http://www.ba.gov.br/modules/destaques/uploads/1383238903destsac.png" width="125" height="50" alt=""></a><a href="http://www.pactopelavida.ba.gov.br" target="_blank"><img src="http://www.ba.gov.br/modules/destaques/uploads/1383238926destpactopelavida.png" width="125" height="50" alt=""></a><a href="http://www.educacao.ba.gov.br/educarparatransformar" target="_blank"><img src="http://www.ba.gov.br/modules/destaques/uploads/1430767383educarparatransformar.png" width="125" height="50" alt=""></a><a href="http://www.meioambiente.ba.gov.br/conteudo.aspx?s=AGUTOD&amp;p=RECHUD" target="_blank"><img src="http://www.ba.gov.br/modules/destaques/uploads/1431102579AGUAPARATODOS.GIF" width="125" height="50" alt=""></a></div><div id="combo-secretarias"><a class="selected">Escolha uma Secretaria</a>
            <div id="navegacao-geral-secretarias">
                <ul id="mn-navegacao-geral-secretarias">
                    <li class="first"><a href="http://www.saeb.ba.gov.br/" target="_blank">Saeb - Sec. da Administração</a></li>
                    <li><a href="http://www.seagri.ba.gov.br/" target="_blank">Seagri - Sec. da Agricultura, Pecuária, Irrigação, Pesca, e Aquicultura</a></li>
                    <li><a href="http://www.sec.ba.gov.br/" target="_blank">Sec - Secretaria da Educação</a></li>
                    <li><a href="http://www.secom.ba.gov.br" target="_blank">Secom - Secretaria de Comunicação Social</a></li>
                    <li><a href="http://www.cultura.ba.gov.br/" target="_blank">Secult - Secretaria de Cultura</a></li>
                    <li><a href="http://www.secti.ba.gov.br/" target="_blank">Secti - Sec. de Ciência, Tecnologia e Inovação</a></li>
                    <li><a href="http://www.sdr.ba.gov.br/" target="_blank">SDR - Secretaria de Desenvolvimento Rural</a></li>
                    <li><a href="http://www.sedur.ba.gov.br/" target="_blank">Sedur - Secretaria de Desenvolvimento Urbano</a></li>
                    <li><a href="http://www.sefaz.ba.gov.br" target="_blank">Sefaz - Secretaria da Fazenda</a></li>
                    <li><a href="http://www.seinfra.ba.gov.br/" target="_blank">Seinfra - Secretaria de Infraestrutura</a></li>
                    <li><a href="http://www.meioambiente.ba.gov.br/" target="_blank">Sema - Secretaria do Meio Ambiente</a></li>
                    <li><a href="http://www.seap.ba.gov.br/" target="_blank">Seap - Sec. de Administração Penitenciária e Ressocialização</a></li>
                    <li><a href="http://www.mulheres.ba.gov.br/" target="_blank">SPM - Sec. de Políticas para as Mulheres</a></li>
                    <li><a href="http://www.seplan.ba.gov.br/" target="_blank">Seplan - Sec. do Planejamento</a></li>
                    <li><a href="http://www.sepromi.ba.gov.br/" target="_blank">Sepromi - Secretaria da Promoção de Igualdade Racial</a></li>
                    <li><a href="http://www.serin.ba.gov.br/" target="_blank">Serin - Secretaria de Relações Institucionais</a></li>
                    <li><a href="http://www.saude.ba.gov.br/" target="_blank">Sesab - Secretaria de Saúde</a></li>
                    <li><a href="http://www.setre.ba.gov.br/" target="_blank">Setre - Secretaria do Trabalho, Emprego, Renda e Esporte</a></li>
                    <li><a href="http://www.setur.ba.gov.br/" target="_blank">Setur - Secretaria de Turismo</a></li>
                    <li><a href="http://www.justicasocial.ba.gov.br/" target="_blank">SJDHDS - Sec. da Justiça, Direitos Humanos e Desenvolvimento Social</a></li>
                    <li><a href="http://www.ssp.ba.gov.br/" target="_blank">SSP - Sec. da Segurança Pública</a></li>
                    <li><a href="http://www.sde.ba.gov.br/" target="_blank">SDE - Secretaria de Desenvolvimento Econômico</a></li>
                    <li class="last"><a href="http://www.sihs.ba.gov.br/" target="_blank">SIHS - Secretaria de Infraestrutura Hídrica e Saneamento</a></li>
                </ul></div></div><script type="text/javascript">$('#combo-secretarias a.selected').click(function () {
                        if ($('#combo-secretarias').hasClass('open')) {
                            $('#combo-secretarias').removeClass('open');
                        } else {
                            $('#combo-secretarias').addClass('open');
                        }
                    });</script></div>
    <div id="footer-info">
        <address class="adr" style="margin-bottom: 25px;">
<!--            <p><span class="street-address">3ª Avenida, nº 390, Ala Norte, 2º andar<br>Centro Administrativo da Bahia - CAB</span></p>
            <p>CEP <span class="postal-code">41.745-005</span> - <span class="locality">Salvador</span> - <span class="region">Bahia</span></p>
            <p><a href="https://maps.google.com/maps?q=3%C2%AA+Avenida,+n%C2%BA+390,+2%C2%BA+andar,+CAB.&amp;hl=pt&amp;ie=UTF8&amp;ll=-12.950527,-38.433094&amp;spn=0.016918,0.012124&amp;sll=37.0625,-95.677068&amp;sspn=55.586984,49.658203&amp;hq=3%C2%AA+Avenida,+n%C2%BA+390,+2%C2%BA+andar&amp;hnear=Salvador+-+Bahia,+Brasil&amp;t=m&amp;z=16" title="Localize no mapa" target="_blank" class="map">Localização</a></p>
            <br>
            <p class="info-complementar">Exerça sua cidadania. <a href="http://www.ouvidoria.ba.gov.br/" target="_blank">Fale com a Ouvidoria</a>.</p>-->
            <p class="info-complementar"><a rel="Physis Brasil" href="http://www.physisbrasil.com/#!incio/c203u" target="_blank"><img alt="Physis Brasil" style="border-width:0" src="<?php echo base_url('layout/assets/images/logo_physis1.png'); ?>" width="50%" height="50%"></a><br></p>
        </address>
        <a href="http://www.ba.gov.br" class="footer-logo-governo" target="_blank">Governo da Bahia</a>
        <p><span class="fn org">
                <strong>Sistema licenciado por Physis.Org Consultoria Solidária de Projetos Ltda.</strong><br> A serviço do Governo da Bahia - Terra-Mãe do Brasil 
        <br><strong>SIHS - Secretaria de Infraestrutura Hídrica e Saneamento</strong>
        </span>

    </div>
</div>

<div style="visibility: hidden;">
    <h1>Em virtude das adequações solicitadas para o sistema, o mesmo estará indisponível por 48 horas.</h1>
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