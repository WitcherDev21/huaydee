<?php

namespace frontend\component;

use yii\base\Widget;
use yii\helpers\Url;
use common\libs\Constants;
use common\models\Games;

class GameRule extends Widget {
	public $game_id = '';
	public function run() {
		$identity = \Yii::$app->user->getIdentity ();

        $model = Games::find()->select(['rule'])->where(['id' => $this->game_id])->one();
		$rule = $model->rule;
		echo $this->render ( 'game-rule', [ 
				'rule' => $rule 
		] );
	}
}

