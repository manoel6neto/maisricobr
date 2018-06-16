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
        width: 100%    !important;
        height: auto   !important;
    }
</style>
<div class="login spacing-x2" style="margin-top: 100px;">
    <div class="col-md-8 col-sm-8 col-sm-offset-2">
        <div
            class="panel panel-default"
            style="margin-bottom: 0px; border-top-color: transparent">
            <div class="panel-body innerAll">
                <div>
                    <div>
                        <video
                            id="video"
                            controls="controls"
                            tabindex="0"
                            preload="auto"
                            poster="<?php echo base_url(); ?>layout/assets/apresentacao/capa_video.png">
                            <source
                                src="<?php echo base_url(); ?>layout/assets/apresentacao/video.mp4"
                                type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
                        </video>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
