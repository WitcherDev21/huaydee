<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-cog"></i></span>
        <h5>การตั้งค่า</h5>
    </div>
    <div class="widget-box">
        <div class="widget-title">
            <ul class="nav nav-tabs">
                <li class="<?php echo((!empty($tabActive['id']) && $tabActive['id'] == 'users') || empty($tabActive['id']) ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/users'])?>">เพิ่มสมาชิก</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'bank' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/bank'])?>">ธนาคาร</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'roles' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/roles'])?>">ควบคุมเมนูการใช้งาน</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'benefit' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/benefit'])?>">กำหนด ผลกำไร</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'commission_invite' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/commission_invite'])?>">คอมมิชชั่นแนะนำ</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'commission_agent' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/commission_agent'])?>">คอมมิชชั่นยี่กี้เอเยนต์</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'play_type' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/play_type'])?>">รางวัลที่ 1</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'credit_master' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/credit_master'])?>">Credit Master</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'setting_play_type' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/setting_play_type'])?>">การตั้งค่าราคาทั้งหมด</a></li>
                <li class="<?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'discount_game' ? 'active' : ''); ?>">
                    <a href="<?= \yii\helpers\Url::to(['setting/index/discount_game'])?>">ส่วนลดเกมส์</a></li>
            </ul>
        </div>
        <div class="widget-content tab-content">
            <div id="users"
                 class="tab-pane <?php echo((!empty($tabActive['id']) && $tabActive['id'] == 'users') || empty($tabActive['id']) ? 'active' : ''); ?>">
                <?php
                echo $this->render('_users', [
                    'modelUsers' => $modelUsers,
                    'RolesList' => $RolesList,
                ]);
                ?>
            </div>
            <div id="bank"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'bank' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_bank', [
                    'modelBank' => $modelBank,
                    'dataBank' => $dataBank,
                ]);
                ?>
            </div>
            <div id="roles"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'roles' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_roles', [
                    'modelAuthRoles' => $modelAuthRoles,
                    'dataAuthRoles' => $dataAuthRoles,
                    'listAuthPermission' => $listAuthPermission
                ]);
                ?>
            </div>
            <div id="benefit"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'benefit' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_benefit', [
                    'modelSettingBenefitForm' => $modelSettingBenefitForm,
                    'yeekee' => $yeekee,
                    'blackred' => $blackred
                ]);
                ?>
            </div>
            <div id="commission_invite"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'commission_invite' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_commission_invite', [
                    'modelSettingCommissionCreditForm' => $modelSettingCommissionCreditForm,
                    'ComInvite' => $ComInvite,
                ]);
                ?>
            </div>
            <div id="commission_agent"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'commission_agent' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_commission_agent', [
                    'modelSettingCommissionCreditForm' => $modelSettingCommissionCreditForm,
                    'ComAgent' => $ComAgent,
                ]);
                ?>
            </div>
            <div id="commission_agent_blackred"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'commission_agent_blackred' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_commission_agent_blackred', [
                    'modelSettingCommissionCreditForm' => $modelSettingCommissionCreditForm,
                    'ComAgentBlackred' => $ComAgentBlackred,
                ]);
                ?>
            </div>
            <div id="credit"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'credit' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_credit', [
                    'modelSettingCommissionCreditForm' => $modelSettingCommissionCreditForm,
                    'CreditAmount' => $CreditAmount,
                ]);
                ?>
            </div>
            <div id="play_type"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'play_type' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_play_type', [
                    'modelPlayTypeForm' => $modelPlayTypeForm,
                    'dataPlayType' => $dataPlayType,
                    'modelGameBenefit' => $modelGameBenefit,
                    'dataGameBenefit' => $dataGameBenefit,
                ]);
                ?>
            </div>
            <div id="play_black_red"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'play_black_red' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_play_black_red', [
                    'modelBlackRed' => $modelBlackRed,
                    'dataPlayType' => $dataPlayType,
                ]);
                ?>
            </div>
            <div id="credit_master"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'credit_master' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_credit_master', [
                    'dataCreditmaster' => $dataCreditmaster,
                    'modelCreditMasterIn' => $modelCreditMasterIn,
                    'modelCreditMasterOut' => $modelCreditMasterOut,
                ]);
                ?>
            </div>
            <div id="setting_play_type"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'setting_play_type' ? 'active' : ''); ?>">
                <?php
                echo $this->render('/play-type/index', [
                    'searchModel' => $searchModelPlayType,
                    'dataProvider' => $dataProviderPlayType,
                ]);
                ?>
            </div>
            <div id="discount_game"
                 class="tab-pane <?php echo(!empty($tabActive['id']) && $tabActive['id'] == 'discount_game' ? 'active' : ''); ?>">
                <?php
                echo $this->render('/discount-game/index', [
                    'searchModel' => $searchModelDiscountGame,
                    'dataProvider' => $dataProviderDiscountGame,
                    'gameObjs' => $gameObjs,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>