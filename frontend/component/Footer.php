<?php

namespace frontend\component;

use common\models\Pusher;
use yii\base\Widget;

class Footer extends Widget {
	public function run() {
	    $pushers = Pusher::find()->orderBy('createdAt DESC')->limit(3)->all();
		echo $this->render ( 'footer', [
		    'pushers' => $pushers,
		]);
	}
}

