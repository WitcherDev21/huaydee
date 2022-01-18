<div class="modal fade" id="rule-yeekee" tabindex="-1" role="dialog" aria-labelledby="rule-yeekee" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:10px;">
            <div class="modal-header bg-danger_bkk text-white">
                <h5 class="modal-title">กติกา</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h3><strong>จับยี่กี่ รอบที่ <?= $yeekeeGame->round ?></strong></h3>
                <p>จับยี่กี่ รอบที่ <?= $yeekeeGame->round ?> กติกาการเเทง&nbsp;<strong>RUAYDEE96</strong></p>
                <?= $game->rule ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger_bkk" data-dismiss="modal">ฉันเข้าใจและยอมรับ</button>
            </div>
        </div>
    </div>
</div>