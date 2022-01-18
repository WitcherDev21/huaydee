<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedAnswerGame */

$this->title = 'Update Thai Shared Answer Game: ' . $thaiSharedGame->game->title.' '.$thaiSharedGame->round;
$this->params['breadcrumbs'][] = ['label' => 'Lottery Game Chit Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $thaiSharedGame->id, 'url' => ['view', 'id' => $thaiSharedGame->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lottery-game-chit-answer-update">

    <?= $this->render('_form', [
        'model' => $model,
        'thaiSharedGame' => $thaiSharedGame,
    ]) ?>

</div>
