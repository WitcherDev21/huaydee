<footer id="footer-member" class="page-footer font-small bg-danger pt-1 mt-5 fixed-bottom pc-view">
    <!-- Copyright -->
    <div class="footer-copyright text-center  py-3">© 2021 Copyright -
        <a href="<?= \yii\helpers\Url::to(['site/index'])?>" class="text-blue-2"> RUAYDEE96.COM</a>
    </div>
    <!-- Copyright -->
</footer>

<div class="modal fade" id="contactbox" tabindex="-1" role="dialog" aria-labelledby="contact" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:10px;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">เลือกช่องทางการติดต่อเรา</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-6">
                        <a href="https://lin.ee/SSe3WV9" target="_blank"
                           class="btn btn-outline-success btn-block btn-contactbox">
                            <span><small>ติดต่อผ่านไลน์</small></span>
                            <i class="fab fa-line"></i>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="tel:0622503975" target="_blank" class="btn btn-outline-info btn-block btn-contactbox">
                            <span><small>ติดต่อผ่านโทรศัพท์</small></span>
                            <i class="fas fa-phone-square"></i>
                        </a>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal rule -->
<?php

if (isset($pushers) && $pushers !== '') {
    foreach ($pushers as $key => $pusher) {
        $second = $pusher->time;
        $title = $pusher->title;
        $link = $pusher->url;
        if ($pusher->image !== '') {
            $message = '<a href="'.$pusher->url.'"><img src="'.Yii::getAlias('@pusher/').$pusher->image.'" style="width:300px;"></a>';
        }else {
            $message = $pusher->message;
        }
        $pusherJs = <<<EOT
second = '$second';
millisecond = second * 1000;
var pusherMessage = localStorage['pusher_message_$pusher->id'];
var time = localStorage['time'];
if (!time){
    time = 1;
    localStorage['time'] = time;
}
setInterval(
    function(){
        time = parseInt(time) + 1;
        localStorage['time'] = time;
    }, 1000
);
if (!pusherMessage) {
    var time_millisecond = time * 1000;
    setTimeout(
        function(){
            localStorage['pusher_message_$pusher->id'] = "true";
            swal({
                title: '$title',
                html: '$message',
                confirmButtonText: 'ปิด'
            }).catch(swal.noop);
        }, millisecond - time_millisecond
    );
}
EOT;
        $this->registerJs($pusherJs);
    }
}
?>