<?php
/* @var $lotteryPlayTypes \common\models\PlayType */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile(Yii::getAlias('@web/version6/js/index/numbersets.js?1561983814'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<?php $form = ActiveForm::begin([
    'method' => 'post',
]); ?>
<div class="bar-back">
    <a href="<?= Url::to(['number-memo/index']) ?>"><i class="fas fa-chevron-left"></i> จัดการเลขชุด</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-plus"></i> สร้างเลขชุด</h4>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded pt-2 px-2 pb-0 xtarget col-lotto">
        <div class="row px-2 align-items-center">
            <div class="col-12 col-sm-12 col-md-2 pr-1">
                <label><i class="fas fa-list-ol"></i> ชื่อชุด</label>
            </div>
            <div class="col-12 col-sm-12 col-md-8 px-1">
                <input name="title" type="text" placeholder="ระบุชื่อเรียกเลขชุด" class="form-control form-control-sm"
                       required="">
            </div>
            <div class="col-2 pl-1"></div>
            <div class="w-100 border-bottom my-3"></div>
            <div class="col-12">
                <label><i class="fas fa-plus-square"></i> สร้างชุดตัวเลข</label>
            </div>
            <div class="col-12">
                <ol class="pl-2" id="add_numbersets_list">
                    <li class="mb-2">
                        <div class="row">
                            <div class="col-5 col-sm-5 col-md-2 pr-1">
                                <div class="dropdown bootstrap-select form-control dropdown-numberset valid">
                                    <select name="option[]" id="option_1"
                                            class="selectpicker form-control dropdown-numberset valid"
                                            aria-invalid="false" data-style="btn-outline-primary btn-sm"
                                            onchange="select_option(1,this.value);" tabindex="-98">
                                        <?php foreach ($lotteryPlayTypes as $key => $lotteryPlayType) { ?>
                                            <option value="<?= $lotteryPlayType->code ?>" <?= $key === 0 ? 'selected=""' : '' ?>>
                                                <?= $lotteryPlayType->title ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    </button>
                                    <div class="dropdown-menu " role="combobox">
                                        <div class="inner show" role="listbox" aria-expanded="false" tabindex="-1">
                                            <ul class="dropdown-menu inner show"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5 col-sm-5 col-md-8 px-1">
                                <input type="tel" class="form-control form-control-sm" name="number[]" id="number_1"
                                       minlength="3" maxlength="3" required=""
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 pl-1">
                                &nbsp;
                            </div>
                        </div>
                    </li>
                    <li class="mb-2 d-none" id="model_add_numbersets">
                        <div class="row">
                            <div class="col-5 col-sm-5 col-md-2 pr-1">
                                <select name="{option[]}" class="form-control dropdown-numberset valid"
                                        aria-invalid="false" data-style="btn-outline-primary btn-sm" id="option_{id}"
                                        onchange="select_option({id},this.value);">
                                    <?php foreach ($lotteryPlayTypes as $key => $lotteryPlayType) { ?>
                                        <option value="<?= $lotteryPlayType->code ?>" <?= $key === 0 ? 'selected=""' : '' ?>>
                                            <?= $lotteryPlayType->title ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-5 col-sm-5 col-md-8 px-1">
                                <input type="tel" class="form-control form-control-sm" name="{number[]}"
                                       id="number_{id}" minlength="3" maxlength="3" {required}=""
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 pl-1">
                                <button class="btn btn-outline-danger btn-sm" onclick="delete_add_numbersets({id})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
            <div class="col-12 table-primary py-2 rounded-bottom">
                <button class="btn btn-outline-primary btn-block" type="button" onclick="append_add_numbersets()">
                    <i class="fas fa-plus-circle"></i> เพิ่มชุดตัวเลข
                </button>
            </div>
        </div>
    </div>
    <div class="bg-white p-2 rounded shadow-sm w-100 mb-5">
        <div class="row">
            <div class="col pr-1">
                <button class="btn btn-secondary btn-block" type="reset">
                    ยกเลิก
                </button>
            </div>
            <div class="col pl-1">
                <button class="btn btn-success_bkk btn-block" type="submit">
                    <i class="fas fa-save"></i> บันทึก
                </button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>