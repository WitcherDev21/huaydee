<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedAnswerGame */

$this->title = 'Create Lottery Game Chit Answer';
$this->params['breadcrumbs'][] = ['label' => 'Thai Shared Answer Game', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-game-chit-answer-create">

    <?= $this->render('_form', [
        'model' => $model,
        'thaiSharedGame' => $thaiSharedGame
    ]) ?>

</div>
