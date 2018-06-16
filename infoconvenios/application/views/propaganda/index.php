<meta charset="utf-8">
<style type="text/css">
    body {
        margin: 0;
        padding: 0;
        background: #ccc;
        text-align: center; /* hack para o IE */
    }

    video {
        position: relative;
        width: 80%    !important;
        height: auto   !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('video').bind('play', function (e) {
            setTimeout(ativar, 10000);
        });

        function ativar() {
            $('#btn_testar').prop('disabled', false);
        }
    });
</script>

<div class="login spacing-x2">
    <div class="col-md-6 col-sm-6 col-sm-offset-3">
        <div
            style="background-color: white; border: solid #D8D8D8; border-width: 2px; border-bottom-color: #3a7ec0;">
            <img
                src="<?php echo base_url(); ?>layout/assets/images/cab_video.png"
                style="width: 100%; height: 100%; padding-left: 5px; padding-right: 5px; padding-top: 5px;" />
        </div>

        <div
            class="panel panel-default"
            style="margin-bottom: 0px; border-top-color: transparent">
            <div class="panel-body innerAll">
                <form
                    name="form_propaganda"
                    method="post"
                    id="form_propaganda">
                    <input
                        type="hidden"
                        name="desconto"
                        value='<?php if (isset($desconto)) {
    echo $desconto;
} ?>'>
                    <div>
                        <div>
                            <video
                                id="video"
                                controls="controls"
                                tabindex="0"
                                preload="auto"
                                poster="">
                                <source
                                    src="<?php echo $video; ?>"
                                    type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
                            </video>
                        </div>
                        <input
                            style="margin-bottom: 10px; margin-top: 50px; padding: 10px; padding-left: 30px; padding-right: 30px; font-size: 20px;" 
                            type="submit"
                            disabled="true"
                            id="btn_testar"
                            class="btn btn-primary place-right"
                            value="Iniciar teste de 7 dias !!"
                            formaction="<?php echo base_url('index.php/compra/cadastro_usuario?gratuito=1&desconto=' . $desconto); ?>"
                            name="btn_testar">
                    </div>
                </form>
            </div>
        </div>
        <div
            style="background-color: white; border: solid #D8D8D8; border-top-color: #3a7ec0; border-width: 2px; margin-bottom: 70px; margin-top: 0px;">
            <img
                src="<?php echo base_url(); ?>layout/assets/images/rod_video.png"
                style="margin-top: 0px; width: 100%; height: 100%; padding-left: 2px; padding-right: 2px; padding-bottom: 5px;" />
        </div>
    </div>
</div>
